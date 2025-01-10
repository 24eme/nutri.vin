<!doctype html>
<html lang="<?php echo substr($current_language, 0, 2); ?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nutri.Vin - Plateforme open source de QR Code nutritionnel pour le vin</title>
    <meta name="description" content="Plateforme open source et communautaire de QR Code pour la déclaration nutritionnelle de vins" />
    <link rel="icon" type="image/png" sizes="16x16" rel="noopener" target="_blank" href="/images/favicons/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" rel="noopener" target="_blank" href="/images/favicons/favicon-32x32.png">
    <link rel="apple-touch-icon" sizes="180x180" rel="noopener" target="_blank" href="/images/favicons/apple-touch-icon.png">
    <link rel="manifest" href="/images/favicons/site.webmanifest">
    <link href="/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/css/bootstrap-icons.min.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="/css/common.css" rel="stylesheet" />
    <?php include($THEME.'css.php'); ?>
  </head>
  <body id="body_public">
    <div class="container-sm text-center" style="max-width: 540px;">
        <div class="dropdown position-absolute top-0 end-0 mt-2 me-2">
                <button class="btn opacity-75 btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="d-none d-md-inline"><i class='bi bi-translate'></i></span>
                    <span class="d-md-none"><i class="bi bi-translate"></i></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <?php foreach ($SUPPORTED_LANGUAGES as $key => $langue):?>
                        <li><a class="dropdown-item" href="?lang=<?php echo $key ?>"><?php if($current_language == $key):?><strong><?php endif;?><?php echo $langue ?><?php if($current_language == $key):?></strong><?php endif;?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php include($THEME.'header_public.php'); ?>
        <?php include($content); ?>
    </div>
  </body>
  <script src="/js/bootstrap.bundle.min.js"></script>
</html>
