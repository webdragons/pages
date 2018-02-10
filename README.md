Pages module
============
Модуль управления страницами для Bulldozer CMF

Установка
------------
Подключить в composer:
```
composer require bulldozer/pages "*"
```

Добавить в backend\config\main.php:
```
return [
    'components' => [
        'menu' => [
            'class' => \bulldozer\components\BackendMenu::class,
            'modules' => [
                'pages',
            ]
        ],
    ],
    'modules' => [
        'pages' => [
            'class' => \bulldozer\pages\backend\Module::class,
        ],
    ],
]
```

Добавить в frontend\config\main.php:
```
return [
    'components' => [
        'urlManager' => [
            'rules' => [
                [
                    'class' => \bulldozer\pages\frontend\PageUrlRule::class,
                    'pattern' => '<param1:[\w_-]+>/',
                    'route' => 'pages/pages/view-with-one-param',
                ],
                [
                    'class' => \bulldozer\pages\frontend\PageUrlRule::class,
                    'pattern' => '<param1:[\w_-]+>/<param2:[\w_-]+>',
                    'route' => 'pages/pages/view-with-two-params',
                ],
                ...
            ],
        ],
    ],
    'modules' => [
        'pages' => [
            'class' => \bulldozer\pages\frontend\Module::class',
        ],
    ],
]
```

Добавить в console\config\main.php:
```
return [
    'controllerMap' => [
        ...
        'migrate' => [
            'class' => \yii\console\controllers\MigrateController::class,
            'migrationNamespaces' => [
                ...
                'bulldozer\pages\console\migrations',
                ...
            ],
        ],
        ...
    ],
]
```