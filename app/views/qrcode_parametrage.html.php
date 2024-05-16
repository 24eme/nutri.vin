<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
  <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/qrcode/<?php echo $qrcode->user_id ?>/list">Liste de vos QR Codes</a></li>
      <li class="breadcrumb-item active" aria-current="page">Visualisation de votre QR Code</li>
  </ol>
</nav>

<div class="row">
    <div class="col-2">
        <a href="/qrcode/<?php echo $qrcode->user_id ?>/list" class="btn btn-light"><i class="bi bi-chevron-compact-left"></i> Retour à la liste</a>
    </div>
    <div class="col-8">
        <h1 class="text-center mb-4">Visualisation de votre QR Code</h1>
    </div>
</div>
<div class="row justify-content-end">
    <div class="col-4 offset-1">
        <?php $iframe = true; ?>
        <?php include('_phone.html.php') ?>
    </div>
    <div class="col-6 mt-5 offset-1 border-start">
        <form id="logoForm" method="POST" action="/qrcode/<?php echo $qrcode->user_id ?>/parametrage/<?php echo $qrcode->id ?>" enctype="multipart/form-data">
            <div class="d-flex justify-content-center align-items-center flex-column">
                <img src="/<?php echo $qrcode->id ?>/svg" class="img-thumbnail" style="height: 350px; width: 350px;">
                <div class="form-check form-switch">
                    <input class="form-check-input" style="cursor: pointer" type="checkbox" role="switch" name="logo" id="switch-logo-qrcode"<?php echo $qrcode->logo ? 'checked' : ''?>>
                    <label class="form-check-label" style="cursor: pointer" for="switch-logo-qrcode">Afficher le logo au centre du qrcode</label>
                </div>
            </div>
        </form>

        <div class="mt-5 text-center">
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-download"></i> Télécharger le qrcode
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" target="_blank" href="/<?php echo $qrcode->id ?>/pdf#zoom=1000">PDF</a></li>
                    <li><a class="dropdown-item" target="_blank" href="/<?php echo $qrcode->id ?>/svg">SVG</a></li>
                    <li><a class="dropdown-item" href="/<?php echo $qrcode->id ?>/eps">EPS</a></li>
                </ul>
            </div>
        </div>

        <div class="mt-5 text-center">
            <a href="/qrcode/<?php echo $qrcode->user_id ?>/edit/<?php echo $qrcode->id ?>" class="btn btn-secondary">Retour à l'édition</a>
            <a href="/<?php echo $qrcode->id ?>" class="btn btn-secondary">Voir la page finale</a>
        </div>
    </div>
</div>

    <script>
    const checkbox = document.getElementById('switch-logo-qrcode');

    checkbox.addEventListener('change', function() {
        document.getElementById('logoForm').submit();
    });
</script>
