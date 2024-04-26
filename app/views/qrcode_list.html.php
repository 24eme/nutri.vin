<h2 class="text-center"><?php echo $userid;?> QR Codes</h2>

<h3 class="mt-4 ">Liste des QR code</h3>
<form id="multiExportForm" method="GET" action="<?php echo $urlbase.'/qrcode/'.$userid.'/multiexport'; ?>" enctype="multipart/form-data">
    <button type="submit" id="multiExportBtn" class="btn btn-primary mb-2" disabled>Télécharger la sélection</button>
</form>
<table id="list_qr" class="table table-bordered table-striped text-center">
    <thead>
        <tr>
            <th class="col-1"><input id="allCheck" type="checkbox"></input></th>
            <th class="col-3">Nom commercial</th>
            <th class="col-6">Vin</th>
            <th class="col-2">Actions</th>
        </tr>
    </thead>
    <?php if ( ! count($qrlist) ): ?>
        <tbody>
            <tr><td colspan=3><center><i>Vous n'avez pas encore créé de QRCode</i></center></td></tr>
        </tbody>
    <?php else: ?>
        <tbody>

            <?php foreach($qrlist as $qr): ?>
                <?php  ?>
                <tr>
                    <td><input form="multiExportForm" type="checkbox" name="qrcodes[]" value='<?php echo $qr->id; ?>'></td>
                    <td><?php echo $qr->domaine_nom; ?></td>
                    <td>
                        <?php echo $qr->cuvee_nom; ?>
                        <?php echo $qr->appellation; ?> <?php echo $qr->couleur; ?>
                        <?php echo $qr->millesime; ?> -
                        <?php echo $qr->centilisation; ?> cl
                    </td>
                    <td class="position-relative">
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <a class="p-1" href="<?php echo $urlbase.'/qrcode/'.$qr->user_id.'/edit/'.$qr->id ?>" style="color: black;">
                                <i class="bi bi-pencil-fill"></i></a>
                                <a class="p-1" href="<?php echo $urlbase.'/'.$qr->id ?>" style="color: black;">
                                    <i class="bi bi-eye-fill"></i></a>
                                    <a class="p-1" href="<?php echo $urlbase.'/qrcode/'.$qr->user_id.'/parametrage/'.$qr->id ?>" style="color: black;">
                                        <i class="bi bi-qr-code"></i></a>
                                    </div>
                                    <div class="position-absolute top-50 end-0 translate-middle-y">
                                        <a href="<?php echo $urlbase.'/qrcode/'.$qr->user_id.'/duplicate/'.$qr->id; ?>" class="btn"><i class="bi bi-copy"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                <?php endif; ?>
            </table>

            <div class="text-end">
                <a href="<?php echo $urlbase.''; ?>/qrcode/<?php echo $userid; ?>/create" class="btn btn-primary">Créer un nouveau QRCode</a>
            </div>

            <script>

            document.getElementById('allCheck').addEventListener("click", function() {
                document.querySelectorAll('[name^=qrcodes]').forEach(function (checkbox) {
                    checkbox.checked = document.getElementById('allCheck').checked;
                });
            });

            document.querySelector('#list_qr').addEventListener('change', function (e) {
                if (e.target.type == 'checkbox' ) {
                    document.getElementById('multiExportBtn').disabled = document.querySelectorAll('[name^=qrcodes]:checked').length == 0;
                }
            })


        </script>
