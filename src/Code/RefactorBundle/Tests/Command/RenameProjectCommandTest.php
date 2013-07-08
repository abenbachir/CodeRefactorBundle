<?php
/**
 * Created by JetBrains PhpStorm.
 * User: abder
 * Date: 2013-07-04
 * Time: 10:28 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code\RefactorBundle\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Code\RefactorBundle\Command\RenameProjectCommand;

class RenameProjectCommandTest extends GenerateCommandTest
{

    /**
     * @dataProvider getInteractiveCommandData
     */
    public function testInteractiveCommand($options, $input, $expected)
    {
        list($projectName, $newProjectName) = $expected;

        $tester = new CommandTester($this->getCommand($input));
        //var_dump($this->getContainer()->get('code_refactor.scan_dir')->getWorkingDir());
        $tester->execute($options);
    }

    public function getInteractiveCommandData()
    {
        return array(
            //array(array('--project-name' => 'Acme', '--new-project-name' => 'Foo'), "Acme\nFoo", array('Acme','Foo')),
            array(array(), "Acme\nFreak", array('Acme2','Freak')),
        );
    }

    protected function getCommand($input)
    {
        $application = new Application($this->getContainer()->get('kernel'));
        $application->add(new RenameProjectCommand());
        $command = $application->find('refactor:project:rename');
        $command->setContainer($this->getContainer());
        $command->setHelperSet($this->getHelperSet($input));
        return $command;
    }
}
