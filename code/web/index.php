<?php
require_once 'config/config.php';
use Web\Services\moService;
use Web\Entities\moMessageEntity;

try {
    if (isset($_REQUEST['msisdn'])) {
        $message = new moMessageEntity();
        $message->setMSISDN($_REQUEST['msisdn']);
        $message->setOperatorID((int)$_REQUEST['operatorid']);
        $message->setShortCodeID((int)$_REQUEST['shortcodeid']);
        $message->setText($_REQUEST['text']);

        $moService = new moService();
        $moService->setMOMessage($message);
        $moService->saveMOMessageToSQL();
        echo '{"status": "ok"}' . "\n";
    } else {
        throw new Exception ("Please pass values first.");
    }
} catch (PDOException $pdoError) {
    echo $pdoError->getMessage();
} catch (Exception $error) {
    echo $error->getMessage();
}
/*
$message= new moMessageEntity();
$message->setMSISDN("923146153385");
$message->setOperatorID(6521600);
$message->setShortCodeID(6);
$message->setText("badabing.");

$moService = new moService();
$moService->setMOMessage($message);
$moService->saveMOMessage();
echo '{"status": "ok"}' . "\n";
*/