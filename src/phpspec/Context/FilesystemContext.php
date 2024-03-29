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

namespace Doyo\PhpSpec\CodeCoverage\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\ScenarioScope;
use Behat\Gherkin\Node\PyStringNode;
use Doyo\Bridge\CodeCoverage\Context\CoverageContext;
use PHPUnit\Framework\Assert;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Defines application features from the specific context.
 */
class FilesystemContext implements Context
{
    /**
     * @var string
     */
    private $workingDirectory;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var CoverageContext
     */
    private $coverageContext;

    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    /**
     * @beforeScenario
     */
    public function prepWorkingDirectory(ScenarioScope $scope)
    {
        $this->coverageContext = $scope->getEnvironment()->getContext(CoverageContext::class);

        $dir = sys_get_temp_dir().'/doyo/tests';
        if(!is_dir($dir)){
            mkdir($dir,0775,true);
        }
        $this->workingDirectory = tempnam($dir, 'phpspec-behat');
        $this->filesystem->remove($this->workingDirectory);
        $this->filesystem->mkdir($this->workingDirectory);
        chdir($this->workingDirectory);

        $fakeHomeDirectory = sprintf('%s/fake-home/', $this->workingDirectory);
        $this->filesystem->mkdir($fakeHomeDirectory.'.phpspec');

        if (!empty($_SERVER['HOMEDRIVE']) && !empty($_SERVER['HOMEPATH'])) {
            $_SERVER['HOMEPATH'] = substr($fakeHomeDirectory, 2);
        } else {
            putenv(sprintf('HOME=%s', $fakeHomeDirectory));
        }

        $this->filesystem->mkdir($this->workingDirectory.'/vendor');
        $this->filesystem->copy(
            __DIR__.'/../Resources/autoloader/autoload.php',
            $this->workingDirectory.'/vendor/autoload.php'
        );
    }

    /**
     * @Given I read phpspec coverage report :file
     *
     * @param string $file
     */
    public function iReadPhpspecCoverageReport($file)
    {
        $context = $this->coverageContext;
        $context->setWorkingDir(getcwd());
        $context->iReadPhpCoverageReport($file);
    }

    /**
     * @afterScenario
     */
    public function removeWorkingDirectory()
    {
        try {
            $this->filesystem->remove($this->workingDirectory);
        } catch (IOException $e) {
            //ignoring exception
        }
    }

    /**
     * @Given I have a custom :template template that contains:
     *
     * @param mixed $template
     */
    public function iHaveACustomTemplateThatContains($template, PyStringNode $contents)
    {
        $this->filesystem->dumpFile(sprintf('fake-home/.phpspec/%s.tpl', $template), $contents);
    }

    /**
     * @Given the bootstrap file :file contains:
     *
     * @param mixed $file
     */
    public function theFileContains($file, PyStringNode $contents)
    {
        $this->filesystem->dumpFile($file, (string) $contents);
    }

    /**
     * @Given the class file :file contains:
     * @Given the trait file :file contains:
     *
     * @param mixed $file
     */
    public function theClassOrTraitFileContains($file, PyStringNode $contents)
    {
        $this->theFileContains($file, $contents);
        require_once $file;
    }

    /**
     * @Given the spec file :file contains:
     *
     * @param mixed $file
     */
    public function theSpecFileContains($file, PyStringNode $contents)
    {
        $this->theFileContains($file, $contents);
    }

    /**
     * @Given the config file contains:
     */
    public function theConfigFileContains(PyStringNode $contents)
    {
        $this->theFileContains('phpspec.yml', $contents);
    }

    /**
     * @Given there is no file :file
     *
     * @param mixed $file
     */
    public function thereIsNoFile($file)
    {
        if (file_exists($file)) {
            throw new \Exception(sprintf(
                "File unexpectedly exists at path '%s'",
                $file
            ));
        }
    }

    /**
     * @Then the class in :file should contain:
     * @Then a new class/spec should be generated in the :file:
     *
     * @param mixed $file
     */
    public function theFileShouldContain($file, PyStringNode $contents)
    {
        if (!file_exists($file)) {
            throw new \Exception(sprintf(
                "File did not exist at path '%s'",
                $file
            ));
        }

        $expectedContents = (string) $contents;
        if ($expectedContents !== file_get_contents($file)) {
            throw new \Exception(sprintf(
                "File at '%s' did not contain expected contents.\nExpected: '%s'\nActual: '%s'",
                $file,
                $expectedContents,
                file_get_contents($file)
            ));
        }
    }

    /**
     * @Given the config file located in :folder contains:
     *
     * @param mixed $folder
     */
    public function theConfigFileInFolderContains($folder, PyStringNode $contents)
    {
        $this->theFileContains($folder.\DIRECTORY_SEPARATOR.'phpspec.yml', $contents);
    }

    /**
     * @Given I have not configured an autoloader
     */
    public function iHaveNotConfiguredAnAutoloader()
    {
        $this->filesystem->remove($this->workingDirectory.'/vendor/autoload.php');
    }

    /**
     * @Given there should be no file :path
     *
     * @param mixed $path
     */
    public function thereShouldBeNoFile($path)
    {
        Assert::assertFileNotExists($path);
    }
}
