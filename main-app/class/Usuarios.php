<?php
class Usuarios {

    public static function obtenerDatosUsuario($usuario = 0)
    {

        global $conexion;
        $resultado = [];

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM usuarios
            WHERE (uss_id='".$usuario."' || uss_usuario='".$usuario."')
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

        global $conexion;
        $num = 0;

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM usuarios
            WHERE (uss_id='".$usuario."' || uss_usuario='".$usuario."' || uss_email='".$usuario."')
            ");
            $num = mysqli_num_rows($consulta);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $num;

    }

    public static function datosUsuarioParaRecuperarClave($usuario = '')
    {

        global $conexion;
        $resultado = [];

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM usuarios
            WHERE (uss_email='".$usuario."' || uss_usuario='".$usuario."')
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
        $BD = $data['institucion_bd']."_".$data['institucion_agno'];

        try {
            mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".restaurar_clave(resc_id_usuario, resc_fec_solicitud, resc_id_institucion, resc_clave_generada) VALUES('".$data['usuario_id']."', now(), '".$data['institucion_id']."', '".sha1($data['nueva_clave'])."')");
            $idatosUsuarioltimoRegistro = mysqli_insert_id($conexion);
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }

        try {  
            mysqli_query($conexion, "UPDATE ".$BD.".usuarios SET uss_clave=SHA1('".$data['nueva_clave']."') 
            WHERE uss_id='".$data['usuario_id']."'");
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
        try{
            mysqli_query($conexion, "UPDATE usuarios SET uss_bloqueado={$bloquearDesbloquear} WHERE uss_tipo={$tipoUsuarios}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
    }

}