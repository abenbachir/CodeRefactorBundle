<?php
/**
 * Created by JetBrains PhpStorm.
 * User: abder
 * Date: 2013-06-28
 * Time: 12:06 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Code\RefactorBundle\Command;

use Code\RefactorBundle\Validation\BasicValidation;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\KernelInterface;

class RenameProjectCommand extends RefactorCommand {
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputOption('project-name', '', InputOption::VALUE_REQUIRED, 'The name of the project to refactor'),
                new InputOption('new-project-name', '', InputOption::VALUE_REQUIRED, 'The new name of the project'),
            ))
            ->setDescription('Rename a project')
            ->setHelp(<<<EOT
                        The <info>refactor:project:rename</info> command helps you refactoring your projects.

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
        $dialog = $this->getDialogHelper();
        $scanDir = $this->getScanDir();

        if ($input->isInteractive()) {
            if (!$dialog->askConfirmation($output, $dialog->getQuestion('Do you confirm refactoring', 'yes', '?'), true)) {
                $output->writeln('<error>Command aborted</error>');

                return 1;
            }
        }
        $projectName = $input->getOption('project-name');
        $newProjectName = $input->getOption('new-project-name');
        //BasicValidation::validateRefactorName($projectName, $newProjectName);



        $newPatterns = $scanDir->getProjectPatterns($newProjectName);
        $results = $scanDir->search($newPatterns);
        if($results)
        {
            if (!$dialog->askConfirmation($output, $dialog->getQuestion(sprintf('This name "%s" already exist in %s files in your code, there will be no rollback if you continue, do you still want to continue', $newProjectName, count($results)), 'no', '?'), false)) {
                $output->writeln('<error>Command aborted</error>');

                return 1;
            }
        }

        $patterns = $scanDir->getProjectPatterns($projectName);
        $files = $scanDir->search($patterns);
        if(count($files) == 0)
            throw new \InvalidArgumentException(sprintf('No changes found for the project "%s".', $projectName));

        $dialog->writeSection($output, 'Project refactoring name');

        foreach($files as $file)
        {
            if($file->isFile())
            {
                // change content
                $newContent = str_replace($patterns, $newPatterns, file_get_contents($file->getPathname()));
                file_put_contents($file->getPathname(), $newContent);
            }
            // rename the file/dir
            $newName = str_replace($patterns, $newPatterns, $file->getFilename());
            rename($file->getPathname(), dirname($file->getPathname()).'/'.$newName);
        }

    }


    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getDialogHelper();
        $scanDir = $this->getScanDir();

        $dialog->writeSection($output, 'Welcome to the Symfony2 bundle refactoring');


        $projectName = $dialog->askAndValidate(
            $output,
            $dialog->getQuestion('Project Name', $input->getOption('project-name')),
            array('Code\RefactorBundle\Validation\BasicValidation', 'validateExistingProject'),
            false,
            $input->getOption('project-name')
        );
        $input->setOption('project-name', $projectName);

        if(!$scanDir->existProject($projectName))
            throw new \InvalidArgumentException(sprintf('This project "%s" does not exist.', $projectName));

        $newProjectName = $dialog->askAndValidate(
            $output,
            $dialog->getQuestion('Rename to ...', $input->getOption('new-project-name')),
            array('Code\RefactorBundle\Validation\BasicValidation', 'validateExistingProject'),
            false,
            $input->getOption('new-project-name')
        );
        $input->setOption('new-project-name', $newProjectName);

        if($scanDir->existProject($newProjectName))
            throw new \InvalidArgumentException(sprintf('This project "%s" already exist.', $projectName));
    }
}