<?php

namespace bulldozer\pages\common\ar;

use bulldozer\db\ActiveRecord;
use bulldozer\pages\common\queries\SectionQuery;
use bulldozer\users\models\User;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%static_sections}}".
 *
 * @property integer $id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $creator_id
 * @property integer $updater_id
 * @property string $name
 * @property string $slug
 * @property integer $left
 * @property integer $right
 * @property integer $depth
 * @property integer $tree
 * @property integer $active
 * @property integer $sort
 *
 * @property-read string $viewUrl
 * @property-read string $fullViewUrl
 *
 * @mixin NestedSetsBehavior
 */
class Section extends ActiveRecord
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
            'tree' => [
                'class' => NestedSetsBehavior::class,
                'treeAttribute' => 'tree',
                'leftAttribute' => 'left',
                'rightAttribute' => 'right',
                'depthAttribute' => 'depth',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%static_sections}}';
    }

    /**
     * @inheritdoc
     */
    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return new SectionQuery(static::class);
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
     * @param bool $full
     * @return string
     */
    public function getViewUrl(bool $full = false): string
    {
        return Url::to(['/pages/pages/view-with-one-param', 'param1' => $this->slug], $full);
    }

    /**
     * @return string
     */
    public function getFullViewUrl(): string
    {
        return $this->getViewUrl(true);
    }
}
