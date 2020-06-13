<?php
    require APP . 'view/helpers/form.php';
    $form = new Form();
    echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Roadmap :: ' . (isset($page_title) ? $page_title : ucwords(preg_replace("/_/", " ", $this->controller))) . '</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- please note: The JavaScript files are loaded in the footer to speed up page construction -->
    
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">';
    
    $header_css = array('bootstrap-datetimepicker.css', 'style.css');
    if (isset($controller_css)) {
        if (is_array($controller_css) && count($controller_css) > 0) {
            $header_css = array_merge($header_css, $controller_css);
        }
    }
    foreach($header_css as $header_file) {
        echo '
    <link href="' . URL . 'css/' . $header_file . '" rel="stylesheet">';
    }
    echo '
    <!-- jquery -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.26.1/js/jquery.tablesorter.min.js"></script>
</head>
<body>';

