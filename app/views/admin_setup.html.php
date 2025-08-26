<?php

use app\exporters\utils\rsvgconvert;
use app\config\Config;

?>
<h1>Configuration</h1>
<h2 class="my-4">Installation serveur</h2>
<table class="table">
  <tbody>
    <tr>
        <th class="align-top">connexion à la base de données</th>
            <td class="text-muted"><?php echo Config::getInstance()->get('db_pdo', '<span class="text-danger">Non renseigné</span>'); ?></td>
        <td>
            <?php if ($table_exists): ?>
                <i class="bi bi-check-square text-success"></i>
            <?php else: ?>
                    <span class="text-danger">
                <i class="bi bi-exclamation-octagon-fill"></i>
                <a href="?createtable=true">(Créer la base)</a>
                        </span>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <th class="align-top">schéma de la base</th>
        <td class="text-muted"><?php echo ($schema_error) ? $schema_error : Config::getInstance()->get('db_pdo'); ?></td>
        <td>
            <?php if (!$schema_error): ?>
                <i class="bi bi-check-square text-success"></i>
            <?php else: ?>
                    <span class="text-danger">
                    <i class="bi bi-exclamation-octagon-fill"></i>
                    (Il faut recréer la base)
                    </span>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <th class="align-top">module php sqlite</th>
    <td class="text-muted">php-sqlite3</td>
        <td>
            <?php if (extension_loaded('pdo_sqlite')): ?>
            <i class="bi bi-check-square text-success"></i>
            <?php else: ?>
            <span class="text-danger">
            <i class="bi bi-exclamation-octagon-fill"></i>
            (php-sqlite3 needed)
            </span>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <th class="align-top">module php curl</th>
    <td class="text-muted">php-curl</td>
        <td>
            <?php if (function_exists('curl_error')): ?>
            <i class="bi bi-check-square text-success"></i>
            <?php else: ?>
            <span class="text-danger">
            <i class="bi bi-exclamation-octagon-fill"></i>
            (php-curl needed)
            </span>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <th class="align-top">module php zip</th>
    <td class="text-muted">php-zip</td>
        <td>
            <?php if (method_exists('ZipArchive', 'addFromString')): ?>
            <i class="bi bi-check-square text-success"></i>
            <?php else: ?>
            <span class="text-danger">
            <i class="bi bi-exclamation-octagon-fill"></i>
            (php-zip needed)
            </span>
            <?php endif; ?>
        </td>
    </tr>
<tr>
    <th class="align-top">module php gd</th>
    <td class="text-muted">php-gd</td>
    <td>
      <?php if (function_exists('imagecreatetruecolor')): ?>
      <i class="bi bi-check-square text-success"></i>
      <?php else: ?>
      <span class="text-danger">
      <i class="bi bi-exclamation-octagon-fill"></i>
      (php-gd needed)
      </span>
      <?php endif; ?>
    </td>
</tr>
<tr>
  <th class="align-top">rsvg (pour la conversion eps)</th>
  <td class="text-muted">librsvg2-bin</td>
  <td>
      <?php if (rsvgconvert::commandExists()): ?>
      <i class="bi bi-check-square text-success"></i>
      <?php else: ?>
      <span class="text-warning">
      <i class="bi bi-exclamation-circle"></i>
      (librsvg2-bin needed)
      </span>
      <?php endif; ?>
  </td>
</tr>
<tr>
    <th class="align-top">php max upload size</th>
    <td class="text-muted">upload_max_filesize = <?php echo ini_get('upload_max_filesize'); ?></td>
    <td>
        <?php if (intval(ini_get('upload_max_filesize')) >= 20): ?>
        <i class="bi bi-check-square text-success"></i>
        <?php else: ?>
        <span class="text-warning">
        <i class="bi bi-exclamation-circle"></i>
        (change upload_max_filesize dans php.ini pour au moins 20M)
        </span>
        <?php endif; ?>
    </td>
</tr>
<tr>
    <th class="align-top">php max post size</th>
    <td class="text-muted">post_max_size = <?php echo ini_get('post_max_size'); ?></td>
    <td>
        <?php if (intval(ini_get('post_max_size')) >= intval(ini_get('upload_max_filesize')) * 3 ): ?>
        <i class="bi bi-check-square text-success"></i>
        <?php else: ?>
        <span class="text-warning">
        <i class="bi bi-exclamation-circle"></i>
        (change post_max_size dans php.ini pour trois fois la valeur upload_max_filesize)
        </span>
        <?php endif; ?>
    </td>
