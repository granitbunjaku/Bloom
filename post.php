<?php

    include 'classes/Comments.php';
    $commentsDB = new Comments;

    $comments = $commentsDB->readComments();

?>

    <?php foreach($posts as $post) : ?>
        <?php extract($post); ?>
        <div class="post">
            <div class="post--header">
                <div class="first--header--part">
                    <img src="profilepics/<?= $pfp ?>" alt="">
                    <a href="profile.php?id=<?= $uid ?>" style="text-decoration: none; color: black; font-weight:600;"><p class="user--name"><?= $fullname ?></p></a>
                </div>
                <div>
                    <?php if($uid === $_SESSION['user_id']) : ?>
                        <a href="deleteItems.php?post_id=<?=$id?><?= isset($_GET['id']) ? "&id=".$_GET['id'] : ''?>" style="text-decoration: none; color: black; font-weight:600;">Delete</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="post--content">
                <?php if (str_contains($content, "iframe=")) : ?>
                    <?php $url = explode("=",$content); $content1 = explode("iframe",$url[0])[0];?>
                    <form class="editContentForm">
                        <input type="text" class="editContent" name="<?=$uid?>" value="<?= $content ?>" id="<?=$id?>" hidden>
                    </form>
                    <p class="content" id="<?=$id?>">
                        <?php
                            $arr = explode("iframe=", $content);
                            foreach($arr as $a) {
                                if(str_starts_with($a, "http")) {
                                    $arrLink = explode(" ", $a, 2);

                                    echo "<a href='$arrLink[0]'>$arrLink[0] </a>";

                                    if(isset($arrLink[1])) {
                                        echo $arrLink[1];
                                    }
                                } else {
                                    echo $a;
                                }

                            } ?></p>
                <?php else: ?>
                    <form class="editContentForm">
                        <input type="text" class="editContent" name="<?=$uid?>" value="<?= $content ?>" id="<?=$id?>" hidden>
                    </form>
                    <p class="content" id="<?=$id?>"><?= $content ?></p>
                <?php endif; ?>
            </div>


            <?php if ($image) : ?>
                <div class="post--image" style="background-image: url('post-photos/<?=$post['image'] ?>')"></div>
            <?php elseif ($video) : ?>
                <video width="400" controls>
                    <source src="post-videos/<?= $video ?>" type="video/mp4">
                </video>
            <?php elseif (str_contains($content, "iframe=")) : ?>
                <?php $url = explode("=",$content); $content = explode("iframe",$url[0])[0]?>
                <iframe class="iframe"
                        src="<?=$url[1]?>">
                </iframe>
            <?php endif; ?>

            <?php $allComments = []; ?>
            <?php foreach($comments as $comment) : ?>
                <?php if($comment['post_id'] === $post['id']) : ?>
                    <?php $allComments[] = $comment; ?>
                <?php endif; ?>
            <?php endforeach; ?>

            <div class="post--insight">
                <?php if (exists($likes, function ($e) {
                    return $e == $_SESSION['user_id'];
                })) : ?>
                    <p><i class="ph-fill ph-thumbs-up like" id="<?= $id ?>"></i> &nbsp; <span><?= count($likes) ?></span> </p>
                <?php else: ?>
                    <p><i class="ph ph-thumbs-up like" id="<?= $id ?>"></i> &nbsp; <span><?= count($likes) ?></span> </p>
                <?php endif; ?>
                <p><i class="ph ph-chat-circle"></i> &nbsp; <span class="numOfComments" id="<?=$id?>"><?=count($allComments);?></span></p>
            </div>

            <form class="comment--input comment">
                <input type="text" class="form-control" id="<?= $id ?>" placeholder="Type comment">
            </form>

            <div class="comments">

                <?php foreach($allComments as $comment) : ?>
                    <div class="comment-holder">
                        <div class="comment--user">
                            <img src="profilepics/<?= $comment['pfp'] ?>" alt="profile pic">
                        </div>
                        <div class="comment--body">
                            <div class="comment--content">
                                <a href="profile.php?id=<?= $comment['id'] ?>" class="user--name"><?= $comment['fullname'] ?></a>
                                <p><?= $comment['content'] ?></p>
                                <form class="editCommentForm">
                                    <input type="text" class="form-control editContent" name="<?=$comment['id']?>" value="<?= $comment['content'] ?>" id="<?=$comment['comment_id']?>" hidden>
                                </form>
                            </div>
                            <div class="deletelink--holder">
                                <a id="<?= $comment['comment_id']?>" class="delete--comment">Delete</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    <?php endforeach; ?>

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    let like = document.getElementsByClassName("like");
    let postContent = document.getElementsByClassName('post--content');
    let editContent = document.getElementsByClassName('editContent');
    let editForm = document.getElementsByClassName('editContentForm');
    let editCommentForm = document.getElementsByClassName('editCommentForm');
    let iframe = document.getElementsByClassName('iframe');
    let comment = document.getElementsByClassName("comment");
    let comments = document.querySelector(".comments");
    let deleteComments = document.getElementsByClassName("delete--comment");
    let commentContent = document.getElementsByClassName("comment--content");

    for(let i = 0; i < commentContent.length; i++) {
        commentContent[i].addEventListener("click", () => {
            commentContent[i].querySelector('p').style.display = "none";
            let inputi = commentContent[i].querySelector(".editCommentForm input");
            inputi.removeAttribute("hidden");
        })

        commentContent[i].querySelector('.editCommentForm').addEventListener('submit', (e) => {
            e.preventDefault();

            let inputi = e.target.querySelector("input");

            axios.post('editComment.php', {
                comment_id: inputi.id,
                content: inputi.value,
                user_id: inputi.name
            })
            .then(data => {
                const statusCode = data.headers['x-status-code'];

                if (statusCode === '200') {
                    commentContent[i].querySelector('p').innerText = inputi.value;
                    commentContent[i].querySelector('p').style.display = "block";
                    inputi.hidden = true;
                } else {
                    inputi.setAttribute("hidden", true);
                    commentContent[i].querySelector("p").style.display = "block";
                    inputi.value = commentContent[i].querySelector("p").innerText;
                }

            })
        });
    }

    for (let i = 0; i < like.length; i++) {
        like[i].addEventListener("click", (e) => {

            axios.get(`likePost.php?id=${e.target.id}`)
                .then(data => {
                    let likes = e.target.parentNode.querySelector('span').innerText;
                    let x;
                    if (e.target.classList.contains("ph")) {
                        x = parseInt(likes) + 1;
                        e.target.classList.replace("ph", "ph-fill");
                    } else {
                        x = parseInt(likes) - 1;
                        e.target.classList.replace("ph-fill", "ph");
                    }
                    e.target.parentNode.querySelector('span').innerText = x;
                }).catch(error => console.log(error));
        })
    }

    for(let i = 0; i < postContent.length; i++) {
        postContent[i].addEventListener("click", (e) => {
            let user_id = <?=$_SESSION['user_id']?>;

            let postIFrame = postContent[i].parentNode.querySelector('iframe');

            if(user_id == editForm[i].querySelector("input").name) {
                postContent[i].querySelector("p").style.display = "none";
                editForm[i].querySelector(".editContent").removeAttribute("hidden");
            }

            editForm[i].addEventListener("submit", (e) => {
                e.preventDefault();
                const inputi = e.target.querySelector("input");
                axios.post('editPost.php', {
                    post_id: inputi.id,
                    new_content: inputi.value,
                    uid: inputi.name
                })
                    .then(data => {
                        const statusCode = data.headers['x-status-code'];

                        if (statusCode === '200') {
                            e.target.querySelector("input").setAttribute("hidden", true);
                            postContent[i].querySelector("p").style.display = "block";

                            if(inputi.value.includes("iframe=")) {
                                let text = inputi.value.split("=");
                                if(postIFrame) postIFrame.src = text[1];
                                let splitIframe = inputi.value.split("iframe=");

                                let wholetext = "";

                                splitIframe.forEach(t => {
                                    if(t.startsWith("http")) {
                                        const theText = t.split(" ");

                                        wholetext += `<a href=${theText[0]}>${theText[0]} </a>`;

                                        wholetext += t.split(theText[0])[1].trim();
                                    } else {
                                        console.log("why");
                                        wholetext += t;
                                    }
                                });

                                postContent[i].querySelector("p").innerHTML = wholetext;

                            } else {
                                postContent[i].querySelector(".content").innerText = inputi.value;
                            }
                        } else {
                            inputi.setAttribute("hidden", true);
                            inputi.value = postContent[i].querySelector("p").innerText;
                            postContent[i].querySelector("p").style.display = "block";
                        }

                    })
            })

        })
    }

    for (let i = 0; i < comment.length; i++) {
        comment[i].addEventListener("submit", (e) => {
            e.preventDefault();
            let commentInput = e.target.querySelector('input');
            let value = commentInput.value;
            let nr = e.target.parentNode.querySelector(".post--insight").querySelector(".numOfComments");

            axios.post('comment.php', {
                postId: commentInput.id,
                content: commentInput.value
            })
                .then(data => {
                    commentDiv = document.createElement('div');
                    imageDiv = document.createElement('div');
                    image = document.createElement('img');
                    bodyDiv = document.createElement('div');
                    contentDiv = document.createElement('div');
                    link = document.createElement('a');
                    content = document.createElement('p');
                    deleteLinkDiv = document.createElement('div');
                    deleteLink = document.createElement('a');

                    bodyDiv.classList.add('comment--body');
                    commentDiv.classList.add('comment-holder');

                    imageDiv.classList.add('comment--user');
                    image.setAttribute('src', "profilepics/<?=$_SESSION['pfp']?>");
                    image.setAttribute('alt', 'profile pic');

                    link.setAttribute("href", "profile.php?id=<?=$_SESSION['user_id']?>");
                    link.classList.add('user--name');
                    link.innerText = "<?= $_SESSION['fullname'] ?>";

                    contentDiv.classList.add('comment--content');
                    content.innerText = value;

                    deleteLinkDiv.classList.add('deletelink--holder');
                    deleteLink.innerText = 'Delete';

                    deleteLink.addEventListener("click", (e) => {
                        axios.post(`deleteItems.php?comment_id=${data.data}`,)
                            .then(data => {
                                let nr = e.target.parentNode.parentNode.parentNode.parentNode.parentNode.querySelector('.post--insight').querySelector('.numOfComments');
                                nr.innerText = parseInt(nr.innerText) - 1;
                                e.target.parentNode.parentNode.parentNode.remove();
                            });
                    })

                    imageDiv.appendChild(image);

                    contentDiv.appendChild(link);
                    contentDiv.appendChild(content);
                    bodyDiv.appendChild(contentDiv);

                    deleteLinkDiv.appendChild(deleteLink);
                    bodyDiv.appendChild(deleteLinkDiv);

                    commentDiv.appendChild(imageDiv);
                    commentDiv.appendChild(bodyDiv);

                    comments.appendChild(commentDiv);

                    nr.innerText = parseInt(nr.innerText) + 1;
                }).catch(error => console.log('error'));

            commentInput.value = '';
        });
    }

    for(let i = 0; i < deleteComments.length; i++) {
        deleteComments[i].addEventListener('click', (e) => {
            axios.post(`deleteItems.php?comment_id=${e.target.id}`,)
                .then(data => {
                    let nr = e.target.parentNode.parentNode.parentNode.parentNode.parentNode.querySelector('.post--insight').querySelector('.numOfComments');
                    nr.innerText = parseInt(nr.innerText) - 1;
                    e.target.parentNode.parentNode.parentNode.remove();
                });
        })
    }
</script>