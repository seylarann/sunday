<?php
require($_SERVER['DOCUMENT_ROOT'] . '/project/config.php');

if($_GET['id']){
    $id = $_GET['id'];
    $mysql->query("DELETE FROM products WHERE id = '$id' ");


    $_SESSION['sms'] = [
        'status' => 'success',
        'background' => 'alert-success',
        'data' => __("Delete Successfully")
    ];


    $route = route("products");

    header("Location: $route");
    exit();
}

?>
