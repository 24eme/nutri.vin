<?php

namespace app\config;

if(file_exists(__DIR__.'/../../config/config.php')) {
    require_once(__DIR__.'/../../config/config.php');
}

class Config
{
    private static $_instance = null;
    protected $config = null;
    protected $f3 = null;

    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new Config();
        }
        return self::$_instance;
    }

    public function __construct() {
        global $config;
        $this->f3 = \Base::instance();
        if (isset($config)) {
            $this->config = [];
        }else{
            $this->config = $config;
        }
        $this->setDefaults();
    }

    public function getConfig() {

        return $this->config;
    }

    public function get($key, $default = null) {
        return array_key_exists($key, $this->config) ? $this->config[$key] : $default;
    }

    public function exists($v) {
        return isset($this->config[$v]);
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

    public function getQRCodeUriSize() {
        if ($this->get('qrcode_force_minimal_size')) {
            return 24 - strlen($this->config['urlbase']);
        }
        return 7;
    }

    public function getUrlbase() {
        if (!isset($this->config['urlbase'])) {
            $port = $this->f3->get('PORT');
            $this->config['urlbase'] = $this->f3->get('SCHEME').'://'.$_SERVER['SERVER_NAME'].(!in_array($port,[80,443])?(':'.$port):'').$this->f3->get('BASE');
        }
        return $this->config['urlbase'];
    }

    private function setDefaults() {
        if (!in_array($_SERVER['SERVER_NAME'], ['127.0.0.1', 'localhost']) && $this->exists('viticonnect_baseurl')) {
            $this->config['viticonnect_baseurl'] = 'https://viticonnect.net/cas';
        }
        if (!isset($this->config['default_user'])) {
            $this->config['default_user'] = 'userid';
        }
        if (!isset($this->config['theme'])) {
            $this->config['theme'] = 'nutrivin';
        }

        if (!isset($this->config['db_pdo']) || !$this->config['db_pdo']) {
            $this->config['db_pdo'] = 'sqlite://'.$this->f3->get('ROOT').'/db/nutrivin.sqlite';
        }

        $instance_id = null;
        if ($this->getUrlbase()) {
            $site = preg_replace('/https?:../', '', $this->getUrlbase());
            if (isset($instances[$site])) {
                $instance_id = $instances[$site];
            }
        }
        if (!$instance_id) {
            if (isset($this->config['instance_id'])) {
                $instance_id = $this->config['instance_id'];
            }
        }
        if (!$instance_id) {
            $instance_id = '0';
        }
        $this->config['instance_id'] = $instance_id;

        $this->getUrlbase();
    }

    public function getInstanceId() {
        return $this->config['instance_id'];
    }

}
