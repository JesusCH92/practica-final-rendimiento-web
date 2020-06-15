<?php

namespace TestApp;

use Symfony\Component\HttpFoundation\Response;
use TestApp\ImagesProcess\ApplicationService\ShowPhotoService;
use TestApp\ImagesProcess\Infrastructure\PhotosInMemory;

class EditedImagesController extends BaseController
{
    public function __invoke()
    {
        $showPhotoService = new ShowPhotoService(new PhotosInMemory());        
        
        return Response::create($this->templateEngine->render('EditedImages/editedImages.html.twig', 
            [
                'imagesCollection' => $showPhotoService(),
            ]
        ));
    }
}