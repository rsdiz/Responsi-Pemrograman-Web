<?php

$user = $_SESSION['admin']["username"];
$total = array('user' => 0,'sertifikasi' => 0 );

try {

    function sqlCount($p,$q) {
        return "SELECT COUNT($p) AS total FROM $q";
    }

    foreach ($db->query(sqlCount("id_user","user")) as $res) $total['user'] = $res['total'];
    foreach ($db->query(sqlCount("id_jenis_sertifikasi","jenis_sertifikasi")) as $res) $total['sertifikasi'] = $res['total'];

} catch (PDOException $th) {
    echo "Error: " + $th->getMessage();
}


?>
<h1 class="big-1">PANEL ADMIN</h1><br>
<p style="text-align:center">Admin: <b><?=$user?></b></p>
<div class="middle">
    <table cellpadding='0' cellspacing='0'>
        <tr>
            <th colspan="2">STATISTIK</th>
        </tr>
        <tr>
            <td>Total Pendaftar</td>
            <td><?=$total['user']?></td>
        </tr>
        <tr>
            <td>Jenis Sertifikasi</td>
            <td><?=$total['sertifikasi']?></td>
        </tr>
    </table>
</div>