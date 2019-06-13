<?php


namespace Doyo\Bridge\CodeCoverage\Http;

use Psr\Http\Message\ResponseInterface;

interface ClientInterface
{
    public function request($method, $uri, array $options = []): ResponseInterface;
}