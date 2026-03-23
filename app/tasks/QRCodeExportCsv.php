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

$csv = new SplFileObject('php://stdout');
$csv->setCsvControl(';');

$csv->fputcsv([
    'QRCode',
    'ID Utilisateur',
    'Nom responsable',
    'Siret responsable',
    'Dénomination',
    'Couleur',
    'Domaine',
    'Cuvée',
    'Volume d\'alcool',
    'Centilisation',
    'Millésime',
    'Lot',
    'Labels',
    'Facturable',
    'Lien',
]);

foreach (QRCode::findAll(false) as $qrcode) {
    $csv->fputcsv([
        $qrcode->getId(),
        $qrcode->user_id,
        $qrcode->responsable_nom,
        $qrcode->responsable_siret,
        $qrcode->denomination,
        $qrcode->couleur,
        $qrcode->domaine_nom,
        $qrcode->cuvee_nom,
        $qrcode->alcool_degre,
        $qrcode->centilisation,
        $qrcode->millesime,
        $qrcode->lot,
        implode(', ', json_decode($qrcode->labels)),
        $qrcode->denomination_instance ? 'NON' : 'OUI', // facturable ?
        $URLBASE.$qrcode->getId(),
    ]);
}

if (getenv('DEBUG')) {
    echo "...End".PHP_EOL;
    $time_end = microtime(true);
    $time = $time_end - $time_start;
    echo "Elapsed time: ".$time." secondes".PHP_EOL;
    echo "Memory usage: ".memory_get_usage().' (peak: '.memory_get_peak_usage().')'.PHP_EOL;
}
