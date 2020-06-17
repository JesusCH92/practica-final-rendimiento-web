<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

use function GuzzleHttp\json_decode;

$rabbitmq = new AMQPStreamConnection('rabbitmq', 5672, 'rabbitmq', 'rabbitmq');
$channel = $rabbitmq->channel();

$channel->queue_declare('sepia', false, false, false, false);

echo 'Add filter to images ' . PHP_EOL;
var_dump(gd_info());

$callback = function ($msg) {
    echo ' [x] Add Filter sepia: ' . $msg->body . PHP_EOL;
    $imageProperties = json_decode($msg->body);
    // var_dump($imageProperties->file_path);
    // $pathImage = $imageProperties->file_path . '/' . $imageProperties->file_name;
    // $pathImage = '/www/html/test-app/assets/files/endpoint-http' . $imageProperties->file_name;
    // echo $pathImage . PHP_EOL;

    // $pathImg = __DIR__ . '\assets\files\endpoint-http.png';
    // echo $pathImg . PHP_EOL;
    // // $imgPath = '/www/html/test-app/assets/files/endpoint-http.png';
    // $im = imagecreatefrompng ($pathImg);
    // if ($im && imagefilter($im, IMG_FILTER_GRAYSCALE)) {
    //     echo 'Imagen convertida a escala de grises.';

    //     imagepng($im, 'dave.png');
    // } else {
    //     echo 'La conversión a escala de grises falló.';
    // }

    // imagedestroy($im);
};

$channel->basic_consume('sepia', '', false, true, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}