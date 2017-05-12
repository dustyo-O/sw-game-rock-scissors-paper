<?php
session_start();
include_once 'functions.php';

$token = isset($_SESSION['token']) ? $_SESSION['token'] : null;
if (!$token) {
    e404(array(
        'message' => 'Не авторизован'
    ));
} else {
    $action = isset($_GET['action']) ? $_GET['action'] : null;

    if (!$action) {
        e404(array(
            'message' => 'Данные не переданы'
        ));        
    }

    if (!gameTurn($token, $action)) {
        e404(array(
            'message' => 'Ход не сохранился'
        ));         
    }
}