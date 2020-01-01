<?php

try {
    $sql = "SELECT user.* , jenis_sertifikasi.kepanjangan FROM user INNER JOIN jenis_sertifikasi WHERE user.id_jenis_sertifikasi = jenis_sertifikasi.id_jenis_sertifikasi";
} catch (PDOException $th) {}

?>

<h1 class="big-1">DAFTAR USER</h1>

<?php
// Aksi Edit dan Hapus
if (isset($_GET['do']) && isset($_GET['id'])) {
    // Aksi Edit
    if ($_GET['do'] == 'edit') {
        // Aksi Simpan Data
        if (isset($_POST['simpan'])) {
            // Filter inputan dari user
            $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
            $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
            $nama_lengkap = filter_input(INPUT_POST, "nama_lengkap", FILTER_SANITIZE_STRING);
            $nim = filter_input(INPUT_POST, "nim", FILTER_SANITIZE_STRING);
            $alamat = filter_input(INPUT_POST, "alamat", FILTER_SANITIZE_STRING);
            $tgl_lahir = filter_input(INPUT_POST, "tgl_lahir", FILTER_SANITIZE_STRING);
            $id_jenis = filter_input(INPUT_POST, "id_jenis", FILTER_SANITIZE_STRING);
            try {
                $sqlUpdate = "UPDATE `user` SET `username` = '$username' , `nama_lengkap` = '$nama_lengkap' , `nim` = '$nim' , `alamat` = '$alamat' , `tgl_lahir` = '$tgl_lahir' , `id_jenis_sertifikasi` = '$id_jenis' WHERE `id_user` = $id";
                $stmt = $db->exec($sqlUpdate);
                if (isset($stmt)) {
                    echo "
                        <script type='text/javascript'>
                            setTimeout(function () {  
                                swal({
                                    title: 'Data Berhasil Diubah',
                                    type: 'success',
                                    showConfirmButton: true
                                }, function() {
                                    window.location = '?page=user';
                                });
                            },10);
                        </script>
                    ";
                } else {
                    echo "
                        <script type='text/javascript'>
                            setTimeout(function () {  
                                swal({
                                    title: 'Terjadi Kesalahan!',
                                    type: 'error',
                                    showConfirmButton: true
                                }, function() {
                                    window.location = '?page=user';
                                });
                            },10);
                        </script>
                    ";
                }
            } catch (Exception $th) {}
        } else {
            $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
            try {
            $sqlEdit = "SELECT * FROM user WHERE id_user='$id'";
                foreach ($db->query($sqlEdit) as $res) { ?>
                    <form action="" method="post">
                        <table>
                            <tr>
                                <th colspan="4">EDIT DATA</th>
                            </tr>
                            <tr>
                                <td><label for="username">Username:</label><input type="text" name="username" value="<?=$res['username']?>"></td>
                                <td><label for="nama_lengkap">Nama Lengkap:</label><input type="text" name="nama_lengkap" value="<?=$res['nama_lengkap']?>"></td>
                                <td><label for="nim">NIM:</label><input type="text" name="nim" value="<?=$res['nim']?>"></td>
                                <td><label for="alamat">Alamat:</label><input type="text" name="alamat" value="<?=$res['alamat']?>"></td>
                            </tr>
                            <tr>
                                <td colspan="2"><label for="tgl_lahir">Tanggal Lahir</label><input type="date" name="tgl_lahir" value="<?=$res['tgl_lahir']?>"></td>
                                    <td colspan="2">
                                        <label for="id_jenis">Jenis Sertifikasi:</label>
                                        <select name="id_jenis">
                                        <?php try {
                                        echo "<option value='' selected disabled>Pilih Jenis Sertifikasi</option>";
                                        foreach ($db->query("SELECT * FROM jenis_sertifikasi") as $row) { ?>
                                            <option <?php $selected = ($row['id_jenis_sertifikasi'] == $res['id_jenis_sertifikasi']) ? "selected" : "" ; echo $selected;?> value="<?=$row['id_jenis_sertifikasi']?>"><?=$row['nama']?></option>
                                            <?php }
                                        } catch (Exception $th) { ?>
                                            <option disabled>Gagal Memuat Data!</option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><input type="button" title="Kembali" value="Kembali" class="btn" onclick="javascript:history.back()"></td>
                                <td colspan="2"><input type="hidden" name="id" value="<?=$res['id_user']?>"><input class="btn" name="simpan" title="Simpan Data" type="submit" value="💾"></td>
                            </tr>
                        </table>
                    </form>
                <?php }
            } catch (Exception $th) {}
        }
    } elseif ($_GET['do'] == 'hapus') {
        try {
            $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
            $sqlDelete =  "DELETE FROM `user` WHERE `id_user` = $id";
            $stmt = $db->exec($sqlDelete);
            if (isset($stmt)) {
                echo "
                        <script type='text/javascript'>
                            setTimeout(function () {  
                                swal({
                                    title: 'Data Berhasil Dihapus',
                                    type: 'success',
                                    showConfirmButton: true
                                }, function() {
                                    window.location = '?page=sertifikasi';
                                });
                            },10);
                        </script>
                    ";
                } else {
                    echo "
                        <script type='text/javascript'>
                            setTimeout(function () {  
                                swal({
                                    title: 'Terjadi Kesalahan!',
                                    type: 'error',
                                    showConfirmButton: true
                                }, function() {
                                    window.location = '?page=sertifikasi';
                                });
                            },10);
                        </script>
                    ";
                }
        } catch (Exception $th) {}
    }
} else { ?>

<table class='center'>
    <tr>
        <th>No</th>
        <th>Username</th>
        <th>Waktu Daftar</th>
        <th>Nama Lengkap</th>
        <th>NIM</th>
        <th>Tanggal Lahir</th>
        <th>Jenis Sertifikasi</th>
        <th>Edit</th>
        <th>Hapus</th>
    </tr>
    <?php
    foreach ($db->query($sql) as $res) { ?>
    <tr>
        <td><?=$res['id_user']?></td>
        <td><?=$res['username']?></td>
        <td><?=$res['waktu_daftar']?></td>
        <td><?=$res['nama_lengkap']?></td>
        <td><?=$res['nim']?></td>
        <td><?=$res['tgl_lahir']?></td>
        <td><?=$res['kepanjangan']?></td>
        <td><a href="?page=user&do=edit&id=<?=$res['id_user']?>"><button class="ic">✏</button></a></td>
        <td><a href="?page=user&do=hapus&id=<?=$res['id_user']?>"><button class="ic">🗑</button></a></td>
    </tr>
    <?php } ?>
</table>

<?php } ?>