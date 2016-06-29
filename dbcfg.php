<?php
// $pdo = new PDO("mysql:host=127.0.0.1;dbname=hkcj","root","lin88750269");
$pdo = new PDO("mysql:host=127.0.0.1;dbname=mmyhq","root","111111");

$pdo->query("SET NAMES 'utf8';");

$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); //禁用prepared statements的仿真效果
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // 捕获SQL语句中的错误
