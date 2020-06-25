<?php

namespace TestApp\ImageDecorator\Domain;

interface CreateTagToImageRepository
{
    public function getImageDetails(string $renameImage);

    public function createTag(string $imageRename, array $imageDetails, string $newTag);
}