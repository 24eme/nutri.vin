<?php

use tests\factories\QRCodeFactory;
use app\models\QRCode;

// INIT
$test = require __DIR__.'/_bootstrap.php';

QRCode::createTable();
$test->message('Création de la base');

$qrcode = QRCodeFactory::create();
$qrcode->image_etiquette = 'data:image/gif;base64,R0lGODdhAQABAPAAAP8AAAAAACwAAAAAAQABAAACAkQBADs='; // petit carré rouge
$qrcode->save();

// mock ne fonctionne pas avec les reroute() :(
// ni les autorisations
// on reproduit le fonctionnement du controller :
$fields = $qrcode->exportToHttp();
$test->expect($fields['domaine_nom'] === $qrcode->domaine_nom, "On exporte les valeurs");
$test->expect(isset($fields['image_etiquette'], $fields['image_etiquette']) === false, "On n'exporte pas les images");
$test->expect(Flash::instance()->hasKey('qrcode.image_etiquette') === true, "On a les images en session flash");

// reroute qrcodeCreate avec en GET : http_query_builder($fields)

$clone = new QRCode();
$clone->user_id = $qrcode->user_id; // pas de clone de l'userid
$clone->clone($fields);
$clone->save();

$test->expect($clone->domaine_nom === $qrcode->domaine_nom, "Le clone reprends les infos du QRCode original");
$test->expect($clone->getId() !== $qrcode->getId(), "Le clone ne reprends pas l'id du QRCode original");
$test->expect($clone->image_etiquette === $qrcode->image_etiquette, "On récupère l'image via la session");
$test->expect(Flash::instance()->hasKey('qrcode.image_etiquette') === false, "La session est clearée");

// Affichage des résultats
include __DIR__.'/_print.php';
