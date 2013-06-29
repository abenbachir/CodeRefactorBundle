<?php
/**
 * Created by JetBrains PhpStorm.
 * User: abder
 * Date: 2013-06-28
 * Time: 9:25 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code\RefactorBundle\Tests\Refactor;

use Code\RefactorBundle\Refactor\Refactor;
use Symfony\Component\Filesystem\Filesystem;

class RefactorTest extends \PHPUnit_Framework_TestCase {

    protected $filesystem;
    protected $tmpDir;
    protected $projectDir;

    public function setUp()
    {
        $this->tmpDir = '/var/www/tmp'; //sys_get_temp_dir().'/sf2';
        $this->projectDir = $this->tmpDir.'/sf2';
        $this->filesystem = new Filesystem();
        $this->filesystem->remove($this->tmpDir);
        // get symfony standard edition
        echo exec("composer create-project symfony/framework-standard-edition $this->projectDir/ 2.3.1 --quiet --no-interaction");
        // configuration
        echo exec("setfacl -R -m u:www-data:rwx -m u:`whoami`:rwx $this->projectDir/app/cache $this->projectDir/app/logs");
        echo exec("setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx $this->projectDir/app/cache $this->projectDir/app/logs");

    }

    public function tearDown()
    {
        //$this->filesystem->remove($this->tmpDir);
    }

    public function testRefactor1()
    {
        $search = 'Acme';
        $replace = 'Application';
        $refactor = new Refactor($search, $replace, $this->projectDir);
        //$refactor->replaceRecursive();
        $this->assertTrue(true);
    }


}
