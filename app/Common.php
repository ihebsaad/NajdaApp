<?php
namespace App;


class Common
{

  public static  function SstartsWith ($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }

}

?>