<?php

namespace TestApp\ImagesFilter\ApplicationService;

use TestApp\ImagesFilter\Domain\FilterRepository;

class AddFilterImagesService
{
    private FilterRepository $filterRepository;
    public function __construct(FilterRepository $filterRepository)
    {
        $this->filterRepository = $filterRepository;
    }
    public function __invoke(string $imagePath, string $imageName, string $imageExtension, string $filterAdded)
    {
        $this->filterRepository->createFilterImage($imagePath, $imageName, $imageExtension, $filterAdded);
    }
}