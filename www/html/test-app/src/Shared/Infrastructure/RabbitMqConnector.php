<?php

namespace TestApp\Shared\Infrastructure;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMqConnector
{
    private AMQPStreamConnection $rabbitmq;

    public function __construct()
    {
        $this->rabbitmq = new AMQPStreamConnection('rabbitmq', 5672, 'rabbitmq', 'rabbitmq');
    }

    public function rabbitmq()
    {
        return $this->rabbitmq;
    }
}