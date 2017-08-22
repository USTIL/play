<?php
include './func/mysql.php';
$aa = new mysql();
$aa->createTable();
echo "<h1>数据库表创建成功 请不要再次运行此文件</h1>";