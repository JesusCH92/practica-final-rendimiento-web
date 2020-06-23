<?php

namespace TestApp\ImagesFilter\ApplicationService;

use Symfony\Component\EventDispatcher\EventDispatcher;
use TestApp\ImageSaver\Domain\ImageCreateDomainEvent;
use TestApp\ImagesFilter\Domain\FilterRepository;

class AddFilterImagesService
{
    private FilterRepository $filterRepository;
    public function __construct(FilterRepository $filterRepository, EventDispatcher $eventDispatcher)
    {
        $this->filterRepository = $filterRepository;
        $this->eventDispatcher = $eventDispatcher;
    }
    public function __invoke(string $imagePath, string $imageName, string $imageExtension, string $filterAdded)
    {
        $filterImageCreate = $this->filterRepository->createFilterImage($imagePath, $imageName, $imageExtension, $filterAdded);

        $imageCreateDomainEvent = new ImageCreateDomainEvent($imagePath, $imageName, "png", $filterAdded);

        $this->eventDispatcher->dispatch($imageCreateDomainEvent, ImageCreateDomainEvent::EVENTNAME);

        return $filterImageCreate;
    }
}