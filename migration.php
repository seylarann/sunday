<?php
$mysql = new mysqli('localhost','root','','project_weekend_pos');

//check connection
if ($mysql -> connect_errno) {
    echo "Failed to connect to MySQL: " . $mysql -> connect_error;
    exit();
}


define('USER_TABLE',"CREATE TABLE IF NOT EXISTS users(
    id int not null auto_increment primary key,
    name varchar(100) not null,
    username varchar(100) not null,
    password varchar(100) not null,
    photo text null
)");

define('PRODUCT_CATEGORIES',"CREATE TABLE IF NOT EXISTS product_categories(
    id int not null auto_increment primary key,
    name varchar(100) not null,
    note varchar(255) null
)");

define('PRODUCTS',"CREATE TABLE IF NOT EXISTS products(
    id int not null auto_increment primary key,
    product_category_id int not null,
    name varchar(100) not null,
    price float(10,2) not null default 0,
    created_at timestamp not null DEFAULT CURRENT_TIMESTAMP,
    note varchar(255) null
)");

define('ORDERS',"CREATE TABLE IF NOT EXISTS orders(
    id int not null auto_increment primary key,
    order_date timestamp not null default CURRENT_TIMESTAMP,
    grand_total float(10,2) not null
)");

define('ORDER_DETAILS',"CREATE TABLE IF NOT EXISTS order_details(
    id int not null auto_increment primary key,
    order_id int,
    product_id int not null,
    qty int not null,
    price float(10,2) not null,
    total float(10,2) not null
)");


$mysql->query(USER_TABLE);
$mysql->query(PRODUCT_CATEGORIES);
$mysql->query(PRODUCTS);
$mysql->query(ORDERS);
$mysql->query(ORDER_DETAILS);


$checkUserIsExist = $mysql->query("SELECT * FROM users limit 1");
if(!$checkUserIsExist->fetch_object()){
    $mysql->query("INSERT INTO users (name,username,password) VALUES ('admin','admin','123')");
}


header("Location: auth/login.php");

?>
