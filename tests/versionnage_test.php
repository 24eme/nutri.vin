<?php
use app\models\QRCode;
use tests\factories\QRCodeFactory;

// INIT
$test = require __DIR__.'/_bootstrap.php';


QRCode::createTable();
$test->message('Création de la base');

$qrTest = QRCodeFactory::create();

$test->expect(
    !$qrTest->getId(),
    "Création d'un QRCode de test"
);

$qrTest->save();

$test->expect(
    $qrTest->getId(),
    "Le QRCode est enregistré en bdd"
);

$test->expect(
    !count($qrTest->getVersions()),
    'Le QRCode n\'a pas de version'
);

$test->expect(
    !count($qrTest->getVisites()),
    'Le QRCode n\'à pas de visite'
);

$qrTest->lot = 'LOT66170';

$test->expect(
    $qrTest->changed(),
    'Le QRCode est modifié'
);

$qrTest->save();

$test->expect(
    !count($qrTest->getVersions()),
    'Le QRCode n\'a toujours pas de version car pas de visite'
);

$qrTest->addVisite(['date' => date('Y-m-d H:i:s')]);
$qrTest->save();

$test->expect(
    count($qrTest->getVisites()) === 1,
    'Le QRCode à 1 visite'
);

$test->expect(
    !count($qrTest->getVersions()),
    'Le QRCode n\'a toujours pas de version'
);

$qrTest->lot = 'LOT75018';

$test->expect(
    $qrTest->changed(),
    'Le QRCode est modifié'
);

$qrTest->save();

$test->expect(
    count($qrTest->getVersions()) === 1,
    'Le QRCode à 1 version'
);

$qrTest->erase();

$test->expect(
    !QRCode::findById($qrTest->getId()),
    "Le QRCode est supprimé en bdd"
);

include __DIR__.'/_print.php';
?>
