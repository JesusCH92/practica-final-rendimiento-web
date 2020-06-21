<?php

namespace TestApp\Shared\Infrastructure\Exceptions;

use TestApp\ImagesFilter\Infrastructure\Exceptions\AddFilterFailedException;

class ExceptionClassToHumanMessageMapper
{
    private array $exceptionClassToHumanMessageMap = [
        AddFilterFailedException::class => 'Add filter to image failed'
    ];

    public function map(string $exceptionClass): string
    {
        if (!array_key_exists($exceptionClass, $this->exceptionClassToHumanMessageMap)) {
            return 'Unknown error';
        }
        return $this->exceptionClassToHumanMessageMap[$exceptionClass];
    }
}
