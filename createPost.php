<?php

$errors = [];

$crud = new CRUD();

if ($_POST) {
    if (isset($_POST["post-button"])) {
        if (strlen($_POST['content']) === 0 && strlen($_FILES['post-file']['name']) === 0) {
            $errors[] = "You should write something!";
        }

        if (count($errors) === 0) {
            $content = $_POST['content'];

            if ($_FILES) {
                if (str_starts_with($_FILES['post-file']['type'], 'image')) {
                    $imagefile = time() . $_FILES['post-file']['name'];
                    $videofile = null;
                } elseif (str_starts_with($_FILES['post-file']['type'], 'video')) {
                    $videofile = time() . $_FILES['post-file']['name'];
                    $imagefile = null;
                } else {
                    $imagefile = null;
                    $videofile = null;
                }
            }

            if ($crud->create("posts", [
                'content' => $_POST['content'],
                'user_id' => $_SESSION['user_id'],
                'image' => $imagefile,
                'video' => $videofile
            ])) {
                if (!is_null($imagefile)) {
                    move_uploaded_file($_FILES['post-file']['tmp_name'], 'post-photos/' . $imagefile);
                } elseif (!is_null($videofile)) {
                    move_uploaded_file($_FILES['post-file']['tmp_name'], 'post-videos/' . $videofile);
                }
            }
        }
    }
}