<?php
session_start();
$username = isset($_GET['name']) ? $_GET['name'] : null;

include_once 'functions.php';

if (getOnlineUsersCount() >= 2) {
    e404(array(
        'message' => 'Слишком много онлайн'
    ));
}

if (isset($_SESSION['token'])) {
       e404(array(
        'message' => 'Вы уже авторизованы'
    )); 
}

$token = generateRandomString(32);

$actionTime = time();

$query = "INSERT INTO `users` VALUES (
    '{$token}', '{$username}', '$actionTime'
)";

mysqli_query($link, $query);
$_SESSION['token'] = $token;
?>{
    "status": "ok"
}