<?php
require($_SERVER['DOCUMENT_ROOT'] . '/project/config.php');

if($_GET['id']){
    $id = $_GET['id'];
    $oldUser = $mysql->query("SELECT * FROM users WHERE id = '$id'")->fetch_object();

    if(file_exists(folder($oldUser->photo))){
        unlink(folder($oldUser->photo));
    }

    $mysql->query("DELETE FROM users WHERE id = '$id' ");


    $_SESSION['sms'] = [
        'status' => 'success',
        'background' => 'alert-success',
        'data' => __("Delete Successfully")
    ];


    $route = route("users");

    header("Location: $route");
    exit();
}

?>
