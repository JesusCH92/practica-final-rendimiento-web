<?php

namespace TestApp\ImagesProcess\Domain;

interface PhotoRepository
{
    public function getAllPhotos($pathPhotos);
}