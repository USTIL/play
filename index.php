<?php
session_start();
include 'func/mysql.php';
$mysql = new mysql();
$mysql->createTable();
if(isset($_SESSION['url'])) {
    $url = ".";
    foreach ($_SESSION['url'] as $t) {
        $url .= '/'.$t;
    }
    $url .= '.php';
    if (is_file($url)) {
        include $url;
    }
} else {
    include './index/index.php';
}