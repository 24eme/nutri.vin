<?php

namespace app\exporters;

use app\exporters\utils\rsvgconvert;


class ExporterRSVG extends ExporterNatif
{
    public function getQRCodeContent($qrCodeData, $format, $logo = false, $energies = []) {
        $svgContent = parent::getQRCodeContent($qrCodeData, 'svg', $logo, $energies);

        if($format == 'svg') {

            return $svgContent;
        }

        return rsvgconvert::convert($svgContent, $format);
    }
}
