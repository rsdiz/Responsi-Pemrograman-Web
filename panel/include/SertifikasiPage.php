<?php

try {
    $sql = "SELECT * FROM jenis_sertifikasi";
} catch (PDOException $th) {}

?>

<h1 class="big-1">JENIS SERTIFIKASI</h1>

<?php
// Aksi Edit dan Hapus
if (isset($_GET['do'])) {
    if (isset($_GET['id'])) {
        // Aksi Edit
        if ($_GET['do'] == 'edit') {
            // Aksi Simpan Data
            if (isset($_POST['simpan'])) {
                // Filter inputan dari user
                $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
                $nama = filter_input(INPUT_POST, "nama", FILTER_SANITIZE_STRING);
                $kepanjangan = filter_input(INPUT_POST, "kepanjangan", FILTER_SANITIZE_STRING);
                try {
                    $sqlUpdate = "UPDATE `jenis_sertifikasi` SET `nama` = '$nama' , `kepanjangan` = '$kepanjangan' WHERE `id_jenis_sertifikasi` = $id";
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
            } else {
                $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
                try {
                $sqlEdit = "SELECT * FROM jenis_sertifikasi WHERE id_jenis_sertifikasi='$id'";
                    foreach ($db->query($sqlEdit) as $res) { ?>
                        <form action="" method="post">
                            <table>
                                <tr>
                                    <th colspan="3">EDIT DATA</th>
                                </tr>
                                <tr>
                                    <td rowspan='2'><input type="button" title="Kembali" value="<" class="btn" onclick="javascript:history.back()"></td>
                                    <td><input type="text" name="nama" value="<?=$res['nama']?>"></td>
                                    <td rowspan="2"><input type="hidden" name="id" value="<?=$res['id_jenis_sertifikasi']?>"><input class="btn" name="simpan" title="Simpan Data" type="submit" value="💾"></td>
                                </tr>
                                <tr>
                                    <td><input type="text" name="kepanjangan" value="<?=$res['kepanjangan']?>"></td>
                                </tr>
                            </table>
                        </form>
                    <?php }
                } catch (Exception $th) {}
            }
        } elseif ($_GET['do'] == 'hapus') {
            try {
                $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
                $sqlDelete =  "DELETE FROM `jenis_sertifikasi` WHERE `id_jenis_sertifikasi` = $id";
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
            } catch (\Throwable $th) {}
        }
    }
}

?>

<table class='center'>
    <tr>
        <th>No</th>
        <th colspan="2">Jenis Sertifikasi</th>
        <th>Edit</th>
        <th>Hapus</th>
    </tr>
    <?php
    foreach ($db->query($sql) as $res) { ?>
    <tr>
        <td><?=$res['id_jenis_sertifikasi']?></td>
        <td><?=$res['nama']?></td>
        <td><?=$res['kepanjangan']?></td>
        <td><a href="?page=sertifikasi&do=edit&id=<?=$res['id_jenis_sertifikasi']?>"><button class="ic">✏</button></a></td>
        <td><a href="?page=sertifikasi&do=hapus&id=<?=$res['id_jenis_sertifikasi']?>"><button class="ic">🗑</button></a></td>
    </tr>
    <?php } ?>
</table>