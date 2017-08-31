<?php
/**
 * Slim Framework (https://slimframework.com)
 *
 * @link      https://github.com/slimphp/Slim
 * @copyright Copyright (c) 2011-2017 Josh Lockhart
 * @license   https://github.com/slimphp/Slim/blob/3.x/LICENSE.md (MIT License)
 */
namespace Slim;
use Slim\App;


class Users{
    public function register(){
        $app = new App();
        $app->options('/{routes:.+}', function ($request, $response, $args) {
            return $response;
        });
        $app->post('/hello/{name}', function (Request $request, Response $response) {
            $origin = $_SERVER['HTTP_HOST'];
            $name = $request->getAttribute('name');
            $response->getBody()->write("Hello, $name");

            return $response;
        });
    }
}