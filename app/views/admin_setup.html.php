<?php use app\exporters\utils\rsvgconvert; ?>
<h1>Configuration</h1>
<h2 class="my-4">Installation serveur</h2>
<table class="table">
  <tbody>
    <tr>
        <th class="align-top">Connexion à la base de données</th>
            <td class="text-muted"><?php echo $config['db_pdo']; ?></td>
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
        <th class="align-top">module php sqlite</th>
    <td class="text-muted">php-sqlite3</td>
        <td>
            <?php if (method_exists('SQLite3', 'query')): ?>
            <i class="bi bi-check-square text-success"></i>
            <?php else: ?>
            <span class="text-danger">
            <i class="bi bi-exclamation-octagon-fill"></i>
            (php-sqlite3 neeeded)
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
            (php-curl neeeded)
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
            (php-zip neeeded)
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
        (librsvg2-bin neeeded)
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
        <td><?php echo $config['instance_id']; ?></td>
    </tr>
    <tr>
        <th class="align-top">theme</th>
        <td class="text-muted">theme</td>
        <td><?php echo $config['theme']; ?></td>
    </tr>
    <tr>
        <th class="align-top">base de données</th>
        <td class="text-muted">db_pdo</td>
        <td><?php echo $config['db_pdo']; ?></td>
    </tr>
    <tr>
        <th class="align-top">admin_user</th>
        <td class="text-muted">admin_user</td>
        <td><?php echo $config['admin_user']; ?></td>
    </tr>
    <tr>
        <th class="align-top">herbergeur_raison_sociale</th>
        <td class="text-muted">herbergeur_raison_sociale</td>
        <td><?php echo $config['herbergeur_raison_sociale']; ?></td>
    </tr>
    <tr>
        <th class="align-top">herbergeur_adresse</th>
        <td class="text-muted">herbergeur_adresse</td>
        <td><?php echo $config['herbergeur_adresse']; ?></td>
    </tr>
    <tr>
        <th class="align-top">herbergeur_siren</th>
        <td class="text-muted">herbergeur_siren</td>
        <td><?php echo $config['herbergeur_siren']; ?></td>
    </tr>
    <tr>
        <th class="align-top">herbergeur_contact</th>
        <td class="text-muted">herbergeur_contact</td>
        <td><?php echo $config['herbergeur_contact']; ?></td>
    </tr>
    <tr>
        <th class="align-top">Logo associable au QRCode</th>
        <td class="text-muted">qrcode_logo</td>
        <td> <?php echo file_get_contents($config['qrcode_logo']); ?> </td>
    </tr>
    <tr>
        <th class="align-top">Appellations de l'instance</th>
        <td class="text-muted">appellations</td>
        <td><?php echo implode('<br/>', $config['appellations']); ?></td>
    </tr>
    <tr>
        <th class="align-top">Couleurs de l'instance</th>
        <td class="text-muted">couleurs</td>
        <td><?php echo implode('<br/>', $config['couleurs']); ?></td>
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
