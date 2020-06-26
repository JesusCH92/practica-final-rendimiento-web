<?php

namespace TestApp;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use TestApp\ImageDecorator\Infrastructure\ImageInMySql;
use TestApp\ImageDecorator\Infrastructure\ImageInRedis;
use TestApp\ImageDecorator\Infrastructure\TagCreatorToImage;
use TestApp\Shared\Infrastructure\ImageDBConnector;

class TagCreatorController extends BaseController
{
    public function __invoke(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new \Exception('Error type request!!!');
        }

        $imageName = $request->get('imageName');
        $tagText = $request->get('tag');

        $imageDBConnector = new ImageDBConnector();

        $imageInRedis = new ImageInRedis($imageDBConnector);
        $imageInMySql = new ImageInMySql($imageDBConnector);

        $tagCreatorToImage = new TagCreatorToImage($imageInRedis, $imageInMySql);

        if ($tagCreatorToImage->getImageDetails($imageName) === null) {
            echo 'ERROR_IMAGE_NOT_FOUND' . PHP_EOL;
            return;
        }

        $imageDetails = $tagCreatorToImage->getImageDetails($imageName);
        $tagCreatorToImage->createTag($imageName, $imageDetails, $tagText);

        array_push($imageDetails["tags"], $tagText);
        $params = [
            'index' => ImageDBConnector::INDEXNAME,
            'id' => $imageName,
            'body' => [
                'script' => [
                    'source' =>'ctx._source.tags=params.tags',
                    'params' => [
                        'tags' => $imageDetails["tags"]
                    ]
                ]
            ]
        ];

        $elk = $this->dc['elasticsearch']->update($params);

        return new JsonResponse([
            'tag_create' => $tagText,
            'image_name' => $imageName,
            'elk' => $elk
        ]);    
    }
}