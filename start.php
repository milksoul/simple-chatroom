<?php
use Workerman\Worker;

require_once './vendor/workerman/workerman/Autoloader.php';

$global_uid = 0;
function handle_connection($connection){
    global $text_workder, $global_uid;
    $connection->uid = ++$global_uid;
}

function handle_message($connection, $data){
    global $text_worker;
    foreach($text_worker->connections as $conn){
        $conn->send('user '.$connection->uid.' said:'.$data);
    }
}

function handle_close($connection){
    global $text_workder;
    foreach ($text_workder->connections as $conn) {
        $conn->send('user '.$connection->uid.' logout');
    }
}

$text_worker = new Worker('text://0.0.0.0:8989');
$text_worker->count = 1;

$text_worker->onConnect = 'handle_connection';
$text_worker->onMessage = 'handle_message';
$text_worker->onClose = 'handle_close';

Worker::runAll();