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
use Code\RefactorBundle\Command\RefactoringValidator;
use Symfony\Component\Filesystem\Filesystem;

class Refactor {

    protected $filesystem;
    private $search = array();
    private $replace = array();
    private $directory;
    private $validExtentions = array('php','yml','js','twig');
    private $ignoreFolders = array('/vendor','/web','/app/cache','app/logs','bin','/js/lib');

    public function __construct($search, $replace, $directory = '')
    {
        RefactoringValidator::validatePreRefactoring($search, $replace);

        $this->filesystem = new Filesystem();
        $this->search = $this->getAvailableWords($search);
        $this->replace = $this->getAvailableWords($replace);
        $this->directory = empty($directory) ? dirname(__FILE__) : $directory;
        echo '<h2> current dir : '. $this->directory . '</h2>';
    }

    private function getAvailableWords($word)
    {
        $word = strtolower($word);
        $list = array();
        $list[] = $word . '_';
        $word[0] = strtoupper($word[0]);
        $list[] = $word;
        var_dump($list);
        return $list;

    }

    public function replaceRecursive($dir = '')
    {
        $dir = empty($dir) ? $this->directory : $dir;
        echo $dir;

        if(StringHelper::endsWith($dir,$this->ignoreFolders))
        {
            echo " <h2 style='color:gold'> Ignore $dir </h2>";
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
                        echo "<p style='color:red;'> $path </p>";
                        $newContent = str_replace($this->search, $this->replace, file_get_contents($path));
                        //echo "<p> $newContent </p>";
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
                echo "<h3 style='color:blue'>change name : " . "$filename" . " -> " . basename($newPath) . " </h3>";
                rename($path, $newPath);
            }

        }
    }
}