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

namespace Spec\Doyo\Bridge\CodeCoverage\Report;

use SebastianBergmann\CodeCoverage\CodeCoverage;

class TestReportProcessor
{
    private $foo;

    private $hello;

    public function __construct($foo = 'Foo Bar', $hello = 'Hello World')
    {
        $this->foo   = $foo;
        $this->hello = $hello;
    }

    /**
     * @return string
     */
    public function getFoo(): string
    {
        return $this->foo;
    }

    /**
     * @return string
     */
    public function getHello(): string
    {
        return $this->hello;
    }

    public function process(CodeCoverage $coverage, $target)
    {
    }
}
