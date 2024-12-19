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

        $svg = parent::paths();
        $svg .= $this->getLogo();
        $svg .= $this->getTitle();
        $svg .= $this->getEnergies();

        return $svg;
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
        return sprintf(
            '%3$s<text x="50%%" y="2" font-size="' . ($this->matrix->getVersion()->getVersionNumber() == 4 ? 3 : 3.5) .'" font-kerning="normal" font-stretch="200%%" font-family="Liberation Mono, Verdana, Arial, Helvetica, sans-serif" text-anchor="middle"><tspan x="50%%" dy="' . ($this->matrix->getVersion()->getVersionNumber() == 4 ? 0.5 : 0.9) .'">%1$s</tspan><tspan x="50%%" dy="' . ($this->matrix->getVersion()->getVersionNumber() == 4 ? 3 : 3.4) .'">%2$s</tspan></text>%3$s',
            $this->options->svgTitle[0],
            $this->options->svgTitle[1],
            $this->options->eol
        );
    }

    protected function getEnergies()
    {   if (!$this->options->svgEnergies || count($this->options->svgEnergies) != 2) {
        return '';
    }
    return sprintf(

        '%4$s<text x="50%%" y="%3$s" font-size="' . ($this->matrix->getVersion()->getVersionNumber() == 4 ? 3 : 3.3) .'" font-kerning="normal" font-stretch="200%%" font-family="Liberation Mono, Verdana, Arial, Helvetica, sans-serif" text-anchor="middle"><tspan x="50%%" dy="' . ($this->matrix->getVersion()->getVersionNumber() == 4 ? 1.8 : 1.1) .'" style="font-weight: bold;">E(100ml)</tspan><tspan x="50%%" dy="' . ($this->matrix->getVersion()->getVersionNumber() == 4 ? 3.5 : 3.8) .'" style="font-weight: bold;">%1$s KCal/%2$s KJ</tspan></text>%4$s',
        (float) $this->options->svgEnergies[0],
        (float) $this->options->svgEnergies[1],
        24 + (4.8 * $this->matrix->getVersion()->getVersionNumber()),
        $this->options->eol
    );
}
}
