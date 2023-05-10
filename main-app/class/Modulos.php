<?php

class Modulos {

    public static function verificarPermisosPaginas($idPaginaInterna): bool
    {
        global $conexion, $baseDatosServicios, $config;

        $consultaPaginaActualUsuarios = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".paginas_publicidad
        INNER JOIN ".$baseDatosServicios.".instituciones_modulos 
        ON ipmod_modulo=pagp_modulo 
        AND ipmod_institucion='".$config['conf_id_institucion']."'
        WHERE pagp_id='".$idPaginaInterna."'");
        $paginaActualUsuario = mysqli_fetch_array($consultaPaginaActualUsuarios, MYSQLI_BOTH);
        if ($paginaActualUsuario[0]=="") { 
            return false;
        }
        return true;
    }

    public static function verificarPermisoDev(){

        global $datosUsuarioActual;

        if($datosUsuarioActual['uss_permiso1'] != CODE_DEV_MODULE_PERMISSION){
            echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
            exit();	
        }
    }

}