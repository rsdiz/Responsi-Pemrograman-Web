<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/assets/css/sweetalert.min.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <title>Simulasi Responsi</title>
</head>
<body>

<?php
include_once 'init/db.php';
// Membuat koneksi ke database
try {
    $database = new db();
    $db = $database->openConnection();
} catch (PDOException $e) {};
// Query Insert dan Select
$queryInput = "INSERT INTO user (`username`,`password`,`waktu_daftar`,`nama_lengkap`,`nim`,`tgl_lahir`,`alamat`,`id_jenis_sertifikasi`) VALUES (:uname,:pass,:waktu,:nama,:nim,:tgl_lahir,:alamat,:id_jenis)";
$querySelectJS = "SELECT * FROM jenis_sertifikasi";
// Inisialisasi Awal
$uname = $nama_lengkap = $nim = $alamat = $ttl = $id_jenis = "";

if (isset($_POST["ok"])) {
    // Mengkonversi inputan user
    $uname = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $nama_lengkap = filter_input(INPUT_POST, "nama_lengkap", FILTER_SANITIZE_STRING);
    $nim = filter_input(INPUT_POST, "nim", FILTER_SANITIZE_STRING);
    $alamat = filter_input(INPUT_POST, "alamat", FILTER_SANITIZE_STRING);
    $ttl = filter_input(INPUT_POST, "ttl", FILTER_SANITIZE_STRING);
    $id_jenis = filter_input(INPUT_POST, "id_jenis", FILTER_SANITIZE_NUMBER_INT);
    // Generate random password
    $pass = randomPassword();
    // Save current date
    $waktu_daftar = date("Y-m-d");

    try {
        // SQL insert
        $stmt = $db->prepare($queryInput);
        // bind parameter to query
        $params = array(':uname' => $uname, ':pass' => $pass, ':waktu' => $waktu_daftar, ':nama' => $nama_lengkap, ':nim' => $nim, ':tgl_lahir' => $ttl, ':alamat' => $alamat, ':id_jenis' => $id_jenis);
        // Insert Record
        $stmt->execute($params);
        echo "
            <script type='text/javascript'>
                setTimeout(function () {  
                    swal({
                        title: 'Berhasil Mendaftar',
                        text: 'Silahkan Login menggunakan password: $pass\\n⠀',
                        type: 'success',
                        showConfirmButton: true
                    }, function() {
                        window.location = '.';
                    });
                },10);
            </script>
        ";
    } catch (PDOException $th) {
        echo "
            <script type='text/javascript'>
                setTimeout(function () {  
                    swal({
                        title: 'Terjadi Kesalahan!',
                        type: 'error',
                        timer: 3000,
                        showConfirmButton: true
                    });
                },10);
            </script>
        ";
        // echo "Error: " . $th->getMessage();
    }
}
// Function to generate random password
function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array();
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}

?>
<div class="content-middle">
    <div id="nav" class="sidenav">
        <span class="closebtn" onclick="closeNav()">&times;</span>
        <a href="/panel"><?php if(!isset($_SESSION["admin"])) echo 'Login Admin'; else echo 'Panel Admin';?></a>
    </div>
    <div id="content">
        <a onclick="openNav()" style="font-size: 30px; cursor: pointer; margin-left: 10px;">&#9776;</a>
        <h1 class="big-1">Registrasi Sertifikasi</h1>
        <form onsubmit="return validateForm()" name="registerForm" action="" method="post">
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td colspan="2"><p id="uname"></p><input value="<?=$uname?>" type="text" name="username" placeholder="Masukkan Username" autocomplete="nickname" maxlength="20"></td>
                </tr>
                <tr>
                    <td colspan="2"><p id="name"></p><input value="<?=$nama_lengkap?>" type="text" placeholder="Masukkan Nama" name="nama_lengkap" autocomplete="name" maxlength="50"></td>
                </tr>
                <tr>
                    <td colspan="2"><p id="nim"></p><input value="<?=$nim?>" type="text" placeholder="Masukkan NIM" name="nim" autocomplete="off" maxlength="11"></td>
                </tr>
                <tr>
                    <td colspan="2"><p id="address"></p><input value="<?=$alamat?>" type="text" name="alamat" placeholder="Masukkan Alamat" autocomplete="address-level1"></td>
                </tr>
                <tr>
                    <td><p id="ttl"></p><input value="<?=$ttl?>" type="date" name="ttl" title="Masukkan Tanggal Lahir"></td>
                    <td>
                        <p id="id_jenis"></p>
                        <select name="id_jenis">
                            <?php
                                try {
                                    echo "<option value='' selected disabled>Pilih Jenis Sertifikasi</option>";
                                    foreach ($db->query($querySelectJS) as $row) { ?>
                                        <option <?php $selected = ($row['id_jenis_sertifikasi'] == $id_jenis) ? "selected" : "" ; echo $selected;?> value="<?=$row['id_jenis_sertifikasi']?>"><?=$row['nama']?></option>
                                    <?php }
                                } catch (Exception $th) { ?>
                                        <option disabled>Gagal Memuat Data!</option>
                                <?php }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="reset" value="CLEAR"><input type="submit" name="ok" value="DAFTAR"></td>
                </tr>
            </table>
        </form>
    </div>
</div>

<script src="/assets/js/sweetalert.min.js"></script>
<script>
function validateForm() {
    let error = 0;
    let erUname = "", erName = "", erNim = "", erAdd = "", erTTL = "", erIdJenis = "";
    let username    = document.forms["registerForm"]["username"].value;
    let name        = document.forms["registerForm"]["nama_lengkap"].value;
    let nim         = document.forms["registerForm"]["nim"].value;
    let address     = document.forms["registerForm"]["alamat"].value;
    let ttl         = document.forms["registerForm"]["ttl"].value;
    let id_jenis    = document.forms["registerForm"]["id_jenis"].value;

    if (username == "" || username == null) {
        error++; erUname = "<div class='err'>Username Belum diisi!</div>";
    }
    if (name == "" || name == null) {
        error++; erName = "<div class='err'>Nama Lengkap Belum diisi!</div>";
    }
    if (nim == "" || nim == null) {
        error++; erNim = "<div class='err'>NIM Belum diisi!</div>";
    }
    if (address == "" || address == null) {
        error++; erAdd = "<div class='err'>Alamat Belum diisi!</div>";
    }
    if (ttl == "" || ttl == null) {
        error++; erTTL = "<div class='err'>Tanggal Lahir Belum diisi!</div>";
    }
    if (id_jenis == "" || id_jenis == null) {
        error++; erIdJenis = "<div class='err'>Pilih Jenis Sertifikasi</div>";
    }

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
        document.getElementById("name").innerHTML = erName;
        document.getElementById("nim").innerHTML = erNim;
        document.getElementById("address").innerHTML = erAdd;
        document.getElementById("ttl").innerHTML = erTTL;
        document.getElementById("id_jenis").innerHTML = erIdJenis;

        return false;
    } else {
        return true;
    }
}

function openNav() {
    document.getElementById("nav").style.width = "250px";
    document.getElementById("content").style.marginLeft = "250px";
    document.getElementById("content").style.opacity = "0.2";
}

function closeNav() {
    document.getElementById("nav").style.width = "0px";
    document.getElementById("content").style.marginLeft = "0px";
    document.getElementById("content").style.opacity = "1";
}
</script>
</body>
</html>