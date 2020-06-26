<?php

namespace TestApp\ImageEditor\Infrastructure;

use PDO;
use TestApp\ImageEditor\Domain\ImagesRepository;
use TestApp\Shared\Infrastructure\ImageDBConnector;

class ImagesSearcher implements ImagesRepository
{
    private ImageDBConnector $imageDBConnector;

    public function __construct(ImageDBConnector $imageDBConnector)
    {
        $this->imageDBConnector = $imageDBConnector;
    }

    public function getAllImagesInCache()
    {
        $imageInCache = $this->imageDBConnector->redis()->keys('*');
        $imageCollection = count($imageInCache) === 0 ? null : $imageInCache;
        
        return $imageCollection;
    }

    public function getImageCollectionWithDetailsInCache($imageNameCollection)
    {
        $imageCollection = [];

        foreach ($imageNameCollection as $imageName) {
            $imageDetails = json_decode($this->imageDBConnector->redis()->get($imageName), true);
            array_push($imageCollection, $imageDetails);
        }

        return $imageCollection;
    }

    public function getAllImagesInDB()
    {
        $stmt = $this->imageDBConnector->pdo()->prepare(
            'SELECT image_path, image_name, image_rename, image_extension, tags, description FROM images'
        );
        $stmt->execute();
        $imageCollection = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // var_dump($imageCollection[0]["image_name"]);
        echo 'Sugar Honey Ice Tea in SQL' . PHP_EOL;
        return $imageCollection;
    }
}