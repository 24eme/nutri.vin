<?php

namespace app\models;

use app\exporters\Exporter;
use app\models\DBManager;
use \Flash;
use \Base;

class QRCode extends Mapper
{
    const IMG_MAX_RESOLUTION = 2000;
    const IMG_VERSION_MAX_RESOLUTION = 200;

    public static $CHARID = 'azertyuiopqsdfghjklmwxcvbn'.
                            'AZERTYUIOPQSDFGHJKLMWXCVBN'.
                            '0123456789';
    public static $LABELS = ["HVE", "Demeter", "Biodyvin"];

    public static $versionning_ignore_fields = [
      'authorization_key',
      'date_version',
      'logo',
      'mentions',
      'visites',
      'versions'
    ];

	public static $copy_field_filter = [
		"domaine_nom" => 1,
		"adresse_domaine" => 1,
		"cuvee_nom" => 1,
		"appellation" => 1,
		"couleur" => 1,
		"millesime" => 1,
		"alcool_degre" => 1,
		"centilisation" => 1,
		"lot" => 1,
		"ingredients" => 1,
		"nutritionnel_energie_kj" => 1,
		"nutritionnel_energie_kcal" => 1,
		"nutritionnel_graisses" => 1,
		"nutritionnel_acides_gras" => 1,
		"nutritionnel_glucides" => 1,
		"nutritionnel_sucres" => 1,
		"nutritionnel_proteines" => 1,
		"nutritionnel_sel" => 1,
		"nutritionnel_sodium" => 1,
		"image_bouteille" => 1,
		"image_etiquette" => 1,
		"image_contreetiquette" => 1,
		"autres_infos" => 1,
        'responsable_nom' => 1,
        'responsable_siret' => 1,
        'responsable_adresse' => 1,
		"authorization_key" => 1,
    "labels" => 1,
  ];

    public static $getFieldsAndType = [
        /* $fields[$id] => 'VARCHAR(255) PRIMARY KEY', */
        'user_id' => 'VARCHAR(255)',
        'domaine_nom' => 'VARCHAR(255)',
        'adresse_domaine' => 'VARCHAR(255)',
        'appellation' => 'VARCHAR(255)',
        'couleur' => 'VARCHAR(255)',
        'cuvee_nom' => 'VARCHAR(255)',
        'alcool_degre' => 'FLOAT',
        'centilisation' => 'FLOAT',
        'millesime' => 'VARCHAR(255)',
        'lot' => 'VARCHAR(255)',
        'ingredients' => 'TEXT',
        'nutritionnel_energie_kj' => 'FLOAT',
        'nutritionnel_energie_kcal' => 'FLOAT',
        'nutritionnel_graisses' => 'FLOAT',
        'nutritionnel_acides_gras' => 'FLOAT',
        'nutritionnel_glucides' => 'FLOAT',
        'nutritionnel_sucres' => 'FLOAT',
        'nutritionnel_fibres' => 'FLOAT',
        'nutritionnel_proteines' => 'FLOAT',
        'nutritionnel_sel' => 'FLOAT',
        'nutritionnel_sodium' => 'FLOAT',
        'image_bouteille' => 'BLOB',
        'image_etiquette' => 'BLOB',
        'image_contreetiquette' => 'BLOB',
        'autres_infos' => 'TEXT',
        'responsable_nom' => 'VARCHAR(255)',
        'responsable_siret' => 'VARCHAR(14)',
        'responsable_adresse' => 'VARCHAR(255)',
        'authorization_key' => 'VARCHAR(100)',
        'date_creation' => 'VARCHAR(26)',
        'date_version' => 'VARCHAR(26)',
        'logo' => 'BOOL',
        'mentions' => 'BOOL',
        'appellation_instance' => 'BOOL',
        'visites' => 'TEXT',
        'labels' => 'TEXT',
        'versions' => 'TEXT',
    ];

    public static function findByUserid($userid) {
      return self::find(['user_id=?',$userid]);
	   }

  public static function findAll() {
      return self::find();
  }

