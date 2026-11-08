<?php

require __DIR__.'/../vendor/autoload.php';

if (getenv('APP_KEY') === false || getenv('APP_KEY') === '') {
    $key = 'base64:'.base64_encode(random_bytes(32));

    putenv('APP_KEY='.$key);
    $_ENV['APP_KEY'] = $key;
    $_SERVER['APP_KEY'] = $key;
}
