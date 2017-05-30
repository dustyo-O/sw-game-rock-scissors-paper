<?php
session_start();
include_once 'functions.php';

$token = isset($_SESSION['token']) ? $_SESSION['token'] : null;
if (!$token) {
    e404(array(
        'message' => 'Не авторизован'
    ));
} else {
    $status = getGameStatusForUser($token);
    $response = $status;

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}