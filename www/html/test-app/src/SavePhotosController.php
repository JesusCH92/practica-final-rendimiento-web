<?php

namespace TestApp;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SavePhotosController extends BaseController
{
    public function __invoke(Request $request)
    {
        // var_dump( $request);
        // var_dump($_FILES);

        if(!$request->isXmlHttpRequest()){
            echo 'mierda';
            throw new \Exception('Error type request!!!');
        }
        var_dump($_FILES['file']);
        echo 'merda';
        // $archivo = $_FILES['photos'];
        $archivo = $_FILES['file'];

        var_dump($archivo);
        $templocation = $archivo["tmp_name"];
        $name = $archivo["name"];

        $routeFiles = __DIR__ . '/../assets/files';
        if (!$templocation) {
            die('no ha seleccionado ningun archivo');
        } 
        if (move_uploaded_file($templocation, "$routeFiles/$name")) {
            echo 'archivo guardado correctamente';
        } else{
            echo 'error al guardar archivo';
        }
        return Response::create("hi", 200);
    }
}