<?php

namespace TestApp\Shared\Infrastructure;

use PDO;

class ImageDBConnector
{
    private PDO $pdo;
    private $redis;

    public function __construct()
    {
        $this->pdo = new PDO('mysql:dbname=db;host=mysql', 'user', 'password');
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->redis = new \Redis();
        $this->redis->connect('redis');
    }

    public function pdo()
    {
        return $this->pdo;
    }
    public function redis()
    {
        return $this->redis;
    }
}