<?php

namespace TestApp\Shared\Infrastructure;

use PDO;

class ImageDBConnector
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO('mysql:dbname=db;host=mysql', 'user', 'password');
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function pdo()
    {
        return $this->pdo;
    }
}