<?php
/**
 * Created by JetBrains PhpStorm.
 * User: abder
 * Date: 2013-06-27
 * Time: 11:43 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code\RefactorBundle\Refactor;


class BundleRefactor extends Refactor
{
    public $bundle;

    public function __construct($bundle)
    {
        // TODO: validate existing bundle

        $this->bundle = $bundle;
    }
    /**
     * @inheritdoc
     */
    public function rename($new)
    {
        // TODO: rename the project
    }
    /**
     * @inheritdoc
     */
    public function safeDelete()
    {
        // TODO: rename the project
    }
    /**
     * @inheritdoc
     */
    public function scan()
    {
        // TODO: scan all files
    }
    /**
     * @inheritdoc
     */
    public function getImpact()
    {
        // TODO: get impact of the refactoring
    }
}