<?php

namespace bulldozer\pages\backend\services;

use bulldozer\App;
use bulldozer\pages\backend\forms\SectionForm;
use bulldozer\pages\common\ar\Section;
use Yii;

/**
 * Class SectionsService
 * @package bulldozer\pages\backend\services
 */
class SectionsService
{
    /**
     * @param int $parentId
     * @param Section|null $section
     * @return SectionForm
     * @throws \yii\base\InvalidConfigException
     */
    public function getForm(int $parentId = 0, ?Section $section = null): SectionForm
    {
        if ($section) {
            $parent = $section->parents(1)->one();

            /** @var SectionForm $model */
            $model = App::createObject([
                'class' => SectionForm::class,
                'section' => $section,
                'parent_id' => $parent ? $parent->id : 0,
            ]);

            $model->setAttributes($section->getAttributes($model->getSavedAttributes()));
        } else {
            $sort = 100;
            $lastSection = Section::find()->orderBy(['sort' => SORT_DESC])->one();
            if ($lastSection) {
                $sort = $lastSection->sort + 100;
            }

            /** @var SectionForm $model */
            $model = App::createObject([
                'class' => SectionForm::class,
                'parent_id' => $parentId,
                'sort' => $sort,
                'section' => App::createObject([
                    'class' => Section::class,
                ])
            ]);
        }

        return $model;
    }

    /**
     * @param SectionForm $form
     * @param Section|null $section
     * @return Section
     * @throws \yii\base\InvalidConfigException
     * @throws SectionSaveException
     */
    public function save(SectionForm $form, ?Section $section = null): Section
    {
        if ($section === null) {
            $section = App::createObject([
                'class' => Section::class,
            ]);
        }

        $old_parent = $section->parents(1)->one();
        $parent = Section::findOne($form->parent_id);

        $section->setAttributes($form->getAttributes($form->getSavedAttributes()));

        if ($form->parent_id == 0 && !$section->isRoot()) {
            $result = $section->makeRoot();
        } elseif ($old_parent !== $parent) {
            $result = $section->appendTo($parent);
        } else {
            $result = $section->save();
        }

        if (!$result) {
            throw new SectionSaveException(json_encode($section->getErrors()));
        }

        return $section;
    }

    /**
     * @return array
     */
    public function getSectionsTree(): array
    {
        $sections = Section::find()->orderBy(['left' => SORT_ASC])->all();
        $tmp = [0 => Yii::t('pages', 'Root section')];

        foreach ($sections as $section) {
            $tmp[$section->id] = str_repeat('--', $section->depth) . ' ' . $section->name;
        }

        return $tmp;
    }
}