<?php

include 'classes/CRUD.php';
$crud = new CRUD;

$request_body = file_get_contents('php://input');
$data = json_decode($request_body, true);

if(isset($_GET['post_id'])) {
    $post = $crud->read("posts", ['id' => $_GET['post_id']], null);

    if($crud->delete("posts", ['id' => $_GET['post_id']], null)) {
        if($post[0]['image']) {
            $photoPath = 'post-photos/'.$post[0]['image'];
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
        } else if($post[0]['video']) {
            $photoPath = 'post-videos/'.$post[0]['video'];
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
        }

            if(isset($_GET['id'])) {
            header('Location: profile.php?id='.$_GET['id']);
        } else {
            header('Location: index.php');
        }
    }
} else {
    $crud->delete("comments", ['id' => $_GET['comment_id']]);
}