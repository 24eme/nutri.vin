<?php if ($iframe): ?>
    <div class="border border-light-subtle border-4 py-5 px-2 bg-light rounded-5 shadow-sm bg-gradient position-relative">
    <div class="input-group shadow-sm">
        <span class="input-group-text border-bottom-0 rounded-bottom-0"><i class="bi bi-globe"></i></span>
        <input type="text" class="form-control text-muted border-bottom-0 rounded-bottom-0" readonly="readonly" value="<?php echo $urlbase.$qrcode->getQRCodeUrl() ?>" />
        <button data-bs-toggle="tooltip" title="Copier le lien" class="input-group-text  border-bottom-0 rounded-bottom-0 animation-fade-in" id="btn-copy" type="button" onclick="document.getElementById('btn-copy').style.display = 'none'; navigator.clipboard.writeText('<?php echo $urlbase."/".$qrcode->getId(); ?>'); setTimeout('document.getElementById(\'btn-copy\').style.display = \'block\'', 500);"><i class="bi bi-clipboard"></i></button>
        <a data-bs-toggle="tooltip" title="Ouvrir la page" class="input-group-text  border-bottom-0 rounded-bottom-0" href="/<?php echo $qrcode->getId() ?>" target="_blank"><i class="bi bi-box-arrow-up-right"></i></a>
    </div>
<?php else:?>
    <div class="border border-light-subtle border-4 py-5 px-2 bg-light rounded-5 shadow-sm bg-gradient position-sticky" style="top: 2rem">
<?php endif; ?>
    <div class="rounded-5 border border-2 bg-white border-light-subtle position-absolute top-0 start-50 translate-middle-x" style="width: 100px; height: 20px; margin-top: 15px;"></div>
    <div class="rounded-5 border border-2 bg-white border-light-subtle position-absolute top-0 end-0 mt-3 me-5" style="width: 20px; height: 20px;"></div>
    <div class="border border-light-subtle overflow-auto <?php if (!$iframe): ?>rounded-3<?php endif; ?>" style="height: 70vh; width: 390px;" data-liveform-container>
        <?php if ($iframe): ?>
            <iframe src="/<?php echo $qrcode->getId() ?>" style="height: 99%; width: 388px;"></iframe>
        <?php else: ?>
            <?php include('qrcode_show.html.php'); ?>
        <?php endif; ?>
    </div>
    <div class="rounded-3 border border-2 bg-white border-light-subtle position-absolute bottom-0 start-50 translate-middle-x mb-1" style="width: 40px; height: 40px;"></div>
</div>
