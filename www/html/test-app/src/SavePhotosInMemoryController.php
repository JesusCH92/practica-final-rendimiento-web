<?php

namespace TestApp;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PhpAmqpLib\Message\AMQPMessage;

class SavePhotosInMemoryController extends BaseController
{
    public function __invoke(Request $request)
    {
        $rabbitmq = $this->dc['rabbitmq'];
        $channel = $rabbitmq->channel();

        if(!$request->isXmlHttpRequest()){
            echo 'mierda';
            throw new \Exception('Error type request!!!');
        }
        var_dump($_FILES['file']);
        echo 'merda';
        // $archivo = $_FILES['photos'];
        $file = $_FILES['file'];

        var_dump($file);
        $templocation = $file["tmp_name"];
        $fileNameAndExtension = explode('.', $file["name"]);

        $fileName = $fileNameAndExtension[0];
        $fileExtension = $fileNameAndExtension[1];

        $routeFiles = __DIR__ . '/../assets/files';
        if (!$templocation) {
            die('no ha seleccionado ningun archivo');
        } 
        if (!move_uploaded_file($templocation, "$routeFiles/$fileName.$fileExtension")) {
            echo 'error al guardar archivo';
        }

        foreach (self::IMAGESFILTERCONSUMERS as $imageFilterConsumer) {
            var_dump($imageFilterConsumer);
            $channel->queue_declare('imageFiler', false, false, false, false);

            $msg = new AMQPMessage(json_encode([
                'file_name' => $fileName,
                'file_extension' =>$fileExtension,
                'file_path' => $routeFiles
            ]));
            
            $channel->basic_publish($msg, '', $imageFilterConsumer);
        }

        echo 'archivo guardado correctamente.' . PHP_EOL;

        return Response::create("hi", 200);
    }
}