  public static function find($criteria = null, $instance_only = true) {
      $class = get_called_class();
      $e = new $class();
      $items = [];
      foreach ($e->mapper->find($criteria) as $result) {
          $a = new $class();
          $a->mapper->load([self::$primaryKey.'=?', $result->{self::$primaryKey}]);
          if ($instance_only && (!$a || strpos($a->getId(), getenv('INSTANCE_ID')) !== 0)) {
              continue;
          }
          $items[] = $a;
      };
      return $items;
  }

  public static function findById($id, $instance_only = true) {
      $a = parent::findById($id);
      if ($instance_only && $a && strpos($a->getId(), getenv('INSTANCE_ID')) !== 0) {
          return null;
      }
      return $a;

  }

    public static function getListeCategoriesAdditif() {
        return [
            "Activateur de fermentation",
            "Agent de fermentation",
            "Agent stabilisateur",
            "Conservateur et antioxydant",
            "Correction de défauts",
            "Enzyme",
            "Gaz et gaz d'emballage",
            "Régulateur d'acidité",

        ];
    }

  public static function getFullListeIngredients() {
        return [
            _("Raisins") => [],
            _("Moût de raisin concentré") => [],
            _("Liqueur de tirage et liqueur d'expédition") => [],
            _("Acide tartrique - E334") => ['additif' => "Régulateur d'acidité"],
            _("Acide malique - E296") => ['additif' => "Régulateur d'acidité"],
            _("Acide lactique - E270") => ['additif' => "Régulateur d'acidité"],
            _("Tartrate de potassium - E336") => ['additif' => "Régulateur d'acidité"],
            _("Bicarbonate de potassium - E501") => ['additif' => "Régulateur d'acidité"],
            _("Carbonate de calcium - E170") => ['additif' => "Régulateur d'acidité"],
            _("Tartrate de calcium - E354") => ['additif' => "Régulateur d'acidité"],
            _("Sulfate de calcium - E516") => ['additif' => "Régulateur d'acidité"],
            _("Carbonate de potassium - E501") => ['additif' => "Régulateur d'acidité"],
            _("Hydrogénotartrate de potassium - E336") => ['additif' => "Agent stabilisateur"],
            _("Tartrate de calcium - E354") => ['additif' => "Agent stabilisateur"],
            _("Acide citrique - E330") => ['additif' => "Agent stabilisateur"],
            _("Tanins") => ['additif' => "Agent stabilisateur"],
            _("Ferrocyanure de potassium - E536") => ['additif' => "Agent stabilisateur"],
            _("Phytate de calcium")=> ['additif' => "Agent stabilisateur"],
            _("Acide métatartrique - E353")=> ['additif' => "Agent stabilisateur"],
            _("Gomme arabique - E414")=> ['additif' => "Agent stabilisateur"],
            _("Acide DL-tartrique")=> ['additif' => "Agent stabilisateur"],
            _("Sel neutre de potassium")=> ['additif' => "Agent stabilisateur"],
            _("Mannoprotéines de levures")=> ['additif' => "Agent stabilisateur"],
            _("Carboxyméthylcellulose - E466")=> ['additif' => "Agent stabilisateur"],
            _("Copolymères polyvinylimidazole/polyvinylpyrrolidone")=> ['additif' => "Agent stabilisateur"],
            _("Polyaspartate de potassium - E456")=> ['additif' => "Agent stabilisateur"],
            _("Dioxyde de soufre - E220") => ['additif' => "Conservateur et antioxydant"],
            _("Bisulfite de potassium - E228") => ['additif' => "Conservateur et antioxydant"],
            _("Métabisulfite de potassium - E224") => ['additif' => "Conservateur et antioxydant"],
            _("Sorbate de potassium - E202") => ['additif' => "Conservateur et antioxydant"],
            _("Lysozyme - E1105") => ['additif' => "Conservateur et antioxydant"],
            _("Acide L-ascorbique - E300") => ['additif' => "Conservateur et antioxydant"],
            _("Dicarbonate de diméthyle - E242") => ['additif' => "Conservateur et antioxydant"],
            _("Argon - E938") => ['additif' => "Gaz et gaz d'emballage"],
            _("Azote - E941") => ['additif' => "Gaz et gaz d'emballage"],
            _("Dioxyde de carbone - E290") => ['additif' => "Gaz et gaz d'emballage"],
            _("Oxygène gazeux - E948") => ['additif' => "Gaz et gaz d'emballage"],
            _("Sulfites") => ["allergene" => true],
            _("Anhydride sulfureux") => ["allergene" => true],
            _("Oeuf") => ["allergene" => true],
            _("Protéine de l'oeuf") => ["allergene" => true],
            _("Produit de l'oeuf") => ["allergene" => true],
            _("Lysozyme de l'oeuf") => ["allergene" => true],
            _("Albumine de l'oeuf") => ["allergene" => true],
            _("Lait") => ["allergene" => true],
            _("Produits du lait") => ["allergene" => true],
            _("Caséine du lait ou protéine du lait") => ["allergene" => true],
            _("Résine de pin d'Alep") => [],
            _("Lies fraîches") => [],
            _("Caramel - E150") => [],
            _("Isothiocyanate d'allyle") => [],
            _("Levures inactivées") => [],
            _("Charbons à usage oenologique") => [],
            _("Fibres végétales sélectives") => [],
            _("Cellulose microcristalline - E460") => ["additif" => "Activateur de fermentation"],
            _("Hydrogénophosphate de diammonium - E342/CAS7783-28-0") => ["additif" => "Activateur de fermentation"],
            _("Sulfate d'ammonium - E517/CAS7783-20-2") => ["additif" => "Activateur de fermentation"],
            _("Bisulfite d'ammonium") => ["additif" => "Activateur de fermentation"],
            _("Chlorhydrate de thiamine") => ["additif" => "Activateur de fermentation"],
            _("Autolysats de levures") => ["additif" => "Activateur de fermentation"],
            _("Levures inactivées") => ["additif" => "Activateur de fermentation"],
            _("Levures inactivées ayant des niveaux garantis de glutathion") => ["additif" => "Activateur de fermentation"],
            _("Gélatine alimentaire") => ["additif" => "Activateur de fermentation"],
            _("Protéine de blé") => ["additif" => "Activateur de fermentation"],
            _("Protéine issue de pois") => ["additif" => "Activateur de fermentation"],
            _("Protéine issue de pommes de terre") => ["additif" => "Activateur de fermentation"],
            _("Colle de poisson") => ["additif" => "Activateur de fermentation"],
            _("Caséines") => ["additif" => "Activateur de fermentation"],
            _("Caséinates de potassium") => ["additif" => "Activateur de fermentation"],
            _("Ovalbumine") => ["additif" => "Activateur de fermentation"],
            _("Bentonite - E558") => ["additif" => "Activateur de fermentation"],
            _("Dioxyde de silicium - E551") => ["additif" => "Activateur de fermentation"],
            _("Kaolin") => ["additif" => "Activateur de fermentation"],
            _("Tanins") => ["additif" => "Activateur de fermentation"],
            _("Chitosane dérivé d'Aspergillus niger") => ["additif" => "Activateur de fermentation"],
            _("Chitine-glucane dérivé d'Aspergillus") => ["additif" => "Activateur de fermentation"],
            _("Polyvinylpolypyrrolidone - E1202") => ["additif" => "Activateur de fermentation"],
            _("Alginate de calcium - E404") => ["additif" => "Activateur de fermentation"],
            _("Alginate de potassium - E402") => ["additif" => "Activateur de fermentation"],
            _("Uréase") => ["additif" => "Enzyme"],
            _("Pectines lyases") => ["additif" => "Enzyme"],
            _("Pectine méthylestérase") => ["additif" => "Enzyme"],
            _("Polygalacturonase") => ["additif" => "Enzyme"],
            _("Hémicellulase") => ["additif" => "Enzyme"],
            _("Cellulase") => ["additif" => "Enzyme"],
            _("Bétaglucanase") => ["additif" => "Enzyme"],
            _("Glycosidase") => ["additif" => "Enzyme"],
            _("Levures de vinification") => ["additif" => "Agent de fermentation"],
            _("Bactéries lactiques") => ["additif" => "Agent de fermentation"],
            _("Sulfate de cuivre, pentahydraté") => ["additif" => "Correction de défauts"],
            _("Citrate de cuivre") => ["additif" => "Correction de défauts"],
            _("Chitosane dérivé d'Aspergillus niger") => ["additif" => "Correction de défauts"],
            _("Chitine-glucane dérivé d'Aspergillus") => ["additif" => "Correction de défauts"],
            _("dérivé d'Aspergillus niger") => ["additif" => "Correction de défauts"],
            _("Levures inactivées") => ["additif" => "Correction de défauts"],
            _("Résine de pin d'Alep") => [],
            _("Lies fraîches") => [],
            _("Caramel - E150") => [],
            _("Isothiocyanate d'allyle") => [],
            _("Levures inactivées") => [],
            _("Hydrogénotartrate de potassium") => ['additif' => "Agent stabilisateur"],
            _("Tartrate de calcium") => ['additif' => "Agent stabilisateur"],
            _("E354") => ['additif' => "Agent stabilisateur"],
            _("Acide citrique") => ['additif' => "Agent stabilisateur"],
            _("E330") => ['additif' => "Agent stabilisateur"],
            _("Ferrocyanure de potassium") => ['additif' => "Agent stabilisateur"],
            _("E536") => ['additif' => "Agent stabilisateur"],
            _("Acide métatartrique") => ['additif' => "Agent stabilisateur"],
            _("E353") => ['additif' => "Agent stabilisateur"],
            _("Gomme arabique") => ['additif' => "Agent stabilisateur"],
            _("E414") => ['additif' => "Agent stabilisateur"],
            _("Carboxyméthylcellulose") => ['additif' => "Agent stabilisateur"],
            _("E466") => ['additif' => "Agent stabilisateur"],
            _("Polyaspartate de potassium") => ['additif' => "Agent stabilisateur"],
            _("E456") => ['additif' => "Agent stabilisateur"],
            _("Dioxyde de soufre") => ['additif' => "Conservateur et antioxydant"],
            _("E220") => ['additif' => "Conservateur et antioxydant"],
            _("Bisulfite de potassium") => ['additif' => "Conservateur et antioxydant"],
            _("E228") => ['additif' => "Conservateur et antioxydant"],
            _("Métabisulfite de potassium") => ['additif' => "Conservateur et antioxydant"],
            _("E224") => ['additif' => "Conservateur et antioxydant"],
            _("Sorbate de potassium") => ['additif' => "Conservateur et antioxydant"],
            _("E202") => ['additif' => "Conservateur et antioxydant"],
            _("Lysozyme") => ['additif' => "Conservateur et antioxydant"],
            _("E1105") => ['additif' => "Conservateur et antioxydant"],
            _("Acide L-ascorbique") => ['additif' => "Conservateur et antioxydant"],
            _("E300") => ['additif' => "Conservateur et antioxydant"],
            _("Dicarbonate de diméthyle") => ['additif' => "Conservateur et antioxydant"],
            _("E242") => ['additif' => "Conservateur et antioxydant"],
            _("Argon") => ['additif' => "Gaz et gaz d'emballage"],
            _("E938") => ['additif' => "Gaz et gaz d'emballage"],
            _("Azote") => ['additif' => "Gaz et gaz d'emballage"],
            _("E941") => ['additif' => "Gaz et gaz d'emballage"],
            _("Dioxyde de carbone") => ['additif' => "Gaz et gaz d'emballage"],
            _("E290") => ['additif' => "Gaz et gaz d'emballage"],
            _("Oxygène gazeux") => ['additif' => "Gaz et gaz d'emballage"],
            _("E948") => ['additif' => "Gaz et gaz d'emballage"],
            _("Cellulose microcristalline") => ["additif" => "Activateur de fermentation"],
            _("E460") => ["additif" => "Activateur de fermentation"],
            _("Hydrogénophosphate de diammonium") => ["additif" => "Activateur de fermentation"],
            _("E342/CAS7783-28-0") => ["additif" => "Activateur de fermentation"],
            _("Sulfate d'ammonium") => ["additif" => "Activateur de fermentation"],
            _("E517/CAS7783-20-2") => ["additif" => "Activateur de fermentation"],
            _("Bentonite") => ["additif" => "Activateur de fermentation"],
            _("E558") => ["additif" => "Activateur de fermentation"],
            _("Dioxyde de silicium") => ["additif" => "Activateur de fermentation"],
            _("E551") => ["additif" => "Activateur de fermentation"],
            _("Polyvinylpolypyrrolidone") => ["additif" => "Activateur de fermentation"],
            _("E1202") => ["additif" => "Activateur de fermentation"],
            _("Alginate de calcium") => ["additif" => "Activateur de fermentation"],
            _("E404") => ["additif" => "Activateur de fermentation"],
            _("Alginate de potassium") => ["additif" => "Activateur de fermentation"],
            _("E402") => ["additif" => "Activateur de fermentation"],
            _("Acide tartrique") => ['additif' => "Régulateur d'acidité"],
            _("E334") => ['additif' => "Régulateur d'acidité"],
            _("Acide malique") => ['additif' => "Régulateur d'acidité"],
            _("E296") => ['additif' => "Régulateur d'acidité"],
            _("Acide lactique") => ['additif' => "Régulateur d'acidité"],
            _("E270") => ['additif' => "Régulateur d'acidité"],
            _("Tartrate de potassium") => ['additif' => "Régulateur d'acidité"],
            _("E336") => ['additif' => "Régulateur d'acidité"],
            _("Bicarbonate de potassium") => ['additif' => "Régulateur d'acidité"],
            _("E501") => ['additif' => "Régulateur d'acidité"],
            _("Carbonate de calcium") => ['additif' => "Régulateur d'acidité"],
            _("E170") => ['additif' => "Régulateur d'acidité"],
            _("Tartrate de calcium") => ['additif' => "Régulateur d'acidité"],
            _("E354") => ['additif' => "Régulateur d'acidité"],
            _("Sulfate de calcium") => ['additif' => "Régulateur d'acidité"],
            _("E516") => ['additif' => "Régulateur d'acidité"],
            _("Carbonate de potassium") => ['additif' => "Régulateur d'acidité"],
            _("E501") => ['additif' => "Régulateur d'acidité"],
        ];
  }

