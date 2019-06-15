<?php

include __DIR__.'/../vendor/autoload.php';

include __DIR__.'/../src/Foo.php';

use Doyo\Bridge\CodeCoverage\Session\LocalSession;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doyo\Behat\CodeCoverage\Resources\fixtures\src\Foo;

LocalSession::startSession('console');
LocalSession::startSession('local');

$response = new JsonResponse([
    'foo' => Foo::say()
]);

$response->send();
