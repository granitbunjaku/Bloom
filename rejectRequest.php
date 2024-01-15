<?php
session_start();
include 'classes/Database.php';
include 'classes/Friends.php';
$friends = new Friends;

if($friends->rejectRequest($_GET['id'], $_SESSION['user_id'])) {
    header('Location: profile.php?id='.$_SESSION['user_id']);
}
