<?php
require($_SERVER['DOCUMENT_ROOT'] . '/project/config.php');

if($_GET['id']){
    $id = $_GET['id'];
    $mysql->query("DELETE FROM product_categories WHERE id = '$id' ");


    $_SESSION['sms'] = [
        'status' => 'success',
        'background' => 'alert-success',
        'data' => __("Delete Successfully")
    ];


    $route = route("categories");

    header("Location: $route");
    exit();
}

?>
