<?php
/**
 * Created by PhpStorm.
 * User: hammad
 * Date: 12/18/2015
 * Time: 5:55 AM
 */

namespace Web\Services;


/**
 * Class dbService
 * @package Web\Services
 */
class dbService
{

    /**
     * @var null
     */
    private static $dbObject = NULL;

    /**
     * dbService constructor.
     */
    private function __construct()
    {
    }

    /**
     * @return null|\PDO
     */
    public static function getDBObject()
    {
        if (self::$dbObject == NULL) {
            self::$dbObject = new \PDO("mysql:host=" . DBLOCATION . ";dbname=" . DBNAME . ';', DBUSER, DBPASSWORD);
            self::$dbObject->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
        return self::$dbObject;
    }

}