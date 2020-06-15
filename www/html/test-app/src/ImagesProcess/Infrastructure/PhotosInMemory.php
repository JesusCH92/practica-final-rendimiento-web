<?php

namespace TestApp\ImagesProcess\Infrastructure;

use TestApp\ImagesProcess\Domain\Photo;
use TestApp\ImagesProcess\Domain\PhotoRepository;

class PhotosInMemory implements PhotoRepository
{
    public function getAllPhotos($photosPath)
    {
        $photosFile = scandir($photosPath);
        $photoCollection = array_filter($photosFile, function($valuePhoto){
            return strpos($valuePhoto, ".jpg") !== false || strpos($valuePhoto, ".png") !== false;
        });

        return $photoCollection;
    }
}