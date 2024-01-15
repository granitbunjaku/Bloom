<?php

include 'includes/header.php';
include 'classes/CRUD.php';
include 'classes/Reports.php';

if(!$_SESSION['is_admin']) {
    header('Location: index.php');
}

$crud = new CRUD;
$reportsDB = new Reports($crud);

$reports = $reportsDB->getAllReports($crud);

?>

<div class="dashboard--reports">
    <table class="reports">
        <tr>
            <th>Email of user</th>
            <th>Title</th>
            <th>Content</th>
        </tr>
        <?php foreach($reports as $report) : ?>
            <tr>
                <td><?= $report['email'] ?></td>
                <td><?= $report['title'] ?></td>
                <td><?= $report['content'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
