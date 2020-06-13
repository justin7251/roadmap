<div class="log-container">
    <?php
        if (isset($error)) {
            echo $form->message($error, array('class' => 'alert-danger'));
        }
    ?>

    <h1>Reset Password </h1><br>
    <form id="form-login" class="form-group " action="<?php echo URL; ?>user/check" method="POST">
        <input type="password" name="password" placeholder="New Password" required>
        <input type="password" name="password2" placeholder="Confirm Password" required>
        <input type="hidden" name="id" value="<?php echo $user->id;?>">
        <input type="hidden" name="reset" value="<?php echo $key;?>">
        <input type="submit" name="submit_reset" class="login login-submit" value="login">
    </form>
    <div class="login-help">
        <a href="<?php echo URL; ?>user/register">Register</a> • <a href="<?php echo URL; ?>user/reset_password">Forgot Password</a>
    </div>
</div>