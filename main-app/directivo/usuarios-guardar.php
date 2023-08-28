<?php 
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0132';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

include("../compartido/sintia-funciones.php");

try{
    $consultaUsuarioA = mysqli_query($conexion, "SELECT * FROM usuarios WHERE uss_usuario='" . $_POST["usuario"] . "'");
} catch (Exception $e) {
    include("../compartido/error-catch-to-report.php");
}
$numUsuarioA = mysqli_num_rows($consultaUsuarioA);
$datosUsuarioA = mysqli_fetch_array($consultaUsuarioA, MYSQLI_BOTH);
if ($numUsuarioA > 0) {
    
    include("../compartido/guardar-historial-acciones.php");
    echo '<script type="text/javascript">window.location.href="usuarios-agregar.php?error=ER_DT_1&usuario='.$_POST["usuario"].'&nombre='.$_POST["nombre"].'&nombre2='.$_POST["nombre2"].'&apellido1='.$_POST["apellido1"].'&apellido2='.$_POST["apellido2"].'&tipoD='.$_POST["tipoD"].'&documento='.$_POST["documento"].'&email='.$_POST["email"].'&celular='.$_POST["celular"].'&genero='.$_POST["genero"].'&tipoUsuario='.$_POST["tipoUsuario"].'";</script>';
    exit();
}
$validarClave=validarClave($_POST["clave"]);
if($validarClave!=true){
    
    include("../compartido/guardar-historial-acciones.php");
    echo '<script type="text/javascript">window.location.href="usuarios-agregar.php?error=5&usuario='.$_POST["usuario"].'&nombre='.$_POST["nombre"].'&nombre2='.$_POST["nombre2"].'&apellido1='.$_POST["apellido1"].'&apellido2='.$_POST["apellido2"].'&tipoD='.$_POST["tipoD"].'&documento='.$_POST["documento"].'&email='.$_POST["email"].'&celular='.$_POST["celular"].'&genero='.$_POST["genero"].'&tipoUsuario='.$_POST["tipoUsuario"].'";</script>';
    exit();
}

try{
    mysqli_query($conexion, "INSERT INTO usuarios (uss_usuario, uss_clave, uss_tipo, uss_nombre, uss_estado, uss_email, uss_celular, uss_genero, uss_foto, uss_portada, uss_idioma, uss_tema, uss_permiso1, uss_bloqueado, uss_fecha_registro, uss_responsable_registro, uss_ocupacion, uss_intentos_fallidos, uss_tema_sidebar,
    uss_tema_header, uss_tema_logo, uss_tipo_documento, uss_apellido1, uss_apellido2, uss_nombre2, uss_documento)VALUES(
        '" . $_POST["usuario"] . "',
        SHA1('" . $_POST["clave"] . "'),
        " . $_POST["tipoUsuario"] . ",
        '" . $_POST["nombre"] . "',
        0,
        '" . strtolower($_POST["email"]) . "',
        '" . $_POST["celular"] . "',
        " . $_POST["genero"] . ",
        'default.png',
        'default.png',
        1, 
        'green', 
        1,
        0,
        now(),
        '" . $_SESSION["id"] . "', 
        '" . $_POST["ocupacion"] . "',
        0,
        'cyan-sidebar-color',
        'header-indigo',
        'logo-indigo', 
        '" . $_POST["tipoD"] . "',
        '" . $_POST["apellido1"] . "',
        '" . $_POST["apellido2"] . "',
        '" . $_POST["nombre2"] . "',
        '" . $_POST["documento"] . "'
        )");
    $idRegistro = mysqli_insert_id($conexion);
} catch (Exception $e) {
    include("../compartido/error-catch-to-report.php");
}

include("../compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="usuarios-editar.php?id=' . $idRegistro . '&success=SC_DT_1";</script>';
exit();