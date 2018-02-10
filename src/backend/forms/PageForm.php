<?php

namespace bulldozer\pages\backend\forms;

use bulldozer\base\Form;
use bulldozer\pages\common\ar\Section;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class PageForm
 * @package bulldozer\pages\backend\forms
 */
class PageForm extends Form
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $body;

    /**
     * @var int
     */
    public $active;

    /**
     * @var int
     */
    public $sort;

    /**
     * @var int
     */
    public $section_id;

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            ['name', 'required'],
            ['name', 'string'],

            ['body', 'required'],
            ['body', 'string'],

            ['active', 'boolean'],

            ['sort', 'required'],
            ['sort', 'integer'],

            ['section_id', 'required'],
            ['section_id', 'in', 'range' => ArrayHelper::merge([0], Section::find()->asArray()->select(['id'])->column())],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => Yii::t('pages', 'Name'),
            'body' => Yii::t('pages', 'Page'),
            'active' => Yii::t('pages', 'Active'),
            'sort' => Yii::t('pages', 'Display order'),
            'section_id' => Yii::t('pages', 'Section'),
        ];
    }

    /**
     * @return array
     */
    public function getSavedAttributes(): array
    {
        return [
            'name',
            'body',
            'active',
            'sort',
            'section_id',
        ];
    }
}