<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    include 'includes/header.php';
    include 'createPost.php';
    include 'classes/Posts.php';

    $_SESSION['title'] = 'Homepage';

    if(!isset($_SESSION['is_loggedin'])) {
        header('Location: login.php');
    }

    $postDB = new Posts();
    $posts = $postDB->readPosts();

?>

<div class="wrapper">
    <form action="<?php $_SERVER['PHP_SELF'] ?>" class="post--form" method="post" enctype="multipart/form-data">
        <div class="post--textarea">
            <textarea name="content" id="" cols="30" rows="3"></textarea>
            <label for="textarea--label">What's on your mind?</label>
        </div>
        <div class="post--file">
            <input type="file" id="file" name="post-file">
        </div>
        <button type="submit" name="post-button"><i class="ph ph-paper-plane-tilt"></i></button>
    </form>
</div>

<div class="wrapper">
    <?php include 'post.php'; ?>
</div>

