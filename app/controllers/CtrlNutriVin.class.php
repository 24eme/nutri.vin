<?php

use app\exporters\Exporter;
use app\models\QRCode;
use Web\Geo;

class CtrlNutriVin {
    function index(Base $f3) {
        echo View::instance()->render('layout_index.html.php');
    }

    function home(Base $f3) {
        echo View::instance()->render('layout_home.html.php');
    }

    function adminSetup(Base $f3) {
        $qrcode = new QRCode();
        if (!$qrcode->tableExists()) {
            QRcode::createTable();
        }
        if (!$this->isAdmin($f3) && count(QRCode::findAll())) {
            die('Unauthorized');
        }
        $f3->set('content','admin_setup.html.php');
        echo View::instance()->render('layout.html.php');

    }

    function exportAll(Base $f3) {
      $csv = null;
      $rows = QRCode::findAll();
      foreach ($rows as $row) {
        $qrcode = $row->cast();
        foreach (QRCode::$versionning_ignore_fields as $field) {
          if (isset($qrcode[$field])) unset($qrcode[$field]);
        }
        if (!$csv) {
          $csv = 'appellation de l\'instance;'.implode(';', array_keys($qrcode))."\n";
        }
        $csv .= (int)$this->isAppellationInConfig($qrcode['appellation']).';'.implode(';', array_values($qrcode))."\n";
      }
      header('Content-Type: text/csv');
      header('Content-Disposition: attachment; filename="'.date('YmdHi').'_qrcodes.csv'.'"');
      echo $csv;
    }

    private function isAdmin(Base $f3) {
        if ( !$f3->exists('SESSION.userid')) {
            return false;
        }
        $config = $this->getConfig($f3);
        if (!isset($config['admin_user'])) {
            return false;
        }
        return $f3->get('SESSION.userid') == $config['admin_user'];
    }

    private function authenticatedUserOnly(Base $f3) {
        if ($this->isAdmin($f3)) {
            return true;
        }
        if ( !$f3->exists('SESSION.userid') || !$f3->exists('PARAMS.userid') ||
             ($f3->get('PARAMS.userid') != $f3->get('SESSION.userid')))
        {
            die('Unauthorized');
        }
        return true;
    }

    function qrcodeWrite(Base $f3) {
        if ($f3->exists('POST.domaine_nom') && $f3->exists('PARAMS.userid')) {
            $this->authenticatedUserOnly($f3);
            if ($f3->exists('POST.id')) {
                $qrcode = QRCode::findById($f3->get('POST.id'));
            } else {
                $qrcode = new QRCode();
            }
            $this->initDefaultOnQRCode($qrcode, $f3);
            if ($qrcode->user_id && $qrcode->user_id != $f3->get('PARAMS.userid')) {
                return $f3->reroute('/qrcode/'.$f3->get('userid').'/create', false);
            }
            if ($f3->get('POST.labels')) {
              $f3->set('POST.labels', json_encode($f3->get('POST.labels')));
            } else {
              $f3->set('POST.labels', json_encode([]));
            }
            $qrcode->copyFrom('POST');
            if (!$qrcode->user_id) {
                $qrcode->user_id = $f3->get('PARAMS.userid');
            }
            foreach(['image_bouteille', 'image_etiquette', 'image_contreetiquette'] as $img) {
                if(isset($_FILES[$img]) && in_array($_FILES[$img]['type'], array('image/jpeg', 'image/png'))) {
                    if ($imageResized = QRCode::resizeImage($_FILES[$img]['tmp_name'], QRCode::IMG_MAX_RESOLUTION)) {
                      $qrcode->{$img} = 'data:'.$_FILES[$img]['type'].';base64,'.base64_encode(file_get_contents($imageResized));
                    }
                }
            }
            $qrcode->appellation_instance = $this->isAppellationInConfig($qrcode->appellation);
            $qrcode->save();
            return $f3->reroute('/qrcode/'.$qrcode->user_id.'/parametrage/'.$qrcode->getId().'?from=create', false);
        }
        return $f3->reroute('/qrcode', false);
    }

    function qrcodeDeleteImage(Base $f3) {
        $this->authenticatedUserOnly($f3);
        $qrcode = QRCode::findById($f3->get('PARAMS.qrcodeid'));
        if ($qrcode->user_id != $f3->get('PARAMS.userid')) {
            throw new Exception('not allowed');
        }
        $images = ['image_bouteille', 'image_etiquette', 'image_contreetiquette'];
        $qrcode->{$images[intval($f3->get('PARAMS.index'))]} = null;
        $qrcode->save();
        return $f3->reroute('/qrcode/'.$qrcode->user_id.'/edit/'.$qrcode->getId()."#photos", false);
    }

