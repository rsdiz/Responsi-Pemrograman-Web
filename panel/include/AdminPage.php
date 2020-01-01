<?php

try {
    $sql = "SELECT * FROM admin";
} catch (Exception $th) {
    // echo "Error: " + $th->getMessage();
}

?>

<h1 class="big-1">DAFTAR ADMIN</h1>

    <div class="middle">
        <table>
            <tr>
                <th>No</th>
                <th>Username</th>
            </tr>
            <?php
            foreach ($db->query($sql) as $res) { ?>
            <tr>
                <td><?=$res['id']?></td>
                <td><?=$res['username']?></td>
            </tr>
            <?php } ?>
        </table>
    </div>