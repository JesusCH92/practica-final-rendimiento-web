<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use Symfony\Component\EventDispatcher\EventDispatcher;
use TestApp\ImageSaver\ApplicationService\ImageCreatorListener;
use TestApp\ImageSaver\Domain\ImageCreateDomainEvent;
use TestApp\ImageSaver\Infrastructure\ImageInDatabase;
use TestApp\ImagesFilter\ApplicationService\AddFilterImagesService;
use TestApp\ImagesFilter\Infrastructure\FilterImageCreator;
use TestApp\Shared\Infrastructure\Exceptions\ExceptionClassToHumanMessageMapper;
use TestApp\Shared\Infrastructure\ImageDBConnector;
use Ramsey\Uuid\Uuid;
use function GuzzleHttp\json_decode;

$rabbitmq = new AMQPStreamConnection('rabbitmq', 5672, 'rabbitmq', 'rabbitmq');
$channel = $rabbitmq->channel();

$channel->queue_declare('flipHorizontal', false, false, false, false);

echo 'Add flip horizontal filter to images ' . PHP_EOL;

$imageDBConnector = new ImageDBConnector();
$imageInDatabase = new ImageInDatabase($imageDBConnector);
$imageCreatorListener = new ImageCreatorListener($imageInDatabase);

$symfonyEventDispatcher = new EventDispatcher();

$symfonyEventDispatcher->addListener(
    ImageCreateDomainEvent::EVENTNAME, 
    array($imageCreatorListener, 'imageCreator')
);

$filterImageCreator = new FilterImageCreator();
$addFilterImagesService = new AddFilterImagesService($filterImageCreator, $symfonyEventDispatcher);

$callback = function ($msg) use ($addFilterImagesService){
    try{
        $imageProperties = json_decode($msg->body);

        $uuid = Uuid::uuid4();

        $imagePath = $imageProperties->file_path;
        $imageOriginalName = $imageProperties->file_name;
        $imageActualName = $imageProperties->file_rename;
        $imageRenameForFilterImage = $uuid->toString();
        $imageExtension = $imageProperties->file_extension;
    
        $filterImageCreate = $addFilterImagesService->__invoke($imagePath, $imageOriginalName, $imageActualName, $imageRenameForFilterImage, $imageExtension, 'addFlipHorizontalFilter');
        echo $filterImageCreate . PHP_EOL;
    } catch (RuntimeException $exception) {
        $exceptionClassToHumanMessage = new ExceptionClassToHumanMessageMapper();
        echo $exceptionClassToHumanMessage->map(get_class($exception)) . '. Error code: ' . $exception->getCode(
            ) . '.' . PHP_EOL;
    }
};

$channel->basic_consume('flipHorizontal', '', false, true, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}