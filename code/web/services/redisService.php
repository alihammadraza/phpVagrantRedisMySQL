<?php
/**
 * Created by PhpStorm.
 * User: hammad
 * Date: 12/23/2015
 * Time: 3:56 PM
 */

namespace Web\Services;


/**
 * Class redisService
 * @package Web\Services
 */
class redisService
{
    /**
     * @var null|string
     */
    private $mode = NULL;
    /**
     * @var null
     */
    private $value = NULL;

    /**
     *
     * Takes in as an argument the string 'predis' or 'cli' which is a reference to the two
     * different types of mechanisms/methods that can be used to access and save the values
     * in redis server. Setting Predis makes the use of predis class object whereas cli uses
     * exec commands to access redis-cli that comes with the default redis-server.
     *
     * redisService constructor.
     * @param string $mode
     */
    public function __construct($mode = 'predis')
    {
        if ($mode == 'predis') {
            $this->mode = 'predis';
        } elseif ($mode == 'cli') {
            $this->mode = 'cli';
        } else {
            throw new \Exception('Invalid value provided to constructor, please either choose predis or cli mode');
        }
    }

    /**
     * Sets a value and saves it in a private variable. Throws an exception if the arg is null.
     * Serializes the value if it is an array otherwise saves it as is.
     *
     * @param $valueStringOrArray
     * @throws \Exception
     */
    public function setValue($valueStringOrArray = NULL)
    {
        if ($valueStringOrArray == NULL) {
            throw new \Exception("Invalid Key Value pair arguments.");
        } elseif (is_array($valueStringOrArray)) {
            $this->value = serialize($valueStringOrArray);
        } else {
            $this->value = $valueStringOrArray;
        }
    }


    /**
     *
     * Depending on the value passed to the constructor two different methods/mechanisms
     * are called into action
     *
     */
    public function save()
    {
        if ($this->mode == 'cli') {
            $this->saveUsingCLI();
        } elseif ($this->mode == 'predis') {
            $this->saveUsingPredis();
        }
        $this->value = NULL;
    }


    /**
     * Saves the value in redis-server using redis-cli inside php exec method.
     *
     */
    private function saveUsingCLI()
    {

        $currentCounterValue = exec('redis-cli GET counter');

        if ($currentCounterValue == "") {
            exec('redis-cli SET counter 0');
            $currentCounterValue = 0;
        } else {
            $currentCounterValue++;
            exec("redis-cli SET counter " . $currentCounterValue);
        }
        exec("redis-cli SET $currentCounterValue '" . $this->value . "'");
    }


    /**
     *Saves the value in redis-server using Predis class object
     *
     */
    private function saveUsingPredis()
    {

        require __DIR__ . "/../vendor/predis/predis/autoload.php";

        \Predis\Autoloader::register();

        $redis = new \Predis\Client();

        if ($redis->get('counter') == NULL) {
            $redis->set('counter', 0);
        } else {
            $redis->incr('counter');
        }
        $redis->set($redis->get('counter'), $this->value);
    }
}