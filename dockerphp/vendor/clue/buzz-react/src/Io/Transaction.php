<?php

namespace Clue\React\Buzz\Io;

use Clue\React\Buzz\Message\Request;
use Clue\React\Buzz\Message\Response;
use Exception;
use Clue\React\Buzz\Browser;
use React\HttpClient\Client as HttpClient;
use Clue\React\Buzz\Io\Sender;
use Clue\React\Buzz\Message\ResponseException;

class Transaction
{
    private $browser;
    private $request;

    private $numRequests = 0;

    // context: http.follow_location
    private $followRedirects = true;

    // context: http.max_redirects
    private $maxRedirects = 10;

    // context: http.ignore_errors
    private $obeySuccessCode = true;

    public function __construct(Request $request, Sender $sender, array $options = array())
    {
        foreach ($options as $name => $value) {
            if (property_exists($this, $name)) {
                $this->$name = $value;
            }
        }

        $this->request = $request;
        $this->sender = $sender;
    }

    public function send()
    {
        return $this->next($this->request);
    }

    protected function next(Request $request)
    {
        $this->progress('request', array($request));

        $that = $this;
        ++$this->numRequests;

        return $this->sender->send($request)->then(
            function (Response $response) use ($request, $that) {
                return $that->onResponse($response, $request);
            },
            function ($error) use ($request, $that) {
                return $that->onError($error, $request);
            }
        );
    }

    public function onResponse(Response $response, Request $request)
    {
        $this->progress('response', array($response, $request));

        if ($this->followRedirects && ($response->getCode() >= 300 && $response->getCode() < 400)) {
            return $this->onResponseRedirect($response, $request);
        }

        // only status codes 200-399 are considered to be valid, reject otherwise
        if ($this->obeySuccessCode && ($response->getCode() < 200 || $response->getCode() >= 400)) {
            throw new ResponseException($response);
        }

        // resolve our initial promise
        return $response;
    }

    public function onError(Exception $error, Request $request)
    {
        $this->progress('error', array($error, $request));

        throw $error;
    }

    private function onResponseRedirect(Response $response, Request $request)
    {
        $location = $request->getUri()->resolve($response->getHeader('Location'));

        // naïve approach..
        $method = ($request->getMethod() === 'HEAD') ? 'HEAD' : 'GET';
        $request = new Request($method, $location);

        $this->progress('redirect', array($request));

        if ($this->numRequests >= $this->maxRedirects) {
            throw new \RuntimeException('Maximum number of redirects (' . $this->maxRedirects . ') exceeded');
        }

        return $this->next($request);
    }

    private function progress($name, array $args = array())
    {
        return;

        echo $name;

        foreach ($args as $arg) {
            echo ' ';
            if ($arg instanceof Response) {
                echo $arg->getStatusLine();
            } elseif ($arg instanceof Request) {
                echo $arg->getRequestLine();
            } else {
                echo $arg;
            }
        }

        echo PHP_EOL;
    }
}
