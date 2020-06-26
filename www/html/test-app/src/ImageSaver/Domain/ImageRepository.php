<?php

namespace TestApp\ImageSaver\Domain;

interface ImageRepository
{
    public function isImageInRedis(string $imageRename);
    public function imageSavedInRedis(string $imagePath, string $imageName, string $imageRename, string $imageExtension, string $filterAdded, string $imageDescription);
    public function isImageInDB(string $imageRename);
    public function imageSavedInMySQL(string $imagePath, string $imageName, string $imageRename, string $imageExtension, string $filterAdded);
    public function documentSavedInELK(string $imagePath, string $imageName, string $imageRename, string $imageExtension, string $filterAdded, string $imageDescription);
}