<nav id="breadcrumb" class="small" aria-label="breadcrumb">
  <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/qrcode/<?php echo $qrcode->user_id ?>/list">Liste de vos QR Codes</a></li>
      <li class="breadcrumb-item"><a href="/qrcode/<?php echo $qrcode->user_id ?>/parametrage/<?php echo $qrcode->getId(); ?>"><?php echo $qrcode->getId(); ?></a></li>
      <li class="breadcrumb-item active" aria-current="page">Statistique relative à un QR Code</li>
  </ol>
</nav>

<div class="mb-4">
    <h1 class="text-center">Stats d'un QRcode</h1>
    <h2 class="text-center text-muted">
    <?php echo $qrcode->cuvee_nom; ?>
    <?php echo $qrcode->denomination; ?> <?php echo $qrcode->couleur; ?>
    <?php echo $qrcode->millesime; ?>
    <?php echo ($qrcode->centilisation) ? ' - '.$qrcode->centilisation . ' cl' : ''; ?>
    </h2>
</div>
<div class="row">
<div class="offset-1 col-10">
<ul class="nav nav-tabs mb-4">
  <li class="nav-item">
    <a class="nav-link<?php if  ($type == 'week') echo ' active' ; ?>" href="week">Vue hebdomadaire</a>
  </li>
  <li class="nav-item">
    <a class="nav-link<?php if  ($type == 'geo') echo ' active' ; ?>" href="geo">Vue géographique</a>
  </li>
</ul>
<p>Voici les statistiques disponibles pour cette vue :</p>
<table class="table">
    <thead>
            <th><?php echo ($type == 'week') ? 'Date' : 'Localisation'; ?></th>
            <th>Visites</th>
    </thead>
    <tbody>
    <?php if (!$qrcode->getVisites()): ?>
        <tr><td colspan="2" class="text-center">Ce QRCode n'a pas encore été visité</td></tr>
    <?php else: ?>
    <?php foreach($qrcode->getStats($type) as $k => $s): ?>
    <tr>
        <?php if ($type == 'week'): ?>
        <td>Semaine du <?php echo $s['title']; ?></td>
        <?php else: ?>
        <td><?php echo $s['title']; ?></td>
        <?php endif; ?>
        <td class="text-end"><?php echo $s['nb']; ?></td>
    </tr>
    <?php endforeach ?>
    <?php endif ?>
    </tbody>
</table>
<p class="text-muted pt-3">Le site ne collecte ou ne suit aucun utilisateur. Les seules informations issues des visites sont la date de la première visite de la page et une évaluation de la localisation de la connexion. Aucune information à caractère personnel n'est collectée, aucun cookie n'est déposé lors de la lecture d'une fiche d'un vin.</p>
</div>

<div class="mt-5">
    <div class="row">
    <p class="col-6"><a href="/qrcode/<?php echo $qrcode->user_id ?>/list" class="btn btn-light"><i class="bi bi-chevron-compact-left"></i> Retour à la liste</a></p>
</div>

</div>
