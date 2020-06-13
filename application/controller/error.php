<?php
/**
* /controller/error.php
*
* PHP Version 5
*/

/**
* The controller class for Error
* Please note:
* Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
* This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
*
* @category Error
* @package Roadmap
* @subpackage Controller
* @author Justin Leung <myjustinleung19@hotmail.com>
* @copyright 2020
* @version Release: 1.0
*/
class Error extends Controller
{
    /**
    * PAGE: index
    * This method handles the error page that will be shown when a page is not found
    * @return void
    */
    public function index()
    {
        // load views
        require APP . 'view/error/index.php';
    }
}
