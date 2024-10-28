<?php

session_start();

$project_path = "/project";
$document_root = $_SERVER['DOCUMENT_ROOT'] . $project_path;
$localhost = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $project_path;

$title = 'Document';
$isActive = 'home';



$mysql = new mysqli('localhost','root','','project_weekend_pos');

//check connection
if ($mysql -> connect_errno) {
    echo "Failed to connect to MySQL: " . $mysql -> connect_error;
    exit();
}


function asset($path){
    global $localhost;
    return $localhost . "/" . $path;
}

function route($path){
    global $localhost;
    return $localhost . "/" . $path;
}

function folder($path){
    global $document_root;
    return $document_root . "/" . $path;
}


$_SESSION['auth'] = !isset($_SESSION['auth']) ? 0 : $_SESSION['auth'];
$_SESSION['auth_data'] = !isset($_SESSION['auth_data']) ? null : $_SESSION['auth_data'];

$_SESSION['language'] = !isset($_SESSION['language']) ? 'en' : $_SESSION['language'];

require(folder('kh.php'));
function __($word){
    global $languages;

    if($_SESSION['language'] == 'en'){
        return $word;
    } else {
        return isset($languages[$word]) ? $languages[$word] : $word;
    }
}




// foreach ($_SERVER as $key => $value) {
//    echo $key . " : " . $value . "<br>";
// }

?>
