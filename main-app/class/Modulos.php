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

        if($datosUsuarioActual['uss_tipo']!= 1 && $datosUsuarioActual['uss_permiso1'] != CODE_DEV_MODULE_PERMISSION){
            echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
            exit();	
        }
    }
    /* Esta función Válida los permisos del un directivo especial */
    public static function verificarPermisoDirectivoEspecial(){

        global $datosUsuarioActual;

        if($datosUsuarioActual['uss_tipo']!= 5 && $datosUsuarioActual['uss_permiso1'] != CODE_PRIMARY_MANAGER){
            echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
            exit();	
        }
    }

    public static function verificarModulosDeInstitucion($idInstitucion,$idModulos)
    {
        global $conexion, $baseDatosServicios;

        $consultaModulos = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".instituciones_modulos 
        WHERE ipmod_institucion='".$idInstitucion."' AND ipmod_modulo='".$idModulos."'");
        $modulos = mysqli_fetch_array($consultaModulos, MYSQLI_BOTH);
        if ($modulos[0]=="") { 
            return false;
        }
        return true;
    }

    public static function validarAccesoDirectoPaginas(){
        if($_SERVER['HTTP_REFERER']==""){
            echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=303";</script>';
            exit();
        }
    }

    public static function validarPermisoEdicion(){
        global $config;

        if($config['conf_permiso_edicion_years_anteriores']==0){
            return false;
        }
        return true;
    }

    /**
     * Este metodo sirve para validar el acceso a las diferentes paginas de los directivos dependiendo de su rol
     * 
     * @param intiger $idPagina
     * 
     * @return void
    **/
    public static function validarSubRol($idPagina){
        global $conexion, $baseDatosServicios, $datosUsuarioActual, $config;

        try{
            $consultaSubRoles = mysqli_query($conexion, "SELECT spu_id_sub_rol FROM ".$baseDatosServicios.".sub_roles_usuarios 
            WHERE spu_id_usuario='".$datosUsuarioActual['uss_id']."' AND spu_institucion='".$config['conf_id_institucion']."' AND spu_year='".$config['conf_agno']."'");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
        $numSubRoles=mysqli_num_rows($consultaSubRoles);
        if ($numSubRoles<1) { 
            return true;
        }

        while($subRoles = mysqli_fetch_array($consultaSubRoles, MYSQLI_BOTH)){
            try{
                $consultaPaginaSubRoles = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".sub_roles_paginas 
                WHERE spp_id_rol='".$subRoles['spu_id_sub_rol']."' AND spp_id_pagina='".$idPagina."'");
            } catch (Exception $e) {
                include("../compartido/error-catch-to-report.php");
            }
            $numPaginaSubRoles=mysqli_num_rows($consultaPaginaSubRoles);

            if ($numPaginaSubRoles>0) { 
                return true;
            }else{
                return false;
            }
        }
    }
}