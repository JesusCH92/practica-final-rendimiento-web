<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use TestApp\ImagesFilter\ApplicationService\AddFilterImagesService;
use TestApp\ImagesFilter\Infrastructure\FilterImageCreator;
use TestApp\Shared\Infrastructure\Exceptions\ExceptionClassToHumanMessageMapper;

use function GuzzleHttp\json_decode;

$rabbitmq = new AMQPStreamConnection('rabbitmq', 5672, 'rabbitmq', 'rabbitmq');
$channel = $rabbitmq->channel();

$channel->queue_declare('flipVertical', false, false, false, false);

echo 'Add flip vertical filter to images ' . PHP_EOL;

$filterImageCreator = new FilterImageCreator();
$addFilterImagesService = new AddFilterImagesService($filterImageCreator);

$callback = function ($msg) use ($addFilterImagesService){
    try{
        $imageProperties = json_decode($msg->body);

        $imagePath = $imageProperties->file_path;
        $imageName = $imageProperties->file_name;
        $imageExtension = $imageProperties->file_extension;
    
        $filterImageCreate = $addFilterImagesService->__invoke($imagePath, $imageName, $imageExtension, 'addFlipVerticalFilter');
        echo $filterImageCreate . PHP_EOL;
    } catch (RuntimeException $exception) {
        $exceptionClassToHumanMessage = new ExceptionClassToHumanMessageMapper();
        echo $exceptionClassToHumanMessage->map(get_class($exception)) . '. Error code: ' . $exception->getCode(
            ) . '.' . PHP_EOL;
    }
};

$channel->basic_consume('flipVertical', '', false, true, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}