	public function save() {
		if (!isset($this->authorization_key) || $this->authorization_key) {
			$this->authorization_key = sha1(implode(',',$this->toArray()).rand());
		}
		if (!$this->getId()) {
			$this->setId(self::generateId());
			$this->logo = true;
			$this->mentions = true;
		}
        if (!$this->appellation_instance) {
            $this->logo = false;
        }

		if (!$this->date_creation) {
      $this->date_version = date('c');
			$this->date_creation = $this->date_version;
		}

    $this->saveVersion();

		return $this->mapper->save();
	}

  private function saveVersion() {
    if (!$this->getVisites()) return;
    if (!$this->changed()) return;
    if (!$this->getId()) return;

    $initial = (self::findById($this->getId()))->toArray();
    $versionDate = $initial['date_version'];
    $current = $this->toArray();

    foreach (self::$versionning_ignore_fields as $field) {
      if (isset($initial[$field])) unset($initial[$field]);
      if (isset($current[$field])) unset($current[$field]);
    }

    if (array_diff_assoc($current, $initial)) {
      $this->addVersion($initial, $versionDate);
      $this->date_version = date('c');
    }
  }

	public static function generateId() {
		for($x = 0 ; $x < 10 ; $x++) {
			$id = getenv('INSTANCE_ID');
			$id = ($id) ? $id : "0";
			for($i = strlen($id) ; $i < 7 ; $i++) {
				$id .= substr(self::$CHARID, rand(0, strlen(self::$CHARID)), 1);
			}
			$qr = self::findById($id);
			if (! $qr) {
				return $id;
			}
		}
		throw new Exception('no free id found');
	}

