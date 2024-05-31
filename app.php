<?php

use app\models\DBManager;

$f3 = require(__DIR__.'/vendor/fatfree-core/base.php');

require __DIR__.'/vendor/autoload.php';

require_once('config/config.php');
require_once('config/instances.php');


if(getenv("DEBUG")) {
    $f3->set('DEBUG', getenv("DEBUG"));
}

$f3->set('ROOT', __DIR__);
$f3->set('UI', $f3->get('ROOT')."/app/views/");
$f3->set('THEME', implode(DIRECTORY_SEPARATOR, [$f3->get('ROOT'), "themes", $config['theme'], '']));

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
    selectLanguage($f3->get('GET.lang'), $f3, true);
} elseif (isset($_COOKIE['LANGUAGE'])) {
    selectLanguage($_COOKIE['LANGUAGE'], $f3, true);
} else {
    selectLanguage($f3->get('LANGUAGE'), $f3);
}

if (!$f3->get('current_language')) {
    $f3->set('current_language', 'fr_FR.utf8');
}

$domain = basename(glob($f3->get('ROOT')."/locale/application.pot")[0], '.pot');

bindtextdomain($domain, $f3->get('ROOT')."/locale");
textdomain($domain);

if (isset($config['urlbase'])) {
    $f3->set('urlbase', $config['urlbase']);
}else{
    $port = $f3->get('PORT');
    $f3->set('urlbase', $f3->get('SCHEME').'://'.$_SERVER['SERVER_NAME'].(!in_array($port,[80,443])?(':'.$port):'').$f3->get('BASE'));
}

$instance_id = null;
if ($f3->get('urlbase')) {
    $site = preg_replace('/https?:../', '', $f3->get('urlbase'));
    if (isset($instances[$site])) {
        $instance_id = $instances[$site];
    }
}
if (!$instance_id) {
    if (isset($config['instance_id'])) {
        $instance_id = $config['instance_id'];
    }
}
if (!$instance_id) {
    $instance_id = '0';
}
putenv('INSTANCE_ID='.$instance_id);
$config['instance_id'] = $instance_id;

if (!isset($config['db_pdo']) || !$config['db_pdo']) {
    $config['db_pdo'] = 'sqlite://'.__DIR__.'/db/nutrivin.sqlite';
}
DBManager::createDB($config['db_pdo']);

$f3->set('config', $config);

include('app/routes.php');


function selectLanguage($lang, $f3, $putCookie = false) {
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
    if($putCookie) {
        $cookieDate = strtotime('+1 year');
        setcookie("LANGUAGE", $langSupported, ['expires' => $cookieDate, 'samesite' => 'Strict', 'path' => "/"]);
    }
    $f3->set('current_language', $langSupported);
    putenv("LANGUAGE=$langSupported");
}




return $f3;
