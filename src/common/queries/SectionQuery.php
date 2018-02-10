<?php

namespace bulldozer\pages\common\queries;

use creocoder\nestedsets\NestedSetsQueryBehavior;
use yii\db\ActiveQuery;

/**
 * Class SectionQuery
 * @package bulldozer\pages\common\queries
 *
 * @mixin NestedSetsQueryBehavior
 */
class SectionQuery extends ActiveQuery
{
    public function behaviors(): array
    {
        return [
            NestedSetsQueryBehavior::class,
        ];
    }
}