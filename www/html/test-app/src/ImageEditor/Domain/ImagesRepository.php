<?php

namespace TestApp\ImageEditor\Domain;

interface ImagesRepository
{
    public function getAllImagesInCache();
    public function getAllImagesInDB();
}