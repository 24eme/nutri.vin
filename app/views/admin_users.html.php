<h1>Les utilisateurs ayant créés des QRCodes</h1>

<table class="table table-striped table-bordered">
  <thead>
    <th>Domaine</th>
    <th class="text-center">QRCodes</th>
    <th class="text-center">Visites totales</th>
  </thead>
  <tbody>
    <?php foreach($users as $id => $user) : ?>
      <tr>
        <td>
          <a href='<?php echo "$urlbase/qrcode/$id/list" ?>'><?php echo $user['domaine'] . " ($id)" ?></a>
        </td>
        <td class="text-end"><?php echo $user['qrcodes'] ?></td>
        <td class="text-end"><?php echo $user['visites'] ?></td>
      </tr>
    <?php endforeach ?>
</table>

<p class="text-end"><a href="<?php echo $urlbase; ?>/qrcode" class="btn btn-success">Accéder à mon espace</a></p>
