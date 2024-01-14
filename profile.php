<?php
    include 'includes/header.php';
    include 'classes/User.php';

    $crud = new CRUD;
    $postDB = new Posts($crud);
    $userDB = new User($crud, $postDB);

    $user = $userDB->getUserProfile();

    extract($user[0]);

    $posts = $userDB->getUserPosts($id);

    if (count($_FILES) > 0) {

        if ($_FILES["newbanner"]) {
            $userDB->updateBanner($id);
        }

        if ($_FILES["newpfp"]) {
            $userDB->updateProfilePicture($id);
        }
    }
?>

<div class="profile--header">
    <img src="bannerpics/<?= $cover ?>" class="profile--cover" alt="">
    </div>
    <?php if($_SESSION['user_id'] == $id) : ?>
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
            <label class="cover--change shadow-sm">
                <p>Update cover photo</p>
                <i class="ph ph-camera"></i>
                <input type="file" id="formFile" name="newbanner" onchange="this.form.submit()">
            </label>
        </form>
    <?php endif; ?>
</div>


<div class="profile--infos">
    <div class="left--profile--part">
        <div class="profile--header">
            <img src="profilepics/<?= $pfp ?>" class="profile--cover" alt="">
            <?php if($_SESSION['user_id'] == $id) : ?>
                <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                    <label class="pfp--change shadow-sm">
                        <i class="ph ph-camera"></i>
                        <input type="file" id="formFile" name="newpfp" onchange="this.form.submit()">
                    </label>
                </form>
            <?php endif; ?>
        </div>
        <div class="profile--cart">
            <h2 class="user--emri"><?= $fullname ?></h2>
            <p class="user--friends">0 friends</p>
            <p>Gender : <?= $gender ?></p>
            <hr>
            <p class="user--bio">Bio</p>
            <p><?= $bio ?></p>
        </div>
        <div class="photos--cart cart">
            <div class="photos--cart-text cart-text">
                <h2>Photos</h2>
                <a href="">See all photos</a>
            </div>
            <div class="photos--cart-content cart--img">
                <?php foreach($posts as $post) : ?>
                    <?php if($post['image']) : ?>
                        <img src="post-photos/<?=$post['image']?>" alt="">
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="friends--cart cart">
            <div class="friends--cart-text cart-text">
                <h2>Friends</h2>
                <a href="">See all friends</a>
            </div>
            <div class="friends--cart-content cart--img">
                <img src="images/tree-736885_1280.jpg" alt="">
                <p>Filan Fisteku 2</p>
            </div>
        </div>
    </div>

    <div class="right--profile--part">

        <?php if($_SESSION['user_id'] == $id) : ?>
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
        <?php endif; ?>

        <?php include 'post.php'; ?>
    </div>
</div>
