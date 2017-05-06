<?php
$username = isset($_GET['name']) ? $_GET['name'] : null;

include_once 'functions.php';

if (getOnlineUsersCount() >= 2) {
    e404(array(
        'message' => 'Слишком много онлайн'
    ));
}

$token = generateRandomString(32);

$actionTime = time();

$query = "INSERT INTO `users` VALUES (
    '{$token}', '{$username}', '$actionTime'
)";

mysql_result($query);

?>{
    "status": "ok"
}