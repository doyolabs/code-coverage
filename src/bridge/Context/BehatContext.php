<?php


namespace Doyo\Bridge\CodeCoverage\Context;


use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\PyStringNode;
use Symfony\Component\Config\Resource\DirectoryResource;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

class BehatContext implements Context
{
    private $configFile;

    /**
     * @var string
     */
    private $cwd;

    /**
     * @var ConsoleContext
     */
    private $consoleContext;

    public function __construct(
        $cwd = false
    )
    {
        $cwd = $cwd ?:getcwd();
        $this->cwd = $cwd;
    }

    /**
     * @beforeScenario
     */
    public function beforeScenario(BeforeScenarioScope $scope)
    {
        $this->consoleContext = $scope->getEnvironment()->getContext(ConsoleContext::class);
    }

    /**
     * @Given I configure behat with:
     */
    public function iConfigureBehat(PyStringNode $node)
    {
        $contents = $node->getRaw();
        $id = md5($contents);
        $tmp = sys_get_temp_dir().'/doyo/tests/behat/behat-'.$id.'.yaml';
        if(!is_dir($dir = dirname($tmp))){
            mkdir($dir, 0775, true);
        }
        file_put_contents($tmp, $contents);

        $this->configFile = $tmp;
    }

    /**
     * @Given I run behat
     *
     * @param array $options
     * @param string $cwd
     */
    public function iRunBehat(array $options = [], $cwd = null)
    {
        $finder = new ExecutableFinder();
        $phpdbg = $finder->find('phpdbg');
        $cmd = realpath(__DIR__.'/../Resources/fixtures/bin/behat');
        $configFile = $this->configFile;
        $cwd = realpath($this->cwd);

        $commands = [
            $phpdbg,
            '-qrr',
            $cmd,
            '--config='.$configFile,
            '--coverage'
        ];

        $commands = array_merge($commands, $options);
        $this->consoleContext->run($commands, $cwd);
    }
}
