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

$csv_unsorted = new SplFileObject('php://temp', 'rw');
$csv_unsorted->setCsvControl(';');

foreach (QRCode::findAll(false) as $qrcode) {
    $csv_unsorted->fputcsv([
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

$users_ids = [];
$csv_unsorted->rewind();
while (! $csv_unsorted->eof()) {
    $users_ids[] = $csv_unsorted->fgetcsv()[1];
}
$csv_unsorted->rewind();

$users_ids = array_unique($users_ids);
sort($users_ids);

ob_flush();

$csv = new SplFileObject('php://output', 'w');
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

foreach ($users_ids as $user_id) {
    while (! $csv_unsorted->eof()) {
        $row = $csv_unsorted->fgetcsv();
        if ($row[1] === $user_id) {
            $csv->fputcsv($row);
        }

        $csv->fflush();
    }

    $csv_unsorted->rewind();
}

if (getenv('DEBUG')) {
    echo "...End".PHP_EOL;
    $time_end = microtime(true);
    $time = $time_end - $time_start;
    echo "Elapsed time: ".$time." secondes".PHP_EOL;
    echo "Memory usage: ".memory_get_usage().' (peak: '.memory_get_peak_usage().')'.PHP_EOL;
}
