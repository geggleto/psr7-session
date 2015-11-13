<?php
use Slim\Http\Environment;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\RequestBody;
use Slim\Http\UploadedFile;
use Slim\Http\Uri;

/**
 * Created by PhpStorm.
 * User: Glenn
 * Date: 2015-11-13
 * Time: 2:08 PM
 */
class SessionMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    public function requestFactory($queryString = '')
    {
        $env = Environment::mock();
        $uri = Uri::createFromString('https://example.com:443/foo/bar'.$queryString);
        $headers = Headers::createFromEnvironment($env);
        $cookies = ['user' => 'john',
                    'id'   => '123',];
        $serverParams = $env->all();
        $body = new RequestBody();
        $uploadedFiles = UploadedFile::createFromEnvironment($env);
        $request = new Request('GET',
            $uri,
            $headers,
            $cookies,
            $serverParams,
            $body,
            $uploadedFiles);

        return $request;

    }

    public function testSessionMiddleware() {
        $sessionMiddleware = new \Geggleto\Middleware\SessionMiddleware();
        $_SESSION = [
            'a' => 'b'
        ];
        $request = $this->requestFactory();
        $response = $sessionMiddleware($request, new \Slim\Http\Response(),
            function (\Psr\Http\Message\ServerRequestInterface $req, $res) {
            $this->assertEquals('b', $req->getAttribute('a'));

           return $res;
        });
    }

}
