<?php

namespace app\exporters\Options;

use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QROutputInterface;

class QRCodeEPSOptions extends QRCodeGeneralOptions
{
    protected string $outputType = QROutputInterface::EPS;

    public static function setResponseHeaders($filename = "qrcode")
    {
        header('Content-type: application/postscript');
        header('Content-Disposition: filename="'.$filename.'.eps"');
    }

    public function setLogo($logo)
    {
        // Not working with this format
    }
}
