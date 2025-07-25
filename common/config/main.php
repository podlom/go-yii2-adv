<?php

use yii\caching\FileCache;

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => FileCache::class,
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'defaultTimeZone' => 'Europe/Kyiv',
            'timeZone' => 'Europe/Kyiv', // для користувача
        ],
    ],
    'timeZone' => 'Europe/Kyiv',
];
