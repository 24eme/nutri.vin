<?php

use app\models\DBManager;
use app\models\QRCode;
use app\config\Config;
use tests\factories\QRCodeFactory;

$test = require __DIR__.'/_bootstrap.php';

if (strpos(Config::getInstance()->get('db_pdo'), "couchdb:") !== 0) {
    die('Pas la bonne connexion spécifiée.'.PHP_EOL);
}

QRCode::createTable();
$mapper = DBManager::getMapper();
$mapper = new $mapper(DBManager::getDB(), QRCode::class);

for ($i = 0; $i < 3; $i++) {
    $qr = QRCodeFactory::create($i % 2 ? 'notuserid' : 'userid');
    $qr->save();
}

// Tests mapper
$qrs = $mapper->findAll();

$test->expect(count($qrs) === 3, "CouchDB récupère bien 3 qrcodes");

$qr = current($qrs);
$firstqr = $mapper->find(["_id" => $qr->_id]);

$test->expect(count($firstqr) === 1, "Le find retourne un qrcode");
$test->expect(current($firstqr)->_id === $qr->_id, "Le find retourne le bon qrcode");

$test->expect($mapper->count(['user_id', 'userid']) === 2, "On compte bien 2 qrcode avec la fonction count avec l'utilisateur userid");
$test->expect($mapper->count(['user_id', 'notuserid']) === 1, "On compte bien 1 qrcode avec la fonction count avec l'utilisateur notuserid");

$test->expect(count($mapper->select(null, ['user_id', 'userid'])) === 2, "Le select avec un filtre autre que _id retourne bien plusieurs résultats");
$test->expect(count($mapper->select(null, ['_id', $qr->_id])) === 1, "Le select avec un filtre _id retourne un résultat");

$mapper->set('test_value', 'test');
$test->expect($mapper->get('test_value') === 'test', "Le setter enregistre une propriété inconnue dans l'objet");
$test->expect(in_array("test_value", $mapper->fields()) === true, "Une propriété inconnue est pas enregistré dans le noeud document");
$test->expect(in_array("user_id", $mapper->fields()) === true, "Une propriété connue est enregistré dans le noeud document");
$mapper->set('user_id', "userid");
$test->expect($mapper->get('user_id') === 'userid', "Le setter enregistre bien une valeur connue");

$mapper->clear('test_value');
$test->expect($mapper->exists('test_value') === false, "Le clear enleve bien les valeurs");

include __DIR__.'/_print.php';
