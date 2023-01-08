<?php
session_start();
$_SESSION['id'] = $_SESSION['admin'];
$_SESSION['admin'] = '';
unset( $_SESSION["admin"] );

header("Location:../directivo/usuarios.php?tipo=".$_GET['tipo']);