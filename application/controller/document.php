<?php
/**
* /controller/document.php
*
* PHP Version 5
*/

/**
* Document Attachment Controller
*
* @category Document
* @package Roadmap
* @subpackage Controller
* @author Justin Leung <myjustinleung19@hotmail.com>
* @copyright 2020
* @version Release: 1.0
*/
class Document extends Controller
{
    /**
    * Import attachment filter
    *
    * @return void
    */
    public function filter_upload_file()
    {
        if (!empty($_FILES["file_upload"])) {
            $file = $_FILES["file_upload"];
            $filename = $_FILES['file_upload']['name'];
            
            if ($file["error"] !== UPLOAD_ERR_OK) {
                echo "<p>An error occurred.</p>";
            }
           
            //can't be larger than 1 MB
            if ($_FILES['file_upload']['size'] > (1024000)) {
                echo 'Your file\'s size is to large.';
            }
            
            $allowed = array('gif', 'png', 'jpg', 'doc','pdf', 'xls', 'ppt', 'csv', 'json');
            $file_type = (explode('.', $filename));
            // file are allow
            if (in_array(end($file_type), $allowed)) {
                $name = rand() . '_' . preg_replace("/[^A-Z0-9._-]/i", "_", $file["name"]);
                $success = move_uploaded_file($file["tmp_name"], 'uploads/' . $name);
                if (!$success) {
                    echo "<p>Unable to save file.</p>";
                    exit;
                }
                echo '<input type="hidden" name="file_path" value="' . $name. '">';
            } else {
               echo "<p>We only allow images, csv, excel, json and pdf files.</p>"; 
            }
        } else {
            echo 'No file has been select';
        }
        $this->render = false;
    }
}
