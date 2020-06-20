<?php

namespace TestApp\ImagesFilter\Infrastructure;

use TestApp\ImagesFilter\Domain\FilterRepository;

class FilterImageCreator implements FilterRepository
{
    public function __construct()
    {
        $this->claviskaSimpleImage = new \claviska\SimpleImage();
    }

    public function addSepiaFilter(string $imagePath, string $imageName, string $imageExtension)
    {
        try {
            $imageClaviskaSimpleImage = $this->claviskaSimpleImage;
            $imageClaviskaSimpleImage
                ->fromFile("$imagePath/$imageName.$imageExtension")
                ->sepia()
                ->toFile("$imagePath/sepia-$imageName.png", 'image/png')
                ->toScreen();
        } catch (\Exception $err) {
            echo 'mierddddda ' . PHP_EOL;
            echo $err->getMessage();
        }
    }

    public function createFilterImage(string $imagePath, string $imageName, string $imageExtension, string $filterAdded)
    {
        $this->$filterAdded($imagePath, $imageName, $imageExtension);
    }
}