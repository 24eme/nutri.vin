<?php

namespace app\exporters;

use app\exporters\Options\QRCodeSVGOptions;
use app\exporters\Options\QRCodeEPSOptions;
use app\exporters\Options\QRCodePDFOptions;
use app\exporters\Options\QRCodePNGOptions;

use chillerlan\QRCode\QRCode;

class ExporterNatif
{
    private $qroptions = [
        'svg' => QRCodeSVGOptions::class,
        'eps' => QRCodeEPSOptions::class,
        'pdf' => QRCodePDFOptions::class,
        'png' => QRCodePNGOptions::class,
    ];
    private $modules_size = null;
    protected $configuration = null;

    public function setResponseHeaders($format, $filename = "qrcode") {

        return $this->qroptions[$format]::setResponseHeaders($filename);
    }

    public function getQRCodeContent($qrCodeData, $format, $logo = false, $energies = []) {
        $this->configuration = new $this->qroptions[$format];
        if (strlen($qrCodeData) < 25) {
            $this->configuration->version = 3;
        }
        if($logo) {
            $this->configuration->setLogo($logo);
        }
        if (count($energies)) {
            $this->configuration->setTitle("INGRÉDIENTS &amp; NUTRITION");
            $this->configuration->setEnergies($energies);
        }

        $qrc = new QRCode($this->configuration);
        $content = $qrc->render($qrCodeData);

        if($format == 'eps') {
            $content = str_replace(',', '.',$content);
        }

        return $content;
    }

}
