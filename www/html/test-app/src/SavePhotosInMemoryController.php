<?php

namespace TestApp;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PhpAmqpLib\Message\AMQPMessage;
use TestApp\ImageSaver\ApplicationService\ImageCreatorListener;
use TestApp\ImageSaver\Domain\ImageCreateDomainEvent;
use TestApp\ImageSaver\Infrastructure\ImageInDatabase;
use TestApp\Shared\Infrastructure\ImageDBConnector;
use Ramsey\Uuid\Uuid;
use TestApp\Shared\Infrastructure\RabbitMqConnector;

class SavePhotosInMemoryController extends BaseController
{
    public function __invoke(Request $request)
    {
        $uuid = Uuid::uuid4();

        $rabbitmq = new RabbitMqConnector();
        $channel = $rabbitmq->rabbitmq()->channel();

        $imageDBConnector = new ImageDBConnector();
        $imageInDatabase = new ImageInDatabase($imageDBConnector);
        $imageCreatorListener = new ImageCreatorListener($imageInDatabase);

        // $this->dc['elasticsearch']->indices()->delete(['index' => ImageDBConnector::INDEXNAME]);
        // $redis = new \Redis();
        // $redis->connect('redis');
        // $redis->delete($redis->keys('*'));
        // var_dump($redis->keys('*'));exit; echo 'works, is die' . PHP_EOL;

        $symfonyEventDispatcher = $this->dc['symfonyEventDispatcher'];

        $symfonyEventDispatcher->addListener(
            ImageCreateDomainEvent::EVENTNAME,
            array($imageCreatorListener, 'imageCreator')
        );

        if (!$request->isXmlHttpRequest()) {
            throw new \Exception('Error type request!!!');
        }

        $file = $_FILES['file'];

        $templocation = $file["tmp_name"];
        $fileNameAndExtension = explode('.', $file["name"]);

        $fileName = $fileNameAndExtension[0];
        $fileRename = $uuid->toString();
        $fileExtension = $fileNameAndExtension[1];

        $routeFiles = __DIR__ . '/../assets/files';
        if (!$templocation) {
            die('no ha seleccionado ningun archivo');
        } 
        if (!move_uploaded_file($templocation, "$routeFiles/$fileRename.$fileExtension")) {
            echo 'error al guardar archivo';
        }

        $imageCreateDomainEvent = new ImageCreateDomainEvent($routeFiles, $fileName, $fileRename,$fileExtension, "");
        $symfonyEventDispatcher->dispatch($imageCreateDomainEvent, ImageCreateDomainEvent::EVENTNAME);

        foreach (self::IMAGESFILTERCONSUMERS as $imageFilterConsumer) {
            $channel->queue_declare('imageFiler', false, false, false, false);

            $msg = new AMQPMessage(json_encode([
                'file_name' => $fileName,
                'file_rename' => $fileRename,
                'file_extension' =>$fileExtension,
                'file_path' => $routeFiles
            ]));
            
            $channel->basic_publish($msg, '', $imageFilterConsumer);
        }

        echo 'archivo guardado correctamente.' . PHP_EOL;

        return Response::create("hi", 200);
    }
}