<?php
    session_start();

    include 'classes/Friends.php';
    include 'classes/User.php';

    $crud = new CRUD;
    $postDB = new Posts($crud);
    $userDB = new User($crud, $postDB);
    $friendsDB = new Friends;

    $user = $userDB->getUserProfile();

    extract($user[0]);

    if ($_GET['id'] === $_SESSION['user_id']) {
        $_SESSION['title'] = "My Profile";
    } else {
        $_SESSION['title'] = "Profile of ".$user[0]['fullname'];
    }

    include 'includes/header.php';


    $posts = $userDB->getUserPosts($id);

    if (count($_FILES) > 0) {
        if ($_FILES["newbanner"]) {
            if($banner) {
                if(file_exists('bannerpics/'.$banner)) {
                    unlink('bannerpics/'.$banner);
                }
            }

            $userDB->updateBanner($id);
        }

        if ($_FILES["newpfp"]) {
            if($pfp) {
                if(file_exists('profilepics/'.$pfp)) {
                    unlink('profilepics/'.$pfp);
                }
            }

            $userDB->updateProfilePicture($id);
        }
    }

    $friends = $friendsDB->readFriends($id);

    for($i = 0; $i<count($friends); $i++) {
        if($friends[$i]['id1'] === $id) {
            unset($friends[$i]['id1']);
            unset($friends[$i]['fullname1']);
            unset($friends[$i]['pfp1']);
        } else {
            unset($friends[$i]['id2']);
            unset($friends[$i]['fullname2']);
            unset($friends[$i]['pfp2']);
        }
    }

    $friendStatus = $friendsDB->isFriend($id, $_SESSION['user_id']);

    if ($_POST) {
        if (isset($_POST['addfriend'])) {
            $crud->create("friends", ['user_id' => $_SESSION['user_id'], 'user_id2' => $id]);
            header('Location: profile.php?id='.$_GET['id']);
        }
    }

    if ($id === $_SESSION['user_id']) {
        $requests = $friendsDB->readRequests($_SESSION['user_id']);
    }
?>

<div class="profile--header">
    <div class="profile--cover" style="background-image: url('bannerpics/<?= $cover ?>');">
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
            <form action="<?=$_SERVER['PHP_SELF']?><?="?id=".$id?>" method="post" style="z-index: 1">
                <?php if ($_GET['id'] !== $_SESSION['user_id']) : ?>
                    <?php if(!count($friendStatus)): ?>
                        <button type="submit" class="add--friend" name="addfriend">Add Friend</button>
                    <?php elseif(!$friendStatus[0]['accepted'] && $friendStatus[0]['user_id'] === $_SESSION['user_id']): ?>
                        <button class="pending--status">Pending</button>
                    <?php elseif(!$friendStatus[0]['accepted'] && $friendStatus[0]['user_id'] !== $_SESSION['user_id']): ?>
                        <div class="buttons">
                            <a class="accept--friend" href="acceptRequest.php?id=<?=$friendStatus[0]['user_id']?>">Accept</a>
                            <a class="reject--friend" href="rejectRequest.php?id=<?=$friendStatus[0]['user_id']?>">Reject</a>
                        </div>
                    <?php else: ?>
                        <a class="btn btn-danger mb-2 unfriend" name="unfriend" href="rejectRequest.php?id=<?= $id ?>">Unfriend</a>
                    <?php endif;?>
                <?php endif; ?>
            </form>
            <p class="user--friends"><?= count($friends); ?> Friends</p>
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
            <div class="friends">
                <?php foreach($friends as $friend): ?>
                    <div class="friends--cart-content cart--img">
                        <img src="profilepics/<?php echo isset($friend['pfp1']) ? $friend['pfp1'] : $friend['pfp2'] ?>" alt="">
                        <a href="profile.php?id=<?php echo isset($friend['id1']) ? $friend['id1'] : $friend['id2'] ?>"><?php echo isset($friend['fullname1']) ? $friend['fullname1'] : $friend['fullname2'] ?></a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php if($_GET['id'] === $_SESSION['user_id']) : ?>
            <div class="friend--requests">
                <div class="friends--text">
                    <h4>Friend Requests</h4>
                </div>
                <?php if(count($requests) == 0) echo "0 Friend Requests" ?>
                <?php foreach($requests as $request): ?>
                    <div class="profile--friendsreq">
                        <div class="infos">
                            <img src="profilepics/<?=$request['pfp']?>" class="shadow-sm" alt="">
                            <p><?=$request['fullname'] ?></p>
                        </div>
                        <div class="buttons">
                            <a class="accept--friend" href="acceptRequest.php?id=<?=$request['id']?>">Accept</a>
                            <a class="reject--friend" href="rejectRequest.php?id=<?=$request['id']?>">Reject</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
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
