<?php

namespace app\models;

use app\config\Config;
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
    public static $LABELS = ["Bio", "HVE", "Demeter", "Biodyvin"];

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
        "ean" => 1,
        "gs1" => 1,
        "adresse_domaine" => 1,
        "cuvee_nom" => 1,
        "denomination" => 1,
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
        'ean' => 'VARCHAR(255)',
        'domaine_nom' => 'VARCHAR(255)',
        'adresse_domaine' => 'VARCHAR(255)',
        'denomination' => 'VARCHAR(255)',
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
        'gs1' => 'BOOL',
        'denomination_instance' => 'BOOL',
        'visites' => 'TEXT',
        'labels' => 'TEXT',
        'versions' => 'TEXT',
    ];

    public static function findByUserid($userid) {
        return self::find(['user_id=?',$userid]);
    }

    public static function findAll($limit = 20, $instance_only = true) {
        $class = get_called_class();
        $e = new $class();
        if (method_exists($e->mapper, 'findAll')) {
            $items = [];
            foreach ($e->mapper->findAll($limit) as $result) {
                $a = new $class();
                $a->mapper->load([self::$primaryKey.'=?', $result->{self::$primaryKey}]);
                if ($instance_only && (!$a || !$a->isPartOfInstance())) {
                    continue;
                }
                $items[] = $a;
            };
            return $items;
        }
        return self::find($criteria);
    }

    public static function find($criteria = null, $instance_only = true) {
        $class = get_called_class();
        $e = new $class();
        $items = [];
        foreach ($e->mapper->find($criteria) as $result) {
            $a = new $class();
            $a->mapper->load([self::$primaryKey.'=?', $result->{self::$primaryKey}]);
            if ($instance_only && (!$a || !$a->isPartOfInstance())) {
                continue;
            }
            $items[] = $a;
        };
        return $items;
    }

    public static function findById($id, $instance_only = true) {
        $a = parent::findById($id);
        if ($instance_only && (!$a || !$a->isPartOfInstance())) {
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
            _("Saccharose") => [],
            _("Acide tartrique - E334") => ['additif' => "Régulateur d'acidité"],
            _("Acide malique - E296") => ['additif' => "Régulateur d'acidité"],
            _("Acide lactique - E270") => ['additif' => "Régulateur d'acidité"],
            _("Tartrate de potassium - E336") => ['additif' => "Régulateur d'acidité", 'facultatif' => true],
            _("Bicarbonate de potassium - E501") => ['additif' => "Régulateur d'acidité", 'facultatif' => true],
            _("Carbonate de calcium - E170") => ['additif' => "Régulateur d'acidité", 'facultatif' => true],
            _("Tartrate de calcium - E354") => ['additif' => "Régulateur d'acidité", 'facultatif' => true],
            _("Sulfate de calcium - E516") => ['additif' => "Régulateur d'acidité"],
            _("Carbonate de potassium - E501") => ['additif' => "Régulateur d'acidité", 'facultatif' => true],
            _("Hydrogénotartrate de potassium - E336") => ['additif' => "Agent stabilisateur", 'facultatif' => true],
            _("Tartrate de calcium - E354") => ['additif' => "Agent stabilisateur", 'facultatif' => true],
            _("Acide citrique - E330") => ['additif' => "Agent stabilisateur"],
            _("Tanins") => ['additif' => "Agent stabilisateur", 'facultatif' => true],
            _("Ferrocyanure de potassium - E536") => ['additif' => "Agent stabilisateur", 'facultatif' => true],
            _("Phytate de calcium")=> ['additif' => "Agent stabilisateur", 'facultatif' => true],
            _("Acide métatartrique - E353")=> ['additif' => "Agent stabilisateur"],
            _("Gomme arabique - E414")=> ['additif' => "Agent stabilisateur"],
            _("Acide DL-tartrique")=> ['additif' => "Agent stabilisateur", 'facultatif' => true],
            _("Sel neutre de potassium")=> ['additif' => "Agent stabilisateur", 'facultatif' => true],
            _("Mannoprotéines de levures")=> ['additif' => "Agent stabilisateur"],
            _("Carboxyméthylcellulose - E466")=> ['additif' => "Agent stabilisateur"],
            _("Copolymères polyvinylimidazole/polyvinylpyrrolidone")=> ['additif' => "Agent stabilisateur", 'facultatif' => true],
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
            _("Oxygène gazeux - E948") => ['additif' => "Gaz et gaz d'emballage", 'facultatif' => true],
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
            _("Lies fraîches") => ['facultatif' => true],
            _("Caramel - E150") => [],
            _("Isothiocyanate d'allyle") => ['facultatif' => true],
            _("Levures inactivées") => ['facultatif' => true],
            _("Charbons à usage oenologique") => ['facultatif' => true],
            _("Fibres végétales sélectives") => ['facultatif' => true],
            _("Cellulose microcristalline - E460") => ["additif" => "Activateur de fermentation", 'facultatif' => true],
            _("Hydrogénophosphate de diammonium - E342/CAS7783-28-0") => ["additif" => "Activateur de fermentation", 'facultatif' => true],
            _("Sulfate d'ammonium - E517/CAS7783-20-2") => ["additif" => "Activateur de fermentation", 'facultatif' => true],
            _("Bisulfite d'ammonium") => ["additif" => "Activateur de fermentation", 'facultatif' => true],
            _("Chlorhydrate de thiamine") => ["additif" => "Activateur de fermentation", 'facultatif' => true],
            _("Autolysats de levures") => ["additif" => "Activateur de fermentation", 'facultatif' => true],
            _("Levures inactivées") => ["additif" => "Activateur de fermentation", 'facultatif' => true],
            _("Levures inactivées ayant des niveaux garantis de glutathion") => ["additif" => "Activateur de fermentation", 'facultatif' => true],
            _("Gélatine alimentaire") => ["additif" => "Agents clarifiants", 'facultatif' => true],
            _("Protéine de blé") => ["additif" => "Agents clarifiants", 'facultatif' => true],
            _("Protéine issue de pois") => ["additif" => "Agents clarifiants", 'facultatif' => true],
            _("Protéine issue de pommes de terre") => ["additif" => "Agents clarifiants", 'facultatif' => true],
            _("Colle de poisson") => ["additif" => "Agents clarifiants", 'facultatif' => true],
            _("Caséines") => ["additif" => "Agents clarifiants", 'facultatif' => true],
            _("Caséinates de potassium") => ["additif" => "Agents clarifiants", 'facultatif' => true],
            _("Ovalbumine") => ["additif" => "Agents clarifiants", 'facultatif' => true],
            _("Bentonite - E558") => ["additif" => "Agents clarifiants", 'facultatif' => true],
            _("Dioxyde de silicium - E551") => ["additif" => "Agents clarifiants", 'facultatif' => true],
            _("Kaolin") => ["additif" => "Agents clarifiants", 'facultatif' => true],
            _("Tanins") => ["additif" => "Agents clarifiants", 'facultatif' => true],
            _("Chitosane dérivé d'Aspergillus niger") => ["additif" => "Agents clarifiants", 'facultatif' => true],
            _("Chitine-glucane dérivé d'Aspergillus") => ["additif" => "Agents clarifiants", 'facultatif' => true],
            _("Polyvinylpolypyrrolidone - E1202") => ["additif" => "Agents clarifiants", 'facultatif' => true],
            _("Alginate de calcium - E404") => ["additif" => "Agents clarifiants", 'facultatif' => true],
            _("Alginate de potassium - E402") => ["additif" => "Agents clarifiants", 'facultatif' => true],
            _("Uréase") => ["additif" => "Enzyme", 'facultatif' => true],
            _("Pectines lyases") => ["additif" => "Enzyme", 'facultatif' => true],
            _("Pectine méthylestérase") => ["additif" => "Enzyme", 'facultatif' => true],
            _("Polygalacturonase") => ["additif" => "Enzyme", 'facultatif' => true],
            _("Hémicellulase") => ["additif" => "Enzyme", 'facultatif' => true],
            _("Cellulase") => ["additif" => "Enzyme", 'facultatif' => true],
            _("Bétaglucanase") => ["additif" => "Enzyme", 'facultatif' => true],
            _("Glycosidase") => ["additif" => "Enzyme", 'facultatif' => true],
            _("Levures de vinification") => ["additif" => "Agent de fermentation", 'facultatif' => true],
            _("Bactéries lactiques") => ["additif" => "Agent de fermentation", 'facultatif' => true],
            _("Sulfate de cuivre, pentahydraté") => ["additif" => "Correction de défauts", 'facultatif' => true],
            _("Citrate de cuivre") => ["additif" => "Correction de défauts", 'facultatif' => true],
            _("Chitosane dérivé d'Aspergillus niger") => ["additif" => "Correction de défauts", 'facultatif' => true],
            _("Chitine-glucane dérivé d'Aspergillus") => ["additif" => "Correction de défauts", 'facultatif' => true],
            _("dérivé d'Aspergillus niger") => ["additif" => "Correction de défauts"],
            _("Levures inactivées") => ["additif" => "Correction de défauts"],
            _("Hydrogénotartrate de potassium") => ['additif' => "Agent stabilisateur", 'facultatif' => true],
            _("Tartrate de calcium") => ['additif' => "Agent stabilisateur", 'facultatif' => true],
            _("E354") => ['additif' => "Agent stabilisateur", 'facultatif' => true],
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
            _("Oxygène gazeux") => ['additif' => "Gaz et gaz d'emballage", 'facultatif' => true],
            _("E948") => ['additif' => "Gaz et gaz d'emballage", 'facultatif' => true],
            _("Mis en bouteille sous atmosphère protectrice") => ['additif' => "Gaz et gaz d'emballage", 'facultatif' => true],
            _("Cellulose microcristalline") => ["additif" => "Activateur de fermentation", 'facultatif' => true],
            _("E460") => ["additif" => "Activateur de fermentation", 'facultatif' => true],
            _("Hydrogénophosphate de diammonium") => ["additif" => "Activateur de fermentation", 'facultatif' => true],
            _("E342/CAS7783-28-0") => ["additif" => "Activateur de fermentation", 'facultatif' => true],
            _("Sulfate d'ammonium") => ["additif" => "Activateur de fermentation", 'facultatif' => true],
            _("E517/CAS7783-20-2") => ["additif" => "Activateur de fermentation", 'facultatif' => true],
            _("Bentonite") => ["additif" => "Activateur de fermentation", 'facultatif' => true],
            _("E558") => ["additif" => "Activateur de fermentation", 'facultatif' => true],
            _("Dioxyde de silicium") => ["additif" => "Activateur de fermentation", 'facultatif' => true],
            _("E551") => ["additif" => "Activateur de fermentation", 'facultatif' => true],
            _("Polyvinylpolypyrrolidone") => ["additif" => "Activateur de fermentation", 'facultatif' => true],
            _("E1202") => ["additif" => "Activateur de fermentation", 'facultatif' => true],
            _("Alginate de calcium") => ["additif" => "Activateur de fermentation", 'facultatif' => true],
            _("E404") => ["additif" => "Activateur de fermentation", 'facultatif' => true],
            _("Alginate de potassium") => ["additif" => "Activateur de fermentation", 'facultatif' => true],
            _("E402") => ["additif" => "Activateur de fermentation", 'facultatif' => true],
            _("Acide tartrique") => ['additif' => "Régulateur d'acidité"],
            _("E334") => ['additif' => "Régulateur d'acidité"],
            _("Acide malique") => ['additif' => "Régulateur d'acidité"],
            _("E296") => ['additif' => "Régulateur d'acidité"],
            _("Acide lactique") => ['additif' => "Régulateur d'acidité"],
            _("E270") => ['additif' => "Régulateur d'acidité"],
            _("Tartrate de potassium") => ['additif' => "Régulateur d'acidité", 'facultatif' => true],
            _("E336") => ['additif' => "Régulateur d'acidité", 'facultatif' => true],
            _("Bicarbonate de potassium") => ['additif' => "Régulateur d'acidité", 'facultatif' => true],
            _("E501") => ['additif' => "Régulateur d'acidité", 'facultatif' => true],
            _("Carbonate de calcium") => ['additif' => "Régulateur d'acidité", 'facultatif' => true],
            _("E170") => ['additif' => "Régulateur d'acidité", 'facultatif' => true],
            _("Tartrate de calcium") => ['additif' => "Régulateur d'acidité", 'facultatif' => true],
            _("E354") => ['additif' => "Régulateur d'acidité", 'facultatif' => true],
            _("Sulfate de calcium") => ['additif' => "Régulateur d'acidité"],
            _("E516") => ['additif' => "Régulateur d'acidité"],
            _("Carbonate de potassium") => ['additif' => "Régulateur d'acidité", 'facultatif' => true],
            _("E501") => ['additif' => "Régulateur d'acidité", 'facultatif' => true],
        ];
    }

    public static function getCouleurs() {
        return [
            'Blanc',
            'Blanc doux',
            'Blanc moelleux',
            'Blanc sec',
            'Rosé',
            'Rouge',
            'Surmûris Blanc',
        ];
    }
    private static $denominations = ["AOC Ajaccio","AOC Aloxe-Corton","AOC Alsace","AOC Alsace Bergheim","AOC Alsace Coteaux du Haut Koenigsbourg","AOC Alsace Côte de Rouffach","AOC Alsace grand cru Altenberg de Bergbieten","AOC Alsace grand cru Altenberg de Bergheim","AOC Alsace grand cru Altenberg de Wolxheim","AOC Alsace grand cru Brand","AOC Alsace grand cru Bruderthal","AOC Alsace grand cru Eichberg","AOC Alsace grand cru Engelberg","AOC Alsace grand cru Florimont","AOC Alsace grand cru Frankstein","AOC Alsace grand cru Froehn","AOC Alsace grand cru Furstentum","AOC Alsace grand cru Geisberg","AOC Alsace grand cru Gloeckelberg","AOC Alsace grand cru Goldert","AOC Alsace grand cru Hatschbourg","AOC Alsace grand cru Hengst","AOC Alsace grand cru Kaefferkopf","AOC Alsace grand cru Kanzlerberg","AOC Alsace grand cru Kastelberg","AOC Alsace grand cru Kessler","AOC Alsace grand cru Kirchberg de Barr","AOC Alsace grand cru Kirchberg de Ribeauvillé","AOC Alsace grand cru Kitterlé","AOC Alsace grand cru Mambourg","AOC Alsace grand cru Mandelberg","AOC Alsace grand cru Marckrain","AOC Alsace grand cru Moenchberg","AOC Alsace grand cru Muenchberg","AOC Alsace grand cru Ollwiller","AOC Alsace grand cru Osterberg","AOC Alsace grand cru Pfersigberg","AOC Alsace grand cru Pfingstberg","AOC Alsace grand cru Praelatenberg","AOC Alsace grand cru Rangen","AOC Alsace grand cru Rosacker","AOC Alsace grand cru Saering","AOC Alsace grand cru Schlossberg","AOC Alsace grand cru Schoenenbourg","AOC Alsace grand cru Sommerberg","AOC Alsace grand cru Sonnenglanz","AOC Alsace grand cru Spiegel","AOC Alsace grand cru Sporen","AOC Alsace grand cru Steinert","AOC Alsace grand cru Steingrubler","AOC Alsace grand cru Steinklotz","AOC Alsace grand cru Vorbourg","AOC Alsace grand cru Wiebelsberg","AOC Alsace grand cru Wineck-Schlossberg","AOC Alsace grand cru Winzenberg","AOC Alsace grand cru Zinnkoepfle","AOC Alsace grand cru Zotzenberg","AOC Alsace Ottrott","AOC Alsace Rodern","AOC Alsace Saint-Hippolyte","AOC Alsace Scherwiller","AOC Alsace Vallée Noble","AOC Alsace Val Saint Grégoire","AOC Alsace Wolxheim","AOC Anjou","AOC Anjou Brissac","AOC Anjou-Coteaux de la Loire","AOC Anjou Villages","AOC Arbois","AOC Arbois Pupillin","AOC Auxey-Duresses","AOC Bandol","AOC Banyuls","AOC Banyuls grand cru","AOC Barsac","AOC Bâtard-Montrachet","AOC Béarn","AOC Beaujolais","AOC Beaujolais Beaujeu","AOC Beaujolais Blacé","AOC Beaujolais Cercié","AOC Beaujolais Charentay","AOC Beaujolais Denicé","AOC Beaujolais Emeringes","AOC Beaujolais Jullié","AOC Beaujolais La Chapelle-de-Guinchay","AOC Beaujolais Lancié","AOC Beaujolais Lantignié","AOC Beaujolais Le Perréon","AOC Beaujolais Les Ardillats","AOC Beaujolais Leynes","AOC Beaujolais Marchampt","AOC Beaujolais Montmelas-Saint-Sorlin","AOC Beaujolais Odenas","AOC Beaujolais Pruzilly","AOC Beaujolais Quincié-en-Beaujolais","AOC Beaujolais Rivolet","AOC Beaujolais Romanèche-Thorins","AOC Beaujolais Saint-Didier-sur-Beaujeu","AOC Beaujolais Saint-Etienne-des-Oullières","AOC Beaujolais Saint-Etienne-la-Varenne","AOC Beaujolais Saint-Julien","AOC Beaujolais Saint-Lager","AOC Beaujolais Salles-Arbuissonnas-en-Beaujolais","AOC Beaujolais Vaux-en-Beaujolais","AOC Beaujolais Vauxrenard","AOC Beaujolais Villages","AOC Beaumes de Venise","AOC Beaune","AOC Bergerac","AOC Bienvenues-Bâtard-Montrachet","AOC Blagny","AOC Blaye","AOC Bonnes-Mares","AOC Bonnezeaux","AOC Bordeaux","AOC Bordeaux Haut-Benauge","AOC Bordeaux supérieur","AOC Bourgogne","AOC Bourgogne aligoté","AOC Bourgogne - blanc exclusif","AOC Bourgogne Chitry","AOC Bourgogne Côte Chalonnaise","AOC Bourgogne Côte d'Or","AOC Bourgogne Côte Saint-Jacques","AOC Bourgogne Côtes d'Auxerre","AOC Bourgogne Côtes du Couchois","AOC Bourgogne Coulanges-la-Vineuse","AOC Bourgogne Epineuil","AOC Bourgogne Hautes Côtes de Beaune","AOC Bourgogne Hautes Côtes de Nuits","AOC Bourgogne La Chapelle Notre-Dame","AOC Bourgogne Le Chapitre","AOC Bourgogne Montrecul","AOC Bourgogne mousseux","AOC Bourgogne Passe-tout-grains","AOC Bourgogne Tonnerre","AOC Bourgueil","AOC Bouzeron","AOC Brouilly","AOC Brulhois","AOC Bugey","AOC Bugey Cerdon","AOC Bugey Manicle","AOC Bugey Montagnieu","AOC Buzet","AOC Cabardès","AOC Cabernet d'Anjou","AOC Cadillac","AOC Cahors","AOC Cairanne","AOC Canon Fronsac","AOC Cérons","AOC Chablis","AOC Chablis Grand Cru","AOC Chablis Grand Cru Blanchot","AOC Chablis Grand Cru Bougros","AOC Chablis Grand Cru Grenouilles","AOC Chablis Grand Cru Les Clos","AOC Chablis Grand Cru Preuses","AOC Chablis Grand Cru Valmur","AOC Chablis Grand Cru Vaudésir","AOC Chambertin","AOC Chambertin-Clos de Bèze","AOC Chambolle-Musigny","AOC Chapelle-Chambertin","AOC Charlemagne","AOC Charmes-Chambertin","AOC Chassagne-Montrachet","AOC Château-Chalon","AOC Château-Grillet","AOC Châteaumeillant","AOC Châteauneuf-du-Pape","AOC Châtillon-en-Diois","AOC Chénas","AOC Chevalier-Montrachet","AOC Cheverny","AOC Chinon","AOC Chiroubles","AOC Chorey-lès-Beaune","AOC Clairette de Bellegarde","AOC Clairette de Die","AOC Clairette du Languedoc","AOC Clairette du Languedoc Adissan","AOC Clairette du Languedoc Aspiran","AOC Clairette du Languedoc Cabrières","AOC Clairette du Languedoc Ceyras","AOC Clairette du Languedoc Fontès","AOC Clairette du Languedoc Le Bosc","AOC Clairette du Languedoc Lieuran-Cabrières","AOC Clairette du Languedoc Nizas","AOC Clairette du Languedoc Paulhan","AOC Clairette du Languedoc Péret","AOC Clairette du Languedoc Saint-André-de-Sangonis","AOC Clos de la Roche","AOC Clos des Lambrays","AOC Clos de Tart","AOC Clos de Vougeot","AOC Clos Saint-Denis","AOC Collioure","AOC Condrieu","AOC Corbières","AOC Corbières-Boutenac","AOC Cornas","AOC Corton","AOC Corton Basses Mourottes","AOC Corton-Charlemagne","AOC Corton Clos des Meix","AOC Corton Hautes Mourottes","AOC Corton La Toppe au Vert","AOC Corton La Vigne au Saint","AOC Corton Le Clos du Roi","AOC Corton Le Corton","AOC Corton Le Meix Lallemand","AOC Corton Le Rognet et Corton","AOC Corton Les Bressandes","AOC Corton Les Carrières","AOC Corton Les Chaumes","AOC Corton Les Combes","AOC Corton Les Fiètres","AOC Corton Les Grandes Lolières","AOC Corton Les Grèves","AOC Corton Les Languettes","AOC Corton Les Maréchaudes","AOC Corton Les Moutottes","AOC Corton Les Paulands","AOC Corton Les Perrières","AOC Corton Les Pougets","AOC Corton Les Renardes","AOC Corton Les Vergennes","AOC Costières de Nîmes","AOC Coteaux Bourguignons","AOC Coteaux d'Aix-en-Provence","AOC Coteaux d'Ancenis","AOC Coteaux de Die","AOC Coteaux de l'Aubance","AOC Coteaux de Saumur","AOC Coteaux du Giennois","AOC Coteaux du Layon","AOC Coteaux du Layon Beaulieu-sur-Layon","AOC Coteaux du Layon Faye-d'Anjou","AOC Coteaux du Layon Rablay-sur-Layon","AOC Coteaux du Layon Rochefort-sur-Loire","AOC Coteaux du Layon Saint-Aubin-de-Luigné","AOC Coteaux du Layon Saint-Lambert-du-Lattay","AOC Coteaux du Loir","AOC Coteaux du Lyonnais","AOC Coteaux du Quercy","AOC Coteaux du Vendômois","AOC Coteaux varois en Provence","AOC Côte de Beaune","AOC Côte de Beaune-Villages","AOC Côte de Brouilly","AOC Côte de Nuits-Villages","AOC Côte roannaise","AOC Côte Rôtie","AOC Côtes d'Auvergne","AOC Côtes d'Auvergne Boudes","AOC Côtes d'Auvergne Chanturgue","AOC Côtes d'Auvergne Chateaugay","AOC Côtes d'Auvergne Corent","AOC Côtes d'Auvergne Madargues","AOC Côtes de Bergerac","AOC Côtes de Bordeaux","AOC Côtes de Bordeaux Blaye","AOC Côtes de Bordeaux Cadillac","AOC Côtes de Bordeaux Castillon","AOC Côtes de Bordeaux Francs","AOC Côtes de Bordeaux-Saint-Macaire","AOC Côtes de Bourg, Bourg et Bourgeais","AOC Côtes de Duras","AOC Côtes de Millau","AOC Côtes de Montravel","AOC Côtes de Provence","AOC Côtes de Provence Sainte-Victoire","AOC Côtes de Toul","AOC Côtes du Forez","AOC Côtes du Jura","AOC Côtes du Marmandais","AOC Côtes du Rhône","AOC Côtes du Rhône Villages","AOC Côtes du Rhône Villages Chusclan","AOC Côtes du Rhône Villages Gadagne","AOC Côtes du Rhône Villages Laudun","AOC Côtes du Rhône Villages Massif d'Uchaux","AOC Côtes du Rhône Villages Nyons","AOC Côtes du Rhône Villages Plan de Dieu","AOC Côtes du Rhône Villages Puyméras","AOC Côtes du Rhône Villages Roaix","AOC Côtes du Rhône Villages Rochegude","AOC Côtes du Rhône Villages Rousset-les-Vignes","AOC Côtes du Rhône Villages Sablet","AOC Côtes du Rhône Villages Saint-Andéol","AOC Côtes du Rhône Villages Sainte-Cécile","AOC Côtes du Rhône Villages Saint-Gervais","AOC Côtes du Rhône Villages Saint-Maurice","AOC Côtes du Rhône Villages Saint-Pantaléon-les-Vignes","AOC Côtes du Rhône Villages Séguret","AOC Côtes du Rhône Villages Signargues","AOC Côtes du Rhône Villages Suze-la-Rousse","AOC Côtes du Rhône Villages Vaison-la-Romaine","AOC Côtes du Rhône Villages Valréas","AOC Côtes du Roussillon","AOC Côtes du Roussillon Villages","AOC Côtes du Roussillon Villages Caramany","AOC Côtes du Roussillon Villages Les Aspres","AOC Côtes du Roussillon Villages Lesquerde","AOC Côtes du Roussillon Villages Tautavel","AOC Côtes du Vivarais","AOC Coulée de Serrant","AOC Cour-Cheverny","AOC Crémant d’Alsace","AOC Crémant de Bordeaux","AOC Crémant de Bourgogne","AOC Crémant de Die","AOC Crémant de Limoux","AOC Crémant de Loire","AOC Crémant du Jura","AOC Criots-Bâtard-Montrachet","AOC Crozes-Hermitage","AOC Echezeaux","AOC Entraygues - Le Fel","AOC Entre-deux-Mers","AOC Entre-deux-Mers Haut-Benauge","AOC Estaing","AOC Faugères","AOC Fiefs Vendéens Brem","AOC Fiefs Vendéens Chantonnay","AOC Fiefs Vendéens Mareuil","AOC Fiefs Vendéens Pissotte","AOC Fiefs Vendéens Vix","AOC Fitou","AOC Fixin","AOC Fleurie","AOC Fronsac","AOC Fronton","AOC Gaillac","AOC Gaillac premières côtes","AOC Gevrey-Chambertin","AOC Gigondas","AOC Givry","AOC Grand Roussillon","AOC Grands-Echezeaux","AOC Graves","AOC Graves de Vayres","AOC Graves supérieures","AOC Grignan-les-Adhémar","AOC Griotte-Chambertin","AOC Gros Plant du Pays Nantais","AOC Haut-Médoc","AOC Haut-Montravel","AOC Haut-Poitou","AOC Hermitage","AOC Irancy","AOC Irouléguy","AOC Jasnières","AOC Juliénas","AOC Jurançon","AOC La Clape","AOC Ladoix","AOC La Grande Rue","AOC Lalande-de-Pomerol","AOC Languedoc","AOC Languedoc Cabrières","AOC Languedoc Grès de Montpellier","AOC Languedoc La Méjanelle","AOC Languedoc Montpeyroux","AOC Languedoc Quatourze","AOC Languedoc Saint-Christol","AOC Languedoc Saint-Drézéry","AOC Languedoc Saint-Georges-d'Orques","AOC Languedoc Saint-Saturnin","AOC La Romanée","AOC La Tâche","AOC Latricières-Chambertin","AOC Les Baux de Provence","AOC L'Etoile","AOC Limoux","AOC Lirac","AOC Listrac-Médoc","AOC Loupiac","AOC Luberon","AOC Lussac-Saint-Emilion","AOC Mâcon","AOC Mâcon Azé","AOC Mâcon Bray","AOC Mâcon Burgy","AOC Mâcon Bussières","AOC Mâcon Chaintré","AOC Mâcon Chardonnay","AOC Mâcon Charnay-lès-Mâcon","AOC Mâcon Cruzille","AOC Mâcon Davayé","AOC Mâcon Fuissé","AOC Mâcon Igé","AOC Mâcon La Roche-Vineuse","AOC Mâcon Loché","AOC Mâcon Lugny","AOC Mâcon Mancey","AOC Mâcon Milly-Lamartine","AOC Mâcon Montbellet","AOC Mâcon Péronne","AOC Mâcon Pierreclos","AOC Mâcon Prissé","AOC Mâcon - rouge exclusif","AOC Mâcon Saint-Gengoux-le-National","AOC Mâcon Serrières","AOC Mâcon Solutré-Pouilly","AOC Mâcon Uchizy","AOC Mâcon Vergisson","AOC Mâcon Verzé","AOC Mâcon Villages","AOC Mâcon Vinzelles","AOC Macvin du Jura","AOC Madiran","AOC Malepère","AOC Maranges","AOC Marcillac","AOC Margaux","AOC Marsannay","AOC Maury","AOC Mazis-Chambertin","AOC Mazoyères-Chambertin","AOC Médoc","AOC Menetou-Salon","AOC Mercurey","AOC Meursault","AOC Minervois","AOC Minervois-La Livinière","AOC Monbazillac","AOC Montagne-Saint-Emilion","AOC Montagny","AOC Monthélie","AOC Montlouis-sur-Loire","AOC Montrachet","AOC Montravel","AOC Morey-Saint-Denis","AOC Morgon","AOC Moselle","AOC Moulin-à-Vent","AOC Moulis","AOC Muscadet","AOC Muscadet Coteaux de la Loire","AOC Muscadet Côtes de Grandlieu","AOC Muscadet Sèvre et Maine","AOC Muscadet Sèvre et Maine Clisson","AOC Muscadet Sèvre et Maine Gorges","AOC Muscadet Sèvre et Maine Le Pallet","AOC Muscat de Beaumes-de-Venise","AOC Muscat de Frontignan","AOC Muscat de Lunel","AOC Muscat de Mireval","AOC Muscat de Rivesaltes","AOC Muscat de Saint-Jean-de-Minervois","AOC Muscat du Cap Corse","AOC Musigny","AOC Nuits-Saint-Georges","AOC Orléans","AOC Orléans-Cléry","AOC Pacherenc du Vic-Bilh","AOC Palette","AOC Patrimonio","AOC Pauillac","AOC Pécharmant","AOC Pernand-Vergelesses","AOC Pessac-Léognan","AOC Petit Chablis","AOC Picpoul de Pinet","AOC Pic Saint-Loup","AOC Pierrevert","AOC Pomerol","AOC Pommard","AOC Pouilly-Fuissé","AOC Pouilly-Fumé","AOC Pouilly-Loché","AOC Pouilly-sur-Loire","AOC Pouilly-Vinzelles","AOC Premières Côtes de Bordeaux","AOC Puisseguin-Saint-Emilion","AOC Puligny-Montrachet","AOC Quarts de Chaume","AOC Quincy","AOC Rasteau","AOC Rasteau Vin Doux Naturel","AOC Régnié","AOC Reuilly","AOC Richebourg","AOC Rivesaltes","AOC Romanée-Conti","AOC Romanée-Saint-Vivant","AOC Rosé d'Anjou","AOC Rosé de Loire","AOC Rosette","AOC Roussette de Savoie","AOC Roussette de Savoie Frangy","AOC Roussette de Savoie Marestel","AOC Roussette de Savoie Monterminod","AOC Roussette de Savoie Monthoux","AOC Roussette du Bugey","AOC Roussette du Bugey Montagnieu","AOC Roussette du Bugey Virieu-le-Grand","AOC Ruchottes-Chambertin","AOC Rully","AOC Sable de Camargue","AOC Saint-Amour","AOC Saint-Aubin","AOC Saint-Bris","AOC Saint-Chinian","AOC Saint-Chinian Berlou","AOC Saint-Chinian Roquebrun","AOC Sainte-Croix-du-Mont","AOC Saint-Emilion","AOC Saint-Emilion grand cru","AOC Saint-Estèphe","AOC Saint-Georges-Saint-Emilion","AOC Saint-Joseph","AOC Saint-Julien","AOC Saint-Mont","AOC Saint-Nicolas-de-Bourgueil","AOC Saint-Péray","AOC Saint-Pourçain","AOC Saint-Romain","AOC Saint-Sardos","AOC Saint-Véran","AOC Sancerre","AOC Santenay","AOC Saumur","AOC Saumur-Champigny","AOC Saussignac","AOC Sauternes","AOC Savennières","AOC Savennières Roche aux Moines","AOC Savigny-lès-Beaune","AOC Seyssel","AOC Taureau de Camargue","AOC Tavel","AOC Terrasses du Larzac","AOC Touraine","AOC Touraine Amboise","AOC Touraine Azay-le-Rideau","AOC Touraine Chenonceaux","AOC Touraine Mesland","AOC Touraine Noble Joué","AOC Touraine Oisly","AOC Tursan","AOC Valençay","AOC Ventoux","AOC Vézelay","AOC Vin de Corse","AOC Vin de Corse Calvi","AOC Vin de Corse Coteaux du Cap Corse","AOC Vin de Corse Porto-Vecchio","AOC Vin de Corse Sartène","AOC Vin de Savoie","AOC Vin de Savoie Abymes","AOC Vin de Savoie Apremont","AOC Vin de Savoie Arbin","AOC Vin de Savoie Ayze","AOC Vin de Savoie Chautagne","AOC Vin de Savoie Chignin","AOC Vin de Savoie Chignin-Bergeron","AOC Vin de Savoie Crépy","AOC Vin de Savoie Cruet","AOC Vin de Savoie Jongieux","AOC Vin de Savoie Marignan","AOC Vin de Savoie Marin","AOC Vin de Savoie Montmélian","AOC Vin de Savoie Ripaille","AOC Vin de Savoie Saint-Jean-de-la-Porte","AOC Vin de Savoie Saint-Jeoire-Prieuré","AOC Vinsobres","AOC Viré-Clessé","AOC Volnay","AOC Vosne-Romanée","AOC Vougeot","AOC Vouvray", "IGP Agenais", "IGP Alpes-de-Haute-Provence", "IGP Alpes-Maritimes", "IGP Alpilles", "IGP Ardèche", "IGP Ariège", "IGP Atlantique", "IGP Aude", "IGP Aveyron", "IGP Calvados", "IGP Cévennes", "IGP Charentais", "IGP Cité de Carcassonne", "IGP Collines Rhodaniennes", "IGP Comtés Rhodaniens", "IGP Comté Tolosan", "IGP Coteaux de Béziers", "IGP Coteaux de Coiffy", "IGP Coteaux de Glanes", "IGP Coteaux de l'Ain", "IGP Coteaux de l'Auxois", "IGP Coteaux de Narbonne", "IGP Coteaux d'Ensérune", "IGP Coteaux de Peyriac", "IGP Coteaux des Baronnies", "IGP Coteaux de Tannay", "IGP Coteaux du Cher et de l'Arnon", "IGP Coteaux du Pont du Gard", "IGP Côtes Catalanes", "IGP Côtes de Gascogne", "IGP Côtes de la Charité", "IGP Côtes de Meuse", "IGP Côtes de Thau", "IGP Côtes de Thongue", "IGP Côtes du Lot", "IGP Côtes du Tarn", "IGP Côte Vermeille", "IGP Drôme", "IGP Franche-Comté", "IGP Gard", "IGP Gers", "IGP Haute-Marne", "IGP Hautes-Alpes", "IGP Haute Vallée de l'Aude", "IGP Haute Vallée de l'Orb", "IGP Haute-Vienne", "IGP Ile de Beauté", "IGP Île-de-France", "IGP Isère", "IGP Landes", "IGP Lavilledieu", "IGP Le Pays Cathare", "IGP Maures", "IGP Méditerranée", "IGP Mont Caume", "IGP Pays de Brive", "IGP Pays des Bouches-du-Rhône", "IGP Pays d'Hérault", "IGP Pays d'Oc", "IGP Périgord", "IGP Puy-de-Dôme", "IGP Sable de Camargue", "IGP Sainte-Marie-la-Blanche", "IGP Saint-Guilhem-le-Désert", "IGP Saône-et-Loire", "IGP Terres du Midi", "IGP Thézac-Perricard", "IGP Urfé", "IGP Val de Loire", "IGP Vallée du Paradis", "IGP Vallée du Torgan", "IGP Var", "IGP Vaucluse", "IGP Vicomté d'Aumelas", "IGP Vin des Allobroges", "IGP Yonne", "Vins Sans IG"];
    public static function getDenominations() {
        if (Config::getInstance()->exists('denominations')) {
            return array_unique(array_merge(self::$denominations, Config::getInstance()->get('denominations', [])));
        }
        return self::$denominations;
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
        if (!$this->denomination_instance) {
            $this->logo = false;
        }

        if (!$this->date_creation) {
            $this->date_version = date('c');
            $this->date_creation = $this->date_version;
        }

        $this->saveVersion();

        $mapper_save = parent::save();

        if ($this->ean && !Redirect::findById("REDIRECT-01-" . $this->ean)) {
            $redirect = new Redirect();
            $redirect->setId("REDIRECT-01-" . $this->ean);
            $redirect->redirect_to = '/' . $this->getId();
            $redirect->doc_origine = $this->getId();
            $redirect->version_origine = $this->date_version;
            $redirect->date_creation = date('c');
            $redirect->save();

            $this->gs1 = 1;
        }

        return $mapper_save;
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
            $id = Config::getInstance()->getInstanceId();
            $id = ($id) ? $id : "0";
            $max_size = Config::getInstance()->getQRCodeUriSize() - 1; // -1 pour le "/" avant l'identifiant
            for($i = strlen($id) ; $i < $max_size ; $i++) {
                $id .= substr(self::$CHARID, rand(0, strlen(self::$CHARID)), 1);
            }
            $qr = self::findById($id);
            if (! $qr) {
                return $id;
            }
        }
        throw new Exception('no free id found');
    }

    public function getQRCodeContent($format, $urlbase) {
        return Exporter::getInstance()->getQRCodeContent(
            $urlbase.'/'.($this->gs1? '01/' . $this->ean: $this->getId()),
            $format,
            ($this->logo) ? Config::getInstance()->get('qrcode_logo') : false,
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

    public function getImageBouteille() {
        if($this->isImageDefault($this->image_bouteille) && preg_match('/rouge/i', $this->couleur)) {
            return '/images/default_bouteille_rouge.jpg';
        }

        if($this->isImageDefault($this->image_bouteille) && preg_match('/blanc/i', $this->couleur)) {
            return '/images/default_bouteille_blanc.jpg';
        }

        if($this->isImageDefault($this->image_bouteille) && preg_match('/ros[ée]/i', $this->couleur)) {
            return '/images/default_bouteille_rose.jpg';
        }

        return $this->image_bouteille;
    }

    public function getImages()
    {
        $images['image_bouteille'] = $this->getImageBouteille();
        $images['image_etiquette'] = $this->image_etiquette;
        $images['image_contreetiquette'] = $this->image_contreetiquette;
        return $images;
    }

    public function exportToHttp()
    {
        $fields = $this->toArray();
        unset($fields['visites']);
        unset($fields['versions']);
        unset($fields['image_bouteille']);
        unset($fields['image_etiquette']);
        unset($fields['image_contreetiquette']);
        unset($fields['date_creation']);
        unset($fields['date_version']);

        Base::instance()->set('SESSION.qrcode.' . $this->getId() . '.image_etiquette', $this->image_etiquette ?: null);
        Base::instance()->set('SESSION.qrcode.' . $this->getId() . '.image_contreetiquette', $this->image_contreetiquette ?: null);
        Base::instance()->set('SESSION.qrcode.' . $this->getId() . '.image_bouteille', $this->image_bouteille ?: null);

        return $fields;
    }

    public function clone($from)
    {
        $this->copyFrom($from);
        $idClone = Base::instance()->get('GET.id');
        $this->image_bouteille = Base::instance()->get('SESSION.qrcode.' . $idClone . '.image_bouteille');
        $this->image_etiquette = Base::instance()->get('SESSION.qrcode.' . $idClone . '.image_etiquette');
        $this->image_contreetiquette = Base::instance()->get('SESSION.qrcode.' . $idClone . '.image_contreetiquette');
    }

    public function getIngredientsTraduits() {
        return implode('',array_map('_',preg_split("/( *[,;()*_] *)/", $this->ingredients, -1, PREG_SPLIT_NO_EMPTY  | PREG_SPLIT_DELIM_CAPTURE)));
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
                $stats[$k] = ['nb' => 0, 'title' => $name];
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

        foreach ($this->getVisites() as $visite) {
            $date = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $visite['date']);
            $week = (int) $date->format('YW'); // YYYYMM (MM = Numéro de semaine)

            if (! isset($stats[$week])) {
                $stats[$week] = ['nb' => 0];
                $startOfWeek = new \DateTime();
                $startOfWeek->setISODate((int)$date->format('Y'), (int)$date->format('W')); // arnaque moldave : https://stackoverflow.com/a/20622278
                $stats[$week]['title'] = $startOfWeek->format('d/m/y');
            }

            $stats[$week]['nb']++;
        }

        ksort($stats);

        $first = array_key_first($stats);
        $last = array_key_last($stats);

        for ($i = $first; $i <= $last; $i++) {
            if (substr((string) $i, 4, 2) > 52) {
                $i += 48; // 52ème semaine dépassée, on ajoute 48 semaines pour tomber a 2XXX01
            }

            if (array_key_exists($i, $stats)) {
                continue;
            }

            $stats[$i]['nb'] = 0;
            $startOfWeek = new \DateTime();
            $startOfWeek->setISODate(substr($i, 0, 4), substr($i, 4, 2));
            $stats[$i]['title'] = $startOfWeek->format('d/m/y');
        }

        ksort($stats);

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
        $json = @json_decode(@file_get_contents($url));
        if (isset($json->etablissements)) {
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
        }
        return ;
    }

    public function isPartOfInstance() {
        return strpos($this->getId(), Config::getInstance()->getInstanceId()) === 0;
    }

    public function isEanValide() {
        if (! $this->ean) {
            return true;
        }
        if (strlen($this->ean) != 13) {
            return false;
        }
        if (! preg_match('/^\d+$/', $this->ean)) {
            return false;
        }

        $base = substr($this->ean, 0, 12);
        $cleControle = substr($this->ean, -1);

        $somme = 0;
        for ($i = 0; $i < strlen($base); $i++) {
            $chiffre = intval($base[$i]);
            $poids = ($i % 2 === 0) ? 1 : 3;
            $somme += $chiffre * $poids;
        }

        $reste = $somme % 10;
        $cleAttendue = ($reste === 0) ? 0 : 10 - $reste;

        return $cleControle == $cleAttendue;
    }

    public function isImageDefault($id_img) {
        return strpos($id_img ?? '', 'data:') === false;
    }

    public function getShowImages($fromView = false) {
        if (! $fromView) {
            return $this->getImages();
        }
        $images = ['image_bouteille' => $this->getImageBouteille()];
        foreach ($this->getImages() as $imgNom => $imgPath) {
            if (! $this->isImageDefault($imgPath)) {
                $images[$imgNom] = $imgPath;
            }
        }

        return $images;
    }

}
