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
    public function checkIfExistImage(string $image)
    {
        echo "$image esta es una imagen molona" . PHP_EOL;
    }

    public function imageSaveInDB()
    {
        echo 'aqui guardaremos la imagen: falta implementarlo' . PHP_EOL;
    }
}