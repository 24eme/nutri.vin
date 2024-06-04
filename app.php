<?php

use app\models\DBManager;
use app\config\Config;

$f3 = require(__DIR__.'/vendor/fatfree-core/base.php');

require __DIR__.'/vendor/autoload.php';

require_once('config/instances.php');

if(getenv("DEBUG")) {
    $f3->set('DEBUG', getenv("DEBUG"));
}

$f3->set('ROOT', __DIR__);
$f3->set('UI', $f3->get('ROOT')."/app/views/");
$f3->set('THEME', implode(DIRECTORY_SEPARATOR, [$f3->get('ROOT'), "themes", Config::getInstance()->get('theme'), '']));

if (is_dir($f3->get('THEME')) === false) {
    $f3->set('THEME', implode(DIRECTORY_SEPARATOR, [$f3->get('ROOT'), "themes", 'nutrivin', '']));
}

setlocale(LC_ALL, '');
$f3->language(isset($f3->get('HEADERS')['Accept-Language']) ? $f3->get('HEADERS')['Accept-Language'] : '');

$f3->set('SUPPORTED_LANGUAGES',
    [
        'en_US.utf8' => 'English',
        'fr_FR.utf8' => 'Français',
    ]);
if ($f3->get('GET.lang')) {
    selectLanguage($f3->get('GET.lang'), $f3);
} else {
    selectLanguage($f3->get('LANGUAGE'), $f3);
}

if (!$f3->get('current_language')) {
    $f3->set('current_language', 'fr_FR.utf8');
}

$domain = basename(glob($f3->get('ROOT')."/locale/application.pot")[0], '.pot');

bindtextdomain($domain, $f3->get('ROOT')."/locale");
textdomain($domain);

$f3->set('urlbase', Config::getInstance()->getUrlbase());

DBManager::createDB(Config::getInstance()->get('db_pdo'));

include('app/routes.php');


function selectLanguage($lang, $f3) {
    $langSupported = null;
    foreach(explode(',', $lang) as $l) {
        if(array_key_exists($l, $f3->get('SUPPORTED_LANGUAGES'))) {
            $langSupported = $l;
            break;
        }
    }
    if(!$langSupported) {
        return null;
    }
    $f3->set('current_language', $langSupported);
    putenv("LANGUAGE=$langSupported");
}




return $f3;