	public function getQRCodeContent($format, $urlbase, $config) {
        return Exporter::getInstance()->getQRCodeContent(
            $urlbase.'/'.$this->getId(),
            $format,
            ($this->logo) ? $config['logo'] : false,
            ($this->mentions) ? [$this->nutritionnel_energie_kcal, $this->nutritionnel_energie_kj]: []
        );
  }

  protected function getJsonValueField($field) {
    $value = $this->get($field);
    if ($value) {
      return json_decode($value, true);
    }
    return [];
  }

  public function getVisites() {
    return $this->getJsonValueField('visites');
  }

  public function getLabels() {
    return $this->getJsonValueField('labels');
  }

  public function getVersions() {
    return $this->getJsonValueField('versions');
  }

  public function addVisite(array $infos) {
    $visites = $this->getVisites();
    $visites[] = $infos;
    $this->visites = json_encode($visites);
  }

  private function addVersion(array $qrcode, $datetime) {
    $versions = $this->getVersions();
    if (!empty($qrcode['image_bouteille'])) {
      $qrcode['image_bouteille'] = $this->getImageResized($qrcode['image_bouteille']);
    }
    if (!empty($qrcode['image_etiquette'])) {
      $qrcode['image_etiquette'] = $this->getImageResized($qrcode['image_etiquette']);
    }
    if (!empty($qrcode['image_contreetiquette'])) {
      $qrcode['image_contreetiquette'] = $this->getImageResized($qrcode['image_contreetiquette']);
    }
    $versions[$datetime] = $qrcode;
    krsort($versions);
    $this->versions = json_encode($versions);
  }

