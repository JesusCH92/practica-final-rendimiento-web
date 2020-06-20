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
                ->toFile("$imagePath/sepia-$imageName.png", 'image/png');

            echo 'image create ' . PHP_EOL;
        } catch (\Exception $err) {
            echo $err->getMessage();
        }
    }

    public function createFilterImage(string $imagePath, string $imageName, string $imageExtension, string $filterAdded)
    {
        $this->$filterAdded($imagePath, $imageName, $imageExtension);
    }
}