<?php

class UsuariosPadre {

    public static function nombreCompletoDelUsuario($usuario)
    {
        if (!is_array($usuario)) {
            return '--';
        }
        return strtoupper($usuario['uss_nombre']." ".$usuario['uss_nombre2']." ".$usuario['uss_apellido1']." ".$usuario['uss_apellido2']);
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

   public static function actualizarUsuariosAnios(){
        $campoGet=null;
        $campoTabla=null;
        global $yearStart;
        global $yearEnd;
        global $conexion;
        switch ($_GET["get"]) {
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
                        if($_GET["get"] == 5)
                        mysqli_query($conexion, "UPDATE usuarios SET uss_tema_header='" . $_GET["temaHeader"] . "', uss_tema_sidebar='" . $_GET["temaSidebar"] . "', uss_tema_logo='" . $_GET["temaLogo"] . "' WHERE uss_id='" . $_SESSION["id"] . "'");
                        else
                        mysqli_query($conexion, "UPDATE usuarios SET $campoTabla='" . $_GET[$campoGet] . "' WHERE uss_id='" . $_SESSION["id"] . "'");
                    }else{
                        $usuarioSession=$_SESSION["datosUsuario"];
                        $instYear =$_SESSION["inst"] ."_". $yearStart;
                        $usauriosOtrosAnios = UsuariosPadre::sesionUsuarioAnio($usuarioSession['uss_usuario'],$instYear);
                        if($usauriosOtrosAnios){
                            if($_GET["get"] == 5)
                            mysqli_query($conexion, "UPDATE ".$instYear.".usuarios SET uss_tema_header='" . $_GET["temaHeader"] . "', uss_tema_sidebar='" . $_GET["temaSidebar"] . "', uss_tema_logo='" . $_GET["temaLogo"] . "' WHERE uss_id='" .$usauriosOtrosAnios["uss_id"]."'");
                            else
                            mysqli_query($conexion, "UPDATE ".$instYear.".usuarios SET $campoTabla='" . $_GET[$campoGet] . "' WHERE uss_id='" .$usauriosOtrosAnios["uss_id"]. "'");
                        }
                        
                    }
                    $yearStart++;
                }		
        }	
    }

    

}