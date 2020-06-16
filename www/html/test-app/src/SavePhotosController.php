<?php

namespace TestApp;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PhpAmqpLib\Message\AMQPMessage;

class SavePhotosController extends BaseController
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
        $archivo = $_FILES['file'];

        var_dump($archivo);
        $templocation = $archivo["tmp_name"];
        $name = $archivo["name"];

        $routeFiles = __DIR__ . '/../assets/files';
        if (!$templocation) {
            die('no ha seleccionado ningun archivo');
        } 
        if (!move_uploaded_file($templocation, "$routeFiles/$name")) {
            echo 'error al guardar archivo';
        }

        foreach (self::IMAGESFILTERCONSUMERS as $imageFilterConsumer) {
            var_dump($imageFilterConsumer);
            $channel->queue_declare('imageFiler', false, false, false, false);

            $msg = new AMQPMessage(json_encode([
                'file_name' => $name,
                'file_path' => $routeFiles
            ]));
            
            $channel->basic_publish($msg, '', $imageFilterConsumer);
        }

        echo 'archivo guardado correctamente.' . PHP_EOL;

        return Response::create("hi", 200);
    }
}