<?php

namespace app\exporters;

use app\exporters\Options\QRCodeSVGOptions;
use app\exporters\Options\QRCodeEPSOptions;
use app\exporters\Options\QRCodePDFOptions;

use chillerlan\QRCode\QRCode;

class ExporterNatif
{
    private $qroptions = [
        'svg' => QRCodeSVGOptions::class,
        'eps' => QRCodeEPSOptions::class,
        'pdf' => QRCodePDFOptions::class,
    ];

    public function setResponseHeaders($format) {

        return $this->qroptions[$format]::setResponseHeaders();
    }

    public function getQRCodeContent($qrCodeData, $format, $logo = false, $energies = []) {
        $configuration = new $this->qroptions[$format];
        if (strlen($qrCodeData) < 25) {
            $configuration->version = 3;
        }
        if($logo) {
            $configuration->setLogo($logo);
        }
        if (count($energies)) {
            $configuration->setTitle("  INGRÉDIENTS :");
            $configuration->setEnergies($energies);
        }

        $content = (new QRCode($configuration))->render($qrCodeData);

        if($format == 'eps') {
            $content = str_replace(',', '.',$content);
        }

        return $content;
    }

}
