<?php

namespace TestApp;

use Symfony\Component\HttpFoundation\Response;

class UploadImageController extends BaseController
{
    public function __invoke()
    {
        return Response::create($this->templateEngine->render('LoadImages/loadImages.html.twig', []));
    }
}