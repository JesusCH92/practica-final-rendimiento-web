<?php

namespace TestApp\ImagesProcess\ApplicationService;

use TestApp\ImagesProcess\Domain\Photo;
use TestApp\ImagesProcess\Domain\PhotoRepository;

class ShowPhotoService
{
    private PhotoRepository $photoRepository;

    public function __construct(PhotoRepository $photoRepository)
    {
        $this->photoRepository = $photoRepository;
    }

    public function __invoke()
    {
        $photoCollection = $this->photoRepository->getAllPhotos(Photo::PHOTOSPATH);

        return $photoCollection;
    }
}