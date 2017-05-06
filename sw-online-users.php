<?php
session_start();

include_once 'functions.php';
$users = getOnlineUsers();

echo json_encode($users, JSON_UNESCAPED_UNICODE);