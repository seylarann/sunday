<?php
    require($_SERVER['DOCUMENT_ROOT'] . '/project/config.php');

    if($_SESSION['language'] == 'en'){
        $_SESSION['language'] = 'kh';
    } else {
        $_SESSION['language'] = 'en';
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
?>
