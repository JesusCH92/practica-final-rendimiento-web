<?php

namespace TestApp;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use TestApp\Shared\Infrastructure\ImageDBConnector;

class ImageDocumentSearcherController extends BaseController
{
    public function __invoke(Request $request)
    {
        $imageSearch = $request->get('imageSearch');

        $responseELK = $this->dc['elasticsearch']->search(
            array(
                'index' => ImageDBConnector::INDEXNAME,
                'body'  => array(
                    'query' => array(
                        'bool' => array(
                            'should' => array(
                                array(
                                    'multi_match' => array(
                                        'query'     => $imageSearch,
                                        'fields'    => array('tags', 'description'),
                                        'boost'     => 10,
                                        "fuzziness" => "AUTO"
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            )
        );

        $imageCollection = $this->formatResponseELk($responseELK);

        return new JsonResponse([
            'image_collection' => $imageCollection,
        ]);  
    }

    public function formatResponseELk($responseELK)
    {
        $results = $responseELK["hits"]["total"]["value"] === 0 ? null : $responseELK["hits"]["hits"];
        $imageCollection = [];

        if ($results !== null){
            foreach($results as $matchElk) {
                array_push($imageCollection, $matchElk["_source"]);
            }
        }

        return $imageCollection;
    }
}