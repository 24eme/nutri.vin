<?php

$f3 = require(__DIR__.'/../app.php');

$test = new Test();

$qrTest = new QRCode();
foreach (QRCode::getFieldsAndType() as $field => $type) {
  if ($type == 'VARCHAR(255)') {
    $qrTest->{$field} = 'TEST';
  } elseif ($type == 'FLOAT') {
    $qrTest->{$field} = '6';
  }
}
$qrTest->ingredients = 'Raisins*,  Acide citrique - E330';
$qrTest->date_creation = date('c');

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
?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nutri.Vin - Test</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" type="image/png" sizes="16x16" rel="noopener" target="_blank" href="/images/favicons/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" rel="noopener" target="_blank" href="/images/favicons/favicon-32x32.png">
    <link rel="apple-touch-icon" sizes="180x180" rel="noopener" target="_blank" href="/images/favicons/apple-touch-icon.png">
    <link rel="manifest" href="/images/favicons/site.webmanifest">
    <link href="/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/css/bootstrap-icons.min.css" rel="stylesheet" />
  </head>
  <body>
    <div id="main_container" class="container-sm my-4 p-3">
      <h1>Résultats des tests</h1>
      <table class="table">
        <?php $i=0; foreach ($test->results() as $result): $i++; ?>
          <tr class="table-<?php if ($result['status']): ?>success<?php else: ?>danger<?php endif; ?>">
            <td>#<?php echo $i ?></td>
            <td>
              <?php if ($result['status']): ?><i class="bi bi-check"></i><?php else: ?><i class="bi bi-x"></i><?php endif; ?>
              <?php echo $result['text'] ?>
            </td>
            <td><?php if (!$result['status']) echo $result['source']; ?></td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </body>
</html>
