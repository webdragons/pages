<?php

namespace bulldozer\pages\frontend\ar;

use himiklab\sitemap\behaviors\SitemapBehavior;
use yii\db\ActiveQuery;

/**
 * Class Page
 * @package frontend\modules\pages\models
 */
class Page extends \bulldozer\pages\common\ar\Page
{
    /**
     * @inheritdoc
     */
    public static function find() : ActiveQuery
    {
        $query = parent::find();
        $query->andWhere([self::tableName() . '.active' => 1]);

        return $query;
    }

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['sitemap'] = [
            'class' => SitemapBehavior::class,
            'dataClosure' => function (self $model) {
                return [
                    'loc' => $model->fullViewUrl,
                    'lastmod' => $model->updated_at,
                    'changefreq' => SitemapBehavior::CHANGEFREQ_DAILY,
                    'priority' => 0.8
                ];
            }
        ];

        return $behaviors;
    }
}