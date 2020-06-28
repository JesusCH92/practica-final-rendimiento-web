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