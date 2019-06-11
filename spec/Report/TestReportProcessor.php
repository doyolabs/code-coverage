<?php


namespace spec\Doyo\Bridge\CodeCoverage\Report;

use SebastianBergmann\CodeCoverage\CodeCoverage;

class TestReportProcessor
{
    private $foo;

    private $hello;

    public function __construct($foo = 'Foo Bar', $hello = 'Hello World')
    {
        $this->foo = $foo;
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
