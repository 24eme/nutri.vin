<div class="py-2 bg-white">
    <div class="container">
        <h1 class="fs-5 d-inline-block text-primary">
            <a href="/"><img class="me-5" src="https://declaration.ivsopro.com/images/logo_site_ivso.png" alt="France sud ouest. Les vins à découvrir."></a>
            Espace de création de QR Code pour les vins et spiritueux du Sud-ouest
        </h1>
        <div class="float-end d-inline-block py-3">
            <img class="pe-5" src="https://declaration.ivsopro.com/images/logo_armagnac.png" alt="Armagnac">
            <img class="pe-5" src="https://declaration.ivsopro.com/images/logo_floc.png" alt="Floc de Gascogne">
            <img src="https://declaration.ivsopro.com/images/logo_cahors.png" alt="Cahors A.O.C">
        </div>
    </div>
</div>

<nav class="navbar navbar-expand-lg bg-white border-top">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/qrcode">Mes QRCodes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://declaration.ivsopro.com/accueil">Déclarations ↗</a>
                </li>
                <?php if (isset($is_admin) && $is_admin): ?>
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
