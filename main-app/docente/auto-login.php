<?php
include("session.php");
$idPaginaInterna = 'DC0065';

$_SESSION['docente'] = $_SESSION['id'];

$_SESSION['id'] = $_GET['user'];

$url = '../estudiante/index.php';

header("Location:".$url);