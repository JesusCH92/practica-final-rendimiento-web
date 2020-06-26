<?php

namespace TestApp\Shared\Infrastructure;

use PDO;
use Elasticsearch\ClientBuilder;

class ImageDBConnector
{
    private PDO $pdo;
    private $redis;
    private $elasticsearch;

    const INDEXNAME = 'imagesmpwar';

    public function __construct()
    {
        $this->pdo = new PDO('mysql:dbname=db;host=mysql', 'user', 'password');
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->redis = new \Redis();
        $this->redis->connect('redis');
        $this->elasticsearch = ClientBuilder::create()->setHosts(["elasticsearch:9200"])->build();
        $params = [
            'index' => self::INDEXNAME,
            'body' => [
                'mappings' => [
                    'properties' => [
                        'image_path' => [
                            'type' => 'text'
                        ],
                        'image_name' => [
                            'type' => 'text'
                        ],
                        'image_rename' => [
                            'type' => 'text'
                        ],
                        'image_extension' => [
                            'type' => 'text'
                        ],
                        'tags' => [
                            'type' => 'keyword'
                        ],
                        'description' => [
                            'type' => 'text'
                        ],
                    ]
                ]
            ]
        ];
        if (!$this->elasticsearch->indices()->exists(['index' => self::INDEXNAME])){
            $this->elasticsearch->indices()->create($params);
        }
    }

    public function pdo()
    {
        return $this->pdo;
    }

    public function redis()
    {
        return $this->redis;
    }

    public function elasticsearch()
    {
        return $this->elasticsearch;
    }

}