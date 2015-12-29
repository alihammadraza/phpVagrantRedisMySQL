<?php
/**
 * Created by PhpStorm.
 * User: hammad
 * Date: 12/18/2015
 * Time: 12:52 AM
 */

namespace Web\Services;

use Web\Entities\moMessageEntity;

/**
 * Class moService
 * @package Web\Services
 *
 *
 */
class moService
{

    /**
     * @var null
     */
    private $moMessage = NULL;


    /**
     * Takes in entity moMessageEntity object as an argument and saves it as a
     * private variable moMessage.
     *
     * @param moMessageEntity $message
     */
    public function setMOMessage(moMessageEntity $message)
    {
        $this->moMessage = $message;
    }

    /**
     * Checks to see if setMOMessage has been called before calling this method and
     * returns the setMOMessage, otherwise throws an exception
     *
     * @return null
     * @throws \Exception
     */
    public function getMOMessage()
    {
        if ($this->moMessage != NULL) {
            return $this->moMessage;
        } else {
            throw new \Exception("Please first set the message.");
        }
    }

    /**
     * Saves the data within moMessage object to the database
     *
     *
     */
    public function saveMOMessageToSQL()
    {
        $query = dbService::getDBObject()->prepare("INSERT INTO mo VALUES (NULL, :msisdn,:operatorID,:shortCodeID,:text,:token,:dateTime,:processStatus)");
        $valuesToInsert = array(
            "msisdn" => $this->getMOMessage()->getMSISDN(),
            "operatorID" => $this->getMOMessage()->getOperatorID(),
            "shortCodeID" => $this->getMOMessage()->getShortCodeID(),
            "text" => $this->getMOMessage()->getText(),
            "token" => $this->getMOMessage()->getAuthToken(),
            "dateTime" => $this->getMOMessage()->getDateTime(),
            "processStatus" => 0 // 0 being unprocessed, assuming that an external application updates this to reflect 1 for processed
        );
        $query->execute($valuesToInsert);
    }

    /**
     *
     * Takes in as an argument 'minutes' and returns the number of messages that have
     * arrived between the time when this method is executed and the number of minutes ago.
     *
     * @param $howManyMinutes
     * @return string
     */
    public static function getCountOfMOMessagesCreatedXMinutesAgo($howManyMinutes)
    {
        if (is_numeric($howManyMinutes)) {
            $moCreated15MinutesAgo = new \DateTime("$howManyMinutes minutes ago");
            $dateTime15MinAgo = $moCreated15MinutesAgo->format("Y-m-d H:i:s");
            $dateTimeNow = date("Y-m-d H:i:s");
            $query = dbService::getDBObject()->prepare("SELECT count(*) from mo where created_at > :dateTime15MinAgo AND created_at <= :dateTimeNow;");
            $query->bindParam(':dateTime15MinAgo', $dateTime15MinAgo);
            $query->bindParam(':dateTimeNow', $dateTimeNow);
            $query->execute();
            $obtainedCount = $query->fetchColumn();
            return $obtainedCount;
        } else {
            throw new \Exception('Please enter minutes in integers.');
        }
    }

    /**
     * Takes in # of message as argument e.g. 10000 and calculates the time that elapsed
     * between the delivery of first and last message. The return value is an array containing
     * time when first and last message was sent
     *
     * @param $howManyMessages
     * @return mixed
     */
    public static function getElapsedTimeBetweenLastXMessages($howManyMessages)
    {
        if (is_numeric($howManyMessages)) {
            $theQuery = "SELECT MIN(created_at), MAX(created_at) FROM (SELECT created_at FROM mo ORDER BY id DESC LIMIT $howManyMessages) AS T1;";
            $statement = dbService::getDBObject()->query($theQuery);
            $minAndMax = $statement->fetch(\PDO::FETCH_ASSOC);
            return $minAndMax;
        } else {
            throw new \Exception('Please enter number of messages in integers.');
        }
    }

    /**
     * Counts and returns the number of messages that have not been processed.
     *
     * @return string
     */
    public static function getCountOfUnprocessedMOMessages()
    {
        $theQuery = "SELECT COUNT(*) FROM mo where process_status = 0;";
        $statement = dbService::getDBObject()->query($theQuery);
        return $statement->fetchColumn();
    }

    /**
     * Takes in TRUE as an argument to ensure no mistake is made. Deletes all unprocessed messages.
     * Executing the method without filling in an argument or the value FALSE will throw an exception.
     *
     * @param TRUE or FALSE
     * @return bool
     * @throws \Exception
     */
      public static function deleteAllUnprocessedMessage($areYouSure = FALSE)
    {
        if ($areYouSure === TRUE) {
            $theQuery = "DELETE FROM mo where process_status = 0";
            $statement = dbService::getDBObject()->query($theQuery);
            return $statement->execute();
        } else {
            throw new \Exception("Please pass a true argument confirming you want to delete all unprocessed items.");
        }
    }
}