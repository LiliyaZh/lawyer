<?php
session_start();

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Функция для загрузки запрашиваемой страницы
function loadPage($page)
{
    $file = "php/$page.php";
    if (file_exists($file)) {
        require_once($file);
    } else {
        require_once("php/404.php");
    }
}
?>