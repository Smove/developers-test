<?php

declare(strict_types=1);

spl_autoload_register(static function (string $className) {
    if (!str_starts_with($className, 'Jan\\DevelopersTest\\')) {
        return;
    }

    $targetFile = __DIR__ . '/files/' . str_replace('\\', '/', mb_substr($className, 19)) . '.php';

    if (!file_exists($targetFile)) {
        return;
    }

    include $targetFile;
});
