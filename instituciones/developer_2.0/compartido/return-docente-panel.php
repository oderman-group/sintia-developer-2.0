<?php
session_start();
$_SESSION['id'] = $_SESSION['docente'];
$_SESSION['docente'] = '';
unset( $_SESSION["docente"] );

header("Location:../docente/index.php");