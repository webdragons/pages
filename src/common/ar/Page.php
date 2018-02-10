<?php

namespace bulldozer\pages\common\ar;

use bulldozer\db\ActiveRecord;
use bulldozer\users\models\User;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%static_pages}}".
 *
 * @property integer $id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $creator_id
 * @property integer $updater_id
 * @property string $name
 * @property string $slug
 * @property string $body
 * @property integer $section_id
 * @property integer $active
 * @property integer $sort
 *
 * @property Section $section
 * @property User $creator
 * @property User $updater
 * @property-read string $viewUrl
 * @property-read string $fullViewUrl
 */
class Page extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'creator_id',
                'updatedByAttribute' => 'updater_id',
            ],
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'name',
                'slugAttribute' => 'slug',
                'ensureUnique' => true,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%static_pages}}';
    }

    /**
     * @return ActiveQuery
     */
    public function getCreator(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'creator_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUpdater(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'updater_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSection(): ActiveQuery
    {
        return $this->hasOne(Section::className(), ['id' => 'section_id']);
    }

    /**
     * @param bool $full
     * @return string
     */
    public function getViewUrl(bool $full = false): string
    {
        if ($this->section_id === 0) {
            return Url::to(['/pages/pages/view-with-one-param', 'param1' => $this->slug], $full);
        } else {
            return Url::to(['/pages/pages/view-with-two-params', 'param1' => $this->section->slug, 'param2' => $this->slug], $full);
        }
    }

    /**
     * @return string
     */
    public function getFullViewUrl(): string
    {
        return $this->getViewUrl(true);
    }
}
