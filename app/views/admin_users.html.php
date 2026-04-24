<h1>Les utilisateurs ayant créés des QRCodes</h1>

<div class="input-group mb-2 float-end">
  <span class="input-group-text"><span class="bi bi-search"></span></span>
  <input type="text" class="form-control" id="search_users" placeholder="Recherche">
</div>

<table id="listing_users" class="table table-striped table-bordered">
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

<script>
const table_users = document.getElementById('listing_users')
document.getElementById('search_users').addEventListener('input', function (e) {
    const terms = document.getElementById(e.target.id).value
    table_users.querySelectorAll('tbody tr').forEach(function (tr) {
        if (tr.textContent.toLowerCase().includes(terms.toLowerCase())) {
            tr.classList.remove('d-none')
        } else {
            tr.classList.add('d-none')
        }
    })
})
</script>
