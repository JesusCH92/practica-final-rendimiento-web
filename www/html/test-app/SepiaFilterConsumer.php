<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use TestApp\ImagesFilter\ApplicationService\AddFilterImagesService;
use TestApp\ImagesFilter\Infrastructure\FilterImageCreator;

use function GuzzleHttp\json_decode;

$rabbitmq = new AMQPStreamConnection('rabbitmq', 5672, 'rabbitmq', 'rabbitmq');
$channel = $rabbitmq->channel();

$channel->queue_declare('sepia', false, false, false, false);

echo 'Add filter to images ' . PHP_EOL;
echo 'Hello there!' . PHP_EOL;
echo 'madein madein!!' . PHP_EOL;

$callback = function ($msg) {
    echo ' [x] Add Filter sepia: ' . $msg->body . PHP_EOL;
    $imageProperties = json_decode($msg->body);
    var_dump($imageProperties);

    // $filterImageCreator = new FilterImageCreator();
    // $addFilterImagesService = new AddFilterImagesService($filterImageCreator);
    echo 'general kenobi!' . PHP_EOL;

    // $addFilterImagesService->__invoke($imageProperties->file_path, $imageProperties->file_name, $imageProperties->file_extension, 'addSepiaFilter');
    echo 'there is a new service for add filter to images!! ' . PHP_EOL;

    // $imagePath = $imageProperties->file_path;
    $imagePath = '/code/test-app/assets/files';
    $imageName = $imageProperties->file_name;
    $imageExtension = $imageProperties->file_extension;

    echo "$imagePath/$imageName.$imageExtension" . PHP_EOL;

    try {
        $imageClaviskaSimpleImage = new \claviska\SimpleImage();
        $imageClaviskaSimpleImage
            ->fromFile("$imagePath/$imageName.$imageExtension")
            ->sepia()
            ->toFile("$imagePath/sepia-$imageName.$imageExtension", 'image/png')
            ->toScreen();
        echo 'it works? ' . PHP_EOL;
    } catch (Exception $err) {
        echo 'mierddddda ' . PHP_EOL;
        // echo $err->getMessage();
    }
    echo 'image create ' . PHP_EOL;
};

$channel->basic_consume('sepia', '', false, true, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}