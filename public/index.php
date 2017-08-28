<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Slim\App;
require '../vendor/autoload.php';

$app = new App();

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    $http_origin = $_SERVER['HTTP_ORIGIN'];
    $default_origin = 'http://localhost:8081';
    if ($http_origin == "http://localhost:8081" || $http_origin == "http://localhost:8080")
    {
//        header("Access-Control-Allow-Origin: $http_origin");
        $default_origin = $http_origin;
    }
    return $response
        ->withHeader('Access-Control-Allow-Origin', $default_origin )
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});


$app->post('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
});
$app->run();