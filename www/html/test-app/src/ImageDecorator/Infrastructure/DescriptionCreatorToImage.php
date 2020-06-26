<?php

namespace TestApp\ImageDecorator\Infrastructure;

use TestApp\ImageDecorator\Domain\CreateDescriptionToImageRepository;

class DescriptionCreatorToImage implements CreateDescriptionToImageRepository
{
    private CreateDescriptionToImageRepository $redisDB;
    private CreateDescriptionToImageRepository $mySqlDB;

    public function __construct(CreateDescriptionToImageRepository $redisDB, CreateDescriptionToImageRepository $mySqlDB)
    {
        $this->redisDB = $redisDB;
        $this->mySqlDB = $mySqlDB;
    }
    public function getImageDetails(string $renameImage)
    {
        if ($this->redisDB->getImageDetails($renameImage) !== null) {
            $imageDetails = $this->redisDB->getImageDetails($renameImage);
            return $imageDetails;
        }
        $imageDetails = $this->mySqlDB->getImageDetails($renameImage);
        return $imageDetails;
    }

    public function createDescription(string $imageRename, array $imageDetails, string $newDescription)
    {
        $this->redisDB->createDescription($imageRename, $imageDetails, $newDescription);
        $this->mySqlDB->createDescription($imageRename, $imageDetails, $newDescription);
    }
}