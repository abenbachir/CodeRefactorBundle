<?php
/**
 * Created by JetBrains PhpStorm.
 * User: abder
 * Date: 2013-06-28
 * Time: 9:32 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code\RefactorBundle\Helper;


class StringHelper
{

    public static function startsWith($haystack, $needle)
    {
        if(empty($haystack) || empty($needle))
            return false;

        switch (gettype($needle))
        {
            case "string":
                return !strncmp($haystack, $needle, strlen($needle));
                break;
            case "array":
                $result = false;
                foreach($needle as $word)
                    $result = $result || self::startsWith($haystack, $word);
                return $result;
                break;
            default:
                throw new \Exception("Invalid input needle type: " . gettype($needle));
        }
    }
    public static function endsWith($haystack, $needle)
    {
        if(empty($haystack) || empty($needle))
            return false;

        switch (gettype($needle))
        {
            case "string":
                $length = strlen($needle);
                if ($length == 0)
                    return true;
                return (substr($haystack, -$length) === $needle);
                break;
            case "array":
                $result = false;
                foreach($needle as $word)
                    $result = $result || self::endsWith($haystack, $word);
                return $result;
                break;
            default:
                throw new \Exception("Invalid input needle type: " . gettype($needle));
        }
    }

    public static function contains($haystack, $needle)
    {
        if(empty($haystack) || empty($needle))
            return false;

        switch (gettype($needle))
        {
            case "string":
                return strpos($haystack, $needle) !== false;
                break;
            case "array":
                $result = false;
                foreach($needle as $word)
                    $result = $result || self::contains($haystack, $word);
                return $result;
                break;
            default:
                throw new \Exception("Invalid input needle type : " . gettype($needle));
        }
    }
}