  private function getImageResized($b64Image) {
    if (strpos($b64Image, 'base64,') === false) {
      return $b64Image;
    }
    $entete = substr($b64Image, 0, strpos($b64Image, 'base64,')+7);
    $image = base64_decode(substr($b64Image, strpos($b64Image, 'base64,')+7));
    $tmp = tmpfile();
    $metas = stream_get_meta_data($tmp);
    fwrite($tmp, $image);
    $imageResized = self::resizeImage($metas['uri'], self::IMG_VERSION_MAX_RESOLUTION);
    if ($imageResized) {
      return $entete.base64_encode(file_get_contents($imageResized));
    }
    fclose($tmp);
    return $b64Image;
  }

    public function getImages()
    {
        $images['image_bouteille'] = $this->image_bouteille;
        $images['image_etiquette'] = $this->image_etiquette;
        $images['image_contreetiquette'] = $this->image_contreetiquette;
        return $images;
    }

    public function exportToHttp()
    {
        $fields = $this->toArray();
        $fields['visites'] = 0;
        unset($fields['versions']);
        unset($fields['image_bouteille']);
        unset($fields['image_etiquette']);
        unset($fields['image_contreetiquette']);
        unset($fields['date_creation']);
        unset($fields['date_version']);

        Base::instance()->set('SESSION.qrcode.image_etiquette', $this->image_etiquette ?: null);
        Base::instance()->set('SESSION.qrcode.image_contreetiquette', $this->image_contreetiquette ?: null);
        Base::instance()->set('SESSION.qrcode.image_bouteille', $this->image_bouteille ?: null);

        return $fields;
    }

