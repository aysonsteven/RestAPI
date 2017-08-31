<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Slim\App;
require '../vendor/autoload.php';

$app = new App();

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});


$app->post('/hello/{name}', function (Request $request, Response $response) {
    $origin = $_SERVER['HTTP_HOST'];
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $origin");

    return $response;
});
$app->run();