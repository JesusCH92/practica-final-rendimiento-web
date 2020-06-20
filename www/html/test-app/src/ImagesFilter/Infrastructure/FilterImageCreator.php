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
                ->toFile("$imagePath/$imageName-sepia.png", 'image/png');

            echo 'image create ' . PHP_EOL;
        } catch (\Exception $err) {
            echo $err->getMessage();
        }
    }
    public function addBlackAndWhiteFilter(string $imagePath, string $imageName, string $imageExtension)
    {
        try {
            $imageClaviskaSimpleImage = $this->claviskaSimpleImage;
            $imageClaviskaSimpleImage
                ->fromFile("$imagePath/$imageName.$imageExtension")
                ->duotone('white', 'black')
                ->toFile("$imagePath/$imageName-black-and-white.png", 'image/png');

            echo 'image create ' . PHP_EOL;
        } catch (\Exception $err) {
            echo $err->getMessage();
        }
    }

    public function addFlipHorizontalFilter(string $imagePath, string $imageName, string $imageExtension)
    {
        try {
            $imageClaviskaSimpleImage = $this->claviskaSimpleImage;
            $imageClaviskaSimpleImage
                ->fromFile("$imagePath/$imageName.$imageExtension")
                ->flip('x')
                ->border('black', 5)
                ->toFile("$imagePath/$imageName-flip-horizontal.png", 'image/png');

            echo 'image create ' . PHP_EOL;
        } catch (\Exception $err) {
            echo $err->getMessage();
        }
    }

    public function addFlipVerticalFilter(string $imagePath, string $imageName, string $imageExtension)
    {
        try {
            $imageClaviskaSimpleImage = $this->claviskaSimpleImage;
            $imageClaviskaSimpleImage
                ->fromFile("$imagePath/$imageName.$imageExtension")
                ->flip('y')
                ->border('black', 5)
                ->toFile("$imagePath/$imageName-flip-vertical.png", 'image/png');

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