    function initDefaultOnQRCode(& $qrcode, $f3) {
        if (!$qrcode->image_bouteille) {
            $qrcode->image_bouteille = '/images/default_bouteille.jpg';
        }
        if (!$qrcode->image_etiquette) {
            $qrcode->image_etiquette = '/images/default_etiquette.jpg';
        }
        if (!$qrcode->image_contreetiquette) {
            $qrcode->image_contreetiquette = '/images/default_contreetiquette.jpg';
        }
        if (!$qrcode->responsable_siret && $f3->get('SESSION.siret')) {
            $qrcode->responsable_siret = $f3->get('SESSION.siret');
        }
        if (!$qrcode->responsable_nom && $f3->get('SESSION.username')) {
            $qrcode->responsable_nom = $f3->get('SESSION.username');
        }
        if (!$qrcode->responsable_adresse && $qrcode->responsable_siret) {
            $qrcode->responsable_adresse = QRCode::siret2adresse($qrcode->responsable_siret);
        }
    }

    function qrcodeCreate(Base $f3) {
        $this->authenticatedUserOnly($f3);
        $qrcode = new QRCode();
        if (!$qrcode->tableExists()) {
            return $f3->reroute('/admin/setup', false);
        }
        $qrcode->user_id = $f3->get('PARAMS.userid');
        $qrcode->clone('GET');

        $this->initDefaultOnQRCode($qrcode, $f3);

        $f3->set('qrcode', $qrcode);
        $f3->set('create', true);
        $f3->set('content','qrcode_form.html.php');
        echo View::instance()->render('layout.html.php');
    }


    function qrcodeEdit(Base $f3) {
        $this->authenticatedUserOnly($f3);
        $qrcode = QRCode::findById($f3->get('PARAMS.qrcodeid'));

        if ($qrcode === null) {
            $f3->error(404, "QRCode non trouvé");
            exit;
        }

        if ($qrcode->user_id != $f3->get('PARAMS.userid')) {
            throw new Exception('not allowed');
        }

        $this->initDefaultOnQRCode($qrcode, $f3);

        $f3->set('qrcode', $qrcode);
        $f3->set('content','qrcode_form.html.php');
        echo View::instance()->render('layout.html.php');
    }

    private function getConfig(Base $f3) {
        $config = $f3->get('config');
        if (!in_array($_SERVER['SERVER_NAME'], ['127.0.0.1', 'localhost']) && !isset($config['viticonnect_baseurl'])) {
            $config['viticonnect_baseurl'] = 'https://viticonnect.net/cas';
        }
        return $config;
    }

    function qrcodeAuthentication(Base $f3) {
        $qrcode = new QRCode();
        if (!$qrcode->tableExists()) {
            return $f3->reroute('/admin/setup', false);
        }
        if (!$f3->exists('SESSION.userid')) {
            if ($f3->exists('SESSION.authtype')) {
                return $f3->reroute('/logout');
            }
            $config = $this->getConfig($f3);
            if (isset($config['http_auth']) && $config['http_auth']) {
                if (isset($_SERVER['PHP_AUTH_USER'])) {
                    $f3->set('SESSION.userid', $_SERVER['PHP_AUTH_USER']);
                    $f3->set('SESSION.username', $_SERVER['PHP_AUTH_USER']);
                    $f3->set('SESSION.authtype', 'http');
                    return $f3->reroute('/qrcode');
                }
                header('WWW-Authenticate: Basic realm="My Realm"');
                header('HTTP/1.0 401 Unauthorized');
                die ("Not authorized qrcodeAuthentication");
            }
            if (isset($config['viticonnect_baseurl']) && $config['viticonnect_baseurl']) {
                return $f3->reroute($config['viticonnect_baseurl'].'/login?service='.$f3->get('urlbase').'/login/viticonnect');
            }
            if (in_array($_SERVER['SERVER_NAME'], ['127.0.0.1', 'localhost'])) {
                if (!isset($config['default_user'])) {
                    $config['default_user'] = 'userid';
                }
                $f3->set('SESSION.userid', $config['default_user']);
                $f3->set('SESSION.username', $config['default_user']);
                $f3->set('SESSION.authtype', 'default');
                return $f3->reroute('/qrcode');
            }
            die ("Not authorized");
        }
        return $f3->reroute('/qrcode/'.$f3->get('SESSION.userid').'/list', false);
    }

