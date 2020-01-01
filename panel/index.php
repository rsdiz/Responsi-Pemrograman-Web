<?php

include_once '../init/db.php';
include_once 'auth.php';
try {
    $database = new db();
    $db = $database->openConnection();
} catch (PDOException $th) {};

echo file_get_contents("include/header.html");

?>

<div id="nav" class="sidenav">
    <span id="close" class="closebtn" onclick="closeNav()" style="visibility:hidden;">&times;</span>
    <a href=".">HOME</a>
    <a href="?page=user">PENDAFTAR</a>
    <a href="?page=sertifikasi">SERTIFIKASI</a>
    <a href="?page=admin">ADMIN</a>
    <a href="logout.php">LOGOUT</a>
</div>
<div id="content">
    <a onclick="openNav()" style="font-size: 30px; cursor: pointer; margin-left: 10px;">&#9776;</a>
    <?php
    if (isset($_GET['page'])) {
        if ($_GET['page'] == 'user') {
            include 'include/UserPage.php';
        } elseif ($_GET['page'] == 'sertifikasi') {
            include 'include/SertifikasiPage.php';
        } elseif ($_GET['page'] == 'admin') {
            include 'include/AdminPage.php';
        }
    } else {
        include 'include/DefaultPage.php';
    }
    ?>
</div>

<?php

echo file_get_contents("include/footer.html");

?>