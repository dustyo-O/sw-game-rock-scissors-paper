<?php
session_start();
$token = isset($_SESSION['token']) ? $_SESSION['token'] : null;
include_once 'functions.php';
$users = getOnlineUsers($token);

echo json_encode($users, JSON_UNESCAPED_UNICODE);