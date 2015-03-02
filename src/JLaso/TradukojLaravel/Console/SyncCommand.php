<?php

namespace JLaso\TradukojLaravel\Console;

use Illuminate\Console\Command;
use JLaso\TradukojConnector\ClientSocketApi;
use JLaso\TradukojConnector\Model\Loader\ArrayLoader;
use JLaso\TradukojConnector\Output\ConsoleOutput;
use JLaso\TradukojConnector\PostClient\PostCurl;
use JLaso\TradukojConnector\Socket\Socket;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class ImportCommand
 * @package JLaso\TradukojLaravel\Console
 */
class SyncCommand extends Command
{
    protected $name = 'tradukoj:sync';
    protected $description = 'Synchronization of translations with Tradukoj server.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $loader = new ArrayLoader();
        $config = $loader->load(config('tradukoj'));

        $debug = $this->option('debug');

        $clientSocketApi = new ClientSocketApi($config, new Socket(), new PostCurl(), new ConsoleOutput(), $debug);
        $clientSocketApi->init();

        $bundles = $clientSocketApi->getBundleIndex();

        var_dump($bundles);

    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('debug', null, InputOption::VALUE_NONE, 'to debug.', null),
        );
    }


}
