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

abstract class GenerateCommandTest extends \PHPUnit_Framework_TestCase
{
    protected $filesystem;
    protected $tmpDir;
    protected $projectDir;

    public function setUp()
    {
        $this->tmpDir = sys_get_temp_dir().'/sf2'; //'/var/www/sf2';
        $this->projectDir = $this->tmpDir;
        $this->filesystem = new Filesystem();
        $this->filesystem->remove($this->tmpDir);
        /*
        // get symfony standard edition
        exec("composer create-project symfony/framework-standard-edition $this->projectDir/ 2.3.1 --quiet --no-interaction");
        // configuration
        exec("setfacl -R -m u:www-data:rwx -m u:`whoami`:rwx $this->projectDir/app/cache $this->projectDir/app/logs");
        exec("setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx $this->projectDir/app/cache $this->projectDir/app/logs");
*/
    }
    public function tearDown()
    {
        $this->filesystem->remove($this->tmpDir);
    }

    protected function getHelperSet($input)
    {
        $dialog = new DialogHelper();
        $dialog->setInputStream($this->getInputStream($input));

        return new HelperSet(array(new FormatterHelper(), $dialog));
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

    protected function getInputStream($input)
    {
        $stream = fopen('php://memory', 'r+', false);
        fputs($stream, $input.str_repeat("\n", 10));
        rewind($stream);

        return $stream;
    }

    protected function getContainer()
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

        $filesystem = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        $filesystem
            ->expects($this->any())
            ->method('isAbsolutePath')
            ->will($this->returnValue(true))
        ;

        $container = new Container();
        $container->set('kernel', $kernel);
        $container->set('filesystem', $filesystem);

        $container->setParameter('kernel.root_dir', $this->projectDir.'/app');
        $container->setParameter('code_refactor.working_dir', $this->projectDir);

        return $container;
    }
}
