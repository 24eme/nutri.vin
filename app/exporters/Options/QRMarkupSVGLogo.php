<?php

namespace app\exporters\Options;

use chillerlan\QRCode\Output\QRMarkupSVG;

class QRMarkupSVGLogo extends QRMarkupSVG
{
    protected function paths(): string
    {
        if ($this->options->svgLogo) {
            $size = (int)ceil($this->moduleCount * $this->options->svgLogoScale);

            // we're calling QRMatrix::setLogoSpace() manually, so QROptions::$addLogoSpace has no effect here
            $this->matrix->setLogoSpace($size, $size);
        }

        $svg = '<g transform="translate(300, 300) scale('.(1000/$this->moduleCount).')">';
        $svg .= parent::paths();
        $svg .= $this->getLogo();
        $svg .= '</g>';
        $svg .= $this->getTitle();
        $svg .= $this->getEnergies();
        return $svg;
    }

    protected function getViewBox():string{

        return sprintf('0 0 %s %s', 1600, 1600);
    }

    /**
    * returns a <g> element that contains the SVG logo and positions it properly within the QR Code
    *
    * @see https://developer.mozilla.org/en-US/docs/Web/SVG/Element/g
    * @see https://developer.mozilla.org/en-US/docs/Web/SVG/Attribute/transform
    */
    protected function getLogo()
    {
        if (! $this->options->svgLogo) {
            return '';
        }

        // @todo: customize the <g> element to your liking (css class, style...)
        return sprintf(
            '%5$s<g transform="translate(%1$s %1$s) scale(%2$s)" class="%3$s">%5$s	%4$s%5$s</g>',
            str_replace(',', '.', (($this->moduleCount - ($this->moduleCount * $this->options->svgLogoScale)) / 2)),
            str_replace(',', '.', (float) $this->options->svgLogoScale),
            $this->options->svgLogoCssClass,
            file_get_contents($this->options->svgLogo),
            $this->options->eol
        );
    }

    protected function getTitle()
    {
        $svg = '<text  x="300" y="150" xml:space="preserve" style="font-style:normal;font-size:145px;line-height:1;font-family:sans-serif;fill:#000000;stroke-width:2;stroke-dasharray:2, 10">';
        $svg .= '<tspan x="50%" y="150" text-anchor="middle">INGRÉDIENTS</tspan>';
        $svg .= '<tspan x="50%" y="260" text-anchor="middle">&amp;  NUTRITION</tspan></text>';
        $svg .= $this->options->eol;
        return $svg;
    }

    protected function getEnergies()
    {   if (!$this->options->svgEnergies || count($this->options->svgEnergies) != 2) {
            return '';
        }
        $svg = '<text  x="300" y="1410" xml:space="preserve" style="font-style:normal;font-size:100px;line-height:1;font-family:sans-serif;fill:#000000;stroke-width:2;stroke-dasharray:2, 10; text-align:center;">';
        $svg .= '<tspan x="50%" y="1410" text-anchor="middle">E (100ml) =</tspan>';
        $svg .= '</text>';
        $svg .= '<text x="300" y="1500" xml:space="preserve" style="font-style:normal;font-size:100px;line-height:1;font-family:sans-serif;fill:#000000;stroke-width:2;stroke-dasharray:2, 10; text-align:center;">';
        $svg .= sprintf('<tspan x="50%%" y="1500" text-anchor="middle"> %1$s KCal / %2$s KJ</tspan>',(float) $this->options->svgEnergies[0],(float) $this->options->svgEnergies[1]);
        $svg .= '</text>';
        $svg .= $this->options->eol;
        return $svg;
    }
}
