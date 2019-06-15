<?php


namespace Doyo\Bridge\CodeCoverage\Context;


use Behat\Behat\Context\Context;
use Symfony\Component\Process\Process;
use Webmozart\Assert\Assert;

class ConsoleContext implements Context
{
    /**
     * @var string
     */
    private $output;

    public function beforeScenario()
    {
        $this->output = '';
    }

    public function run($commands, $cwd)
    {
        $process = new Process($commands,$cwd);
        $process->run();

        if(0 === $process->getExitCode()){
            $this->output = (string)$process->getOutput();
        }else{
            if(!empty($errorOutput = $process->getErrorOutput())){
                $this->output = $errorOutput;
            }else{
                $this->output = $process->getOutput();
            }

        }
    }

    /**
     * @Then I should see console output :expected
     * @param string $expected
     */
    public function iShouldSeeConsoleOutput($expected)
    {
        Assert::contains($this->output, $expected);
    }
}
