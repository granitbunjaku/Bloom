<?php
    include 'classes/CRUD.php';

    $crud = new CRUD;

    $messages = $crud->read("messages", ['chat_id' => $_GET['chat_id']]);
    echo json_encode($messages);
?>