<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Slim\App;
use \functions\functions;
require '../vendor/autoload.php';
require '../src/config/db.php';

$app = new App();

//$app->options('/{routes:.+}', function ($request, $response, $args) {
//    return $response;
//});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
        ->withHeader('Access-Control-Allow-Origin', 'http://localhost:4200')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'POST, DELETE, OPTIONS');
});

$app->post('/users', function (Request $request, Response $response) {
    $id = $request->getParam('id');
    if( $id )$sql = "SELECT * FROM users WHERE id= $id";
    else $sql = "SELECT * FROM users";
    try{
        $db = new db();
        $db = $db->connect();

        $stmt = $db->query( $sql );
        $users = $stmt->fetchAll( PDO::FETCH_OBJ );
        $db = null;
        if( count($users) < 1 ) return  '[{"code": 404, "error": "No results" }]';
        return json_encode( $users );
    }catch ( PDOException $e){
        $err = json_encode( "{asd:'asdasd'}" );

        return '[{ "code": "'.$e->getCode().'", "error": "'.$e->getMessage().'" }]' ;
    }
});


$app->run();