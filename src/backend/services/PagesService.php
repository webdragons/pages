<?php

namespace bulldozer\pages\backend\services;

use bulldozer\App;
use bulldozer\pages\backend\forms\PageForm;
use bulldozer\pages\common\ar\Page;

/**
 * Class PagesService
 * @package bulldozer\pages\backend\services
 */
class PagesService
{
    /**
     * @param int $sectionId
     * @param Page|null $page
     * @return PageForm
     * @throws \yii\base\InvalidConfigException
     */
    public function getForm(int $sectionId, ?Page $page = null): PageForm
    {
        if ($page) {
            /** @var PageForm $model */
            $model = App::createObject([
                'class' => PageForm::class,
            ]);

            $model->setAttributes($page->getAttributes($model->getSavedAttributes()));
        } else {
            $sort = 100;
            $lastPage = Page::find()->orderBy(['sort' => SORT_DESC])->one();
            if ($lastPage) {
                $sort = $lastPage->sort + 100;
            }

            /** @var PageForm $model */
            $model = App::createObject([
                'class' => PageForm::class,
                'section_id' => $sectionId,
                'sort' => $sort,
            ]);
        }

        return $model;
    }

    /**
     * @param PageForm $form
     * @param Page|null $page
     * @return Page
     * @throws \yii\base\InvalidConfigException
     * @throws PageSaveException
     */
    public function save(PageForm $form, ?Page $page = null): Page
    {
        if ($page === null) {
            $page = App::createObject([
                'class' => Page::class,
            ]);
        }

        $page->setAttributes($form->getAttributes($form->getSavedAttributes()));

        if ($page->save()) {
            return $page;
        }

        throw new PageSaveException(json_encode($page->getErrors()));
    }
}