<?php
/**
 * Created by JetBrains PhpStorm.
 * User: abder
 * Date: 2013-06-30
 * Time: 12:13 AM
 * To change this template use File | Settings | File Templates.
 */
namespace Code\RefactorBundle\Tests\Services;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ScanDirTest extends WebTestCase {

    public function testRecursiveSearch()
    {
        $client = static::createClient();
        $scanDir = $client->getContainer()->get('code_refactor.scan_dir');

        $resultsRecursive = $scanDir->search('Sensio');
        $resultsLinear = $scanDir->linearSearch('Sensio');
        $this->assertEquals(count($resultsRecursive),count($resultsLinear));
        foreach($resultsRecursive as $key => $file)
        {
            $this->assertArrayHasKey($key,$resultsLinear);
        }
        $this->assertEmpty(array_diff($resultsRecursive,$resultsLinear));
    }

}