    public function clone($from)
    {
        $this->copyFrom($from);
        $this->image_bouteille = Base::instance()->get('SESSION.qrcode.image_bouteille');
        $this->image_etiquette = Base::instance()->get('SESSION.qrcode.image_etiquette');
        $this->image_contreetiquette = Base::instance()->get('SESSION.qrcode.image_contreetiquette');
    }

    public function getIngredientsTraduits() {
        return implode('',array_map('_',preg_split("/([ ]*[,;()][ ]*)/", $this->ingredients, -1, PREG_SPLIT_NO_EMPTY  | PREG_SPLIT_DELIM_CAPTURE)));
    }


    public function getGeoStats() {
        $stats = [];
        foreach($this->getVisites() as $v) {
            $v = $v['location'];
            $name = 'localisation inconnue';
            $k = $name;
            if (isset($v['country_code']) && isset($v['region_code']) ) {
                $k = $v['country_code'].$v['region_code'];
                $name = $v['region_name'].' ('.$v['country_name'].')';
            }
            if (!isset($stats[$k])) {
                $stats[$k] = ['nb' => 0, 'name' => $name];
            }
            $stats[$k]['nb']++;
        }
        return $stats;
    }


    public function getStats($type) {
        switch ($type) {
            case 'week':
                return $this->getWeekStats();
                break;
            case 'geo':
                return $this->getGeoStats();
            default:
                throw new \Exception('wrong stats type '.$type);
                break;
        }
    }
    public function getWeekStats() {
        $stats = [];
        foreach($this->getVisites() as $v) {
            $v = str_replace(' ', '-', $v['date']);
            $duedt = explode("-", $v);
            $date  = mktime(0, 0, 0, $duedt[1], $duedt[2], $duedt[0]);
            $week  = sprintf('%04d%02d', $duedt[0], (int)date('W', $date));
            if (!isset($stats[$week])) {
                $stats[$week] = ['nb' => 0];
            }
            $stats[$week]['nb']++;
        }
        for($i = min(array_keys($stats)) ; $i <= max(array_keys($stats)) ; $i++) {
            $week = substr($i, 4, 2);
            $annee = substr($i, 0, 4);
            if ($week > 52) {
                $i = $annee.'00';
                continue;
            }
            if (!isset($stats[$i])) {
                $stats[$i] = ['nb' => 0];
            }
            $wday = (date('w', strtotime($annee.'-01-01')) + 6) % 7;
            $stats[$i]['name'] = date('Y-m-d', strtotime($annee.'-01-01 + ' . $wday.' days +'.($week -1).' weeks'));
        }
        return $stats;
    }

