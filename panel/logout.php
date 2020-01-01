<?php

session_start();
session_unset("admin");
session_destroy();
header("Location: login.php");

?>