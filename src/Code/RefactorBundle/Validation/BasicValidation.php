<?php
/**
 * Created by JetBrains PhpStorm.
 * User: abder
 * Date: 2013-06-29
 * Time: 12:19 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Code\RefactorBundle\Validation;


class BasicValidation
{

    public static function validateExistingProject($project)
    {
        if(gettype($project) !== 'string')
            throw new \InvalidArgumentException('Invalid type, only accept string type');
        return $project;
    }

    public static function validateName($name)
    {
        if(empty($name))
            throw new \InvalidArgumentException('The $name can not be empty');

        if(strlen($name) < 2)
            throw new \InvalidArgumentException('The length should be at least 2');
    }

    public static function validateRefactorName($old, $new)
    {
        self::validateName($old);
        self::validateName($new);

        if( $old === $new )
            throw new \InvalidArgumentException('you must provide a different name for refactoring');
    }
}