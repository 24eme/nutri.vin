<h1>Configuration</h1>
<div class="align-center">
<table class="table-bordered">
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
        <td><textarea><?php echo file_get_contents(__DIR__.'/../../config/config.php'); ?></textarea></td>
    </tr>
  </tbody>
</table>
</div>
<p class="text-end"><a class="btn btn-primary" href="/admin/users">Voir le listing des utilisateurs</a></p>
