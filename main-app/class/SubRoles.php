<?php
class SubRoles {

     /**
     * Esta función  crea un registro en la tabla sub_roles
     *
     * @param string $nombre
     * @param array $paginas
     *
     * @return String // se retorna el id del registro ingresado    */

    public static function crear(String $nombre = "",array $paginas = []){
        global $conexion, $baseDatosServicios,$config;
        $idRegistro = -1;        
        try {
            $sqlUpdate="INSERT INTO ".$baseDatosServicios.".sub_roles(
                subr_nombre, 
                subr_institucion, 
                subr_year)
            VALUES(
                '".$nombre."',
                '".$config['conf_id_institucion']."', 
                '".$config['conf_agno']."'
                )";
            mysqli_query($conexion,$sqlUpdate);
            $idRegistro = mysqli_insert_id($conexion);  
            self::crearRolesPaginas( $idRegistro,$paginas);
                
            
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        
        return $idRegistro;
    }
  /**
     * Esta función  crea un registro en la tabla sub_roles_paginas
     *
     * @param string $idSubRol
     * @param array $paginas
     *
     * @return String // se retorna el id del registro ingresado    */
    public static function crearRolesPaginas($idSubRol,array $paginas= []){
        global $conexion, $baseDatosServicios;
        $idRegistro = -1;        
        try {
            foreach ($paginas as $page ) {
                $sqlinsert="INSERT INTO ".$baseDatosServicios.".sub_roles_paginas(
                    spp_id_rol, 
                    spp_id_pagina
                 )
                VALUES(
                    '".$idSubRol."',
                    '".$page."'                        
                )";
                mysqli_query($conexion,$sqlinsert);
                $idRegistro = mysqli_insert_id($conexion);                   
            }                  
            
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        
        return $idRegistro;
    }
     /**
     * Esta función  crea un registro en la tabla sub_roles_usuarios
     *
     * @param string $idUsuario
     * @param array $subRoles
     *
     * @return String // se retorna el id del registro ingresado    */
    public static function crearRolesUsuario($idUsuario,array $subRoles= []){
        global $conexion, $baseDatosServicios,$config;;
        $idRegistro = -1;        
        try {
            foreach ($subRoles as $subrol ) {
                $sqlinsert="INSERT INTO ".$baseDatosServicios.".sub_roles_usuarios(
                    spu_id_sub_rol, 
                    spu_id_usuario,
                    spu_institucion,
                    spu_year
                 )
                VALUES(
                    '".$subrol."',
                    '".$idUsuario."',                    
                    '".$config['conf_id_institucion']."', 
                    '".$config['conf_agno']."'                        
                )";
                mysqli_query($conexion,$sqlinsert);
                $idRegistro = mysqli_insert_id($conexion);                   
            }                  
            
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        
        return $idRegistro;
    }
          /**
     * Esta función actauliza  la tabla sub_roles_usuarios
     * importante que la estructura del array pueda venir los valores:
     * ($datos["nombre"],$datos["id"],$datos["paginas"])
     * @param array $datos
     *
     * @return void //   */
    public static function actualizar(array $datos = []){
        global $conexion, $baseDatosServicios;
        
        $setNombre=empty($datos["nombre"])?"":$datos["nombre"];            

        try {
            $sqlUpdate="
            UPDATE ".$baseDatosServicios.".sub_roles
            SET subr_nombre='".$setNombre."'
            WHERE subr_id='".$datos["id"]."'";  
            mysqli_query($conexion,$sqlUpdate);
            if(!empty($datos["paginas"])){
                $sqlDelete="DELETE FROM ".$baseDatosServicios.".sub_roles_paginas
                WHERE spp_id_rol='".$datos["id"]."'";
                mysqli_query($conexion,$sqlDelete);               
                self::crearRolesPaginas($datos["id"],$datos["paginas"]);
            }
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }

     /**
     * Esta función  crea o elimina un registro si es necesario en la tabla sub_roles_usuarios
     *
     * @param string $idUsuario
     * @param array $subRoles
     *
     * @return void // */
     public static function actualizarRolesUsuario($idUsuario,array $subRoles= []){
        global $conexion, $baseDatosServicios,$config;
        $subRolesActualales= self::listarRolesUsuarios($idUsuario);
        $subRolesCrear= [];
        $subRolesElimnar= [];
        $cantAgregar = 0;
        $cantEliminar = 0; 
        $subRolesArray= [];
        foreach ($subRoles as $subRol ) {
            $subRolesArray[$subRol]=$subRol;
        } 
        foreach ($subRolesActualales as $subrolBD ) {
            if(!array_key_exists($subrolBD["spu_id_sub_rol"], $subRolesArray)){
                $subRolesElimnar[$subrolBD["spu_id_sub_rol"]]= $subrolBD["spu_id_sub_rol"];
                $cantEliminar ++;
            }
        }
        foreach ($subRolesArray as $subrol ) {                
            if(!array_key_exists($subrol, $subRolesActualales)){
               $subRolesCrear[$subrol]= $subrol;
               $cantAgregar ++;
            }
        }
        if($cantEliminar>=1){
            self::eliminarSubrolesUsuarios($idUsuario, $subRolesElimnar);
        }
        if($cantAgregar>=1){
            self::crearRolesUsuario($idUsuario, $subRolesCrear);
        }
    }
     /**
     * Esta función  consulta  la tabla sub_roles por la identificaicon unica
     * @param String $id
     *
     * @return array // retorna el dato encontrado  */
    public static function consultar(String $id ='-1' ) {
        global $conexion, $baseDatosServicios;
        $resultado=[];        
       
        $sqlExecute="SELECT * FROM ".$baseDatosServicios.".sub_roles
        LEFT JOIN ".$baseDatosServicios .".instituciones ON ins_id = subr_institucion
        WHERE subr_id = '".$id."'";
        try {
           $resultadoConsuta = mysqli_query($conexion,$sqlExecute);
           while($fila=$resultadoConsuta->fetch_assoc()){
               $fila["paginas"]=self::listarPaginasRoles($fila["subr_id"]); 
               $fila["usuarios"]=self::listarUsuariosRoles($fila["subr_id"]); 
               $resultado=$fila; 
           } 
           return $resultado;
        } catch (Exception $e) {            
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        
    }
     /**
     * Esta función  Lista  la tabla sub_roles
     * @param array $parametrosBusqueda
     *
     *   */   
    public static function listar(array $parametrosBusqueda = []) {
        global $conexion,$config, $baseDatosServicios;
        $resultado = [];
        $institucion=empty($parametrosBusqueda["institucion"])?$config['conf_id_institucion']:$parametrosBusqueda['institucion'];
        $andYear=empty($parametrosBusqueda["year"])?" ":"AND subr_year='".$parametrosBusqueda["year"]."'";
        
        $sqlExecute="SELECT * FROM ".$baseDatosServicios.".sub_roles
        LEFT JOIN ".$baseDatosServicios .".instituciones ON ins_id = subr_institucion
        WHERE subr_institucion =".$institucion
        .$andYear;
        try {
            $resultado= mysqli_query($conexion,$sqlExecute);
           
            return $resultado;
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        return $resultado;
    }
     /**
     * Esta función  Lista  la tabla paginas_publicidad por el tipo de usuario
     * por defecto se buscará por el tipo de usuario=5 y las ordenara teniendo encuenta el sub rol selecionado
     * @param String $tipoUsuario
     * @param String $subRol
     * */
    public static function listarPaginas($subRol,$tipoUsuario = '5'){
        global $conexion, $baseDatosServicios;
        $resultado = [];
        
        $sqlExecute="SELECT * FROM ".$baseDatosServicios.".paginas_publicidad
        LEFT JOIN ".$baseDatosServicios .".modulos ON mod_id=pagp_modulo
        LEFT JOIN ".$baseDatosServicios .".sub_roles_paginas ON spp_id_pagina=pagp_id AND spp_id_rol='".$subRol."'
        WHERE pagp_tipo_usuario = '".$tipoUsuario."' 
        ORDER BY spp_id_pagina DESC";
        try {
            $resultado = mysqli_query($conexion,$sqlExecute);
            
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        return $resultado;
    }
    /**
     * Esta función  Lista  la tabla sub_roles_paginas teniendo en cuenta el rol
     * por defecto se buscará por el tipo de usuario=1
     * @param String $idRol
     *   */
    public static function listarPaginasRoles($idRol = '1'){
        global $conexion, $baseDatosServicios;
        $arraysDatos = [];
        
        $sqlExecute="SELECT * FROM ".$baseDatosServicios.".sub_roles_paginas
        LEFT JOIN ".$baseDatosServicios .".sub_roles ON subr_id=spp_id_rol  
        LEFT JOIN ".$baseDatosServicios .".paginas_publicidad ON pagp_id=spp_id_pagina
        WHERE spp_id_rol = '".$idRol."'";
        try {
            $resultadoConsulta = mysqli_query($conexion,$sqlExecute);
            while($fila=$resultadoConsulta->fetch_assoc()){
                $arraysDatos[$fila["pagp_id"]]=$fila;
            } 
            return $arraysDatos;
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        
    }
 /**
     * Esta función  Lista  la tabla sub_roles_usuarios teniendo en cuenta el id del usuario
     * por defecto se buscará por el  usuario=1
     * @param String $idUsuario
     *  */
    public static function listarRolesUsuarios($idUsuario = '1'){
        global $conexion,$config, $baseDatosServicios;
        $arraysDatos = [];
        $sqlExecute="SELECT * FROM ".$baseDatosServicios.".sub_roles_usuarios
        WHERE spu_id_usuario = '".$idUsuario."'
        AND spu_institucion =".$config['conf_id_institucion'];
        try {
            $resultadoConsulta = mysqli_query($conexion,$sqlExecute);
            while($fila=$resultadoConsulta->fetch_assoc()){
                $arraysDatos[$fila["spu_id_sub_rol"]]=$fila;
            } 
            return $arraysDatos;
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        
    }
    /**
     * Esta función  Elimina los  registros en la tabla sub_roles
     *
     * @param String $idUsuario
     * @param array $subRoles
     *
     * @return void //   */

     public static function eliminarSubrolesUsuarios(String $idUsuario = "",array $subRoles = []){
        global $conexion, $baseDatosServicios,$config;               
        try {
            $INsubroles="";
            if(!empty($subRoles)){
                $INsubroles=" AND spu_id_sub_rol IN ('".implode(",",$subRoles)."')";
            }
            $sqlUpdate="DELETE FROM ".$baseDatosServicios.".sub_roles_usuarios
            WHERE spu_id_usuario=".$idUsuario.
            " AND spu_institucion =".$config['conf_id_institucion'].
            $INsubroles;
            mysqli_query($conexion,$sqlUpdate);              
            
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }

    /**
     * Esta función  Lista sirve para listar los usuarios que tienen un Subrol en especifico
     * por defecto se buscará por el  usuario=1
     * @param String $subrol
     *  */
    public static function listarUsuariosRoles($subrol){
        global $conexion, $baseDatosServicios;
        $arraysDatos = [];
        
        $sqlExecute="SELECT * FROM ".$baseDatosServicios.".sub_roles_usuarios
        WHERE spu_id_sub_rol = '".$subrol."'";
        try {
            $resultadoConsulta = mysqli_query($conexion,$sqlExecute);
            while($fila=$resultadoConsulta->fetch_assoc()){
                $arraysDatos[$fila["spu_id_usuario"]]=$fila;
            } 
            return $arraysDatos;
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        
    }

}