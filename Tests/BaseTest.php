<?php
/**
 * Created by JetBrains PhpStorm.
 * User: abder
 * Date: 2013-07-04
 * Time: 10:29 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code\RefactorBundle\Tests;

use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\DependencyInjection\Container;
use Code\RefactorBundle\Helper\DialogHelper;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Code\RefactorBundle\Services\ScanDir;

abstract class BaseTest extends WebTestCase
{
    protected $filesystem;
    protected $container;
    protected $dialog;
    protected $projectDir;

    protected function getContainer()
    {
        if(is_null($this->container))
            $this->createContainer();
        return $this->container;
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


        $this->container = new Container();
        $this->filesystem = new Filesystem();
        $this->container->set('kernel', $kernel);
        $this->container->set('filesystem', $this->filesystem);
        $this->container->set('code_refactor.scan_dir', new ScanDir());

        $this->container->setParameter('kernel.root_dir', $this->projectDir.'/app');
        $this->container->setParameter('code_refactor.working_dir', $this->projectDir);


        return $this->container;
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

    protected function getHelperSet($input)
    {
        $this->dialog = new DialogHelper();
        $this->dialog->setInputStream($this->getInputStream($input));

        return new HelperSet(array(new FormatterHelper(), $this->dialog));
    }

    protected function getInputStream($input)
    {
        $stream = fopen('php://memory', 'r+', false);
        fputs($stream, $input.str_repeat("\n", 10));
        rewind($stream);

        return $stream;
    }




}
