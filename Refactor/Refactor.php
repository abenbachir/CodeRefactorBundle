<?php
/**
 * Created by JetBrains PhpStorm.
 * User: abder
 * Date: 2013-06-27
 * Time: 11:36 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code\RefactorBundle\Refactor;


use Code\RefactorBundle\Helper\StringHelper;
use Code\RefactorBundle\Validation\BasicValidation;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Output\ConsoleOutput;

abstract class Refactor {

    protected $filesystem;
    private $search = array();
    private $replace = array();
    private $directory;
    private $validExtentions = array('php','yml','xml','js','twig');
    private $ignoreFolders = array('/vendor','/web','/app/cache','app/logs','bin','/js/lib');
    private $output ;

    public function __construct($search, $replace, $directory = '')
    {
        BasicValidation::validatePreRefactoring($search, $replace);

        $this->filesystem = new Filesystem();
        $this->search = $this->getAvailableWords($search);
        $this->replace = $this->getAvailableWords($replace);
        $this->directory = empty($directory) ? dirname(__FILE__) : $directory;
        $this->output = new ConsoleOutput();
        $this->dump("\ncurrent dir :  $this->directory");
    }

    protected function dump($message)
    {
        $this->output->writeln( $message );
    }
    protected function getAvailableWords($word)
    {
        $word = strtolower($word);
        $list = array();
        $list[] = $word . '_';
        $list[] = $word . '.';
        $word[0] = strtoupper($word[0]);
        $list[] = $word;
        return $list;

    }

    public function replaceRecursive($dir = '')
    {
        $dir = empty($dir) ? $this->directory : $dir;
        $this->dump("Scan dir : $dir ");

        if(StringHelper::endsWith($dir,$this->ignoreFolders))
        {
            $this->dump("\tIgnore");
            return 0;
        }

        $files = array_diff( scandir($dir), array('..', '.'));
        foreach($files as $filename)
        {
            $path = $dir .'/' . $filename;
            $ext = pathinfo($filename, PATHINFO_EXTENSION);

            if(file_exists($path) && !empty($ext))
            {
                if(is_writable($path) && in_array($ext,$this->validExtentions))
                {
                    // change file content
                    $content = file_get_contents($path);
                    if(StringHelper::contains($content, $this->search))
                    {
                        $this->dump("\tRefactor content : $path");
                        $newContent = str_replace($this->search, $this->replace, file_get_contents($path));
                        file_put_contents($path, $newContent);
                    }
                }
            }else{
                if(is_dir($path))
                    $this->replaceRecursive($path);
            }

            // rename the file/folder
            $newPath = $dir . '/' . str_replace($this->search, $this->replace, $filename);
            if(strcmp($newPath, $path) != 0 )
            {
                $this->dump("\tRefactor name $filename -> " . basename($newPath));
                rename($path, $newPath);
            }

        }
    }

    public function rename($new)
    {
        // TODO: rename the project
    }

    public function safeDelete()
    {
        // TODO: rename the project
    }

    public function scan()
    {
        // TODO: scan all files
    }

    public function getImpact()
    {
        // TODO: get impact of the refactoring
    }
}