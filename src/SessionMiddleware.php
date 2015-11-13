<?php
/**
 * Created by PhpStorm.
 * User: Glenn
 * Date: 2015-11-13
 * Time: 2:04 PM
 */

namespace Geggleto\Middleware;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class SessionMiddleware
{
    public function __construct ($name = 'default')
    {
        if (!isset($_SESSION)) {
            session_name($name);
            session_start();
        }
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $requestInterface
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param callable                                 $next
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $requestInterface,
        ResponseInterface $response,
        callable $next) {

        foreach ($_SESSION as $k => $value) {
            $requestInterface = $requestInterface->withAttribute($k, $value);
        }

        return $next($requestInterface, $response);
    }
}