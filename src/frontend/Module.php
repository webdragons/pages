<?php

namespace bulldozer\pages\frontend;

use bulldozer\App;
use bulldozer\base\FrontendModule;
use yii\i18n\PhpMessageSource;

/**
 * pages module definition class
 */
class Module extends FrontendModule
{
    /**
     * @inheritdoc
     */
    public $defaultRoute = 'pages';

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();

        if (empty(App::$app->i18n->translations['pages'])) {
            App::$app->i18n->translations['pages'] = [
                'class' => PhpMessageSource::class,
                'basePath' => __DIR__ . '/../messages',
            ];
        }
    }

    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    public function createController($route)
    {
        $validRoutes  = [$this->defaultRoute];
        $isValidRoute = false;

        foreach ($validRoutes as $validRoute) {
            if (strpos($route, $validRoute) === 0) {
                $isValidRoute = true;
                break;
            }
        }

        return (empty($route) or $isValidRoute)
            ? parent::createController($route)
            : parent::createController("{$this->defaultRoute}/{$route}");
    }
}
