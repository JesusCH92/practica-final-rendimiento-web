<?php

namespace TestApp\ImageSaver\Domain;

interface ImageRepository
{
    public function isImageInRedis(string $imagePath, string $imageName, string $imageExtension, string $filterAdded);
    public function imageSavedInRedis(string $imagePath, string $imageName, string $imageExtension, string $filterAdded, string $imageDescription);
    public function isImageInDB(string $imagePath, string $imageName, string $imageExtension, string $filterAdded);
    public function imageSavedInMySQL(string $imagePath, string $imageName, string $imageExtension, string $filterAdded);
}