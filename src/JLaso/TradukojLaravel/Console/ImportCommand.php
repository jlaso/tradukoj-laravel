<?php

namespace JLaso\TradukojLaravel\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class ImportCommand
 * @package JLaso\TradukojLaravel\Console
 */
class ImportCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'tradukoj:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import translations from the PHP sources';


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {


    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {

    }


}
