<?php 
include("session.php");
$idPaginaInterna = 'DT0131';
include("../compartido/sintia-funciones.php");
include("../compartido/guardar-historial-acciones.php");


$validarClave=validarClave($_POST["clave"]);
if($validarClave!=true){
	echo '<script type="text/javascript">window.location.href="usuarios-editar.php?error=5&id='.$_POST["idR"].'";</script>';
	exit();
}

mysqli_query($conexion, "UPDATE usuarios SET 
uss_usuario=           '" . $_POST["usuario"] . "', 
uss_clave=             '" . $_POST["clave"] . "', 
uss_tipo=              " . $_POST["tipoUsuario"] . ", 
uss_nombre=            '" . $_POST["nombre"] . "', 
uss_email=             '" . strtolower($_POST["email"]) . "', 
uss_genero=            '" . $_POST["genero"] . "',
uss_celular=           '" . $_POST["celular"] . "',
uss_ocupacion=         '" . $_POST["ocupacion"] . "',
uss_lugar_expedicion=  '" . $_POST["lExpedicion"] . "',
uss_direccion=         '" . $_POST["direccion"] . "',
uss_telefono=          '" . $_POST["telefono"] . "',
uss_intentos_fallidos= '" . $_POST["intentosFallidos"] . "',

uss_ultima_actualizacion=now()
WHERE uss_id='" . $_POST["idR"] . "'");

if ($_POST["tipoUsuario"] == 4) {
	mysqli_query($conexion, "UPDATE academico_matriculas SET mat_email='" . strtolower($_POST["email"]) . "'");
}

echo '<script type="text/javascript">window.location.href="usuarios-editar.php?id='.$_POST["idR"].'&success=SC_DT_2";</script>';
exit();