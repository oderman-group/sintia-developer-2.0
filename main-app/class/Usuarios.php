<?php
class Usuarios {

    public static function obtenerDatosUsuario($usuario = 0)
    {

        global $conexion, $config;
        $resultado = [];

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_GENERAL.".usuarios
            WHERE (uss_id='".$usuario."' || uss_usuario='".$usuario."') AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}
            ");
            $num = mysqli_num_rows($consulta);
            if($num == 0){
                $resultado = "";
            }
            $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;

    }

    public static function validarExistenciaUsuario($usuario = 0)
    {

        global $conexion, $config;
        $num = 0;

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_GENERAL.".usuarios
            WHERE (uss_id='".$usuario."' || uss_usuario='".$usuario."' || uss_email='".$usuario."') AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}
            ");
            $num = mysqli_num_rows($consulta);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $num;

    }

    public static function datosUsuarioParaRecuperarClave($idInstitucion,$usuario = '')
    {

        global $conexion;
        $resultado = [];

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_GENERAL.".usuarios
            WHERE (uss_email='".$usuario."' || uss_usuario='".$usuario."') AND institucion={$idInstitucion} AND year={$_SESSION["bd"]}
            ");
            $num = mysqli_num_rows($consulta);
            if($num == 0){
                return $resultado;
            }
            $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;

    }

    public static function guardarRegistroRestaruracion($data)
    {
        global $conexion, $baseDatosServicios;

        try {
            mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".restaurar_clave(resc_id_usuario, resc_fec_solicitud, resc_id_institucion, resc_clave_generada) VALUES('".$data['usuario_id']."', now(), '".$data['institucion_id']."', '".sha1($data['nueva_clave'])."')");
            $idatosUsuarioltimoRegistro = mysqli_insert_id($conexion);
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }

        try {  
            mysqli_query($conexion, "UPDATE ".BD_GENERAL.".usuarios SET uss_clave=SHA1('".$data['nueva_clave']."') 
            WHERE uss_id='".$data['usuario_id']."' AND institucion={$data['institucion_id']} AND year={$data['institucion_agno']}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }

        return $idatosUsuarioltimoRegistro;

    }

    public static function generatePassword($length)
    {
        $key = "";
        $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
        $max = strlen($pattern)-1;
        for($i = 0; $i < $length; $i++){
            $key .= substr($pattern, mt_rand(0,$max), 1);
        }
        return $key;
    }

    public static function validarClave($clave) {
        $regex = "/^[a-zA-Z0-9\.\$\*]{8,20}$/";
        $validarClave = preg_match($regex, $clave);
    
        if($validarClave === 0){
            return false;
        }else{
            return true;
        }
    }

    /**
     * Este metodo se usa para bloquear o desbloquear usuarios por tipo
     * @param mysqli    $conexion
     * @param int       $tipoUsuarios
     * @param bool      $bloquearDesbloquear
     **/
    public static function bloquearDesbloquearUsuarios($conexion,$tipoUsuarios,$bloquearDesbloquear)
    {
        global $config;
        try{
            mysqli_query($conexion, "UPDATE ".BD_GENERAL.".usuarios SET uss_bloqueado={$bloquearDesbloquear} WHERE uss_tipo={$tipoUsuarios} AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
    }

}