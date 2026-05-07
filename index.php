<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$rootPath = __DIR__;
$enginePath = $rootPath.DIRECTORY_SEPARATOR.'nw-includes';

if (file_exists($maintenance = $enginePath.DIRECTORY_SEPARATOR.'storage/framework/maintenance.php')) {
    require $maintenance;
}

require $enginePath.DIRECTORY_SEPARATOR.'vendor/autoload.php';

/** @var Application $app */
$app = require_once $enginePath.DIRECTORY_SEPARATOR.'bootstrap/app.php';

$app->handleRequest(Request::capture());
