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
        $tester->execute($options);
    }

    protected function getCommand($input)
    {
        $command = $this
            ->getMockBuilder('Code\RefactorBundle\Command\RenameProjectCommand')
            ->getMock()
        ;

        $command->setContainer($this->getContainer());
        $command->setHelperSet($this->getHelperSet($input));

        return $command;
    }
}
