<?php

class Document_Model extends Model
{
    function upload_file($file)
    {
        // ensure a safe filename
        $name = preg_replace("/[^A-Z0-9._-]/i", "_", $file["file_upload"]["name"]);
        
        $success = move_uploaded_file($file["file_upload"]["tmp_name"],
        '/public/uploads/' . $name);
        if (!$success) { 
            echo "<p>Unable to save file.</p>";
            exit;
        }
        
    }
}
?>