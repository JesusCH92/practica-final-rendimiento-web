<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$rabbitmq = new AMQPStreamConnection('rabbitmq', 5672, 'rabbitmq', 'rabbitmq');
$channel = $rabbitmq->channel();

$channel->queue_declare('sepia', false, false, false, false);

echo 'Add filter to images ' . PHP_EOL;

$callback = function ($msg) {
    echo ' [x] Add Filter sepia: ' . $msg->body . PHP_EOL;
};

$channel->basic_consume('sepia', '', false, true, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}