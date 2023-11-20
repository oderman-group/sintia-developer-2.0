<?php

class UsuariosPadre {

    public static function nombreCompletoDelUsuario($usuario)
    {
        if (!is_array($usuario)) {
            return '--';
        }
        $nombre=$usuario['uss_nombre'];
        if(!empty($usuario['uss_nombre2'])){
            $nombre.=" ".$usuario['uss_nombre2'];
        }
        if(!empty($usuario['uss_apellido1'])){
            $nombre.=" ".$usuario['uss_apellido1'];
        }
        if(!empty($usuario['uss_apellido2'])){
            $nombre.=" ".$usuario['uss_apellido2'];
        }
        return strtoupper($nombre);
    }

    public static function listarUsuariosAnio($usuario)
    {
        global $conexion;
        global $yearStart;
        global $yearEnd;
        global $baseDatosServicios;
        global $filtro;
        $index=0;
        $tableName = BDT_GeneralPerfiles::getTableName();        
        while($yearStart <= $yearEnd){
            $instYear =$_SESSION["inst"] ."_". $yearStart;            
            $consultaUsuarioAuto = mysqli_query($conexion, "SELECT * FROM ". $instYear.".usuarios 
            INNER JOIN ".$baseDatosServicios.".{$tableName} ON pes_id=uss_tipo
            WHERE uss_usuario LIKE '".$usuario."%'");
            if($consultaUsuarioAuto->num_rows>0){               
                while($fila=$consultaUsuarioAuto->fetch_assoc()){
                    $fila["anio"]=$yearStart;
                    $arraysDatos[$index]=$fila;
                    $index++;
                }
            }
            $yearStart++; 
        }
        return $arraysDatos;
    }

    /**
     * Obtiene los datos de un usuario a partir de su ID de usuario.
     *
     * Esta función consulta la base de datos para recuperar los datos de un usuario utilizando su ID de usuario.
     *
     * @param string $idUsuario - El ID de usuario para el cual se desean obtener los datos.
     * @param string $filtroAdicional (Opcional) - Un filtro adicional que se puede aplicar a la consulta SQL.
     *
     * @return array - Un array que contiene los datos del usuario si se encuentra en la base de datos, o un array vacío si no se encuentra.
     */
    public static function sesionUsuario($idUsuario, $filtroAdicional='')
    {
        global $conexion;

        try{
            $consultaUsuarioAuto = mysqli_query($conexion, "SELECT * FROM usuarios WHERE uss_id='".$idUsuario."' {$filtroAdicional}");
            $datosUsuarioAuto = mysqli_fetch_array($consultaUsuarioAuto, MYSQLI_BOTH);
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
            return [];
        }
    
        return $datosUsuarioAuto;
    }

    public static function sesionUsuarioAnio($usuario,$instYear)
    {
        global $conexion;
        $consultaUsuarioAuto = mysqli_query($conexion, "SELECT * FROM ". $instYear.".usuarios WHERE uss_usuario='".$usuario."' limit 1");
        $datosUsuarioAuto = mysqli_fetch_array($consultaUsuarioAuto, MYSQLI_BOTH);
        return $datosUsuarioAuto;
    }

    public static function actualizarUsuariosAnios()
    {
        $get=$_GET["get"];
        $campoGet=null;
        $campoTabla=null;
        global $yearStart;
        global $yearEnd;
        global $conexion;
        switch ($get) {
            case 1://CAMBIAR IDIOMA
                $campoGet="idioma";
                $campoTabla="uss_idioma";
                break;
            case 2://CAMBIAR TEMA ENCABEZADO
                $campoGet="temaHeader";
                $campoTabla="uss_tema_header";
                break;
            case 3://CAMBIAR TEMA MENÚ
                $campoGet="temaSidebar";
                $campoTabla="uss_tema_sidebar";
                break;
            case 4://CAMBIAR TEMA LOGO
                $campoGet="temaLogo";
                $campoTabla="uss_tema_logo";
                break;
            case 5://CAMBIAR TODO EL TEMA
                $campoGet="temaHeader";
                $campoTabla="uss_tema_header";
                break;
        }
        if($campoGet){
                while($yearStart <= $yearEnd){	
                    if ($_SESSION["bd"] == $yearStart) {			
                        if($get == 5) {
                            mysqli_query($conexion, "UPDATE usuarios SET 
                            uss_tema_header='" . $_GET["temaHeader"] . "', 
                            uss_tema_sidebar='" . $_GET["temaSidebar"] . "', 
                            uss_tema_logo='" . $_GET["temaLogo"] . "' 
                            WHERE uss_id='" . $_SESSION["id"] . "'");
                        }
                        else {
                            mysqli_query($conexion, "UPDATE usuarios SET $campoTabla='" . $_GET[$campoGet] . "' 
                            WHERE uss_id='" . $_SESSION["id"] . "'");
                        }
                    } else {
                        $usuarioSession = $_SESSION["datosUsuario"];
                        $instYear = $_SESSION["inst"] ."_". $yearStart;
                        $usauriosOtrosAnios = UsuariosPadre::sesionUsuarioAnio($usuarioSession['uss_usuario'], $instYear);
                        if($usauriosOtrosAnios) {
                            if($get == 5) {
                                mysqli_query($conexion, "UPDATE ".$instYear.".usuarios SET 
                                uss_tema_header='" . $_GET["temaHeader"] . "', 
                                uss_tema_sidebar='" . $_GET["temaSidebar"] . "', 
                                uss_tema_logo='" . $_GET["temaLogo"] . "' 
                                WHERE uss_id='" .$usauriosOtrosAnios["uss_id"]."'");
                            }
                            else {
                                mysqli_query($conexion, "UPDATE ".$instYear.".usuarios SET $campoTabla='" . $_GET[$campoGet] . "' 
                                WHERE uss_id='" .$usauriosOtrosAnios["uss_id"]. "'"); 
                            }
                        }
                        
                    }
                    $yearStart++;
                }
            $_SESSION["datosUsuario"][$campoTabla] = $_GET[$campoGet];		
        }        	
    }

    public static function listarUsuariosCompartir($nombre='',$BD='')
    {
        global $conexion,$baseDatosServicios;

        $consulta= mysqli_query($conexion, "SELECT uss_id,uss_apellido1,uss_apellido2,uss_nombre,uss_nombre2,pes_nombre FROM ".$BD.".usuarios 
        INNER JOIN ".$baseDatosServicios.".general_perfiles ON pes_id=uss_tipo
        WHERE CONCAT(uss_apellido1,' ',uss_apellido2,' ',uss_nombre,' ',uss_nombre2) LIKE '%".$nombre."%' ORDER BY uss_apellido1, uss_apellido2, uss_nombre LIMIT 10");

        return $consulta;         
    }

    /**
     * Obtiene todos los datos de usuarios de la base de datos, opcionalmente aplicando un filtro de búsqueda.
     *
     * Esta función realiza una consulta a la base de datos para recuperar todos los datos de los usuarios. 
     * Puede aplicarse un filtro de búsqueda opcional para refinar la consulta.
     *
     * @param string $filtroBusqueda (Opcional) - Un filtro de búsqueda que se puede aplicar a la consulta SQL.
     *
     * @return mixed - Un objeto de resultado de la consulta si tiene éxito, o 0 si ocurre un error.
     */
    public static function obtenerTodosLosDatosDeUsuarios($filtroBusqueda='')
    {
        global $conexion;

        try{
            $consultaUsuario = mysqli_query($conexion, "SELECT * FROM usuarios 
            INNER JOIN ".BD_GENERAL.".general_perfiles ON pes_id=uss_tipo 
            WHERE uss_id=uss_id {$filtroBusqueda}");
            return $consultaUsuario;
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
            return 0;
        }

    }

}   