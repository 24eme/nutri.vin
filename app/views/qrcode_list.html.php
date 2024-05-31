<nav id="breadcrumb" class="small" aria-label="breadcrumb">
  <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">Liste de vos QR Codes</li>
    </ol>
</nav>

<h2 class="text-center mb-5"><?php echo htmlspecialchars($_SESSION["username"]);?> QR Codes</h2>

<div class="text-end">
    <form id="multiExportForm" method="GET" action="/qrcode/<?php echo $userid ?>/multiexport" enctype="multipart/form-data">
        <div class="col">
            <?php if ($qrlist): ?>
                <button type="submit" id="multiExportBtn" class="btn btn-light mb-2" disabled>Télécharger la sélection</button>
            <?php endif; ?>
        </div>
    </form>
    <table id="list_qr" class="table table-bordered table-striped text-center">
        <thead>
            <tr>
                <th class="col-3">Nom commercial</th>
                <th class="col-5">Vin</th>
                <th class="col-1">Nb vues</th>
                <th class="col-2">Actions</th>
                <th class="col-1"><input id="allCheck" type="checkbox"></input></th>
            </tr>
        </thead>
        <?php if (!$qrlist): ?>
            <tbody>
                <tr><td colspan=5><center><i>Vous n'avez pas encore créé de QRCode</i></center></td></tr>
            </tbody>
        <?php else: ?>
            <tbody>

                <?php foreach($qrlist as $qr): ?>
                    <tr>
                        <td><?php echo $qr->domaine_nom; ?></td>
                        <td>
                            <?php echo $qr->cuvee_nom; ?>
                            <?php echo $qr->denomination; ?> <?php echo $qr->couleur; ?>
                            <?php echo $qr->millesime; ?>
                            <?php echo ($qr->centilisation) ? ' - '.$qr->centilisation . ' cl' : ''; ?>
                        </td>
                        <td><?php if ($qr->visites) {
                                echo "<a href='$urlbase/qrcode/".$qr->user_id."/stats/".$qr->getId()."/week'>";
                                echo count( (array) json_decode($qr->visites) );
                            }else {
                                echo 0;
                            }
                        ?></td>
                        <td>
                            <a data-bs-toggle="tooltip" title="Modifier" class="p-1 text-dark" href="/qrcode/<?php echo $qr->user_id ?>/edit/<?php echo $qr->getId() ?>"><i class="bi bi-pencil-fill"></i></a>
                            <a data-bs-toggle="tooltip" title="Visualiser et télécharger" class="p-1 text-dark" href="/qrcode/<?php echo $qr->user_id ?>/parametrage/<?php echo $qr->getId() ?>"><i class="bi bi-qr-code"></i></a>
                            <a data-bs-toggle="tooltip" title="Voir les statistiques d'utilisations" class="p-1 text-dark" href="/qrcode/<?php echo $qr->user_id ?>/stats/<?php echo $qr->getId() ?>/week"><i class="bi bi-clipboard2-data"></i></a>
                            <a data-bs-toggle="tooltip" title="Dupliquer (créer un nouveau QR Code à partir des informations de celui-ci)" href="/qrcode/<?php echo $qr->user_id ?>/duplicate/<?php echo $qr->getId() ?>" class="text-dark float-end"><i class="bi bi-copy"></i></a>
                        </td>
                        <td><input form="multiExportForm" type="checkbox" name="qrcodes[]" value='<?php echo $qr->getId(); ?>'></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        <?php endif; ?>
    </table>

    <div class="col">
        <a href="/qrcode/<?php echo $userid ?>/create" class="btn btn-primary pull-end">Créer un nouveau QRCode</a>
    </div>
</div>

<?php if (!$qrlist): ?>
<div class="modal" id="modal-info-list" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modalTitle">À votre aimable attention</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Ce service vous est mis à disposition par l'IVSO.</p>
        <p>Vous ne serez facturé que si la dénomination de votre vin ne figure pas dans le catalogue de l'interprofession.</p>

        <p>Happy QRCoding !</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Démarrer</button>
      </div>
    </div>
  </div>
</div>
<?php endif ?>

<script>

document.getElementById('allCheck').addEventListener("click", function() {
    document.querySelectorAll('[name^=qrcodes]').forEach(function (checkbox) {
        checkbox.checked = document.getElementById('allCheck').checked;
    });
});

document.querySelector('#list_qr').addEventListener('change', function (e) {
    if (e.target.type == 'checkbox' ) {
        document.getElementById('multiExportBtn').disabled = document.querySelectorAll('[name^=qrcodes]:checked').length == 0;
    }
})

<?php if (! $qrlist): ?>
document.addEventListener('DOMContentLoaded', function () {
    const modal = new bootstrap.Modal('#modal-info-list');
    modal.show();
});
<?php endif ?>
</script>
