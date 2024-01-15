<?php
    session_start();
    $_SESSION['title'] = 'Report Problems';

    include 'includes/header.php';
    include 'classes/CRUD.php';
    include 'classes/Reports.php';

    if($_SESSION['is_admin']) {
        header('Location: index.php');
    }

    $crud = new CRUD;
    $reportsDB = new Reports($crud);

    $errors = [];
    $success = null;

    if(isset($_POST['submit-report'])) {
        $errors = $reportsDB->sendReport();
        if(count($errors) == 0) {
            $timeout = 5;
            $success = "Successfully sent the report! Now you will be redirected to homepage! Enjoy socializing!";
            header("refresh:$timeout;url=index.php");
        }
    }
?>

<form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" class="report--form">
    <div class="error-holder">
        <?php foreach($errors as $error) : ?>
            <p class="error-message" id="errorMessage"><?=$error?></p>
        <?php endforeach; ?>
    </div>
    <?php if($success) echo $success; ?>
    <label>Title : </label>
    <input type="text" name="title" />
    <label>Explain the problem : </label>
    <textarea rows="15" name="content"></textarea>
    <button type="submit" name="submit-report">Send the report</button>
</form>
