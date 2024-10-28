<?php
    require($_SERVER['DOCUMENT_ROOT'] . '/project/config.php');


    $_SESSION['auth'] = 0;
    $_SESSION['auth_data'] = null;
    $_SESSION['sms'] = [
        'status' => 'success',
        'background' => 'alert-success',
        'data' => __("Logout Successfully")
    ];

    $path = route('auth/login.php');


    header("Location: $path");


?>
