<?php
include("session.php");
require_once(ROOT_PATH."/main-app/class/Usuarios.php");

$numUsuarios = (count($_POST["usuario"]));
$contUsuarios = 0;

while ($contUsuarios < $numUsuarios) {
    if($_POST["tipo"]==1){

        $validarClave = Usuarios::validarClave($_POST["clave"]);
        if(!$validarClave){
            echo '<script type="text/javascript">window.location.href="usuarios-generar-clave-filtros.php?error=5";</script>';
            exit();
        }
        
        try{
            mysqli_query($conexion, "UPDATE usuarios SET uss_clave=SHA1('".$_POST["clave"]."') WHERE uss_tipo='".$_POST["usuario"][$contUsuarios]."'");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }

    }elseif($_POST["tipo"]==2){

        try{
            mysqli_query($conexion, "UPDATE usuarios SET uss_clave=SHA1(uss_usuario) WHERE uss_tipo='".$_POST["usuario"][$contUsuarios]."'");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }

    }
    $contUsuarios++;
}

echo '<script type="text/javascript">window.location.href="usuarios-generar-clave-filtros.php?success=SC_DT_8";</script>';
exit();