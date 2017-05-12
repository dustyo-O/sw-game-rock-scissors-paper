<?php
session_start();
include_once('functions.php');

$token = isset($_SESSION['token']) ? $_SESSION['token'] : null;

logoutUser($token);

unset($_SESSION['token']);

session_destroy();