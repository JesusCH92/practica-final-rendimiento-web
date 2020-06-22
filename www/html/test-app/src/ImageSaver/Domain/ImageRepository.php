<?php

namespace TestApp\ImageSaver\Domain;

interface ImageRepository
{
    public function checkIfExistImage(string $image);
    public function imageSaveInDB();
}