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
    protected string $svgLogoCssClass = '';
    protected string $svgTitle = '';
    protected array $svgEnergies = [];
    protected int $moduleCount = 0;

    public function __construct()
    {
        $this->eccLevel = EccLevel::M;
        $this->outputBase64 = false;
        $this->connectPaths = true;
        $this->addQuietzone = false;
        $this->svgUseFillAttribute = true;
        $this->outputType = QROutputInterface::CUSTOM;
        $this->outputInterface = QRMarkupSVGLogo::class;
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

    protected function set_svgTitle(string $svgTitle)
    {
        $this->svgTitle = $svgTitle;
    }

    public function setLogo($logo)
    {
        $this->addLogoSpace = true;
        $this->logoSpaceWidth = 8;
        $this->logoSpaceHeight = 8;
        $this->eccLevel = EccLevel::H;

        $this->outputType = QROutputInterface::CUSTOM;
        $this->outputInterface = QRMarkupSVGLogo::class;

        $this->svgLogo = $logo;
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

    public function setModuleCount($m) {
        $this->moduleCount = $m;
    }

    public function getModuleCount() {
        return $this->moduleCount;
    }

    public static function setResponseHeaders($filename = "qrcode")
    {
        throw new \Exception('should not be called directly');
    }
}
