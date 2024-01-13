<?php
include 'includes/header.php';
include 'classes/Posts.php';

$crud = new CRUD;
$postDB = new Posts;

include('createPost.php');

if (isset($_GET['id'])) {
    if ($_GET['id'] === $_SESSION['user_id']) {
        $user = $crud->read("users", ['id' => $_SESSION['user_id']]);
        $_SESSION['title'] = "My Profile";
    } else {
        $user = $crud->read("users", ['id' => $_GET['id']]);
        $_SESSION['title'] = "Profile of ".$user[0]['fullname'];
    }
}

extract($user[0]);

$posts = $postDB->readPosts("u.id", $id);

if (count($_FILES) > 0) {

    if ($_FILES["newbanner"]) {
        $bannerfile = time() . $_FILES['newbanner']['name'];

        if ($crud->update("users", [
            'cover' => $bannerfile
        ], ['column' => 'id', 'value' => $id])) {
            move_uploaded_file($_FILES['newbanner']['tmp_name'], 'bannerpics/' . $bannerfile);
            header('Location: profile.php?id=' . $_SESSION['user_id']);
        } else {
            echo "Something went wrong!";
        }
    }

    if ($_FILES["newpfp"]) {
        $pfpfile = time() . $_FILES['newpfp']['name'];

        if ($crud->update("users", [
            'pfp' => $pfpfile
        ], ['column' => 'id', 'value' => $id])) {
            move_uploaded_file($_FILES['newpfp']['tmp_name'], 'profilepics/' . $pfpfile);
            $_SESSION['pfp'] = $pfpfile;
            header('Location: profile.php?id=' . $_SESSION['user_id']);
        } else {
            echo "Something went wrong!";
        }
    }
}
