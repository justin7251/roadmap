    <!-- jQuery, loaded in the recommended protocol-less way -->
    <!-- more http://www.paulirish.com/2010/the-protocol-relative-url/ -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    

    <!-- define the project's URL (to make AJAX calls possible, even when using this in sub-folders etc) -->
    <script>
        var url = "<?php echo URL; ?>";
    </script>
    <!-- our JavaScript -->
    <?php 
        $footer_js = array('moment.js', 'bootstrap-datetimepicker.js', 'jquery.tools.min.js', 'validator.js', 'application.js', 'login.js');
        if (isset($controller_js)) {
            if (is_array($controller_js) && count($controller_js) > 0) {
                $footer_js = array_merge($footer_js, $controller_js);
            }
        }
        foreach($footer_js as $footer_file) {
            echo '<script src="' . URL . 'js/' . $footer_file . '"></script>';
        }
    ?>
</body>
</html>
