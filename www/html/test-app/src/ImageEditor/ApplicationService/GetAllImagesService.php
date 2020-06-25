<?php

namespace TestApp\ImageEditor\ApplicationService;

use TestApp\ImageEditor\Domain\ImagesRepository;

class GetAllImagesService
{
    private ImagesRepository $imagesRepository;
    public function __construct(ImagesRepository $imagesRepository)
    {
        $this->imagesRepository = $imagesRepository;
    }
    public function __invoke()
    {
        $imagesCollectionInCache = $this->imagesRepository->getAllImagesInCache();

        $imagesCollection = (null === $imagesCollectionInCache) ? $imagesCollection = $this->imagesRepository->getAllImagesInDB() : $this->imagesRepository->getImageCollectionWithDetailsInCache($imagesCollectionInCache);
        
        return $imagesCollection;
    }
}