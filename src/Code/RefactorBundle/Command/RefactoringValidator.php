<?php
/**
 * Created by JetBrains PhpStorm.
 * User: abder
 * Date: 2013-06-29
 * Time: 12:19 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Code\RefactorBundle\Command;


class RefactoringValidator {

    public static function validateSearchName($search)
    {
        if(empty($search))
            throw new \InvalidArgumentException('The $search can not be empty');

        if(strlen($search) < 2)
            throw new \InvalidArgumentException('The length should be at least 2');
    }

    public static function validateReplaceName($replace)
    {
        if(empty($replace))
            throw new \InvalidArgumentException('The $replace can not be empty');

        if(strlen($replace) < 2)
            throw new \InvalidArgumentException('The length should be at least 2');
    }

    public static function validatePreRefactoring($search, $replace)
    {
        self::validateSearchName($search);
        self::validateReplaceName($replace);

        if( $search === $replace )
            throw new \InvalidArgumentException('$search and $replace must be different');
    }
}