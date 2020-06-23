<?php

namespace TestApp\ImageSaver\Infrastructure;

use TestApp\ImageSaver\Domain\ImageRepository;
use TestApp\Shared\Infrastructure\ImageDBConnector;

class ImageInDatabase implements ImageRepository
{
    private ImageDBConnector $imageDBConnector;

    public function __construct(ImageDBConnector $imageDBConnector)
    {
        $this->imageDBConnector = $imageDBConnector;
    }

    public function isImageInRedis(string $image)
    {
        $isImageInRedis = $this->imageDBConnector->redis()->exists($image) === 1;
        return  $isImageInRedis;
    }

    public function imageSavedInRedis(string $image, string $tag, string $imageDescription)
    {
        $imageMetadata = array(
            'tag' => array($tag),
            'description' => $imageDescription
        );

        $this->imageDBConnector->redis()->set($image,json_encode($imageMetadata));
    }

    public function imageSavedInMySQL(string $image)
    {
        echo 'aqui guardaremos la imagen: falta implementarlo' . PHP_EOL;
        echo 'esto ha guardado redis de la image: ' . PHP_EOL;
        var_dump(   $this->imageDBConnector->redis()->get($image)  );
        echo 'Estas son todas las keys en Redis: ' . PHP_EOL;
        var_dump(   $this->imageDBConnector->redis()->keys('*')  );

        if (count($this->imageDBConnector->redis()->keys('*') > 12)){
            echo 'Borrar todas las keys en Redis: ' . PHP_EOL;
            $this->imageDBConnector->redis()->delete($this->imageDBConnector->redis()->keys('*'));
        }
        
    }
}