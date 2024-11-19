<?php
require_once('controllers/CtrlNutriVin.class.php');

$f3->route('GET /', 'CtrlNutriVin->index');
$f3->route('GET /index', 'CtrlNutriVin->index');
$f3->route('GET /faq', 'CtrlNutriVin->faq');
$f3->route('GET /admin/setup', 'CtrlNutriVin->adminSetup');
$f3->route('GET /admin/users', 'CtrlNutriVin->adminUsers');
$f3->route('GET @qrcodecreate: /qrcode/@userid/create', 'CtrlNutriVin->qrcodeCreate');
$f3->route('GET /admin/exportall', 'CtrlNutriVin->exportAll');
$f3->route('POST /qrcode/@userid/write', 'CtrlNutriVin->qrcodeWrite');
$f3->route('GET /qrcode/@userid/edit/@qrcodeid', 'CtrlNutriVin->qrcodeEdit');
$f3->route('GET /qrcode/@userid/edit/@qrcodeid/img/@index/delete', 'CtrlNutriVin->qrcodeDeleteImage');
$f3->route('GET @userlist: /qrcode/@userid/list', 'CtrlNutriVin->qrcodeList');
$f3->route('GET @qrcodeduplicate: /qrcode/@userid/duplicate/@qrcodeid', 'CtrlNutriVin->qrcodeDuplicate');
$f3->route('GET /qrcode/@userid/multiexport', 'CtrlNutriVin->qrcodeMultiExport');
$f3->route('GET /qrcode', 'CtrlNutriVin->qrcodeAuthentication');
$f3->route('GET /login', 'CtrlNutriVin->qrcodeAuthentication');
$f3->route('GET /login/viticonnect', 'CtrlNutriVin->qrcodeViticonnect');
$f3->route('GET /logout', 'CtrlNutriVin->qrcodeDisconnect');
$f3->route('GET @qrview: /@qrcodeid', 'CtrlNutriVin->qrcodeView');
$f3->route('GET @qrcodeexport: /@qrcodeid/@format', 'CtrlNutriVin->export');
$f3->route('GET /qrcode/@userid/parametrage/@qrcodeid', 'CtrlNutriVin->qrcodeParametrage');
$f3->route('POST /qrcode/@userid/parametrage/@qrcodeid', 'CtrlNutriVin->qrcodeDisplay');
$f3->route('GET /qrcode/@userid/stats/@qrcodeid/@type', 'CtrlNutriVin->qrcodeStats');
$f3->route('GET /@qrcodeid/fiche', 'CtrlNutriVin->qrcodeFiche');
$f3->route('GET @eanview: /ean/01/@ean', 'CtrlNutriVin->qrcodeEANView');
