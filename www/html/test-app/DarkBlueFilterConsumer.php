<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use TestApp\ImagesFilter\ApplicationService\AddFilterImagesService;
use TestApp\ImagesFilter\Infrastructure\FilterImageCreator;

use function GuzzleHttp\json_decode;

$rabbitmq = new AMQPStreamConnection('rabbitmq', 5672, 'rabbitmq', 'rabbitmq');
$channel = $rabbitmq->channel();

$channel->queue_declare('darkblue', false, false, false, false);

echo 'Add filter to images ' . PHP_EOL;
echo 'Hello there!' . PHP_EOL;

$filterImageCreator = new FilterImageCreator();
$addFilterImagesService = new AddFilterImagesService($filterImageCreator);

$callback = function ($msg) use ($addFilterImagesService){
    echo ' [x] Add Filter sepia: ' . $msg->body . PHP_EOL;
    $imageProperties = json_decode($msg->body);

    echo 'general kenobi!' . PHP_EOL;

    $imagePath = $imageProperties->file_path;
    $imageName = $imageProperties->file_name;
    $imageExtension = $imageProperties->file_extension;

    $addFilterImagesService->__invoke($imagePath, $imageName, $imageExtension, 'addDarkBlueFilter');

    echo 'order 66 is execute!!!' . PHP_EOL;
};

$channel->basic_consume('darkblue', '', false, true, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}