<?php

/**
 * Configuration
 *
 * For more info about constants please @see http://php.net/manual/en/function.define.php
 */

/**
 * Configuration for: Error reporting
 * Useful to show every little problem during development, but only show hard errors in production
 */
define('ENVIRONMENT', 'development');

if (ENVIRONMENT == 'development' || ENVIRONMENT == 'dev') {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}

/**
 * Configuration for: URL
 * Here we auto-detect your applications URL and the potential sub-folder. Works perfectly on most servers and in local
 * development environments (like WAMP, MAMP, etc.). Don't touch this unless you know what you do.
 *
 * URL_PUBLIC_FOLDER:
 * The folder that is visible to public, users will only have access to that folder so nobody can have a look into
 * "/application" or other folder inside your application or call any other .php file than index.php inside "/public".
 *
 * URL_PROTOCOL:
 * The protocol. Don't change unless you know exactly what you do.
 *
 * URL_DOMAIN:
 * The domain. Don't change unless you know exactly what you do.
 *
 * URL_SUB_FOLDER:
 * The sub-folder. Leave it like it is, even if you don't use a sub-folder (then this will be just "/").
 *
 * URL:
 * The final, auto-detected URL (build via the segments above). If you don't want to use auto-detection,
 * then replace this line with full URL (and sub-folder) and a trailing slash.
 */

define('URL_PUBLIC_FOLDER', 'public');
define('URL_PROTOCOL', ($_SERVER['HTTP_HOST'] == 'localhost' ?  'http://' : 'https://' ));
define('URL_DOMAIN', $_SERVER['HTTP_HOST']);
define('URL_SUB_FOLDER', str_replace(URL_PUBLIC_FOLDER, '', dirname($_SERVER['SCRIPT_NAME'])));
define('URL', URL_PROTOCOL . URL_DOMAIN . URL_SUB_FOLDER);
/**
 * Configuration for: Database
 * This is the place where you define your database credentials, database type etc.
 */
define('DB_TYPE', 'mysql');
define('DB_HOST',  ($_SERVER['HTTP_HOST'] == 'localhost' ?  '127.0.0.1' : 'localhost'));
if ($_SERVER['HTTP_HOST'] == 'devroadmap.s2qa.com') {
    define('DB_NAME', 'roadmap_dev');
} else {
    define('DB_NAME', 'roadmap');
}
define('DB_USER', ($_SERVER['HTTP_HOST'] == 'localhost' ?  'root' : 'roadmap'));
define('DB_PASS', ($_SERVER['HTTP_HOST'] == 'localhost' ?  '' : 'S2R04dmAp'));
define('DB_CHARSET', 'utf8');


/**
* A wrapper and extension for print_r(). The output looks the same in the browser as the output of print_r() in the source, as it turns the pure
* text output of print_r() into HTML (XHTML).
*
* @param mixed $data the data to be printed or returned
* @param mixed $var_name null if we don't want to display the variable name, otherwise the name of the variable
* @praram boolean $return default false; if true it returns with the result, if true then prints it
* @return mixed void (null) or a string
*/
function prr($data, $var_name=null, $return=false)
{
    if ($return === false)
        print "\n<pre>\n" . ($var_name === null ? '' : "\${$var_name} = ") . print_r($data, true) . "\n</pre>\n";
    else
        return "\n<pre>\n" . ($var_name === null ? '' : "\${$var_name} = ") . print_r($data, true) . "\n</pre>\n";
}