<?php
/**
 * Created by JetBrains PhpStorm.
 * User: abder
 * Date: 2013-07-04
 * Time: 10:29 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code\RefactorBundle\Tests\Command;

use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Code\RefactorBundle\Tests\BaseTest;

abstract class GenerateCommandTest extends BaseTest
{

    public function setUp()
    {
        $this->projectDir =  sys_get_temp_dir().'/sf2'; //'/var/www/sf2';

        $this->container = $this->getContainer();
        $scanDir = $this->container->get('code_refactor.scan_dir');
        $scanDir->setWorkingDir($this->projectDir);

        $this->filesystem->remove($this->projectDir);

        // get symfony standard edition
        exec("composer create-project symfony/framework-standard-edition $this->projectDir/ 2.3.1 --quiet --no-interaction");
        // configuration
        exec("chmod -R 777 $this->projectDir");
        exec("setfacl -R -m u:www-data:rwx -m u:`whoami`:rwx $this->projectDir/app/cache $this->projectDir/app/logs");
        exec("setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx $this->projectDir/app/cache $this->projectDir/app/logs");


    }


    public function tearDown()
    {
        $this->filesystem->remove($this->projectDir);
    }



}
