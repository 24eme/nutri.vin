<?php

use app\models\QRCode;
use app\models\DBManager;
use app\config\Config;

if (php_sapi_name() !== 'cli') {
    throw new Exception('Doit être lancé en shell');
}

if (getenv('DEBUG')) {
    echo "Start...".PHP_EOL;
    $time_start = microtime(true);
}

$f3 = require __DIR__.'/../../vendor/fatfree-core/base.php';
require __DIR__.'/../../vendor/autoload.php';

$URLBASE = Config::getInstance()->getUrlbase().'/';
DBManager::createDB(Config::getInstance()->get('db_pdo'));

QRCode::exportToCsv();

if (getenv('DEBUG')) {
    echo "...End".PHP_EOL;
    $time_end = microtime(true);
    $time = $time_end - $time_start;
    echo "Elapsed time: ".$time." secondes".PHP_EOL;
    echo "Memory usage: ".memory_get_usage().' (peak: '.memory_get_peak_usage().')'.PHP_EOL;
}
