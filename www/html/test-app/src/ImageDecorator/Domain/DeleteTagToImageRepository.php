<?php

namespace TestApp\ImageDecorator\Domain;

interface DeleteTagToImageRepository
{
    public function getImageDetails(string $renameImage);

    public function deleteTag(string $imageRename, array $imageDetails, string $deleteTag);
}