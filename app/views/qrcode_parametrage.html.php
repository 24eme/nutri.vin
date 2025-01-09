<nav id="breadcrumb" class="small" aria-label="breadcrumb">
  <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/qrcode/<?php echo $qrcode->user_id ?>/list">Liste de vos QR Codes</a></li>
      <li class="breadcrumb-item active" aria-current="page">Visualisation de votre QR Code</li>
  </ol>
</nav>

<div class="mb-4">
    <h1 class="text-center">Visualisation de votre QR Code <a class="btn btn-link" href="/qrcode/<?php echo $qrcode->user_id ?>/edit/<?php echo $qrcode->getId() ?>"><i class="bi bi-pencil-fill"></i> Modifier</a></h1>
</div>
<div class="row justify-content-end">
    <div class="col-4 offset-1">
        <?php $iframe = true; ?>
        <?php include('_phone.html.php') ?>
    </div>
    <div class="col-6 offset-1 border-start">
        <form id="logoForm" method="POST" action="/qrcode/<?php echo $qrcode->user_id ?>/parametrage/<?php echo $qrcode->getId() ?>">
            <div class="d-flex justify-content-center align-items-center flex-column">
                <p class="text-center mb-4 alert alert-success border-3 p-2 mx-4 shadow-sm" style="opacity: 0.5;"><strong>Le QR Code a été créé et il ne changera pas.</strong><br class="mb-1" />Il peut dés maintenant être téléchargé et transmis à l'impression et les informations de la fiche peuvent être modifiées à tout moment.</p>

                <div class="shadow-sm p-2 bg-light border rounded">
                    <img src="/<?php echo $qrcode->getId() ?>/svg" class="img-thumbnail" style="height: 375px; width: 375px;">
                </div>

                <div class="text-align-start mt-2">
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" style="cursor: pointer" type="checkbox" role="switch" value="1" name="mentions" id="switch-mentions-qrcode"<?php echo $qrcode->mentions ? ' checked' : ''?>>
                        <label class="form-check-label" style="cursor: pointer" for="switch-mentions-qrcode">Intégrer les mentions obligatoires autour du QR Code</label>
                    </div>
                    <div class="form-check form-switch mt-1">
                        <input class="form-check-input" style="cursor: pointer" type="checkbox" role="switch" value="1" name="logo" id="switch-logo-qrcode"<?php echo ($qrcode->logo && $canSwitchLogo) ? ' checked' : ''?> <?php echo $canSwitchLogo === false ? ' disabled' : '' ?>>
                        <label class="form-check-label" style="cursor: pointer" for="switch-logo-qrcode">Intégrer le logo au centre du QR Code<?php if (!$canSwitchLogo) { echo "<small> (Dénomination non compatible)</small>";} ?></label>
                    </div>
                    <div class="form-check form-switch mt-1">
                        <input class="form-check-input" style="cursor: pointer" type="checkbox" role="switch" value="1" name="gs1" id="switch-gs1" <?php if (!$qrcode->ean): ?> data-bs-toggle="modal" data-bs-target="#gs1"<?php endif; ?><?php if ($qrcode->gs1): ?>checked<?php endif; ?>></input>
                        <label class="form-check-label" style="cursor: pointer" for="switch-gs1">Compatible avec la norme GS1 <small>(peut augmenter légèrement la taille du QR Code)</small></label>
                    </div>
                </div>
            </div>
        </form>

            <div class="modal fade" id="gs1" tabindex="-1" aria-labelledby="gs1-Label" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5" id="gs1-Label">Compatibilité avec la norme GS1</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <p class="text-align-justify">
                        La norme GS1 permettra au QR Code d'être scanné au même titre que le code barre par les lecteurs nouvelles génération afin d'éviter la confusion entre le QR Code et le code barre.
                        <br>
                        <br>
                        Pour que votre QR Code soit compatible avec cette norme, vous devez ajouter un code-barre EAN-13 dans sa fiche produit.
                    </p>
                  </div>
                  <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <a type="button" class="btn btn-secondary" href="/qrcode/<?php echo $qrcode->user_id ?>/edit/<?php echo $qrcode->getId() ?>#ean">Saisir un code-barre</a>
                  </div>
                </div>
              </div>
            </div>

        <div class="mt-4 text-center">
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-download"></i> Télécharger le QR Code
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/<?php echo $qrcode->getId() ?>/eps">EPS</a></li>
                    <li><a class="dropdown-item" target="_blank" href="/<?php echo $qrcode->getId() ?>/pdf">PDF</a></li>
                    <li><a class="dropdown-item" target="_blank" href="/<?php echo $qrcode->getId() ?>/svg">SVG</a></li>
                </ul>
            </div>
        </div>
        <div class="mt-4 text-center">
            <a class="btn btn-link" target="_blank" href="/<?php echo $qrcode->getId(); ?>/fiche">Télécharger la fiche d'accompagnement</a>
        </div>
    </div>
</div>

<div class="mt-5 row">
    <div class="col-4">
        <a href="/qrcode/<?php echo $qrcode->user_id ?>/list" class="btn btn-light"><i class="bi bi-chevron-compact-left"></i> Retour à la liste</a>
    </div>
    <div class="col-4 text-center">
        <a class="btn btn-light" href="/qrcode/<?php echo $qrcode->user_id ?>/edit/<?php echo $qrcode->getId() ?>"><i class="bi bi-pencil-fill"></i> Modifier</a>
    </div>
</div>

<script>
    const checkboxes = [document.getElementById('switch-logo-qrcode'), document.getElementById('switch-mentions-qrcode'), document.getElementById('switch-gs1')];

    checkboxes.forEach((item, i) => {
        item.addEventListener('change', function() {
            if (item.id == "switch-gs1") {
                <?php if (! $qrcode->ean): ?>
                item.checked = false;
                    return;
                <?php endif;?>
            }
            document.getElementById('logoForm').submit();
        });
    });

    <?php if (isset($from)): ?>
        history.pushState({ page: "edit" }, "edit", "<?php echo $urlbase."/qrcode/".$qrcode->user_id."/edit/".$qrcode->getId(); ?>");
        history.pushState({ page: "parametrage" }, "parametrage", "<?php echo $urlbase."/qrcode/".$qrcode->user_id."/parametrage/".$qrcode->getId(); ?>");

        window.addEventListener("popstate", (event) => {
            history.replaceState({ page: "edit" }, "edit", "<?php echo $urlbase."/qrcode/".$qrcode->user_id."/edit/".$qrcode->getId(); ?>");
            window.location.reload();
        });
    <?php endif; ?>
</script>
