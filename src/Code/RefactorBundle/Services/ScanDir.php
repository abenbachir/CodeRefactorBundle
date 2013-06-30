<?php
/**
 * Created by JetBrains PhpStorm.
 * User: abder
 * Date: 2013-06-29
 * Time: 9:10 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code\RefactorBundle\Services;

use Symfony\Component\Filesystem\Filesystem;
use Code\RefactorBundle\Helper\StringHelper;
use Symfony\Component\HttpFoundation\File\File;

class ScanDir
{
    private $workingDir;
    private $filesystem;
    private $extensions = array();
    private $ignorePaths = array();

    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    public function search($pattern)
    {
        return $this->recursiveSearch($pattern, $this->getWorkingDir());
    }

    public function linearSearch($pattern)
    {
        $files = array();
        // linear iteration over the tree
        $dir  = new \RecursiveDirectoryIterator($this->getWorkingDir(), \RecursiveDirectoryIterator::SKIP_DOTS);
        $tree = new \RecursiveIteratorIterator($dir, \RecursiveIteratorIterator::SELF_FIRST);
        foreach ($tree as $filename)
        {
            $file = new File($filename, false);

            if($file->isFile() && $file->isWritable()
                && !StringHelper::startsWith(str_replace($this->getWorkingDir(),'',$file->getPathname()),$this->getIgnorePaths())
                && in_array($file->getExtension(),$this->getExtensions())
                && (StringHelper::contains(file_get_contents($file->getPathname()), $pattern) ||
                StringHelper::contains($file->getFilename(), $pattern))
            )
            {
                $files[$file->getPathname()] = $file;
            }else if($file->isDir()
                && !StringHelper::startsWith(str_replace($this->getWorkingDir(),'',$file->getPathname()),$this->getIgnorePaths()) &&
                StringHelper::contains($file->getFilename(), $pattern))
            {

                $files[$file->getPathname()] = $file;
            }
        }
        return $files;
    }

    public function recursiveSearch($pattern, $dir = '')
    {
        $results = array();
        $files = array_diff( scandir($dir), array('..', '.'));
        foreach($files as $filename)
        {
            $file = new File($dir .'/' . $filename, false);
            if($file->isFile() && $file->isWritable()
                && in_array($file->getExtension(),$this->getExtensions())
                && ( StringHelper::contains(file_get_contents($file->getPathname()), $pattern)
                    || StringHelper::contains($file->getFilename(), $pattern) )
                )
            {
                $results[$file->getPathname()] = $file;
            }else if($file->isDir() && !StringHelper::endsWith($file->getPathname(),$this->getIgnorePaths()))
            {
                if(StringHelper::contains($file->getFilename(), $pattern))
                    $results[$file->getPathname()] = $file;
                $results = array_merge($results, $this->recursiveSearch($pattern, $file->getPathname()));
            }

        }
        return $results;
    }


    /**
     * @param array $extensions
     */
    public function setExtensions($extensions)
    {
        $this->extensions = $extensions;
    }

    /**
     * @return array
     */
    public function getExtensions()
    {
        return $this->extensions;
    }
    /**
     * @param \Symfony\Component\Filesystem\Filesystem $filesystem
     */
    public function setFilesystem($filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @return \Symfony\Component\Filesystem\Filesystem
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * @param array $ignorePaths
     */
    public function setIgnorePaths($ignorePaths)
    {
        $this->ignorePaths = $ignorePaths;
    }

    /**
     * @return array
     */
    public function getIgnorePaths()
    {
        return $this->ignorePaths;
    }

    /**
     * @param mixed $workingDir
     */
    public function setWorkingDir($workingDir)
    {
        $this->workingDir = $workingDir;
    }

    /**
     * @return mixed
     */
    public function getWorkingDir()
    {
        return $this->workingDir;
    }


}