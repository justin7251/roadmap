<?php

function __autoload($class)
{
    $class = strtolower($class);
    if (file_exists(APP . "libs/$class.php")) :
        require_once APP . "libs/$class.php";
    elseif (file_exists(APP . "controller/$class.php")) :
        require_once APP ."controller/$class.php";
    elseif (file_exists(APP . "model/$class.php")) :
        require_once APP ."model/$class.php";
    endif;
}
