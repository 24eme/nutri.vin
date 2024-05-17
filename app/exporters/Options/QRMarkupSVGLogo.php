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
            (($this->moduleCount - ($this->moduleCount * $this->options->svgLogoScale)) / 2),
            $this->options->svgLogoScale,
            $this->options->svgLogoCssClass,
            file_get_contents($this->options->svgLogo),
            $this->options->eol
        );
    }

    protected function getTitle()
    {
        return sprintf(
            '%2$s<text x="50%%" y="3" font-size="3" text-anchor="middle">%1$s</text>%2$s',
            $this->options->svgTitle,
            $this->options->eol
        );
    }
}
