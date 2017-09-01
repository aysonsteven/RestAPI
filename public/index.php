<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Slim\App;
require '../vendor/autoload.php';
require '../src/config/db.php';

$app = new App();

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
        ->withHeader('Access-Control-Allow-Origin', 'http://localhost:4200')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});


$app->post('/hello/{name}', function (Request $request, Response $response) {
    $sql = "SELECT * FROM users";

    try{
        $db = new db();
        $db = $db->connect();

        $stmt = $db->query( $sql );
        $users = $stmt->fetchAll( PDO::FETCH_OBJ );
        $db = null;

        echo json_encode( $users );
    }catch ( PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});
$app->run();