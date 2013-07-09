<?php
/**
 * Created by JetBrains PhpStorm.
 * User: abder
 * Date: 2013-06-28
 * Time: 9:40 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code\RefactorBundle\Tests\Helper;

use Code\RefactorBundle\Helper\StringHelper;

class StringHelperTest extends \PHPUnit_Framework_TestCase
{
    private $text = "Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                    Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                    when an unknown printer took a galley of type and scrambled it to make a type
                    specimen book. It has survived not only five centuries, but also the leap into
                    electronic typesetting, remaining essentially unchanged. It was popularised in
                    the 1960s with the release of Letraset sheets containing Lorem Ipsum passages,
                    and more recently with desktop publishing software like Aldus PageMaker
                    including versions of Lorem Ipsum.";

    /*
     * StartsWith test cases
     */
    public function testStartsWith()
    {
        $this->assertFalse(StringHelper::startsWith($this->text,"Test"));
        $this->assertFalse(StringHelper::startsWith($this->text,""));
        $this->assertTrue(StringHelper::startsWith($this->text,"Lorem Ipsum is simply dum"));
        $this->assertFalse(StringHelper::startsWith($this->text,array()));
        $this->assertFalse(StringHelper::startsWith($this->text,array('-','+','=')));
        $this->assertTrue(StringHelper::startsWith($this->text,array('versions','essentially','Lorem Ipsum is simply dummy text of the printing and typesetting industry')));
    }

    /*
     * EndsWith test cases
     */
    public function testEndsWith()
    {
        $this->assertFalse(StringHelper::endsWith($this->text,'Test'));
        $this->assertFalse(StringHelper::endsWith($this->text,''));
        $this->assertTrue(StringHelper::endsWith($this->text,'ns of Lorem Ipsum.'));
        $this->assertFalse(StringHelper::endsWith($this->text,array()));
        $this->assertFalse(StringHelper::endsWith($this->text,array('-','+','=')));
        $this->assertTrue(StringHelper::endsWith($this->text,array('versions','essentially','Ipsum.')));
    }

    /*
     * Contains test cases
     */
    public function testContains()
    {
        $this->assertFalse(StringHelper::contains($this->text,'Test'));
        $this->assertFalse(StringHelper::contains($this->text,''));
        $this->assertTrue(StringHelper::contains($this->text,'as survived not only f'));
        $this->assertFalse(StringHelper::contains($this->text,array()));
        $this->assertFalse(StringHelper::contains($this->text,array('-','+','=')));
        $this->assertTrue(StringHelper::contains($this->text,array('versions','essentially','...')));
    }
}
