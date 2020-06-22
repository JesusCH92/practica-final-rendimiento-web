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
        
        $image = "$imagePath/$imageName.$imageExtension";

        $this->imageRespository->checkIfExistImage($image);

        $this->imageRespository->imageSaveInDB();
    }
}