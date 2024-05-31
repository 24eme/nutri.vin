<nav class="navbar navbar-expand-lg bg-body-tertiary">
<div class="container">
  <a class="navbar-brand" href="/qrcode/userid/list"><img src="/images/logo.svg" class="mx-auto d-block" alt="Logo nutrivin" width="42" height="42"></a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="/qrcode">Mes QRCodes</a>
      </li>
      <?php if ($is_admin): ?>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="/admin/setup">Admin</a>
          </li>
      <?php endif; ?>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo $urlbase; ?>/logout">Déconnexion</a>
      </li>
    </ul>
  </div>
</div>
</nav>
