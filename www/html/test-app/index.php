<?php

require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client as GuzzleClient;
use Elasticsearch\ClientBuilder;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\Request;
use TestApp\DescriptionCreatorController;
use TestApp\EditedImagesController;
// If you add a new route don't forget to include it's namespace
use TestApp\UserController;
use TestApp\HomeController;
use TestApp\ImageDocumentSearcherController;
use TestApp\TagCreatorController;
use TestApp\SavePhotosInMemoryController;
use TestApp\SearchImagesController;
use TestApp\TagDeleterController;
use TestApp\UploadImageController;

/*
 * ----------------
 * | Dependencies |
 * ----------------
 */
$elasticsearch = $client = ClientBuilder::create()->setHosts(["elasticsearch:9200"])->build();
$guzzle = new GuzzleClient();
$symfonyEventDispatcher = new EventDispatcher();

$dc = [
    'elasticsearch' => $elasticsearch,
    'guzzle' => $guzzle,
    'symfonyEventDispatcher' => $symfonyEventDispatcher
];

/*
 * -----------
 * | Routing |
 * -----------
 */
$routes = [
    'home'      => (new Route('/',           ['controller' => HomeController::class]))->setMethods([Request::METHOD_GET]),
    'get_user'  => (new Route('/users/',     ['controller' => UserController::class]))->setMethods([Request::METHOD_GET]),
    'post_user' => (new Route('/users/{id}', ['controller' => UserController::class, 'method' => 'create']))->setMethods([Request::METHOD_POST]),
    'load_img'  => (new Route('/imagen',     ['controller' => UploadImageController::class]))->setMethods([Request::METHOD_GET]),
    'save_img'  => (new Route('/save_photos', ['controller' => SavePhotosInMemoryController::class]))->setMethods([Request::METHOD_POST]),
    'edited_img'  => (new Route('/edited-imagen', ['controller' => EditedImagesController::class]))->setMethods([Request::METHOD_GET]),
    'create_tag' => (new Route('/create-tag', ['controller' => TagCreatorController::class]))->setMethods([Request::METHOD_POST]),
    'delete_tag' => (new Route('/delete-tag', ['controller' => TagDeleterController::class]))->setMethods([Request::METHOD_DELETE]),
    'add_description' => (new Route('/add-description', ['controller' => DescriptionCreatorController::class]))->setMethods([Request::METHOD_POST]),
    'search_img' => (new Route('/searcher-imagen', ['controller' => SearchImagesController::class]))->setMethods([Request::METHOD_GET]),
    'search_img_elk' => (new Route('/image-searcher-elk', ['controller' => ImageDocumentSearcherController::class]))->setMethods([Request::METHOD_POST]),
];

/*
 * ------------
 * | Dispatch |
 * ------------
 */
$rc = new RouteCollection();
foreach ($routes as $key => $route) {
    $rc->add($key, $route);
}
$context = new RequestContext();
$matcher = new UrlMatcher($rc, $context);
$request = Request::createFromGlobals();
$context->fromRequest($request);

try {
    $attributes = $matcher->match($context->getPathInfo());
    $ctrlName = $matcher->match($context->getPathInfo())['controller'];
    $ctrl = new $ctrlName($dc);
    $request->attributes->add($attributes);
    if (isset($matcher->match($context->getPathInfo())['method'])) {
        $response = $ctrl->{$matcher->match($context->getPathInfo())['method']}($request);
    } else {
        $response = $ctrl($request);
    }
} catch (ResourceNotFoundException $e) {
    $response = new Response('Not found!', Response::HTTP_NOT_FOUND);
}

$response->send();
