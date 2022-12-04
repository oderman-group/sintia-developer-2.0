<?php
include("php-funciones.php");
$valor = 50000;
pagarOnline($_POST['id'], $_POST['email'], $valor, $_POST['documentoAcudiente'], $_POST['nombreAcudiente'], $_POST['celular']);
?>



