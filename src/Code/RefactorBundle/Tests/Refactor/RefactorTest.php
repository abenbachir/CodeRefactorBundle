<?php
/**
 * Created by JetBrains PhpStorm.
 * User: abder
 * Date: 2013-06-28
 * Time: 9:25 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code\RefactorBundle\Tests\Refactor;

use Code\RefactorBundle\Refactor\Refactor;
use Symfony\Component\Filesystem\Filesystem;
use Code\RefactorBundle\Helper\DialogHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Output\StreamOutput;

class RefactorTest extends \PHPUnit_Framework_TestCase
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
        // get symfony standard edition
        exec("composer create-project symfony/framework-standard-edition $this->projectDir/ 2.3.1 --quiet --no-interaction");
        // configuration
        exec("setfacl -R -m u:www-data:rwx -m u:`whoami`:rwx $this->projectDir/app/cache $this->projectDir/app/logs");
        exec("setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx $this->projectDir/app/cache $this->projectDir/app/logs");

    }

    public function tearDown()
    {
        $this->filesystem->remove($this->tmpDir);
    }


    public function testRefactor()
    {
        /*
        $dialog = $this->getDialogHelper();
        $dialog->writeSection($this->getOutputStream(), "++++++++++++++++++++++");
        */
        $refactor = new Refactor('Acme', 'Application', $this->projectDir);
        $refactor->replaceRecursive();
        $this->assertTrue(true);
    }

    protected function getInputStream($input)
    {
        $stream = fopen('php://memory', 'r+', false);
        fputs($stream, $input);
        rewind($stream);

        return $stream;
    }

    protected function getOutputStream()
    {
        return new StreamOutput(fopen('php://memory', 'r+', false));
    }

    protected function getDialogHelper()
    {
        $dialog = new DialogHelper();

        $helperSet = new HelperSet(array(new FormatterHelper()));
        $dialog->setHelperSet($helperSet);
        return $dialog;
    }

}
