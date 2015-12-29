<?php
/**
 * Created by PhpStorm.
 * User: hammad
 * Date: 12/18/2015
 * Time: 1:20 AM
 */

namespace Web\Entities;

/**
 * Class moMessageEntity
 * @package Web\Entities
 *
 * Objects from this class are used as standard data types in services and the rest of the architecture.
 *
 */
class moMessageEntity
{
    /**
     * @var null
     */
    private $msisdn = NULL;

    /**
     * @var null
     */
    private $operatorID = NULL;

    /**
     * @var null
     */
    private $shortCodeID = NULL;

    /**
     * @var null
     */
    private $text = NULL;

    /**
     * @var bool|null|string
     */
    private $dateTime = NULL;


    /**
     * moMessageEntity constructor.
     */
    public function __construct()
    {
        /*
        * There are certain advantages of filling in the dateTime here instead of using mysql now because
        * this way we can use/reuse this db object in cases of sleep and wakeup later i.e. create these objects
        * and store them for any required reason for later immediate use without data validation overhead
        */
        $this->dateTime = date("Y-m-d H:i:s");
    }

    /**
     * @param $phoneNumber
     * @throws \Exception
     */
    public function setMSISDN($phoneNumber)
    {
        if (is_string($phoneNumber)) { //testing for string instead of int/float because the field is varchar in mysql
            $this->msisdn = $phoneNumber;
        } else {
            throw new \Exception ("This is an invalid phone number, please enter a valid number.");
        }
    }

    /**
     * @param $operatorID
     * @throws \Exception
     */
    public function setOperatorID($operatorID)
    {
        if (is_int($operatorID)) {
            $this->operatorID = $operatorID;
        } else {
            throw new \Exception ("This is an invalid operator ID, only integers are allowed.");
        }
    }

    /**
     * @param $shortCodeID
     * @throws \Exception
     */
    public function setShortCodeID($shortCodeID)
    {
        if (is_int($shortCodeID)) {
            $this->shortCodeID = $shortCodeID;
        } else {
            throw new \Exception ("This is an invalid short code ID, only integers are allowed.");
        }
    }


    /**
     * @param $text
     * @throws \Exception
     */
    public function setText($text)
    {
        if (is_string($text)) {
            $this->text = $text;
        } else {
            throw new \Exception ("This is an invalid text, only simple strings are allowed.");
        }
    }

    /**
     * @return null
     * @throws \Exception
     */
    public function getMSISDN()
    {
        if ($this->msisdn != NULL) {
            return $this->msisdn;
        } else {
            throw new \Exception("Please first set the msisdn.");
        }

    }

    /**
     * @return null
     * @throws \Exception
     */
    public function getOperatorID()
    {
        if ($this->operatorID != NULL) {
            return $this->operatorID;
        } else {
            throw new \Exception("Please first set the operator ID.");
        }
    }

    /**
     * @return null
     * @throws \Exception
     */
    public function getShortCodeID()
    {
        if ($this->shortCodeID != NULL) {
            return $this->shortCodeID;
        } else {
            throw new \Exception("Please first set the short code ID.");
        }
    }

    /**
     * @return null
     * @throws \Exception
     */
    public function getText()
    {
        if ($this->text != NULL) {
            return $this->text;
        } else {
            throw new \Exception("Please first set the text for message .");
        }
    }

    /**
     * @return bool|null|string
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getAuthToken()
    {
        $arrayToBeJSONEncoded = array
        (// assuming that only the following 4 values are sent through GET
            'msisdn' => $this->getMSISDN(),
            'operatorid' => $this->getOperatorID(),
            'shortcodeid' => $this->getShortCodeID(),
            'text' => $this->getText()
        );
        $arg = json_encode($arrayToBeJSONEncoded);
        return "./registermo $arg";
    }
}