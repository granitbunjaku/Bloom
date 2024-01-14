<?php

include 'classes/CRUD.php';
$crud = new CRUD;

$request_body = file_get_contents('php://input');
$data = json_decode($request_body, true);

if(isset($_GET['post_id'])) {
    if($crud->delete("posts", ['id' => $_GET['post_id']], null)) {
        if(isset($_GET['id'])) {
            header('Location: profile.php?id='.$_GET['id']);
        } else {
            header('Location: index.php');
        }
    }
} else {
    $crud->delete("comments", ['id' => $_GET['comment_id']]);
}