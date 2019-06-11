<?php

namespace Test\Doyo\Bridge\CodeCoverage\Environment;

use Doyo\Bridge\CodeCoverage\Driver\Dummy;
use Doyo\Bridge\CodeCoverage\Environment\Runtime;
use PHPUnit\Framework\TestCase;

class RuntimeTest extends TestCase
{
    /**
     * @var Runtime
     */
    private $env;

    public function setUp()
    {
        $this->env = new Runtime();
    }

    /**
     * @dataProvider getTestMixin
     * @param string $method
     * @param string $returnsType
     */
    public function testMixin($method, $returnsType)
    {
        $env = $this->env;
        $this->assertInternalType($returnsType, call_user_func([$env, $method]));
    }

    public function getTestMixin()
    {
        return [
            ['canCollectCodeCoverage', 'bool'],
            ['getDriverClass', 'string'],
            ['isHHVM', 'bool' ],
            ['isPHPDBG', 'bool'],
            ['hasXdebug', 'bool'],
            ['hasPHPDBGCodeCoverage', 'bool']
        ];
    }
}
