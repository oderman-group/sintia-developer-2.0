<?php
include("session.php");

$_SESSION['docente'] = $_SESSION['id'];

$_SESSION['id'] = $_GET['user'];

$url = '../estudiante/index.php';

header("Location:".$url);