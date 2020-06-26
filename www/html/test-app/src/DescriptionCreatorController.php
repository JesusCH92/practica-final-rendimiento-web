<?php

namespace TestApp;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use TestApp\ImageDecorator\Infrastructure\DescriptionCreatorToImage;
use TestApp\ImageDecorator\Infrastructure\ImageInMySql;
use TestApp\ImageDecorator\Infrastructure\ImageInRedis;
use TestApp\Shared\Infrastructure\ImageDBConnector;

class DescriptionCreatorController extends BaseController
{
    public function __invoke(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new \Exception('Error type request!!!');
        }

        $imageName = $request->get('imageName');
        $descriptionText = $request->get('description');

        $imageDBConnector = new ImageDBConnector();
        
        $imageInRedis = new ImageInRedis($imageDBConnector);
        $imageInMySql = new ImageInMySql($imageDBConnector);

        $descriptionCreatorToImage = new DescriptionCreatorToImage($imageInRedis, $imageInMySql);

        if ($descriptionCreatorToImage->getImageDetails($imageName) === null) {
            echo 'ERROR_IMAGE_NOT_FOUND' . PHP_EOL;
            return;
        }

        $imageDetails = $descriptionCreatorToImage->getImageDetails($imageName);
        $descriptionCreatorToImage->createDescription($imageName, $imageDetails, $descriptionText);

        return new JsonResponse([
            'description' => $descriptionText,
            'image_name' => $imageName
        ]);    
    }
}