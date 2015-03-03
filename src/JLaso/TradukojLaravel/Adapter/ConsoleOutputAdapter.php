<?php


namespace JLaso\TradukojLaravel\Adapter;

use JLaso\TradukojConnector\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\ConsoleOutputInterface;

class ConsoleOutputAdapter implements OutputInterface
{
    /**
     * @var ConsoleOutput
     */
    protected $laravelConsoleOutput;

    function __construct(ConsoleOutputInterface $consoleOutput)
    {
        $this->laravelConsoleOutput = $consoleOutput;
    }

    public function write($sprintf)
    {
        $message = call_user_func_array("sprintf", func_get_args());
        $this->laravelConsoleOutput->write($message);
    }

    public function writeln($sprintf)
    {
        $arguments = func_get_args();
        $arguments[0] .= PHP_EOL;
        $message = call_user_func_array("sprintf", $arguments);
        $this->laravelConsoleOutput->write($message);
    }


}