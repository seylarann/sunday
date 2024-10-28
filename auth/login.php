<?php
    require($_SERVER['DOCUMENT_ROOT'] . '/project/config.php');
    $title = __('Login');

    require(folder("layouts/header.php"));



    if(isset($_SESSION['auth']) && $_SESSION['auth'] == 1){

        $path = route('home');
        $_SESSION['sms'] = __('Welcome Back !!!');
        header("Location: $path");
    }
?>




<div class="container">
        <div class="row vh-100 align-items-center justify-content-center">
            <div class="col-lg-4 col-12">
                <?php if(isset($_SESSION['sms'])){ ?>
                    <div class="alert <?php echo $_SESSION['sms']['background']; ?>">
                        <?php echo $_SESSION['sms']['data']; ?>
                    </div>
                <?php } ?>
                <form action="<?php echo route('auth/action_login.php'); ?>" method="POST">
                    <div class="card">
                        <div class="card-header text-center"><?php echo __("Login Page"); ?></div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="username"><?php echo __("Username"); ?></label>
                                <input type="text" name="username" class="form-control form-control-sm" id="username">
                            </div>
                            <div class="mb-3">
                                <label for="password"><?php echo __("Password"); ?></label>
                                <input type="password" name="password" class="form-control form-control-sm" id="password">
                            </div>
                            <a href="<?php echo route('migration.php'); ?>">Migration DB</a>
                            <button class="btn btn-primary btn-sm float-end"><?php echo __("Login"); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>





<?php require(folder("layouts/footer.php")); ?>
