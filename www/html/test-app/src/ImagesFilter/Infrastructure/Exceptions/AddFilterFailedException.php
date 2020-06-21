<?php

namespace TestApp\ImagesFilter\Infrastructure\Exceptions;

use RuntimeException;

class AddFilterFailedException extends RuntimeException
{
    protected $code = 'ADD_FILTER_FAILED';
}