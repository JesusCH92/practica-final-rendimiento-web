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
        $imageRename = $event->imageRename();
        $imageExtension = $event->imageExtension();
        $filterAdded = $event->filterAdded();
        
        if (!$this->imageRespository->isImageInRedis($imageRename)) {
            $this->imageRespository->imageSavedInRedis($imagePath, $imageName, $imageRename, $imageExtension, $filterAdded, '');
        }

        $isImageInDB = $this->imageRespository->isImageInDB($imageRename);

        if (null !== $isImageInDB) {
            echo 'image exist in MySQL ' . PHP_EOL;
            return;
        }

        $this->imageRespository->imageSavedInMySQL($imagePath, $imageName, $imageRename, $imageExtension, $filterAdded);
        $this->imageRespository->documentSavedInELK($imagePath, $imageName, $imageRename, $imageExtension, $filterAdded, '');
    }
}