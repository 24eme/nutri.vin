<?php use app\exporters\utils\rsvgconvert; ?>
<h1>Configuration</h1>
<h2>Installation serveur</h2>
<table class="table">
  <tbody>
    <tr>
        <th class="align-top">Connexion à la base de données</th>
        <td>
            <?php if ($table_exists): ?>
                <i class="bi bi-check-square"></i>
            <?php else: ?>
                <i class="bi bi-exclamation-octagon-fill"></i>
                <a href="?createtable=true">(Créer la base)</a>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <th class="align-top">module php sqlite</th>
        <td>
            <?php if (method_exists('SQLite3', 'query')): ?>
            <i class="bi bi-check-square"></i>
            <?php else: ?>
            <i class="bi bi-exclamation-octagon-fill"></i>
            (php-sqlite3 neeeded)
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <th class="align-top">module php curl</th>
        <td>
            <?php if (function_exists('curl_error')): ?>
            <i class="bi bi-check-square"></i>
            <?php else: ?>
            <i class="bi bi-exclamation-octagon-fill"></i>
            (php-curl neeeded)
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <th class="align-top">module php zip</th>
        <td>
            <?php if (method_exists('ZipArchive', 'addFromString')): ?>
            <i class="bi bi-check-square"></i>
            <?php else: ?>
            <i class="bi bi-exclamation-octagon-fill"></i>
            (php-zip neeeded)
            <?php endif; ?>
        </td>
    </tr>
<tr>
    <th class="align-top">rsvg (pour la conversion eps)</th>
    <td>
        <?php if (rsvgconvert::commandExists()): ?>
        <i class="bi bi-check-square"></i>
        <?php else: ?>
        <i class="bi bi-exclamation-octagon-fill"></i>
        (librsvg2-bin neeeded)
        <?php endif; ?>
    </td>
</tr>
  </tbody>
</table>
<h2>Fichier de configuration</h2>
<div class="align-center">
<table class="table">
  <tbody>
    <tr>
        <th class="align-top">URL de l'instance</th>
        <td><?php echo $urlbase; ?></td>
    </tr>
    <tr>
        <th class="align-top">instance_id</th>
        <td><?php echo $config['instance_id']; ?></td>
    </tr>
    <tr>
        <th class="align-top">theme</th>
        <td><?php echo $config['theme']; ?></td>
    </tr>
    <tr>
        <th class="align-top">base de données</th>
        <td><?php echo $config['db_pdo']; ?></td>
    </tr>
    <tr>
        <th class="align-top">admin_user</th>
        <td><?php echo $config['admin_user']; ?></td>
    </tr>
    <tr>
        <th class="align-top">herbergeur_raison_sociale</th>
        <td><?php echo $config['herbergeur_raison_sociale']; ?></td>
    </tr>
    <tr>
        <th class="align-top">herbergeur_adresse</th>
        <td><?php echo $config['herbergeur_adresse']; ?></td>
    </tr>
    <tr>
        <th class="align-top">herbergeur_siren</th>
        <td><?php echo $config['herbergeur_siren']; ?></td>
    </tr>
    <tr>
        <th class="align-top">herbergeur_contact</th>
        <td><?php echo $config['herbergeur_contact']; ?></td>
    </tr>
    <tr>
        <th class="align-top">Logo associable au QRCode</th>
        <td> <?php echo file_get_contents($config['qrcode_logo']); ?> </td>
    </tr>
    <tr>
        <th class="align-top">Appellations de l'instance</th>
        <td><?php echo implode('<br/>', $config['appellations']); ?></td>
    </tr>
    <tr>
        <th class="align-top">Couleurs de l'instance</th>
        <td><?php echo implode('<br/>', $config['couleurs']); ?></td>
    </tr>
    <tr>
        <th class="align-top">Données brutes</th>
        <td><textarea readonly><?php echo file_get_contents(__DIR__.'/../../config/config.php'); ?></textarea></td>
    </tr>
  </tbody>
</table>
</div>
<p class="text-end"><a class="btn btn-primary" href="/admin/users">Voir le listing des utilisateurs</a></p>
