<?php

namespace TestApp\ImageDecorator\Domain;

interface CreateDescriptionToImageRepository
{
    public function getImageDetails(string $renameImage);

    public function createDescription(string $imageRename, array $imageDetails, string $newDescription);
}