</tr>
<tr>
    <th class="align-top">configuration complète</th>
    <td class="text-muted">config/config.php</td>
    <td>
        <?php if (
                   Config::getInstance()->exists('db_pdo') && Config::getInstance()->exists('instance_id') && Config::getInstance()->exists('theme') &&
                   Config::getInstance()->exists('admin_user') && Config::getInstance()->exists('herbergeur_raison_sociale') &&
                   Config::getInstance()->exists('herbergeur_adresse') && Config::getInstance()->exists('herbergeur_siren') && Config::getInstance()->exists('herbergeur_contact')
                  ): ?>
        <i class="bi bi-check-square text-success"></i>
        <?php else: ?>
        <span class="text-danger">
        <i class="bi bi-exclamation-octagon-fill"></i>
        (voir plus bas)
        </span>
        <?php endif; ?>
    </td>
</tr>
</tbody>
</table>
<h2 class="my-4">Fichier de configuration</h2>
<div class="align-center">
<table class="table">
  <tbody>
    <tr>
        <th class="align-top">URL de l'instance</th>
        <td class="text-muted">urlbase</td>
        <td><?php echo $urlbase; ?></td>
    </tr>
    <tr>
        <th class="align-top">instance_id</th>
        <td class="text-muted">instance_id</td>
        <td><?php echo Config::getInstance()->get('instance_id', '<span class="text-danger">Non renseigné</span>'); ?></td>
    </tr>
    <tr>
        <th class="align-top">theme</th>
        <td class="text-muted">theme</td>
        <td><?php echo Config::getInstance()->get('theme', '<span class="text-danger">Non renseigné</span>'); ?></td>
    </tr>
    <tr>
        <th class="align-top">base de données</th>
        <td class="text-muted">db_pdo</td>
        <td><?php echo Config::getInstance()->get('db_pdo', '<span class="text-danger">Non renseigné</span>'); ?></td>
    </tr>
    <tr>
        <th class="align-top">type d'authentification</th>
        <td class="text-muted">viticonnect_baseurl ou http_auth</td>
        <td><?php echo Config::getInstance()->get('http_auth', Config::getInstance()->get('viticonnect_baseurl', '<span class="text-danger">no auth</span>')); ?></td>
    </tr>
    <tr>
        <th class="align-top">admin_user</th>
        <td class="text-muted">admin_user</td>
        <td><?php echo Config::getInstance()->get('admin_user', '<span class="text-danger">Non renseigné</span>'); ?></td>
    </tr>
    <tr>
        <th class="align-top">herbergeur_raison_sociale</th>
        <td class="text-muted">herbergeur_raison_sociale</td>
        <td><?php echo Config::getInstance()->get('herbergeur_raison_sociale', '<span class="text-danger">Non renseigné</span>'); ?></td>
    </tr>
    <tr>
        <th class="align-top">herbergeur_adresse</th>
        <td class="text-muted">herbergeur_adresse</td>
        <td><?php echo Config::getInstance()->get('herbergeur_adresse', '<span class="text-danger">Non renseigné</span>'); ?></td>
    </tr>
    <tr>
        <th class="align-top">herbergeur_siren</th>
        <td class="text-muted">herbergeur_siren</td>
        <td><?php echo Config::getInstance()->get('herbergeur_siren', '<span class="text-danger">Non renseigné</span>'); ?></td>
    </tr>
    <tr>
        <th class="align-top">herbergeur_contact</th>
        <td class="text-muted">herbergeur_contact</td>
        <td><?php echo Config::getInstance()->get('herbergeur_contact', '<span class="text-danger">Non renseigné</span>'); ?></td>
    </tr>
    <tr>
        <th class="align-top">Logo associable au QRCode</th>
        <td class="text-muted">qrcode_logo</td>
        <td> <?php echo (Config::getInstance()->exists('qrcode_logo')) ? file_get_contents(Config::getInstance()->get('qrcode_logo')) : '<i>Non renseigné</i>'; ?> </td>
    </tr>
    <tr>
        <th class="align-top">Denominations de l'instance</th>
        <td class="text-muted">denominations</td>
        <td><?php echo (Config::getInstance()->exists('denominations')) ? implode('<br/>', Config::getInstance()->get('denominations')) : '<i>Pas de dénomination spécifique</i>'; ?></td>
    </tr>
    <tr>
        <th class="align-top">Données brutes</th>
        <td class="text-muted">config/config.php</td>
        <td><textarea readonly><?php echo file_get_contents(__DIR__.'/../../config/config.php'); ?></textarea></td>
    </tr>
  </tbody>
</table>
</div>
<p class="text-end"><a class="btn btn-primary" href="/admin/users">Voir le listing des utilisateurs</a></p>
