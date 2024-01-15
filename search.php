<?php
    include('classes/CRUD.php');
    $crud = new CRUD;

    if (isset($_GET['search'])) {
        $users = $crud->search("users", "fullname", $_GET['search']);
    }
?>


<?php foreach ($users as $user) : ?>
    <div class="info--div">
        <img src="profilepics/<?= $user['pfp'] ?>" class="user--pfp" />
        <a href="./profile.php?id=<?= $user['id'] ?>" style="text-decoration: none; color: black;"><?= $user['fullname'] ?></a>
    </div>
<?php endforeach; ?>