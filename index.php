<?php
    session_start();

    if(!isset($_SESSION['is_loggedin'])) {
        header('Location: login.php');
    }

    $_SESSION['title'] = 'Homepage';

    include 'includes/header.php';
    include 'createPost.php';


    $crud = new CRUD();
    $postDB = new Posts($crud);

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

<div class="wrapper slider">
    <i class="ph ph-arrow-left prev--icon" onclick="slide(-1)"></i>
    <div class="slides">
        <?php foreach($posts as $post): ?>
            <?php if($post['image']): ?>
                <div class="slideItem" style="background-image: url('post-photos/<?=$post['image']?>');">
                    <p><?= $post['content'] ?></p>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <i class="ph ph-arrow-right next--icon" onclick="slide(1)"></i>
</div>

<div class="wrapper">
    <?php include 'post.php'; ?>
</div>

<script src="assets/js/slider.js"></script>

