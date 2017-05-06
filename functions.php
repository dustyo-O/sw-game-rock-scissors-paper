<?php

$link = mysqli_connect('localhost', 'root', '', 'sw-games');

function e404($body) {
    if (!is_string($body)) {
        $body = json_encode($body);
    }

    header('HTTP/1.1 404 Not Found');
    echo $body;

    die();
}

function generateRandomString($length = 32) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function getGameWaitingForStart() {
    global $link;
    userOnline($token);
    $query = "SELECT * FROM `games` 
    WHERE (`player1token` IS NOT NULL) AND (`player2token` IS NULL)";
    $result = mysqli_query($link, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        return $row;
    } else {
        return null;
    }
}

function createNewGame($token) {
    global $link;
    userOnline($token);
    $query = "INSERT INTO `games` VALUES (
        NULL, '{$token}', NULL, NULL, NULL
    )";
    $result = mysqli_query($link, $query);

    if ($result) {
        return mysqli_insert_id($link);
    } else {
        return null;
    }
}

function joinGame($id, $token) {
    global $link;
    userOnline($token);
    $query = "UPDATE `games` SET `player2token` = '{$token}' WHERE `id` = '$id'";

    $result = mysqli_query($link, $query);

    if ($result) {
        return mysqli_insert_id($link);
    } else {
        return null;
    } 
}

function getUserGameAndNumber($token) {
    global $link;
    userOnline($token);
    $query = "SELECT * FROM `games` 
    WHERE (`player1token` = '{$token}') OR (`player2token` = '{$token}')";
    $result = mysqli_query($link, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        if ($row['player1token'] === $token) {
            $number = 1; 
        } else {
            $number = 2;
        }
        return array(
            'number' => $number,
            'game' => $game
        );
    } else {
        return null;
    }    
}

function gameTurn($token, $turn) {
    global $link;
    userOnline($token);
    $gameData = getUserGameAndNumber($token);

    $game = $gameData['game'];
    $number = $gameData['number'];

    $query = "UPDATE `games` SET `player{$number}turn` = '{$turn}' WHERE `id` = '{$game['id']}'";

    return mysqli_query($link, $query);
}

function getGameStatus($id) {
    $query = "SELECT * FROM `games` 
    WHERE `id` = '{$id}'";
    $result = mysqli_query($link, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        if ($row['player2token'] === null) {
            return 'not-started';
        } else {
            if (($row['player2turn'] === null) || ($row['player1turn'] === null))
                return 'progress';
            return 'finished';
        }
    } else {
        return 'error';
    }    
}

function getGameStatusForUser($token) {
    userOnline($token);
    $data = getUserGameAndNumber($token);
    if ($data) {
        return getGameStatus($data['game']['id']);
    } else {
        return 'error';
    }
}

function getOnlineUsers() {
    global $link;
    $currentTime = time();
    $onlineTime = $currentTime - 60 * 5;
    $query = "SELECT * FROM `users` WHERE `action-time` > '{$onlineTime}'";

    $users = [];

    $result = mysqli_query($link, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    } 
    return $users;
}

function getOnlineUsersCount() {
    return count(getOnlineUsers);
}

function userOnline($token) {
    global $link;

   $currentTime = time();
   $query = "UPDATE `users` SET 'action-time' = '{$currentTime}' WHERE `token` = '{$token}'"; 

   return mysqli_query($query);
}