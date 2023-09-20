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
        while($yearStart <= $yearEnd){
            $instYear =$_SESSION["inst"] ."_". $yearStart;            
            $consultaUsuarioAuto = mysqli_query($conexion, "SELECT * FROM ". $instYear.".usuarios 
            INNER JOIN ".$baseDatosServicios.".general_perfiles ON pes_id=uss_tipo
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

    public static function sesionUsuario($idUsuario)
    {
        global $conexion;

        $consultaUsuarioAuto = mysqli_query($conexion, "SELECT * FROM usuarios WHERE uss_id='".$idUsuario."'");
        $datosUsuarioAuto = mysqli_fetch_array($consultaUsuarioAuto, MYSQLI_BOTH);
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
        $get=base64_decode($_GET["get"]);
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
                    if($_SESSION["bd"]==$yearStart){			
                        if($get == 5)
                        mysqli_query($conexion, "UPDATE usuarios SET uss_tema_header='" . base64_decode($_GET["temaHeader"]) . "', uss_tema_sidebar='" . base64_decode($_GET["temaSidebar"]) . "', uss_tema_logo='" . base64_decode($_GET["temaLogo"]) . "' WHERE uss_id='" . $_SESSION["id"] . "'");
                        else
                        mysqli_query($conexion, "UPDATE usuarios SET $campoTabla='" . base64_decode($_GET[$campoGet]) . "' WHERE uss_id='" . $_SESSION["id"] . "'");
                    }else{
                        $usuarioSession=$_SESSION["datosUsuario"];
                        $instYear =$_SESSION["inst"] ."_". $yearStart;
                        $usauriosOtrosAnios = UsuariosPadre::sesionUsuarioAnio($usuarioSession['uss_usuario'],$instYear);
                        if($usauriosOtrosAnios){
                            if($get == 5)
                            mysqli_query($conexion, "UPDATE ".$instYear.".usuarios SET uss_tema_header='" . base64_decode($_GET["temaHeader"]) . "', uss_tema_sidebar='" . base64_decode($_GET["temaSidebar"]) . "', uss_tema_logo='" . base64_decode($_GET["temaLogo"]) . "' WHERE uss_id='" .$usauriosOtrosAnios["uss_id"]."'");
                            else
                            mysqli_query($conexion, "UPDATE ".$instYear.".usuarios SET $campoTabla='" . base64_decode($_GET[$campoGet]) . "' WHERE uss_id='" .$usauriosOtrosAnios["uss_id"]. "'");
                        }
                        
                    }
                    $yearStart++;
                }		
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

}   