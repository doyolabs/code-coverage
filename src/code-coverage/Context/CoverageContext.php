<?php

/*
 * This file is part of the doyo/code-coverage project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

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
     *
     * @param string $file
     */
    public function iReadPhpCoverageReport($file)
    {
        $file = getcwd().\DIRECTORY_SEPARATOR.$file;

        /** @var CodeCoverage $coverage */
        include $file;

        $this->coverage = $coverage;
    }

    /**
     * @Then file :file line :line should covered
     *
     * @param mixed      $file
     * @param mixed|null $line
     */
    public function fileAtLineShouldCovered($file, $line = null)
    {
        $data = $this->coverage->getData();
        $file = getcwd().\DIRECTORY_SEPARATOR.$file;

        Assert::true(isset($data[$file]));
        if (null === $line) {
            return;
        }

        Assert::true(isset($data[$file][$line]));
        Assert::notEmpty($data[$file][$line]);
    }
}
