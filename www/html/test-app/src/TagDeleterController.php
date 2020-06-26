<?php

namespace TestApp;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use TestApp\ImageDecorator\Infrastructure\ImageInMySql;
use TestApp\ImageDecorator\Infrastructure\ImageInRedis;
use TestApp\ImageDecorator\Infrastructure\TagDeleterToImage;
use TestApp\Shared\Infrastructure\ImageDBConnector;

class TagDeleterController extends BaseController
{
    public function __invoke(Request $request)
    {
        echo 'works DELETE?' . PHP_EOL;

        if (!$request->isXmlHttpRequest()) {
            throw new \Exception('Error type request!!!');
        }

        $imageName = $request->request->get('imageName');
        $tagText = $request->get('tag');

        $imageDBConnector = new ImageDBConnector();

        $imageInRedis = new ImageInRedis($imageDBConnector);
        $imageInMySql = new ImageInMySql($imageDBConnector);

        $tagDeleterToImage = new TagDeleterToImage($imageInRedis, $imageInMySql);

        if ($tagDeleterToImage->getImageDetails($imageName) === null) {
            echo 'ERROR_IMAGE_NOT_FOUND' . PHP_EOL;
            return;
        }

        $imageDetails = $tagDeleterToImage->getImageDetails($imageName);
        $tagDeleterToImage->deleteTag($imageName, $imageDetails, $tagText);

        if (($key = array_search($tagText, $imageDetails["tags"])) !== false) {
            unset($imageDetails["tags"][$key]);
        }

        $params = [
            'index' => ImageDBConnector::INDEXNAME,
            'id' => $imageName,
            'body' => [
                'script' => [
                    'source' =>'ctx._source.tags=params.tags',
                    'params' => [
                        'tags' => array_values($imageDetails["tags"])
                    ]
                ]
            ]
        ];

        $elk = $this->dc['elasticsearch']->update($params);

        return new JsonResponse([
            'elk' => $elk
        ]);
    }
}