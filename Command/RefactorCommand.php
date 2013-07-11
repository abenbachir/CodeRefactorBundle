<?php
/**
 * Created by JetBrains PhpStorm.
 * User: abder
 * Date: 2013-06-28
 * Time: 12:05 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Code\RefactorBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Code\RefactorBundle\Refactor;
use Code\RefactorBundle\Helper\DialogHelper;

abstract class RefactorCommand extends ContainerAwareCommand 
{

    protected function getDialogHelper()
    {
        $dialog = $this->getHelperSet()->get('dialog');
        if (!$dialog || get_class($dialog) !== 'Code\RefactorBundle\Helper\DialogHelper') {
            $this->getHelperSet()->set($dialog = new DialogHelper());
        }

        return $dialog;
    }

    protected function getScanDir()
    {
        return $this->getContainer()->get('code_refactor.scan_dir');
    }
}