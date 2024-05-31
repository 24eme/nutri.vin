<?php

namespace tests\factories;

use app\models\QRCode;

class QRCodeFactory
{
    public static function create($userid = 'userid')
    {
        $web = \Web::instance();

        $qrcode = new QRCode();
        $qrcode->user_id = $userid;
        $qrcode->domaine_nom = $web->filler(1, 3, false); // sentences, words, lorem ipsum
        $qrcode->adresse_domaine = $web->filler(1, 4, false);
        $qrcode->denomination = "AOP ".$web->filler(1, 3, false);
        $qrcode->couleur = array_rand(array_flip(['Rouge', 'Rosé', 'Blanc']));
        $qrcode->cuvee_nom = $web->filler(1, 3, false);
        $qrcode->alcool_degre = floatval(rand(9, 17));
        $qrcode->centilisation = '75 cl';
        $qrcode->millesime = date('Y') - 1;
        $qrcode->lot = "Lot ".rand(1, 1000);
        $qrcode->ingredients = implode(', ', array_rand(QRCode::getFullListeIngredients(), 4));
        $qrcode->authorization_key = \Base::instance()->hash(uniqid());
        $qrcode->date_creation = date('c');
        $qrcode->date_version = date('c');
        $qrcode->logo = false;

        return $qrcode;
    }
}
