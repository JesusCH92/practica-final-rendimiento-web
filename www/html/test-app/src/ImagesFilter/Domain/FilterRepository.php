<?php

namespace TestApp\ImagesFilter\Domain;

interface FilterRepository
{
    public function createFilterImage(string $imagePath, string $imageName, string $imageExtension, string $filterAdded);
}