<?php


namespace Doyo\Bridge\CodeCoverage\Context;


use Behat\Behat\Context\Context;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use Webmozart\Assert\Assert;

class CoverageContext implements Context
{
    /**
     * @var CodeCoverage
     */
    private $coverage;

    /**
     * @Given I read coverage report :file
     * @param string $file
     */
    public function iReadPhpCoverageReport($file)
    {
        $file = getcwd().DIRECTORY_SEPARATOR.$file;

        /* @var CodeCoverage $coverage */

        include $file;

        $this->coverage = $coverage;
    }

    /**
     * @Then file :file line :line should covered
     */
    public function fileAtLineShouldCovered($file, $line = null)
    {
        $data = $this->coverage->getData();
        $file = getcwd().DIRECTORY_SEPARATOR.$file;

        Assert::true(isset($data[$file]));
        if(is_null($line)){
            return;
        }

        Assert::true(isset($data[$file][$line]));
        Assert::notEmpty($data[$file][$line]);
    }
}
