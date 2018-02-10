<?php

namespace bulldozer\pages\frontend;

use bulldozer\pages\frontend\ar\Page;
use bulldozer\pages\frontend\ar\Section;
use yii\web\UrlRule;

/**
 * Class PageUrlRule
 * @package bulldozer\pages\frontend
 */
class PageUrlRule extends UrlRule
{
    /**
     * @inheritdoc
     */
    public function parseRequest($manager, $request)
    {
        $result = parent::parseRequest($manager, $request);

        if ($result) {
            $params = $result[1];

            if (isset($params['param2'])) {
                $page = Page::findOne(['slug' => $params['param2']]);
                $section = Section::findOne(['slug' => $params['param1']]);

                if ($page === null || $section === null) {
                    return false;
                }
            } else {
                $page = Page::findOne(['slug' => $params['param1']]);
                $section = Section::findOne(['slug' => $params['param1']]);

                if ($page === null && $section === null) {
                    return false;
                }
            }
        }

        return $result;
    }
}