    function qrcodeViticonnect(Base $f3) {
        $ticket = $f3->get('GET.ticket');
        $config = $this->getConfig($f3);
        if (!$ticket) {
            return $f3->reroute('/qrcode');
        }
        if (!isset($config['viticonnect_baseurl']) || !$config['viticonnect_baseurl']) {
            return $f3->reroute('/');
        }
        $validate = file_get_contents($config['viticonnect_baseurl'].'/serviceValidate?service='.$f3->get('urlbase').'/login/viticonnect&ticket='.$ticket);
        if ($validate) {
            if(strpos($validate, 'INVALID_TICKET') !== false) {
                return $f3->reroute('/qrcode');
            }
            $userid = null;
            $origin = null;
            $raison_sociale = null;
            if (preg_match('/<cas:viticonnect_origin>([^<]*)<\/cas:viticonnect_origin>/', $validate, $m)) {
                $origin = $m[1];
            }
            if (preg_match('/cas:viticonnect_entity_1_raison_sociale>([^<]*)<\/cas:viticonnect_entity_1/', $validate, $m)) {
                $raison_sociale = $m[1];
            }
            if (preg_match('/cas:viticonnect_entity_1_cvi>([^<]*)<\/cas:viticonnect_entity_1/', $validate, $m)) {
                $userid = $m[1];
            }
            if (preg_match('/cas:viticonnect_entity_1_accises>([^<]*)<\/cas:viticonnect_entity_1/', $validate, $m)) {
                $userid = $m[1];
            }
            if (preg_match('/cas:viticonnect_entity_1_siret>([^<]*)<\/cas:viticonnect_entity_1/', $validate, $m)) {
                $f3->set('SESSION.siret', $m[1]);
                if (!$userid) {
                    $userid = $m[1];
                }
            }
            if (!$userid && $origin && preg_match('/cas:user>([^<]*)<\/cas:user/', $validate, $m)) {
                $userid = $origin.':'.$m[1];
            }
            if ($userid) {
                if ($raison_sociale) {
                    $f3->set('SESSION.username', $raison_sociale);
                }
                $f3->set('SESSION.userid', $userid);
                $f3->set('SESSION.authtype', 'viticonnect');
            }
        }
        return $f3->reroute('/qrcode');
    }

    function qrcodeDisconnect(Base $f3) {
        $f3->clear('SESSION.userid');
        $f3->clear('SESSION.username');
        if ($f3->get('SESSION.authtype') == 'viticonnect') {
            $f3->clear('SESSION.authtype');
            $config = $this->getConfig($f3);
            return $f3->reroute($config['viticonnect_baseurl'].'/logout?service='.$f3->get('urlbase').'/');
        } elseif ($f3->get('SESSION.authtype') == 'http') {
            if ($f3->exists('SESSION.disconnection')) {
                $f3->clear('SESSION.authtype');
                $f3->clear('SESSION.disconnection');
                return $f3->reroute('/qrcode');
            }
            $f3->set('SESSION.disconnection', true);
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            die ("Not authorized qrcodeAuthentication");
        }
        $f3->clear('SESSION.authtype');
        return $f3->reroute('/');
    }

    function qrcodeList(Base $f3) {
      $this->authenticatedUserOnly($f3);
      if ($f3->exists('PARAMS.userid')) {
        $f3->set('qrlist', QRCode::findByUserid($f3->get('PARAMS.userid')));
        $f3->set('userid', $f3->get('PARAMS.userid'));
        $f3->set('content', 'qrcode_list.html.php');
        echo View::instance()->render('layout.html.php');
      }
    }

    public function qrcodeDuplicate(Base $f3) {
        $qrcode = QRCode::findById($f3->get('PARAMS.qrcodeid'));
        $fields = $qrcode->exportToHttp();

        return $f3->reroute(
            $f3->alias('qrcodecreate', ['userid' => $qrcode->user_id]) . '?' . http_build_query($fields),
        );
    }

    public function qrcodeView(Base $f3)
    {
        $qrcode = QRCode::findById($f3->get('PARAMS.qrcodeid'));

        if ($qrcode === null) {
            $f3->error(404, "QRCode non trouvé");
            exit;
        }

        if (! ( isset($_SESSION)  || isset($_COOKIE) ) || ! $f3->get('SESSION.userid')) {
            $geo = Geo::instance();
            $location = $geo->location();
            unset($location['request'], $location['delay'], $location['credit']);
            $qrcode->addVisite(['date' => date('Y-m-d H:i:s'), 'location' => $location]);
            $qrcode->save();
            header('Cache-Control: max-age=1800');
            header('Expires: '.date('r', strtotime('+30min')));
            header('Pragma: cache');
        }

        $versions = $qrcode->getVersions();
        $lastVersion = $qrcode->date_version;
        $allVersions = array_merge([$lastVersion], array_keys($versions));
        $currentVersion = $qrcode->date_version;
        if ($f3->get('GET.version') && !empty($versions[$f3->get('GET.version')])) {
          $qrcode->date_version = $f3->get('GET.version');
          $qrcode->copyfrom($versions[$qrcode->date_version]);
        }

        $this->initDefaultOnQRCode($qrcode, $f3);

        $f3->set('content', 'qrcode_show.html.php');
        $f3->set('qrcode', $qrcode);
        $f3->set('publicview', true);
        $f3->set('lastVersion', $lastVersion);
        $f3->set('allVersions', $allVersions);

        echo View::instance()->render('layout_public.html.php');
    }

