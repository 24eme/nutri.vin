<?php

use app\config\Config;
use app\models\DBManager;

// INIT
$f3 = require(__DIR__.'/../vendor/fatfree-core/base.php');
require __DIR__.'/../vendor/autoload.php';

if (getenv('DEBUG')) {
    $f3->set('DEBUG', getenv('DEBUG'));
}

if ($argc !== 2) {
    echo "Usage : TEST=1 $argv[0] <sqlite:/path/to/db_test.sqite|couchdb:http://127.0.0.1/db_test>".PHP_EOL;
    throw new \Exception("Il manque la connexion à la base");
}

$test = new Test();

/** @return array $config */
Config::getInstance()->set('db_pdo', $argv[1]);
$f3->set('config', Config::getInstance()->getConfig());

/* $dbtype = DBManager::getDB(); */
$db = new \DB\Couch(Config::getInstance()->get('db_pdo'));
try {
    $db->getDBInfos();
    $db->deleteDB();
} catch (\Exception $e) {
    try {
        unlink(str_replace('sqlite:', '', Config::getInstance()->get('db_pdo')));
    } catch (\Exception $e) {
        $test->message('Pas de base existante');
    }
}

DBManager::createDB(Config::getInstance()->get('db_pdo'));

return $test;
