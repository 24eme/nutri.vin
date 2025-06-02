<?php

namespace app\exporters\Options;

use app\exporters\QRCodeSVG;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\QRCodeException;
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QROutputInterface;

class QRCodePNGOptions extends QRCodeGeneralOptions
{
    protected string $outputType = QROutputInterface::GDIMAGE_PNG;

    public static function setResponseHeaders($filename = "qrcode")
    {
        header('Content-type: image/png');
    }
}
