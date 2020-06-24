<?php

namespace TestApp\ImageSaver\ApplicationService;

use TestApp\ImageSaver\Domain\ImageCreateDomainEvent;
use TestApp\ImageSaver\Domain\ImageRepository;

class ImageCreatorListener
{
    private ImageRepository $imageRespository;

    public function __construct(ImageRepository $imageRespository)
    {
        $this->imageRespository = $imageRespository;
    }

    public function imageCreator(ImageCreateDomainEvent $event)
    {
        $imagePath = $event->imagePath();
        $imageName = $event->imageName();
        $imageExtension = $event->imageExtension();
        $filterAdded = $event->filterAdded();
        
        if (!$this->imageRespository->isImageInRedis($imagePath, $imageName, $imageExtension, $filterAdded)) {
            echo 'image saved in Redis ' . PHP_EOL;
            $this->imageRespository->imageSavedInRedis($imagePath, $imageName, $imageExtension, $filterAdded, 'descripcion-prueba');
        }

        $isImageInDB = $this->imageRespository->isImageInDB($imagePath, $imageName, $imageExtension, $filterAdded);

        if (null !== $isImageInDB) {
            echo 'image exist in MySQL ' . PHP_EOL;
            return;
        }
        echo 'image saved in MySQL ' . PHP_EOL;
        $this->imageRespository->imageSavedInMySQL($imagePath, $imageName, $imageExtension, $filterAdded);
    }
}