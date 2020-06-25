<?php

namespace TestApp\ImageDecorator\Infrastructure;

use TestApp\ImageDecorator\Domain\DeleteTagToImageRepository;

class TagDeleterToImage implements DeleteTagToImageRepository
{
    private DeleteTagToImageRepository $redisDB;
    private DeleteTagToImageRepository $mySqlDB;

    public function __construct(DeleteTagToImageRepository $redisDB, DeleteTagToImageRepository $mySqlDB)
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

    public function deleteTag(string $imageRename, array $imageDetails, string $deleteTag)
    {
        $this->redisDB->deleteTag($imageRename, $imageDetails, $deleteTag);
        $this->mySqlDB->deleteTag($imageRename, $imageDetails, $deleteTag);
    }
}