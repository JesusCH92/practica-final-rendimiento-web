<?php

namespace TestApp;

use Twig\Loader\FilesystemLoader;
class BaseController
{
    /** @var array */
    protected $dc;
    protected $templateEngine;
    const IMAGESFILTERCONSUMERS = ['sepia', 'blackAndWhite', 'flipHorizontal', 'flipVertical', 'darkblue'];

    public function __construct(array $dc)
    {
        $this->dc = $dc;
        $loader = new FilesystemLoader(__DIR__ .'/../templates');
        $this->templateEngine = new \Twig\Environment($loader, [
            'debug' => true,
            // 'cache' => 'false',
        ]); 
        $this->templateEngine->addExtension(new \Twig\Extension\DebugExtension());
    }
}