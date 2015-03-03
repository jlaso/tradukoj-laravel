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
}
