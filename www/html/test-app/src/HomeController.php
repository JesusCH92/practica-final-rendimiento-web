<?php

namespace TestApp;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends BaseController
{
    public function __invoke(Request $request): Response
    {
        return Response::create($this->templateEngine->render('base.html.twig', []));
    }
}