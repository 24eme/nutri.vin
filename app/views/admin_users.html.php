<h1>Les utilisateurs ayant créés des QRCodes</h1>

<table class="table table-striped table-bordered">
  <thead>
    <th>Domaine</th>
  </thead>
  <tbody>
    <?php foreach($users as $u => $n) : ?>
      <tr>
        <td>
          <a href='<?php echo "$urlbase/qrcode/$u/list" ?>'><?php echo "$n ($u)" ?></a>
        </td>
      </tr>
    <?php endforeach ?>
</table>

<p class="text-end"><a href="<?php echo $urlbase; ?>/qrcode" class="btn btn-success">Accéder à mon espace</a></p>
