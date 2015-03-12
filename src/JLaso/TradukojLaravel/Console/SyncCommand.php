<?php

namespace JLaso\TradukojLaravel\Console;

use Illuminate\Console\Command;
use JLaso\TradukojConnector\ClientSocketApi;
use JLaso\TradukojConnector\Model\Loader\ArrayLoader;
use JLaso\TradukojConnector\Output\ConsoleOutput;
use JLaso\TradukojConnector\PostClient\PostCurl;
use JLaso\TradukojConnector\Socket\Socket;
use JLaso\TradukojLaravel\Adapter\ConsoleOutputAdapter;
use JLaso\TradukojLaravel\Manager\Manager;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class ImportCommand
 * @package JLaso\TradukojLaravel\Console
 */
class SyncCommand extends Command
{
    protected $name = 'tradukoj:sync';
    protected $description = 'Synchronization of translations with Tradukoj server.';
    protected $debugStatus = false;
    /** @var  Manager */
    protected $manager;

    function __construct(Manager $manager)
    {
        parent::__construct();

        $this->manager = $manager;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $config = ArrayLoader::load(config('tradukoj'));

        $this->debugStatus = $this->option('debug');
        $output = $this->getOutput();

        $clientSocketApi = new ClientSocketApi(
            $config,
            new Socket(),
            new PostCurl(),
            new ConsoleOutput(), //new ConsoleOutputAdapter($output),
            $this->debugStatus
        );

        $this->info("Connecting with Tradukoj ...");

        $clientSocketApi->init( 'localhost', 10999 );

        if($this->option('upload-first')){

            $catalogs = $this->manager->getCatalogs();

            foreach($catalogs as $catalog){

                $output->writeln(PHP_EOL . sprintf('<info>Uploading catalog %s ...</info>', $catalog));

                $data = $this->manager->getTranslations($catalog);
                $this->output->writeln('uploadKeys("' . $catalog . '", $data)');
                $clientSocketApi->uploadKeys($catalog, $data);
            }

            $this->info('Done!');

            $clientSocketApi->shutdown();

            return;

        }

        // normal synchronization (not upload-first)

        $catalogs = $clientSocketApi->getCatalogIndex();

        foreach($catalogs as $catalog){

            $buffer = array();

            $output->writeln(PHP_EOL . sprintf('Downloading catalog "%s" ...', $catalog));

            $translations = $clientSocketApi->downloadKeys($catalog);

            foreach ($translations['data'] as $key=>$transInfo){
                //$output->writeln(PHP_EOL . sprintf("\tprocessing key '%s'", $key));

                foreach($transInfo as $locale=>$data){

                    $buffer[$locale][$key] = $data['message'];

                }
            }

            var_dump($buffer);

            //$data = $this->manager->getTranslations($catalog);
            //$this->output->writeln('uploadKeys("' . $catalog . '", $data)');
            //$clientSocketApi->uploadKeys($catalog, $data);
        }

        $clientSocketApi->shutdown();

        $this->info('Done!');

    }

    protected function debug()
    {
        if($this->debugStatus){
            $msg = call_user_func_array('sprintf', func_get_args());
            $this->info($msg);
        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('debug', null, InputOption::VALUE_NONE, 'to help debugging, print some useful info.', null),
            array('upload-first', null, InputOption::VALUE_NONE, 'to upload the local translations to server, useful for the first time.', null),
        );
    }


}
