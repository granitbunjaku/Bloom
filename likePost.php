<?php

session_start();
include('classes/Posts.php');
include('classes/CRUD.php');

$crud = new CRUD;
$postDB = new Posts;

$postRelations = $crud->read("likes", ['user_id' => $_SESSION['user_id'], 'post_id' => $_GET['id']]);

if(!$postRelations) {
    $postDB->increaseLike($_SESSION['user_id'], $_GET['id']);
} else {
    $postDB->decreaseLike($_SESSION['user_id'], $_GET['id']);
}