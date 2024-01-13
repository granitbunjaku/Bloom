<?php

include 'classes/CRUD.php';
$crud = new CRUD;

$request_body = file_get_contents('php://input');
$data = json_decode($request_body, true);

if(isset($_GET['post_id'])) {
    if($crud->delete("posts", ['id' => $_GET['post_id']], null)) {
        header('Location: index.php');
    }
} else {
    if($crud->delete("comments", ['id' => $_GET['comment_id']])) {
        header('Location: index.php');
    }
}