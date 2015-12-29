<?php

require_once 'config/config.php';

use Web\Services\moService;

$response = array();

$response['last_15_min_mo_count'] = moService::getCountOfMOMessagesCreatedXMinutesAgo(15);
$response['time_span_last_10k'] = moService::getElapsedTimeBetweenLastXMessages(10000);
echo json_encode($response) . "\n";