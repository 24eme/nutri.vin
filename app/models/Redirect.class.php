<?php

namespace app\models;

use app\config\Config;
use app\models\DBManager;
use \Base;

class Redirect extends Mapper
{

    public static $copy_field_filter = [
        'redirect_to' => 1,
        'doc_origine' => 1,
        'version_origine' => 1,
        'date_creation' => 1,
    ];

    public static $getFieldsAndType = [
        'redirect_to' => "VARCHAR(255)",
        'doc_origine' => "VARCHAR(255)",
        'version_origine' => "VARCHAR(26)",
        'date_creation' => "VARCHAR(26)",
    ];
}
