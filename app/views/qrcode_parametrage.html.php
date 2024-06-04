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
    <div class="col-6 mt-5 offset-1 border-start">
        <form id="logoForm" method="POST" action="/qrcode/<?php echo $qrcode->user_id ?>/parametrage/<?php echo $qrcode->getId() ?>">
            <div class="d-flex justify-content-center align-items-center flex-column">
                <div class="shadow-sm p-3 bg-light border rounded">
                <img src="/<?php echo $qrcode->getId() ?>/svg" class="img-thumbnail" style="height: 375px; width: 375px;">
                </div>
                <div class="form-check form-switch mt-3">
                    <input class="form-check-input" style="cursor: pointer" type="checkbox" role="switch" value="1" name="mentions" id="switch-mentions-qrcode"<?php echo $qrcode->mentions ? ' checked' : ''?>>
                    <label class="form-check-label" style="cursor: pointer" for="switch-mentions-qrcode">Intégrer les mentions obligatoires autour du QRCode</label>
                </div>
                <div class="form-check form-switch mt-1">
                    <input class="form-check-input" style="cursor: pointer" type="checkbox" role="switch" value="1" name="logo" id="switch-logo-qrcode"<?php echo ($qrcode->logo && $canSwitchLogo) ? ' checked' : ''?> <?php echo $canSwitchLogo === false ? ' disabled' : '' ?>>
                    <label class="form-check-label" style="cursor: pointer" for="switch-logo-qrcode">Intégrer le logo au centre du QR Code<?php if (!$canSwitchLogo) { echo " (Dénomination non compatible)";} ?></label>
                </div>
            </div>
        </form>

        <div class="mt-5 text-center">
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
    const checkboxes = [document.getElementById('switch-logo-qrcode'), document.getElementById('switch-mentions-qrcode')];

    checkboxes.forEach((item, i) => {
        item.addEventListener('change', function() {
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
