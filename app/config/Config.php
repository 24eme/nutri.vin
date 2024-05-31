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
        if (!in_array($_SERVER['SERVER_NAME'], ['127.0.0.1', 'localhost']) && !isset($this->config['viticonnect_baseurl'])) {
            $this->config['viticonnect_baseurl'] = 'https://viticonnect.net/cas';
        }
    }

    public function getConfig() {

        return $this->config;
    }

    public function get($key, $default = null) {
        return array_key_exists($key, $this->config) ? $this->config[$key] : $default;
    }

    public function getDenominations() {
        return $this->get('denominations', []);
    }

    public function hasDenominations() {

        return count($this->getDenominations()) > 0;
    }

    public function isDenominationInConfig($denomination) {
        $denominationsInstance = $this->getDenominations();

        return in_array($denomination, $denominationsInstance);
    }
}
