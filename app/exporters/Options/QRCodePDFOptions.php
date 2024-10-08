<?php

namespace app\exporters\Options;

use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QROutputInterface;

class QRCodePDFOptions extends QRCodeGeneralOptions
{
    protected string $outputType = QROutputInterface::FPDF;
    protected string $fpdfMeasureUnit = 'mm';

    protected string $fpdfTitle = '';
    protected array $fpdfEnergies = [];

    public static function setResponseHeaders()
    {
        header('Content-type: application/pdf');
        header('Content-Disposition: filename="qrcode.pdf"');
    }

    public function setLogo($logo)
    {
        parent::setLogo($logo);
        $this->outputType = QROutputInterface::CUSTOM;
        $this->outputInterface = QRFpdfCustom::class;
    }

    public function setTitle($title)
    {
        $this->outputType = QROutputInterface::CUSTOM;
        $this->outputInterface = QRFpdfCustom::class;

        $this->returnResource = true;
        $this->fpdfTitle = $title;
    }

    public function setEnergies($energies)
    {
        $this->outputType = QROutputInterface::CUSTOM;
        $this->outputInterface = QRFpdfCustom::class;

        $this->returnResource = true;
        $this->fpdfEnergies = $energies;
    }
}
