<?php

/**
 * Created by PhpStorm.
 * User: hammad
 * Date: 12/18/2015
 * Time: 3:33 AM
 */

require_once(dirname(__FILE__) . "/../config/config.php");
use Web\Entities\moMessageEntity;

/**
 * Class moMessageEntityTest
 */
class moMessageEntityTest extends PHPUnit_Framework_TestCase
{
    /**
     * Tests if set method is working by setting and getting the value
     *
     */
    public function testSetMSISDN()
    {
        $moMessage = new moMessageEntity();
        $phoneNumber = "923146153385";
        $moMessage->setMSISDN($phoneNumber);
        $this->assertEquals($moMessage->getMSISDN(), $phoneNumber);
    }

    /**
     * Tests if set method is working by setting and getting the value
     *
     */
    public function testSetOperatorID()
    {
        $moMessage = new moMessageEntity();
        $operatorID = 83718;
        $moMessage->setOperatorID($operatorID);
        $this->assertEquals($moMessage->getOperatorID(), $operatorID);
    }

    /**
     * Tests if set method is working by setting and getting the value
     *
     */
    public function testSetShortCodeID()
    {
        $moMessage = new moMessageEntity();
        $shortCodeID = 21;
        $moMessage->setShortCodeID($shortCodeID);
        $this->assertEquals($moMessage->getShortCodeID(), $shortCodeID);
    }

    /**
     * Tests if set method is working by setting and getting the value
     *
     */
    public function testSetText()
    {
        $moMessage = new moMessageEntity();
        $text = "hello this is a text.";
        $moMessage->setText($text);
        $this->assertEquals($moMessage->getText(), $text);
    }

    /**
     * Tests an expected exception when passing an invalid argument to the method
     *
     */
    public function testInvalidMSISDNInput()
    {
        $moMessage = new moMessageEntity();
        $this->setExpectedException('Exception');
        $moMessage->setMSISDN(19.212);
    }

    /**
     * Tests an expected exception when passing an invalid argument to the method
     *
     * @throws Exception
     */
    public function testInvalidOperatorIDInput()
    {
        $moMessage = new moMessageEntity();
        $this->setExpectedException('Exception');
        $moMessage->setOperatorID("this doesn't look like an operator ID.");
    }

    /**
     * Tests an expected exception when passing an invalid argument to the method
     *
     * @throws Exception
     */
    public function testInvalidShortCodeIDInput()
    {
        $moMessage = new moMessageEntity();
        $this->setExpectedException('Exception');
        $moMessage->setShortCodeID("this doesn't look like a short code ID either.");
    }

    /**
     * Tests an expected exception when passing an invalid argument to the method
     *
     * @throws Exception
     */
    public function testInvalidTextInput()
    {
        $moMessage = new moMessageEntity();
        $this->setExpectedException('Exception');
        $moMessage->setText(array());
    }
}