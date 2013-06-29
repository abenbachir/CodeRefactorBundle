<?php
/**
 * Created by JetBrains PhpStorm.
 * User: abder
 * Date: 2013-06-28
 * Time: 12:06 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Code\RefactorBundle\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\HttpKernel\KernelInterface;

class RefactorProjectCommand extends RefactorCommand {
    /**
     * @see Command
     */
    protected function configure()
    {

    }

    /**
     * @see Command
     *
     * @throws \InvalidArgumentException When namespace doesn't end with Bundle
     * @throws \RuntimeException         When bundle can't be executed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }


    protected function interact(InputInterface $input, OutputInterface $output)
    {

    }
}