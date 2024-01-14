<?php
session_start();
include 'classes/Database.php';
include 'classes/Friends.php';
$friends = new Friends;

if($friends->acceptRequest($_GET['id'], $_SESSION['user_id'])) {
    header('Location: profile.php?id='.$_GET['id']);
}
