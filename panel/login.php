<?php
session_start();
if (isset($_SESSION["admin"])) header("Location: index.php");
else session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <link rel="stylesheet" href="/assets/css/sweetalert.min.css">
    <title>Panel Admin</title>
</head>
<body>
<?php

require_once '../init/db.php';
// Membuat koneksi ke database
try {
    $database = new db();
    $db = $database->openConnection();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
};
$username = "";

if (isset($_POST['login'])) {
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

    $queryLogin = "SELECT * FROM `admin` WHERE username=:username";
    $stmt = $db->prepare($queryLogin);
    // bind parameter ke query
    $params = array(":username" => $username);
    $stmt->execute($params);
    $adm = $stmt->fetch(PDO::FETCH_ASSOC);
    // jika user terdaftar
    if($adm){
        // verifikasi password
        if($password == $adm["password"]){
        // buat Session
            session_start();
            $_SESSION["admin"] = $adm;
            // login sukses, alihkan ke halaman timeline
            header("Location: index.php");
        } else {
        echo "
        <script type='text/javascript'>
            setTimeout(function () {  
                swal({
                    title: 'Username atau Password salah!',
                    type: 'error',
                    timer: 10000,
                    showConfirmButton: true
                });
            },10);
        </script>
            ";
        }
    } else {
        echo "
            <script type='text/javascript'>
                setTimeout(function () {  
                    swal({
                        title: 'Username atau Password salah!',
                        type: 'error',
                        timer: 10000,
                        showConfirmButton: true
                    });
                },10);
            </script>
        ";
    }
}

?>

    <div class="content-middle">
        <div id="content">
            <h1 class="big-1">LOGIN ADMIN</h1>
            <form action="" name="loginForm" method="POST" onsubmit="return validateForm()">
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td><p id="uname"></p><input value="<?=$username?>" tabindex="1" placeholder="Masukkan Username" type="text" name="username" autocomplete="username"></td>
                        <td rowspan="2"><input style="width: 100%; height: 5em;" tabindex="3" type="submit" value="Login" name="login"></td>
                    </tr>
                    <tr>
                        <td><p id="pass"></p><input placeholder="Masukkan Password" type="password" name="password" autocomplete="off" tabindex="2"></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>

    <script src="/assets/js/sweetalert.min.js"></script>
    <script>
    // script untuk memvalidasi apakah form kosong atau tidak
    function validateForm() {
        let error = 0;
        let erUname = "", erPass = "";
        // mengambil isi inputan pada username
        let username = document.forms["loginForm"]["username"].value;
        // mengambil isi inputan pada password
        let password = document.forms["loginForm"]["password"].value;
        // jika username kosong, maka ..
        if (username == "" || username == null) {
            error++; erUname = "<div class='err'>Username Belum diisi!</div>";
        }
        // jika password kosong, maka ..
        if (password == "" || password == null) {
            error++; erPass = "<div class='err'>Password Belum diisi!</div>";
        }
        // jika terdapat error, maka muncul alert dialog dan info letak errornya
        if (error > 0) {
            setTimeout(function () {
                swal({
                    title: 'Data belum lengkap!',
                    text: 'Lengkapi semua data\n⠀',
                    type: 'error',
                    showConfirmButton: true
                });
            },10);
            document.getElementById("uname").innerHTML = erUname;
            document.getElementById("pass").innerHTML = erPass;
            return false;
        }
    }
    </script>
</body>
</html>