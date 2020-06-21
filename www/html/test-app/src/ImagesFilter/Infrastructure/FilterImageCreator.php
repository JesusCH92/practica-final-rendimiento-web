<?php

namespace TestApp\ImagesFilter\Infrastructure;

use TestApp\ImagesFilter\Domain\FilterRepository;
use TestApp\ImagesFilter\Infrastructure\Exceptions\AddFilterFailedException;

class FilterImageCreator implements FilterRepository
{
    const SUCCESSFULLFILTERIMAGE = 'FILTER_IMAGE_CREATED';
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
        } catch (\Exception $err) {
            throw new AddFilterFailedException();
        }
        return self::SUCCESSFULLFILTERIMAGE;
    }
    public function addBlackAndWhiteFilter(string $imagePath, string $imageName, string $imageExtension)
    {
        try {
            $imageClaviskaSimpleImage = $this->claviskaSimpleImage;
            $imageClaviskaSimpleImage
                ->fromFile("$imagePath/$imageName.$imageExtension")
                ->duotone('white', 'black')
                ->toFile("$imagePath/$imageName-black-and-white.png", 'image/png');
        } catch (\Exception $err) {
            echo $err->getMessage();
        }
        return self::SUCCESSFULLFILTERIMAGE;
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
        } catch (\Exception $err) {
            echo $err->getMessage();
        }
        return self::SUCCESSFULLFILTERIMAGE;
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
        } catch (\Exception $err) {
            echo $err->getMessage();
        }
        return self::SUCCESSFULLFILTERIMAGE;
    }

    public function addDarkBlueFilter(string $imagePath, string $imageName, string $imageExtension)
    {
        try {
            $imageClaviskaSimpleImage = $this->claviskaSimpleImage;
            $imageClaviskaSimpleImage
                ->fromFile("xx$imagePath/$imageName.$imageExtension")
                ->flip('x')
                ->flip('y')
                ->colorize('DarkBlue')
                ->border('black', 5)
                ->toFile("$imagePath/$imageName-darkblue.png", 'image/png');
        } catch (\Exception $err) {
            throw new AddFilterFailedException();
        }
        return self::SUCCESSFULLFILTERIMAGE;
    }

    public function createFilterImage(string $imagePath, string $imageName, string $imageExtension, string $filterAdded)
    {
        $filterImageCreated = $this->$filterAdded($imagePath, $imageName, $imageExtension);
        return $filterImageCreated;
    }
}