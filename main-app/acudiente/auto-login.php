<?php
include("session.php");
$idPaginaInterna = 'AC0018';

$_SESSION['acudiente'] = $_SESSION['id'];

$_SESSION['id'] = $_GET['user'];

$url = '../estudiante/index.php';

header("Location:".$url);