<?php

$config = [

    'denominations' => [
    ],
    'qrcode_logo'  => __DIR__.'/../web/images/ivso.svg',
    'theme' => getenv('THEME') ?: 'nutrivin',
   'db_pdo' => 'couchdb:http://10.20.31.2:5984/nutrivin_test',
   'admin_user' => 'userid',
   'herbergeur_raison_sociale' => 'Ma SARL',
   'herbergeur_adresse' => 'place de la mairie 01000 ville',
   'herbergeur_siren' => '123456789',
   'herbergeur_contact' => '0123456789'
];
