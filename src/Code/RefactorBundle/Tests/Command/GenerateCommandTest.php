<?php
/**
 * Created by JetBrains PhpStorm.
 * User: abder
 * Date: 2013-07-04
 * Time: 10:29 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code\RefactorBundle\Command;

use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\DependencyInjection\Container;
use Code\RefactorBundle\Helper\DialogHelper;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Code\RefactorBundle\Command\RenameProjectCommand;

abstract class GenerateCommandTest extends WebTestCase
{
    protected $filesystem;
    protected $tmpDir;
    protected $projectDir;
    protected $container;
    protected $dialog;



    public function setUp()
    {
        $this->tmpDir = '/var/www/sf2';//sys_get_temp_dir().'/sf2';
        $this->projectDir = $this->tmpDir;
        $this->filesystem = new Filesystem();

        $client = static::createClient();

        $this->container = $client->getContainer();
        $scanDir = $this->container->get('code_refactor.scan_dir');
        $scanDir->setWorkingDir($this->projectDir);
/*
        $this->filesystem->remove($this->tmpDir);

        // get symfony standard edition
        exec("composer create-project symfony/framework-standard-edition $this->projectDir/ 2.3.1 --quiet --no-interaction");
        // configuration
        exec("setfacl -R -m u:www-data:rwx -m u:`whoami`:rwx $this->projectDir/app/cache $this->projectDir/app/logs");
        exec("setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx $this->projectDir/app/cache $this->projectDir/app/logs");
*/
    }

    protected function createContainer()
    {
        $kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        $kernel
            ->expects($this->any())
            ->method('getBundle')
            ->will($this->returnValue($this->getBundle()))
        ;
        $kernel
            ->expects($this->any())
            ->method('getBundles')
            ->will($this->returnValue(array($this->getBundle())))
        ;


        $scanDir = $this->getMock('Code\RefactorBundle\Services\ScanDir');
        /*$scanDir
            ->expects($this->any())
            ->method('isAbsolutePath')
            ->will($this->returnValue(true))
        ;*/

        $this->container = new Container();
        $this->container->set('kernel', $kernel);
        $this->container->set('filesystem', $this->filesystem);
        //$this->container->set('code_refactor.scan_dir', $scanDir);

        $this->container->setParameter('kernel.root_dir', $this->projectDir.'/app');
        $this->container->setParameter('code_refactor.working_dir', $this->projectDir);

        return $this->container;
    }

    public function tearDown()
    {
        //$this->filesystem->remove($this->tmpDir);
    }

    protected function getHelperSet($input)
    {
        $this->dialog = new DialogHelper();
        $this->dialog->setInputStream($this->getInputStream($input));

        return new HelperSet(array(new FormatterHelper(), $this->dialog));
    }

    protected function getBundle()
    {
        $bundle = $this->getMock('Symfony\Component\HttpKernel\Bundle\BundleInterface');
        $bundle
            ->expects($this->any())
            ->method('getPath')
            ->will($this->returnValue(sys_get_temp_dir()))
        ;

        return $bundle;
    }
    /**
     * @return mixed
     */
    public function getDialog()
    {
        return $this->dialog;
    }
    protected function getInputStream($input)
    {
        $stream = fopen('php://memory', 'r+', false);
        fputs($stream, $input.str_repeat("\n", 10));
        rewind($stream);

        return $stream;
    }

    protected function getContainer()
    {
        return $this->container;
    }


}
