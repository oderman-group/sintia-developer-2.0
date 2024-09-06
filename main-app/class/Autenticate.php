<?php

use Kreait\Firebase\Exception\DatabaseApiExceptionConverter;

require_once(ROOT_PATH."/main-app/class/Conexion.php");
require_once(ROOT_PATH."/main-app/class/RedisInstance.php");
require_once(ROOT_PATH."/main-app/class/Usuarios.php");
require_once(ROOT_PATH."/main-app/class/UsuariosPadre.php");
require_once(ROOT_PATH."/main-app/class/Instituciones.php");
require_once(ROOT_PATH."/main-app/class/Usuarios/Directivo.php");

class Autenticate {

    private static $instance = null;

    private function __construct() {
    }

    /**
     * Returns the singleton instance of the Autenticate class.
     * If the instance does not exist, it creates one.
     *
     * @return Autenticate The singleton instance of the Autenticate class.
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Autenticate;
        }

        return self::$instance;
    }

    private function validateUser(string $user) {
        if (empty(trim($user))) {
            return false;
        }

        return true;
    }

    private function validatePass(string $pass, string $user) {
        if (empty(trim($pass))) {
            return false;
        }

        // if($user === $pass) {
        //     throw new Exception("El usuario y la clave no pueden ser iguales");
        // }

        return true;
    }

    public function getUserData(string $user, string $pass): array
    { 
        if (!$this->validateUser($user)) {
            throw new Exception("El usuario es invalido");
        }

        if (!$this->validatePass($pass, $user)) {
            throw new Exception("La contraseña es invalida");
        }

        $sql = "SELECT id_nuevo, uss_usuario, uss_id, institucion, uss_intentos_fallidos FROM ".BD_GENERAL.".usuarios 
        WHERE uss_usuario='".trim($user)."' 
        AND TRIM(uss_usuario)!='' 
        AND uss_clave=SHA1('".$pass."')  
        AND uss_usuario IS NOT NULL  
        ORDER BY uss_ultimo_ingreso DESC 
        LIMIT 1";

        $conexion = Conexion::newConnection('MYSQL');

        $consulta = mysqli_query($conexion, $sql);
        $data     = mysqli_fetch_array($consulta, MYSQLI_ASSOC);

        if (empty($data)) {
            throw new Exception("El usuario o la clave son incorrectos");
        }

        return $data;
    }

    public function cerrarSesion($urlRedirect = null) {

        if(empty($urlRedirect)){
            $urlRedirect = REDIRECT_ROUTE."?inst=".base64_encode($_SESSION["idInstitucion"])."&year=".base64_encode($_SESSION["bd"]);
        }

        setcookie("carga","",time()-3600);
        setcookie("periodo","",time()-3600);
        setcookie("cargaE","",time()-3600);
        setcookie("periodoE","",time()-3600);
        session_destroy();

        Conexion::getConexion()->closeConnection();

        $redis = RedisInstance::getRedisInstance();
        $keysToDelete = [RedisInstance::KEY_SYSTEM_CONFIGURATION, RedisInstance::KEY_MODULES_INSTITUTION];
        $redis->del($keysToDelete);

        header("Location:".$urlRedirect);
    }

    /**
     * Switches the current institution for the user session.
     *
     * This function updates the session data to reflect the selected institution.
     * It verifies if the current institution and the selected institution are linked,
     * retrieves the necessary data for the selected institution, and updates the session variables accordingly.
     *
     * @param int $idInstitucion The ID of the institution to switch to.
     *
     * @throws Exception If there is any error occurs during the switch.
     *
     * @return void
     */
    public function switchInstitution(int $idInstitucion, array $datosUsuarioActual): void
    {
        if ($_SESSION["idInstitucion"] == $idInstitucion) {
            return;
        }

        if (!in_array($datosUsuarioActual["uss_tipo"], [TIPO_DIRECTIVO, TIPO_DEV])) {
            throw new Exception("Debes ser un usuario directivo o desarrollador para continuar. Tipo Actual: {$datosUsuarioActual["uss_tipo"]}", -4);
        }

        $areVinculed = Instituciones::areSitesVinculed($_SESSION["idInstitucion"], $idInstitucion);

        if (!$areVinculed) {
            throw new Exception("No se encuentra vinculo entre la institución actual y la seleccionada {$_SESSION["idInstitucion"]}, {$idInstitucion}.", -2);
        }

        if (empty($datosUsuarioActual["uss_documento"])) {
            throw new Exception("Debes tener registrado tu número de documento para continuar: {$datosUsuarioActual["uss_id"]}, {$datosUsuarioActual["uss_usuario"]}.", -3);
        }

        $objetInstitution = Instituciones::getDataInstitution($idInstitucion);
        $dataInstitution  = mysqli_fetch_array($objetInstitution, MYSQLI_ASSOC);
        $mySelf           = Directivo::getMyselfByDocument($datosUsuarioActual["uss_documento"], $datosUsuarioActual["uss_tipo"], $idInstitucion);

        $_SESSION["idInstitucion"]           = $idInstitucion;
        $_SESSION['id']                      = $mySelf["uss_id"];
        $_SESSION["inst"]                    = $dataInstitution['ins_bd'];
        $_SESSION["bd"]                      = $dataInstitution['ins_year_default'];
        $_SESSION["datosUnicosInstitucion"]  = $dataInstitution;
        $_SESSION["modulos"]                 = RedisInstance::getModulesInstitution(true);
        $_SESSION["informacionInstConsulta"] = Instituciones::getGeneralInformationFromInstitution($_SESSION["idInstitucion"], $_SESSION["bd"]);;
        $_SESSION["datosUsuario"]            = UsuariosPadre::sesionUsuario($_SESSION['id']);
    }
}