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
        $command = $this->getCommand($input);
        $tester = new CommandTester($command);
        $tester->execute($options);

        $this->assertRegExp($expected, $tester->getDisplay(), '->execute() ');
    }

    public function getInteractiveCommandData()
    {
        return array(
            array(array('command'=>'refactor:project:rename'), "Acme\nFoo\nno",'/[yes]? Command aborted$/'),
            array(array('command'=>'refactor:project:rename'), "Acme\nTest\nyes\nno",'/[no]? Command aborted$/'),
        );
    }

    public function testRenameProjectCommand()
    {
        $project = "Acme";
        $command = $this->getCommand($project);
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command'=>$command->getName(), '--project-name' => $project, '--new-project-name' => 'MyApplication'));

        $files = $this->getContainer()->get('code_refactor.scan_dir')->search($project);
        foreach($files as $key => $file)
            var_dump($key);
        $this->assertEmpty($files);
    }

    protected function getCommand($input = "")
    {
        $application = new Application($this->getContainer()->get('kernel'));
        $application->add(new RenameProjectCommand());
        $command = $application->find('refactor:project:rename');
        $command->setContainer($this->getContainer());
        $command->setHelperSet($this->getHelperSet($input));
        return $command;
    }
}
