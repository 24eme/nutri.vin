<h1>Les utilisateurs ayant créés des QRCodes</h1>

<ul>
<?php foreach($users as $u => $n) {
    echo "<p><a href='$urlbase/qrcode/$u/list'>$u - $n</a></p>";
} ?>
</ul>

<p class="text-end"><a href="<?php echo $urlbase; ?>/qrcode" class="btn btn-success">Accéder à mon espace</a></p>