    public function qrcodeParametrage(Base $f3) {
        if (isset($_GET['from'])) {
            $f3->set('from', $_GET['from']);
        }
        $this->authenticatedUserOnly($f3);
        $qrcode = $f3->get('PARAMS.qrcodeid');

        $qrcode = QRCode::findById($qrcode);
        if ($qrcode === null) {
            $f3->error(404, "QRCode non trouvé");
            exit;
        }
        $f3->set('qrcode', $qrcode);

        $f3->set('canSwitchLogo', $this->isAppellationInConfig($qrcode->appellation));
        $f3->set('content', 'qrcode_parametrage.html.php');
        echo View::instance()->render('layout.html.php');
    }

    public function qrcodeStats(Base $f3) {
        $this->authenticatedUserOnly($f3);
        $qrcodeid = $f3->get('PARAMS.qrcodeid');
        $qrcode = QRCode::findById($qrcodeid);

        $f3->set('qrcode', $qrcode);
        $f3->set('type', $f3->get('PARAMS.type'));
        $f3->set('content', 'qrcode_stats.html.php');
        echo View::instance()->render('layout.html.php');

    }

    public function qrcodeDisplay(Base $f3) {
        $qrcode = QRCode::findById($f3->get('PARAMS.qrcodeid'));
        $qrcode->logo = (bool)$f3->get('POST.logo');
        $qrcode->mentions = (bool)$f3->get('POST.mentions');

        $config = $this->getConfig($f3);
        if ($this->isAppellationInConfig($qrcode->appellation) === false) {
            $qrcode->logo = false;
        }

        $qrcode->save();
        return $f3->reroute('/qrcode/'.$qrcode->user_id.'/parametrage/'.$qrcode->getId(), false);
    }

    public function qrcodeMultiExport(Base $f3) {
        $qrcodes = $f3->get('GET.qrcodes');
        $formats = ['svg', 'pdf', 'eps'];
        $config = $this->getConfig($f3);
        $options = isset($config['qrcode']) ? $config['qrcode'] : [];
        $userid = null;


        foreach ($qrcodes as $qr) {
            $qr = QRCode::findById($qr);
            if ($qr === null) {
                $f3->error(404, "QRCode non trouvé");
                exit;
            }

            if ($qr->user_id != $f3->get('PARAMS.userid')) {
                throw new Exception('not allowed');
            }
            $userid = $qr->user_id;

            foreach ($formats as $format) {
                $files[$format][$qr->getId()] = $qr->getQRCodeContent($format, $f3->get('urlbase'), $options);
            }
        }

        $name = tempnam(sys_get_temp_dir(), "qrcodes");
        $zip = new ZipArchive;
        if ($zip->open($name, ZipArchive::OVERWRITE) === TRUE) {
                foreach ($files as $format => $id) {
                    foreach ($id as $id => $content) {
                        $zip->addFromString($format.'/'.$id, $content);
                    }
                }
                $zip->close();
            }

        header('Content-type: application/zip');
        header('Content-disposition: attachment; filename=qrcodes_'.$userid.'.zip');
        readfile($name);
    }

    public function export(Base $f3)
    {
        $qrcode = QRCode::findById($f3->get('PARAMS.qrcodeid'));

        if ($qrcode === null) {
            $f3->error(404, "QRCode non trouvé");
            exit;
        }

        Exporter::getInstance()->setResponseHeaders($f3->get('PARAMS.format'));

        echo $qrcode->getQRCodeContent($f3->get('PARAMS.format'), $f3->get('urlbase'), $f3->get('config')['qrcode']);
    }

    public function adminUsers(Base $f3) {
        if (!$this->isAdmin($f3)) {
            die('Unauthorized');
        }
        $users = [];
        foreach (QRCode::findAll() as $d) {
            $users[$d->user_id] = $d->domaine_nom;
        }
        $f3->set('users', $users);
        $f3->set('content', 'admin_users.html.php');
        echo View::instance()->render('layout.html.php');
    }

    public function isAppellationInConfig($appellation) {
        $c = Base::instance()->get('config');
        $appellationsInstance = array_key_exists('appellations', $c) ? $c['appellations'] : [];

        return in_array($appellation, $appellationsInstance);
    }
}
