<?php

/**
 * Created by PhpStorm.
 * User: hammad
 * Date: 12/18/2015
 * Time: 3:25 AM
 */

require_once(dirname(__FILE__) . "/../config/config.php");
use Web\Services\moService;
use \Web\Entities\moMessageEntity;

/**
 * Class moServiceTest
 */
class moServiceTest extends PHPUnit_Framework_TestCase
{
    /**
     * Creates and fills valid data in an object of class moMessageEntity.
     * Then passes the object based on dependency based injection to
     * moService's object method setMoMessage. Afterwards, Gets the set
     * moMessage from moService object and confirms its equal to what was passed in the
     * first place.
     *
     * @throws Exception
     */
    public function testSetMOMessage()
    {
        $moService = new moService();
        $message = new moMessageEntity();
        $message->setMSISDN("923146153385");
        $message->setOperatorID(6521600);
        $message->setShortCodeID(6);
        $message->setText("bada bing bada boom");
        $moService->setMOMessage($message);
        $this->assertEquals($moService->getMOMessage(), $message);
    }

    /**
     *
     */
    public function testExceptionOnSavingMessageWithoutFirstSettingMOMessage()
    {
        $moService = new moService();
        $this->setExpectedException('Exception');
        $moService->saveMOMessageToSQL();
    }

    /**
     * Tests call of a function out of order i.e. calling getMOMessage without
     * first setting it
     *
     * @throws Exception
     */
    public function testExceptionOnGettingMOMessageWithoutFirstSettingMOMessage()
    {
        $moService = new moService();
        $this->setExpectedException('Exception');
        $moService->getMOMessage();
    }

    /**
     * Tests to check if an expected exception is thrown when an invalid argument
     * is passed to the method
     *
     * @throws Exception
     */
    public function testInvalidArgGetElapsedTimeBetweenLastXMessages()
    {
        $this->setExpectedException('Exception');
        moService::getElapsedTimeBetweenLastXMessages('abcdIsNotANumber');
    }

    /**
     * Tests to check if an expected exception is thrown when an invalid argument
     * is passed to the method
     *
     * @throws Exception
     */
    public function testInvalidArgGetCountOfMOMessagesCreatedXMinutesAgo()
    {
        $this->setExpectedException('Exception');
        moService::getCountOfMOMessagesCreatedXMinutesAgo('abcdIsNotANumber');
    }

    /**
     * Tests to check if the result obtained from calling the method falls
     * within the accepted criteria i.e. it is either 0 or a + integer.
     *
     */
    public function testReturnValueValidityForGetCountOfUnprocessedMOMessages()
    {
        $numberOfUnprocessedMOMessages = moService::getCountOfUnprocessedMOMessages();
        $this->assertStringMatchesFormat('%d', $numberOfUnprocessedMOMessages);
        $this->assertGreaterThanOrEqual(0, $numberOfUnprocessedMOMessages);
    }

    /**
     * Confirms if the method returns TRUE on successful execution
     * @throws Exception
     */
    /*
     * PLEASE NOTE this test takes time, uncomment it if you know what you are doing
    public function testReturnValueForDeleteAllUnprocessedMessage()
    {
        $confirmation = moService::deleteAllUnprocessedMessage(TRUE);
        $this->assertEquals(TRUE, $confirmation);
    }
    */


    /**
     * Tests to see if an expected exception is thrown when no argument or
     * invalid argument is passed to the method
     *
     * @throws Exception
     */
     public function testInvalidArgForDeleteAllUnprocessedMessage()
    {
        $this->setExpectedException('Exception');
        moService::deleteAllUnprocessedMessage();
    }
}