    public function getResponsableSIREN() {
        if (!$this->responsable_siret) {
            return '';
        }
        return substr($this->responsable_siret, 0, 9);
    }

    public static function resizeImage($image, $max) {
      if (!is_file($image)) {
        return false;
      }
      $size = getimagesize($image);
      $width = $size[0];
      $height = $size[1];
      $mime = $size['mime'];
      if ($height <= $max) {
        return $image;
      }
      $newHeight = $max;
      $newWidth = floor($max * $width / $height);
      $newImage = imagecreatetruecolor($newWidth, $newHeight);
      if ($mime == 'image/jpeg') {
        $source = imagecreatefromjpeg($image);
        imagecopyresampled($newImage, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagejpeg($newImage, $image);
      } elseif ($mime == 'image/png') {
        $source = imagecreatefrompng($image);
        imagealphablending($newImage, false);
        imagesavealpha($newImage,true);
        imagecopyresampled($newImage, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagepng($newImage, $image);
      } else {
        return false;
      }
      imagedestroy($source);
      imagedestroy($newImage);
      return $image;
    }

    public static function siret2adresse($siret) {
        if (strlen($siret) === 9) {
            $url = "https://api-avis-situation-sirene.insee.fr/identification/siren/".$siret;
        }else{
            $url = "https://api-avis-situation-sirene.insee.fr/identification/siret/".$siret;
        }
        $json = json_decode(file_get_contents($url));
        foreach($json->etablissements as $e) {
            if (strpos($e->siret, $siret) === 0 ) {
                $adresse = '';
                $adresse .= ($e->adresseEtablissement->complementAdresseEtablissement) ? $e->adresseEtablissement->complementAdresseEtablissement.' ' : '';
                $adresse .= ($e->adresseEtablissement->numeroVoieEtablissement) ? $e->adresseEtablissement->numeroVoieEtablissement.' ' : '';
                $adresse .= ($e->adresseEtablissement->indiceRepetitionEtablissement) ? $e->adresseEtablissement->indiceRepetitionEtablissement.' ' : '';
                $adresse .= ($e->adresseEtablissement->typeVoieEtablissement) ? $e->adresseEtablissement->typeVoieEtablissement.' ' : '';
                $adresse .= ($e->adresseEtablissement->libelleVoieEtablissement) ? $e->adresseEtablissement->libelleVoieEtablissement.' ' : '';
                $adresse .= ($e->adresseEtablissement->codePostalEtablissement) ? $e->adresseEtablissement->codePostalEtablissement.' ' : '';
                $adresse .= ($e->adresseEtablissement->libelleCommuneEtablissement) ? $e->adresseEtablissement->libelleCommuneEtablissement.' ' : '';
                $adresse .= ($e->adresseEtablissement->libelleCommuneEtrangerEtablissement) ? $e->adresseEtablissement->libelleCommuneEtrangerEtablissement.' ' : '';
                $adresse = rtrim($adresse);
                return strtolower($adresse);
            }
        }
        return ;
    }
}
