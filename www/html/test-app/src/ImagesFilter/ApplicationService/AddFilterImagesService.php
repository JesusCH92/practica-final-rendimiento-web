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
        $isFilterImageCreate = $this->filterRepository->createFilterImage($imagePath, $imageName, $imageExtension, $filterAdded);
        return $isFilterImageCreate;
    }
}