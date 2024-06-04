<?php

namespace app\models;

class DBManager {
    static $db = null;
    static $mapper = null;

    static function setDB($db) {
        self::$db = $db;
    }
    static function getDB() {
        return self::$db;
    }

    public static function getMapper()
    {
        return self::$mapper;
    }

    static function createDB($pdo, $dbname = null, $user = null, $pass = null) {
        self::parseDSN($pdo);
        self::setDB(new self::$db($pdo, $user, $pass));
    }

    private static function parseDSN($dsn)
    {
        $scheme = strtok($dsn, ':');
        switch ($scheme) {
            case 'couchdb':
            self::$db = "DB\\Couch";
            self::$mapper = "DB\\Couch\\Mapper";
            break;
            case 'sqlite':
            self::$db = "DB\\SQL";
            self::$mapper = "DB\\SQL\\Mapper";
            break;
            default:
            throw new LogicException('Pas de config pour ce type de connexion');
            break;
        }
    }

    public static function createTable($table, $fields = null)
    {
        if (get_class(self::$db) === "DB\\SQL") {
            $create_fields_sql = '';
            foreach($fields as $field => $type) {
                $create_fields_sql .= ($create_fields_sql) ? ",\n" : '';
                $create_fields_sql .= "$field $type";
            }
            $sql = "CREATE TABLE ".strtolower($table)." (".$create_fields_sql.");";
            return self::$db->exec($sql);
        } else {
            self::$db->createDb();
            self::$db->createIndex([
                'index' => [
                    'fields' => ['user_id']
                ],
                "name" => "user_id-index",
                "type" => "json"
            ]);
        }
    }
}
