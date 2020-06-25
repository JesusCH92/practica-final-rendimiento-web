<?php

namespace TestApp\ImageDecorator\Infrastructure;

use TestApp\ImageDecorator\Domain\CreateTagToImageRepository;

class TagCreatorToImage implements CreateTagToImageRepository
{
    private CreateTagToImageRepository $redisDB;
    private CreateTagToImageRepository $mySqlDB;

    public function __construct(CreateTagToImageRepository $redisDB, CreateTagToImageRepository $mySqlDB)
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

    public function createTag(string $imageRename, array $imageDetails, string $newTag)
    {
        $this->redisDB->createTag($imageRename, $imageDetails, $newTag);
        $this->mySqlDB->createTag($imageRename, $imageDetails, $newTag);
    }
}