<?php

namespace app\config;

class Config
{
    private static $_instance = null;
    protected $config = null;

    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new Config();
        }
        return self::$_instance;
    }

    public function __construct() {
        $this->config = \Base::instance()->get('config');
    }

    public function getDenominations() {
        return array_key_exists('denominations', $this->config) ? $this->config['denominations'] : [];
    }

    public function hasDenominations() {

        return count($this->getDenominations()) > 0;
    }

    public function isDenominationInConfig($denomination) {
        $denominationsInstance = $this->getDenominations();

        return in_array($denomination, $denominationsInstance);
    }
}
