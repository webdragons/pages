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
                \bulldozer\pages\backend\Module::class,
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

Добавить в common\config\main.php:
```
return [
    ...
    'components' => [
        'authManager' => [
            'class' => 'bulldozer\users\rbac\DbManager',
        ],
    ],
    ...
];
```