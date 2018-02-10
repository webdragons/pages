<?php

namespace bulldozer\pages\frontend\ar;

use bulldozer\pages\common\queries\SectionQuery;
use himiklab\sitemap\behaviors\SitemapBehavior;

/**
 * Class Section
 * @package frontend\modules\pages\models
 */
class Section extends \bulldozer\pages\common\ar\Section
{
    /**
     * @inheritdoc
     */
    public static function find() : SectionQuery
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