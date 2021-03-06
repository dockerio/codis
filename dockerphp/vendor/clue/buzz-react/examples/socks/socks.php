<?php

use Clue\React\Buzz\Io\Sender;
use React\HttpClient\Client as HttpClient;
use React\EventLoop\Factory as LoopFactory;
use Clue\React\Socks\Client as SocksClient;
use Clue\React\Buzz\Browser;
use Clue\React\Buzz\Message\Response;

require __DIR__ . '/vendor/autoload.php';

$loop = LoopFactory::create();

// create a new SOCKS client which connects to a SOCKS server listening on localhost:9050
// not already running a SOCKS server? Try this: ssh -D 9050 localhost
$socks = new SocksClient($loop, '127.0.0.1', 9050);

// create a Browser object that uses the SOCKS client for connections
$sender = Sender::createFromLoopConnectors($loop, $socks->createConnector());
$browser = new Browser($loop, $sender);

// demo fetching HTTP headers (or bail out otherwise)
$browser->head('https://www.google.com/')->then(function (Response $response) {
    var_dump($response->getHeaders());
}, 'var_dump');

$loop->run();
