<?php

namespace TestApp\ImageEditor\Domain;

interface ImagesRepository
{
    public function getAllImagesInCache();
    public function getImageCollectionWithDetailsInCache(array $imageNameCollection);
    public function getAllImagesInDB();
}