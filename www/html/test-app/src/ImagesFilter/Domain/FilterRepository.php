<?php

namespace TestApp\ImagesFilter\Domain;

interface FilterRepository
{
    public function createFilterImage(string $imagePath, string $imageName, string $imageRename, string $imageExtension, string $filterAdded);
}