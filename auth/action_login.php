<?php
require($_SERVER['DOCUMENT_ROOT'] . '/project/config.php');


$username = $_POST['username'];
$password = $_POST['password'];

$check = $mysql->query("SELECT * FROM users WHERE username = '$username' AND password = '$password' limit 1");

if($auth = $check->fetch_object()){
    $_SESSION['auth'] = 1;
    $_SESSION['auth_data'] = $auth;

    $_SESSION['sms'] = [
        'status' => 'success',
        'background' => 'alert-success',
        'data' => __("Login Successfully")
    ];

    $path = route('home');
    header("Location: $path");
} else {
    $_SESSION['sms'] = [
        'status' => 'error',
        'background' => 'alert-danger',
        'data' => __("Wrong Username or Password")
    ];

    $path = route('auth/login.php');
    header("Location: $path");
}


?>
