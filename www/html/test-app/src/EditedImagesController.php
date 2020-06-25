<?php

namespace TestApp;

use Symfony\Component\HttpFoundation\Response;
use TestApp\ImageEditor\ApplicationService\GetAllImagesService;
use TestApp\ImageEditor\Infrastructure\ImagesSearcher;
use TestApp\Shared\Infrastructure\ImageDBConnector;

class EditedImagesController extends BaseController
{
    public function __invoke()
    {
        $imageDBConnector = new ImageDBConnector();
        $imagesSearcher = new ImagesSearcher($imageDBConnector);

        $getAllImagesService = new GetAllImagesService($imagesSearcher);

        $imageCollection = $getAllImagesService();         
        
        return Response::create($this->templateEngine->render('EditedImages/editedImages.html.twig', 
            [
                'imagesCollection' => $imageCollection
            ]
        ));
    }
}