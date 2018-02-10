<?php

namespace bulldozer\pages\backend;

use bulldozer\App;
use bulldozer\base\BackendModule;
use Yii;
use yii\i18n\PhpMessageSource;

/**
 * Class Module
 * @package bulldozer\pages\backend
 */
class Module extends BackendModule
{
    public $defaultRoute = 'sections';

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
        $validRoutes = ['pages', 'sections'];
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

    /*
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $action->controller->view->params['breadcrumbs'][] = ['label' => Yii::t('pages', 'Pages'), 'url' => ['/pages']];

        return parent::beforeAction($action);
    }

    /**
     * @return array
     */
    public function getMenuItems(): array
    {
        $moduleId = isset(App::$app->controller->module) ? App::$app->controller->module->id : '';

        return [
            [
                'label' => Yii::t('pages', 'Pages'),
                'icon' => 'fa fa-pagelines',
                'url' => ['/pages'],
                'rules' => ['pages_manage'],
                'active' => $moduleId == 'pages',
            ],
        ];
    }
}