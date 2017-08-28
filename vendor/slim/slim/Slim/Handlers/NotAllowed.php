<?php
/**
 * Slim Framework (https://slimframework.com)
 *
 * @link      https://github.com/slimphp/Slim
 * @copyright Copyright (c) 2011-2017 Josh Lockhart
 * @license   https://github.com/slimphp/Slim/blob/3.x/LICENSE.md (MIT License)
 */
namespace Slim\Handlers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Body;
use UnexpectedValueException;

/**
 * Default Slim application not allowed handler
 *
 * It outputs a simple message in either JSON, XML or HTML based on the
 * Accept header.
 */
class NotAllowed extends AbstractHandler
{
    /**
     * Invoke error handler
     *
     * @param  ServerRequestInterface $request  The most recent Request object
     * @param  ResponseInterface      $response The most recent Response object
     * @param  string[]               $methods  Allowed HTTP methods
     *
     * @return ResponseInterface
     * @throws UnexpectedValueException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $methods)
    {
        if ($request->getMethod() === 'OPTIONS') {
            $status = 200;
            $contentType = 'text/plain';
            $output = $this->renderPlainNotAllowedMessage($methods);
        } else {
            $status = 405;
            $contentType = $this->determineContentType($request);
            switch ($contentType) {
                case 'application/json':
                    $output = $this->renderJsonNotAllowedMessage($methods);
                    break;

                case 'text/xml':
                case 'application/xml':
                    $output = $this->renderXmlNotAllowedMessage($methods);
                    break;

                case 'text/html':
                    $output = $this->renderHtmlNotAllowedMessage($methods);
                    break;
                default:
                    throw new UnexpectedValueException('Cannot render unknown content type ' . $contentType);
            }
        }

        $body = new Body(fopen('php://temp', 'r+'));
        $body->write($output);
        $allow = implode(', ', $methods);

        return $response
                ->withStatus($status)
                ->withHeader('Content-type', $contentType)
                ->withHeader('Allow', $allow)
                ->withBody($body);
    }

    /**
     * Render PLAIN not allowed message
     *
     * @param  array                  $methods
     * @return string
     */
    protected function renderPlainNotAllowedMessage($methods)
    {
        $allow = implode(', ', $methods);

        return 'Allowed methods: ' . $allow;
    }

    /**
     * Render JSON not allowed message
     *
     * @param  array                  $methods
     * @return string
     */
    protected function renderJsonNotAllowedMessage($methods)
    {
        $allow = implode(', ', $methods);

        return '{"message":"Method not allowed. Must be one of: ' . $allow . '"}';
    }

    /**
     * Render XML not allowed message
     *
     * @param  array                  $methods
     * @return string
     */
    protected function renderXmlNotAllowedMessage($methods)
    {
        $allow = implode(', ', $methods);

        return "<root><message>Method not allowed. Must be one of: $allow</message></root>";
    }

    /**
     * Render HTML not allowed message
     *
     * @param  array                  $methods
     * @return string
     */
    protected function renderHtmlNotAllowedMessage($methods)
    {
        $allow = implode(', ', $methods);
        $output = <<<END
        
<html>
    <head>
        <title>Secured Rest API</title>
        <style>
            body{
                margin:0;
                padding:10px;
                font:12px/1 Helvetica,Arial,Verdana,sans-serif;
            }
            h1{
                margin:0;
                font-size:34px;
                font-weight:normal;
                line-height:30px;
            }
            div{
            background-color: #d2d2d2;
            text-align: center;
            }
        </style>
    </head>
    <body>
        <div>
            <h1>REST Security Applied</h1>
            <p>Get Method not allowed. Must be one of: <strong>$allow</strong></p>
        </div>
    </body>
</html>
END;

        return $output;
    }
}
