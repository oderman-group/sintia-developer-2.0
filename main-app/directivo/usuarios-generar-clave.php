<?php
include("session.php");
require_once(ROOT_PATH."/main-app/class/Usuarios.php");

$clave=Usuarios::generatePassword(8);
$numUsuarios = (count($_POST["usuario"]));
$contUsuarios = 0;

while ($contUsuarios < $numUsuarios) {
    if($_POST["tipo"]==1){

        $validarClave=Usuarios::validarClave($_POST["clave"]);
        if($validarClave!=true){
            echo '<script type="text/javascript">window.location.href="usuarios-generar-clave-filtros.php?error=5";</script>';
            exit();
        }
        
        mysqli_query($conexion, "UPDATE usuarios SET uss_clave='".$_POST["clave"]."' WHERE uss_tipo='".$_POST["usuario"][$contUsuarios]."'");

    }elseif($_POST["tipo"]==2){

        mysqli_query($conexion, "UPDATE usuarios SET uss_clave='".$clave."' WHERE uss_tipo='".$_POST["usuario"][$contUsuarios]."'");

    }
    $contUsuarios++;
}

echo '<script type="text/javascript">window.location.href="usuarios-generar-clave-filtros.php?success=SC_DT_8";</script>';
exit();