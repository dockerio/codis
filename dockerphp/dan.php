<?php

require_once "vendor/autoload.php";

$loop = React\EventLoop\Factory::create();

$factory = new Clue\React\Docker\Factory($loop);

$client = $factory->createClient();

$client->imageSearch('php')->then(function ($images) {
    var_dump($images);
});

$loop->run();

//var_dump($client);
