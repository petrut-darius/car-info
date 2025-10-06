<head>
    <link rel="stylesheet" href="stylesheet/style.css">
</head>

<?php
session_start();
require_once "views/includes/header.inc.php";
if(isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])) {
    header("Location: /pdi/car-app/views/cars/index.php");
}else{
    header("Location: /pdi/car-app/views/users/register.php");
}