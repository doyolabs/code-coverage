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

namespace Doyo\Bridge\CodeCoverage\Context;

use Behat\Behat\Context\Context;
use Doyo\Bridge\CodeCoverage\Driver\Dummy;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use Webmozart\Assert\Assert;

class CoverageContext implements Context
{
    /**
     * @var CodeCoverage
     */
    private $coverage;

    /**
     * @var string
     */
    private $workingDir;

    public function setWorkingDir($workingDir)
    {
        $this->workingDir = $workingDir;
    }

    /**
     * @Given I read coverage report :file
     *
     * @param string $file
     */
    public function iReadPhpCoverageReport($file)
    {
        $file = $this->workingDir.\DIRECTORY_SEPARATOR.$file;
        Assert::fileExists($file);
        $this->coverage = $this->getCoverage($file);
    }

    /**
     * @Then file :file line :line should be covered
     *
     * @param mixed      $file
     * @param mixed|null $line
     */
    public function fileAtLineShouldCovered($file, $line = null)
    {
        $data = $this->coverage->getData();
        $expectedFile = $this->workingDir.\DIRECTORY_SEPARATOR.$file;

        Assert::true(isset($data[$expectedFile]), 'File '.$file.' is not covered.');
        if (null === $line) {
            return;
        }

        Assert::true(isset($data[$expectedFile][$line]), 'Line: '.$line. ' is not covered');
        Assert::notEmpty($data[$expectedFile][$line]);
    }

    /**
     * @return CodeCoverage
     */
    private function getCoverage($file)
    {
        $coverageFile = $file;
        Assert::fileExists($coverageFile, 'Code coverage is not generated');
        $patchedFile = $coverageFile.'.php';

        $contents = file_get_contents($coverageFile);
        $pattern  = '/^\$coverage\s\=.*/im';
        $contents = preg_replace($pattern, '', $contents);

        file_put_contents($patchedFile, $contents);

        $driver   = new Dummy();
        $coverage = new CodeCoverage($driver);

        include $patchedFile;

        return $coverage;
    }
}
