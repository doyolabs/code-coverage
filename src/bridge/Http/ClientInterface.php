<?php

/*
 * This file is part of the doyo/code-coverage project.
 *
 * (c) Anthonius Munthi <https://itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Doyo\Bridge\CodeCoverage\Http;

use Psr\Http\Message\ResponseInterface;

interface ClientInterface
{
    public function request($method, $uri, array $options = []): ResponseInterface;
}
