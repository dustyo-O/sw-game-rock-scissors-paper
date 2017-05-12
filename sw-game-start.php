<?php
session_start();
include_once 'functions.php';

$token = isset($_SESSION['token']) ? $_SESSION['token'] : null;

if (!$token) {
    e404(array(
        'message' => 'Не авторизован'
    ));
} else {
    if (!($game = getGameWaitingForStart())) {
        $game = createNewGame($token);
        echo '{"status": "started"}';
    } else {
        joinGame($game['id'], $token);
        echo '{"status": "joined"}';
    }

    
}
