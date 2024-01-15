<?php
    session_start();

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    if(isset($_GET['action']) && $_GET['action'] == "log_out") {
        session_destroy();
        header('Location: ./login.php');
    }
?>

<head>
    <title>Social Pulse | <?= $_SESSION['title'] ?></title>
    <link rel="stylesheet" href="assets/css/index.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>

<nav>
    <div class="wrapper">
        <div class="left--part">
            <a href="./index.php"><img src="images/Bloom.png" alt="bloom logo"></a>
            <ul>
                <li>Home</li>
                <li class="explore"><?= isset($_SESSION['is_loggedin']) && $_SESSION['is_loggedin'] ? $_SESSION["fullname"] : "Guest" ?>
                    <i class="ph ph-caret-down arrow--down2"></i>
                    <ul class="nav--options">
                        <?php if(isset($_SESSION['is_loggedin']) && $_SESSION['is_loggedin']) : ?>
                            <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin']) : ?>
                                <a href="./dashboard.php" class="nav--link"><li>Dashboard</li></a>
                            <?php else: ?>
                                <a href="./reportProblems.php" class="nav--link"><li>Report Problems</li></a>
                            <?php endif; ?>
                            <a href="./profile.php?id=<?=$_SESSION['user_id']?>" class="nav--link"><li>Profile</li></a>
                            <a href="./settings.php" class="nav--link"><li>Settings</li></a>
                            <a href="?action=log_out" class="nav--link"><li>Log out</li></a>
                        <?php else : ?>
                            <a href="./login.php" class="nav--link"><li>Log In</li></a>
                            <a href="./register.php" class="nav--link"><li>Register</li></a>
                        <?php endif; ?>
                    </ul>
                </li>
        </div>
        <div class="right--part">
            <div class="search--div">
                <?php if (isset($_SESSION['is_loggedin'])) : ?>
                    <form class="">
                        <input class="search" type="search" name="search" placeholder="Search">
                    </form>
                <?php endif; ?>
                <div id="node-id" class="node-id"></div>
            </div>
        </div>

        <div class="icon">
            <i class="ph ph-list sidebar--icon"></i>
        </div>
    </div>
</nav>



<div class="sidebar">
    <div class="sidebar--header">
        <img src="images/Bloom.png" alt="bloom logo">
        <i class="ph ph-x close--icon"></i>
    </div>
    <ul>
        <li>Home</li>
        <li class="explore">Explore <i class="ph ph-caret-down arrow--down"></i></li>
        <ul class="sidenav--options">
            <?php if(isset($_SESSION['is_loggedin']) && $_SESSION['is_loggedin']) : ?>
                <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin']) : ?>
                    <a href="./dashboard.php" class="nav--link"><li>Dashboard</li></a>
                <?php endif; ?>
                <a href="./profile.php?id=<?=$_SESSION['user_id']?>" class="nav--link"><li>Profile</li></a>
                <a href="./settings.php" class="nav--link"><li>Settings</li></a>
                <a href="?action=log_out" class="nav--link"><li>Log out</li></a>
            <?php else : ?>
                <a href="./login.php" class="nav--link"><li>Log In</li></a>
                <a href="./register.php" class="nav--link"><li>Register</li></a>
            <?php endif; ?>
        </ul>
    </ul>
    <?php if (isset($_SESSION['is_loggedin'])) : ?>
        <form class="">
            <input class="search" type="search" name="search" placeholder="Search">
        </form>
    <?php endif; ?>
    <div id="node-id-sidebar" class="node-id-sidebar"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="assets/js/script.js"></script>

<script>
    let search = document.getElementsByClassName("search")[0];
    let searchSidebar = document.getElementsByClassName("search")[1];
    let body = document.body;
    let node = document.getElementById('node-id');
    let nodeSidebar = document.getElementById('node-id-sidebar');

    search.addEventListener("keyup", (e) => {
        axios.get(`./search.php?search=${e.target.value}`)
            .then(data => {
                node.innerHTML = data.data;
                node.style.display = "flex";
            });
    })

    searchSidebar.addEventListener("keyup", (e) => {
        axios.get(`./search.php?search=${e.target.value}`)
            .then(data => {
                nodeSidebar.innerHTML = data.data;
                nodeSidebar.style.display = "flex";
            });
    })


    body.addEventListener("click", () => {
        node.style.display = "none";
        nodeSidebar.style.display = "none";
    })
</script>