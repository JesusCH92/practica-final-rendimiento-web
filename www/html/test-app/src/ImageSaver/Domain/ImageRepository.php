<?php

namespace TestApp\ImageSaver\Domain;

interface ImageRepository
{
    public function isImageInRedis(string $image);
    public function imageSavedInRedis(string $image, string $tag, string $imageDescription);
    public function isImageInDB(string $imageName);
    public function imageSavedInMySQL(string $image);
}