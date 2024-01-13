<?php

include 'classes/CRUD.php';
$crud = new CRUD;

session_start();

$request_body = file_get_contents('php://input');
$data = json_decode($request_body, true);

$post_id = $data['post_id'];
$new_content = $data['new_content'];
$uid = $data['uid'];

if($uid === $_SESSION['user_id']) {
    if(strlen($new_content) > 0) {
        $crud->update("posts", ['content' => $new_content], ['column' => 'id', 'value' => $post_id]);
    } else {
        http_response_code(404);
    }
}

echo $new_content;
