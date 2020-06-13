<div class="log-container">
    <?php
        if ($error) {
            echo $form->message($error, array('class' => 'alert-danger'));
        }
        if (isset($this->info)) {
            echo $form->message($this->info, array('class' => 'alert-info'));
        }
    ?>
    <h1>Log-in</h1><br>
    <form id="form-login" class="form-group " action="<?php echo URL; ?>user/login_post" method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" name="submit_compare_user" class="login login-submit" value="login">
    </form>
</div>