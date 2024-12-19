<?php

namespace app\exporters\Options;

use app\exporters\QRCodeSVG;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\QRCodeException;
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\Common\EccLevel;

abstract class QRCodeGeneralOptions extends QROptions
{
    protected string $svgLogo = '';
    protected float $svgLogoScale = 0.20;
    protected string $svgLogoCssClass = '';
    protected string $svgTitle = '';
    protected array $svgEnergies = [];

    public function __construct()
    {
        $this->eccLevel = EccLevel::H;
        $this->outputBase64 = false;
        $this->connectPaths = true;
        $this->addQuietzone = true;
        $this->svgUseFillAttribute = true;
    }

    // check logo
    protected function set_svgLogo(string $svgLogo)
    {
        if(!file_exists($svgLogo) || !is_readable($svgLogo)) {
            throw new QRCodeException('invalid svg logo');
        }

        // @todo: validate svg

        $this->svgLogo = $svgLogo;
    }

    // clamp logo scale
    protected function set_svgLogoScale(float $svgLogoScale)
    {
        $this->svgLogoScale = max(0.05, min(0.3, $svgLogoScale));
    }

    protected function set_svgTitle(string $svgTitle)
    {
        $this->svgTitle = $svgTitle;
    }

    public function setLogo($logo)
    {
        $this->addLogoSpace = true;
        $this->logoSpaceWidth = 8;
        $this->logoSpaceHeight = 8;

        $this->outputType = QROutputInterface::CUSTOM;
        $this->outputInterface = QRMarkupSVGLogo::class;

        $this->svgLogo = $logo;
        $this->svgLogoScale = 0.25;
        $this->svgLogoCssClass = 'dark';
    }

    public function setTitle($title)
    {
        $this->outputType = QROutputInterface::CUSTOM;
        $this->outputInterface = QRMarkupSVGLogo::class;

        $this->svgTitle = $title;
    }

    public function setEnergies($energies)
    {
        $this->outputType = QROutputInterface::CUSTOM;
        $this->outputInterface = QRMarkupSVGLogo::class;

        $this->svgEnergies = $energies;
    }

    public static function setResponseHeaders()
    {
        throw new \Exception('should not be called directly');
    }
}
