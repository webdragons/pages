<?php

namespace bulldozer\pages\backend\forms;

use bulldozer\pages\common\ar\Section;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class SectionForm
 * @package bulldozer\pages\backend\forms
 */
class SectionForm extends Model
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var int
     */
    public $parent_id;

    /**
     * @var integer
     */
    public $active;

    /**
     * @var integer
     */
    public $sort;

    /**
     * @var string
     */
    public $slug;

    /**
     * @var Section
     */
    private $section;

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            ['name', 'required'],
            ['name', 'string', 'max' => 255],

            ['parent_id', 'integer'],
            ['parent_id', 'parentValidator'],

            ['active', 'boolean'],

            ['sort', 'required'],
            ['sort', 'integer'],
        ];
    }

    /**
     * @param $attribute
     * @param $params
     * @param $validator
     */
    public function parentValidator($attribute, $params, $validator)
    {
        if ($this->$attribute !== null) {
            $section_ids = ArrayHelper::merge([0], Section::find()->select('id')->asArray()->column());

            if (!in_array($this->$attribute, $section_ids)) {
                $this->addError($attribute, Yii::t('pages', 'Section not found'));
            }

            $child_section_ids = $this->section->children()->select('id')->asArray()->column();

            if (in_array($this->$attribute, $child_section_ids)) {
                $this->addError($attribute, Yii::t('pages', 'You can not move a section to a child'));
            }

            if ($this->$attribute == $this->section->id) {
                $this->addError($attribute, Yii::t('pages', 'You can not transfer the partition to itself'));
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'name' => Yii::t('pages', 'Name'),
            'parent_id' => Yii::t('pages', 'Parent section'),
            'active' => Yii::t('pages', 'Active'),
            'sort' => Yii::t('pages', 'Display order'),
        ];
    }

    /**
     * @return array
     */
    public function getSavedAttributes(): array
    {
        return [
            'name',
            'active',
            'sort',
        ];
    }

    /**
     * @param Section $section
     */
    public function setSection(Section $section): void
    {
        $this->section = $section;
    }
}