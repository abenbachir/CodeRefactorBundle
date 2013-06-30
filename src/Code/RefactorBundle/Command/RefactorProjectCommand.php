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
        $this
            ->setDefinition(array(
                new InputOption('project-name', '', InputOption::VALUE_REQUIRED, 'The project-name of the project to refactor'),
                new InputOption('new-project-name', '', InputOption::VALUE_REQUIRED, 'The new-project-name of the project to refactor'),
            ))
            ->setDescription('Refactor a project name')
            ->setHelp(<<<EOT
                        The <info>refactor:project</info> command helps you refactoring your projects.

                        By default, the command interacts with the developer to tweak the refactoring.
                        Any passed option will be used as a default value for the interaction
                        (<comment>--project-name</comment> is the only one needed if you follow the
                        conventions):

                        <info>php app/console refactor:project --project-name=Acme</info>

                        Note that you can use <comment>/</comment> instead of <comment>\\ </comment>for the namespace delimiter to avoid any
                        problem.

                        If you want to disable any user interaction, use <comment>--no-interaction</comment> but don't forget to pass all needed options:

                        <info>php app/console generate:bundle --namespace=Acme/BlogBundle --dir=src [--project-name=...] --no-interaction</info>

                        Note that the bundle namespace must end with "Bundle".
EOT
            )
            ->setName('refactor:project:rename')
        ;
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