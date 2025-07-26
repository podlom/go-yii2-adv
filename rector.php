<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $config): void {
    // Каталоги для аналізу
    $config->paths([
        __DIR__ . '/common',
        __DIR__ . '/backend',
        __DIR__ . '/frontend',
        __DIR__ . '/console',
    ]);

    // Набори правил (можеш додати більше)
    $config->sets([
        SetList::PHP_81,             // Оновлення синтаксису під PHP 8.1
        SetList::CODE_QUALITY,       // Покращення якості коду
        SetList::TYPE_DECLARATION,   // Додає типи там, де це можливо
        SetList::EARLY_RETURN,       // Заміна вкладених умов на return early
        SetList::DEAD_CODE,          // Видалення мертвого коду
    ]);

    // Виключення (необов'язково)
    $config->skip([
        __DIR__ . '/vendor',
        __DIR__ . '/runtime',
        __DIR__ . '/tests',
        __DIR__ . '/backend/runtime',
        __DIR__ . '/frontend/runtime',
        __DIR__ . '/console/runtime',
    ]);

    // Додаткові параметри
    $config->importNames(); // Додає `use` замість повних імен класів
};
