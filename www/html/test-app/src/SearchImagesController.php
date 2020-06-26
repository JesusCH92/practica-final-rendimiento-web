<?php

namespace TestApp;

use Symfony\Component\HttpFoundation\Response;

class SearchImagesController extends BaseController
{
    public function __invoke()
    {
        return Response::create($this->templateEngine->render('SearcherImages/searcherImages.html.twig', []));
    }
}