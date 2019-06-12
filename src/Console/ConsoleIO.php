<?php


namespace Doyo\Bridge\CodeCoverage\Console;


interface ConsoleIO
{
    /**
     * Print current report section
     *
     * @param string $section
     * @return void
     */
    public function coverageSection(string $section);

    /**
     * Display info message during coverage
     *
     * @param string $message
     * @return void
     */
    public function coverageInfo(string $message);

    /**
     * Display error during coverage
     *
     * @param   string $message
     * @return  void
     */
    public function coverageError(string $message);
}
