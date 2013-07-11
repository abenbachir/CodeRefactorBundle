<?php
/**
 * Created by JetBrains PhpStorm.
 * User: abder
 * Date: 2013-06-30
 * Time: 12:13 AM
 * To change this template use File | Settings | File Templates.
 */
namespace Code\RefactorBundle\Tests\Services;

use Code\RefactorBundle\Tests\BaseTest;

class ScanDirTest extends BaseTest
{

    public function testRecursiveSearch()
    {
        $this->projectDir = dirname(dirname(__DIR__)).'/vendor';
        $container = $this->getContainer();
        $scanDir = $container->get('code_refactor.scan_dir');
        $scanDir->setWorkingDir($this->projectDir);
        $resultsRecursive = $scanDir->search('Symfony');
        $resultsLinear = $scanDir->linearSearch('Symfony');
        $this->assertEquals(count($resultsRecursive),count($resultsLinear));
        foreach($resultsRecursive as $key => $file)
        {
            $this->assertArrayHasKey($key,$resultsLinear);
        }
        $this->assertEmpty(array_diff($resultsRecursive,$resultsLinear));
        
    }

}
