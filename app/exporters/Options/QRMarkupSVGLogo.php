<?php

namespace app\exporters\Options;

use chillerlan\QRCode\Output\QRMarkupSVG;

class QRMarkupSVGLogo extends QRMarkupSVG
{
    protected function paths(): string
    {
        $svg = '<g transform="translate(300, 300) scale('.(1000/$this->moduleCount).')">';
        $svg .= parent::paths();
        $svg .= '</g>';
        $svg .= $this->getLogo();
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
        $original = file_get_contents($this->options->svgLogo);
        $original = preg_replace('/<.xml [^>]*>/', '', $original);
        if (!preg_match('/viewBox="[\d\.]+ [\d\.]+ ([\d\.]+) ([\d\d\.]+)"/', $original, $m)) {
            return '';
        }

        // @todo: customize the <g> element to your liking (css class, style...)
        return sprintf(
            '%5$s<g transform="translate(%1$s, %1$s) scale(%2$s)" class="%3$s">%5$s%4$s%5$s</g>',
            308+($this->moduleCount-$this->options->logoSpaceWidth)*1000/$this->moduleCount/2 + $this->options->logoSpaceStartX/2,
            ($this->options->logoSpaceWidth/$m[1]/4)*((1000-$m[1]/2)/$this->moduleCount),
            //1000*$m[1]/($this->moduleCount*$m[1]/$this->options->logoSpaceWidth)/$m[1]/4,
            $this->options->svgLogoCssClass,
            $original,
            $this->options->eol
        );
    }

    protected function getTitle()
    {
        if (!$this->options->svgEnergies || count($this->options->svgEnergies) != 2) {
            return '';
        }
        $svg = '<text  x="300" y="180" xml:space="preserve" style="font-style:normal;font-size:120px;line-height:1;font-family:sans-serif;fill:#000000;stroke-width:2;stroke-dasharray:2, 10">';
        $svg .= '<tspan x="50%" y="160" text-anchor="middle">INGRÉDIENTS</tspan>';
        $svg .= '<tspan x="50%" y="260" text-anchor="middle">&amp;  NUTRITION</tspan></text>';
        $svg .= $this->options->eol;
        return $svg;
    }

    protected function getEnergies()
    {   if (!$this->options->svgEnergies || count($this->options->svgEnergies) != 2) {
            return '';
        }
        $svg = '<text  x="300" y="1410" xml:space="preserve" style="font-style:normal;font-size:120px;line-height:1;font-family:sans-serif;fill:#000000;stroke-width:2;stroke-dasharray:2, 10;text-align:center;font-weight:bold;">';
        $svg .= '<tspan x="50%" y="1410" text-anchor="middle">E (100ml) =</tspan>';
        $svg .= '</text>';
        $svg .= '<text x="300" y="1520" xml:space="preserve" style="font-style:normal;font-size:120px;line-height:1;font-family:sans-serif;fill:#000000;stroke-width:2;stroke-dasharray:2, 10;text-align:center;font-weight:bold;">';
        $svg .= sprintf('<tspan x="50%%" y="1520" text-anchor="middle"> %1$s KCal / %2$s KJ</tspan>',(float) $this->options->svgEnergies[0], (float) $this->options->svgEnergies[1]);
        $svg .= '</text>';
        $svg .= $this->options->eol;
        return $svg;
    }
}
