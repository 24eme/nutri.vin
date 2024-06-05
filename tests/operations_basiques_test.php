<?php

use app\models\QRCode;

// INIT
$test = require __DIR__.'/_bootstrap.php';

// TESTS
QRCode::createTable();
$test->message('Création de la base');

$qr = new QRCode();
$test->message('Création d\'un qrcode');

$test->expect(get_class($qr) === QRCode::class, "QRCode créé un qrcode");

$qr->domaine_nom = "Domaine test";
$qr->user_id = "userid";

$qr->save();

$test->expect($qr->getId() !== null, "Le qrcode a un identifiant : ".$qr->getId());

$old_id = $qr->getId();
$qr = QRCode::findById($qr->getId());
@$test->expect(! is_null($qr), "On retrouve bien le qrcode");
@$test->expect($qr->getId() === $old_id, "Son id est bien le même");
@$test->expect($qr->domaine_nom ===  "Domaine test", "Son domaine est bien le même");

$test->message('On créé un deuxième qrcode');
$qr2 = new QRCode();
$qr2->user_id = "userid";
$qr2->domaine_nom = "Domaine Lorem";
$qr2->logo = true;
$qr2->save();

$test->expect($qr2->getId() !== null, "Le 2ème qrcode a un identifiant : ".$qr2->getId());
$test->expect($qr2->getId() !== $qr->getId(), "Le 2ème qrcode a un identifiant différent du premier");
$qr2 = QRCode::findById($qr2->getId());
$test->expect(! is_null($qr2), "On retrouve bien le 2ème qrcode");

$results = QRCode::findByUserid('userid');
$test->expect(count($results) === 2, "La recherche par userid retourne bien 2 résultats");
$test->expect(get_class($results[0]) === QRCode::class, "Ce sont bien des objets QRCode");
$test->expect(in_array($results[0]->getId(), [$qr->getId(), $qr2->getId()]), "C'est bien 1 des QRcodes qu'on a créé auparavant");

$test->message("On créé un 3ème QRCode, mais un utilisateur différent");
$qr3 = new QRCode();
$qr3->user_id = "not_userid";
$qr3->domaine_nom = "NotUserid Domaine";
$qr3->save();

$results = QRCode::findByUserid('userid');
$test->expect(count($results) === 2, "Il y a toujours 2 résultats pour le premier utilisateur");

$results = QRCode::findByUserid('not_userid');
$test->expect(count($results) === 1, "Le 2ème utilisateur a bien un qrcode");
$test->expect($results[0]->getId() === $qr3->getId(), "C'est bien le QRCode qu'on lui a créé");

$results = QRCode::findByUserid('undefined_userid');
$test->expect(count($results) === 0, "Pas de résultats pour un utilisateur inexistant");

// Affichage des résultats
include __DIR__.'/_print.php';
