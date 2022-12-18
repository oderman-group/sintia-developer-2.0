<?php
session_start();
$_SESSION['id'] = $_SESSION['acudiente'];
$_SESSION['acudiente'] = '';
unset( $_SESSION["acudiente"] );

header("Location:../acudiente/index.php");