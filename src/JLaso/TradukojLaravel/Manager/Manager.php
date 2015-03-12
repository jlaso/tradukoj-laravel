<?php

namespace JLaso\TradukojLaravel\Manager;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Symfony\Component\Finder\Finder;

/**
 * @author Joseluis Laso <jlaso@joseluislaso.es>
 */
class Manager
{
    /** @var \Illuminate\Foundation\Application  */
    protected $app;
    /** @var \Illuminate\Filesystem\Filesystem  */
    protected $filesystem;

    public function __construct(Application $app, Filesystem $filesystem)
    {
        $this->app = $app;
        $this->filesystem = $filesystem;
    }

    protected function getLangDir()
    {
        $rootDir = dirname($this->app->make('path'));

        return $rootDir.'/resources/lang';
    }

    /**
     * @return array
     */
    public function getCatalogs()
    {
        $result = array();
        foreach($this->filesystem->directories($this->getLangDir()) as $langPath){
            foreach($this->filesystem->files($langPath) as $file){
                $catalog = str_replace($langPath.'/', '', $file);
                $catalog = preg_replace('/\.php$/', '', $catalog);
                $result[$catalog] = $catalog;
            }
        }

        return array_keys($result);
    }


    public function getTranslations($catalog)
    {
        $data = array();
        $rootDir = dirname($this->app->make('path'));
        foreach($this->filesystem->directories($rootDir.'/resources/lang') as $langPath){
            $locale = basename($langPath);
            $fileName = $langPath.'/'.$catalog.'.php';
            $date = date_create_from_format('U',filemtime($fileName));
            $translations = array_dot(\Lang::getLoader()->load($locale, $catalog));
            foreach($translations as $key => $value){
                $fileName = str_replace($rootDir, "", $fileName);
                $data[$key][$locale] = array(
                    'message'   => $value,
                    'updatedAt' => $date->format('c'),
                    'fileName'  => $fileName,
                    'bundle'    => $catalog,
                );
            }
        }

        return $data;
    }

    /**
     * @param $catalog
     * @param $locale
     * @param $remoteTranslations
     */
    public function integrateTranslations($catalog, $locale, $remoteTranslations)
    {
        $rootDir = dirname($this->app->make('path'));
        $file = sprintf("%s/resources/lang/%s/%s.php", $rootDir, $locale, $catalog);

        $localeTranslations = array_dot(\Lang::getLoader()->load($locale, $catalog));

        $translations = array_merge(
            $localeTranslations,
            $remoteTranslations
        );

        $this->dump($file, $translations);
    }

    /**
     * @param $file
     * @param $data
     */
    public function dump($file, $data)
    {
        $content = "<?php\n\nreturn array(\n";

        foreach($data as $key=>$value){
            $content .= sprintf("\t\"%s\" \t=> \"%s\"", $key, $value);
        }

        $content .= "\n);\n";

        file_put_contents($file, $content);
    }

}
