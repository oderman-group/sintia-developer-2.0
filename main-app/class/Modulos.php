<?php

class Modulos {

    public static function verificarPermisosPaginas($idPaginaInterna): bool
    {

        $datosPaginaActual = self::datosPaginaActual($idPaginaInterna);
        if( empty($datosPaginaActual) ) {
            return false;
        }
        return true;

    }

    public static function verificarPermisoDev(){

        global $datosUsuarioActual;

        if($datosUsuarioActual['uss_tipo']!= TIPO_DEV && $datosUsuarioActual['uss_permiso1'] != CODE_DEV_MODULE_PERMISSION){
            echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
            exit();	
        }
    }
    /* Esta función Válida los permisos del un directivo especial */
    public static function verificarPermisoDirectivoEspecial(){

        global $datosUsuarioActual;

        if($datosUsuarioActual['uss_tipo']!= TIPO_DIRECTIVO && $datosUsuarioActual['uss_permiso1'] != CODE_PRIMARY_MANAGER){
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
        if (empty($modulos[0])) { 
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
     * @param array     $paginas
     * @param bool      $menu
     * 
     * @return bool
    **/
    public static function validarSubRol($paginas){
        global $conexion, $baseDatosServicios, $datosUsuarioActual, $config, $arregloModulos;

        //Si la institución no tiene este módulo (Subroles) asignado entonces devolvemos true siempre
        if( ( $datosUsuarioActual['uss_tipo'] == TIPO_DIRECTIVO && (empty($arregloModulos) || !array_key_exists(16, $arregloModulos)) ) 
        || $datosUsuarioActual['uss_tipo'] == TIPO_DEV ) {
            return true;
        }

        if ($datosUsuarioActual['uss_tipo'] != TIPO_DIRECTIVO) { 
            return false;
        }

        try{
            $consultaSubRoles = mysqli_query($conexion, "SELECT spu_id_sub_rol FROM ".$baseDatosServicios.".sub_roles_usuarios 
            WHERE spu_id_usuario='".$datosUsuarioActual['uss_id']."' AND spu_institucion='".$config['conf_id_institucion']."'");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
        $numSubRoles=mysqli_num_rows($consultaSubRoles);
        if ($numSubRoles<1) { 
            return true;
        }else{
            $subRoles = mysqli_fetch_all($consultaSubRoles, MYSQLI_ASSOC);
            $valoresArray = array_column($subRoles, 'spu_id_sub_rol');
            $valoresCadena = implode(',', $valoresArray);
            try{
                $consultaPaginaSubRoles = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".sub_roles_paginas 
                WHERE spp_id_rol IN ($valoresCadena)");
            } catch (Exception $e) {
                include("../compartido/error-catch-to-report.php");
            }
            $subRolesPaginas = mysqli_fetch_all($consultaPaginaSubRoles, MYSQLI_ASSOC);
            $valoresPaginas = array_column($subRolesPaginas, 'spp_id_pagina');
            $permitidos= array_intersect($paginas,$valoresPaginas);
            if(!empty($permitidos)){
                return true;
            }
        }
        return false;
    }

    /**
     * Este metodo sirve para validar si las paginas hijas estan asignadas aun rol
     * 
     * @param string     $idPagina
     * 
     * @return bool
    **/
    public static function validarPaginasHijasSubRol($idPagina){
        global $conexion, $baseDatosServicios;

        try{
            $consultaPaginasHijas=mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".paginas_publicidad WHERE pagp_pagina_padre='".$idPagina."'");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
        $numPaginasHijas=mysqli_num_rows($consultaPaginasHijas);
        if ($numPaginasHijas>0) {
            $datosPaginasHijas = mysqli_fetch_all($consultaPaginasHijas, MYSQLI_ASSOC);
            $arrayPaginasHijas = array_column($datosPaginasHijas, 'pagp_id');
            $arrayPaginasHijasCadena = array_map(function($valor) { return "'" . $valor . "'"; }, $arrayPaginasHijas);
            $idPaginasHijas = implode(',', $arrayPaginasHijasCadena);
            try{
                $consultaPaginaSubRoles = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".sub_roles_paginas 
                WHERE spp_id_pagina IN ($idPaginasHijas)");
            } catch (Exception $e) {
                include("../compartido/error-catch-to-report.php");
            }
            $subRolesPaginas = mysqli_fetch_all($consultaPaginaSubRoles, MYSQLI_ASSOC);
            $valoresPaginas = array_column($subRolesPaginas, 'spp_id_pagina');
            $permitidos= array_intersect($arrayPaginasHijas,$valoresPaginas);
            if(!empty($permitidos)){
                return false;
            }
        }
        return true;
    }

    /**
     * Obtener Datos de la Página Actual por su Identificador Interno
     *
     * Esta función se utiliza para recuperar datos de una página actual en función de su identificador interno.
     *
     * @param int $idPaginaInterna El identificador interno de la página que se desea obtener.
     *
     * @return array Un array asociativo que contiene los datos de la página actual, o un array vacío si no se encuentra la página.
     */
    public static function datosPaginaActual($idPaginaInterna): array
    {
        global $conexion, $baseDatosServicios, $config;

        $consultaPaginaActualUsuarios = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".paginas_publicidad
        INNER JOIN ".$baseDatosServicios.".instituciones_modulos 
        ON ipmod_modulo=pagp_modulo 
        AND ipmod_institucion='".$config['conf_id_institucion']."'
        WHERE pagp_id='".$idPaginaInterna."'");
        $paginaActualUsuario = mysqli_fetch_array($consultaPaginaActualUsuarios, MYSQLI_BOTH);
        if ($paginaActualUsuario[0]=="") { 
            return [];
        }
        return $paginaActualUsuario;
    }
}