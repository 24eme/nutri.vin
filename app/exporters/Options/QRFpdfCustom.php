<?php

namespace app\exporters\Options;

use chillerlan\QRCode\Output\QRFpdf;

class QRFpdfCustom extends QRFpdf
{
    public function dump(string $file = null) // la signature doit correspondre au parent
    {
        $fpdf = parent::dump($file);
        $fpdf->SetAutoPageBreak(TRUE, 0); // pas de margin bottom, sinon ça passe à la ligne après la fin des carrés du QRCode
        $fpdf->SetFont('helvetica', '', floor(8 * $this->scale));
        $fpdf->Text(ceil($this->length / 2) - ($fpdf->GetStringWidth($this->options->fpdfTitle) / 2), 0, $this->options->fpdfTitle);
        $fpdf->Text(ceil($this->length / 2) - ($fpdf->GetStringWidth($this->getEnergies()) / 2), $this->length - (4 * $this->scale), $this->getEnergies());

        if ($this->options->svgLogo) {
            $fpdf->ImageSVG(
                $this->options->svgLogo,
                ($this->length - $this->options->logoSpaceWidth * $this->scale) / 2,
                ($this->length - $this->options->logoSpaceHeight * $this->scale) / 2,
                $this->options->logoSpaceWidth * $this->scale,
                $this->options->logoSpaceHeight * $this->scale,
            );
        }

        $pdfData = $fpdf->Output('S');

        if($file !== null){
            $this->saveToFile($pdfData, $file);
        }

        if($this->options->imageBase64){
            $pdfData = sprintf('data:application/pdf;base64,%s', base64_encode($pdfData));
        }

        return $pdfData;
    }

    protected function getEnergies()
    {
        return sprintf("E (100 ml) = %s KCal / %s KJ", $this->options->fpdfEnergies[0], $this->options->fpdfEnergies[1]);
    }
}
