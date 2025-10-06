<?php
session_start();

if(isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])) {
    header("Location: /pdi/car-app/views/cars/index.php");
    exit;
}

?>