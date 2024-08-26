<?php
//namespace App\class\Usuarios;

require_once(ROOT_PATH."/main-app/class/Conexion.php");
require_once(ROOT_PATH."/main-app/class/UsuariosPadre.php");

class Directivo {

    protected $tipo          = 'DIRECTIVO';
    protected $nombre        = null;
    protected $nombreUsuario = null;
    protected $clave         = null;
    protected $email         = null;

    public function __construct(
        protected Usuarios $usuarios
    ) {
    }

    public function guardarDirectivo() {
        
    }

    public function getManagerPrimaryFromInstitution(int $idInstitution, int $year)
    {
        return $this->usuarios->getManagerPrimaryFromInstitution($idInstitution, $year);
    }

    public static function getMyselfByDocument(string $document, int $tipoUsuario) {
        $conexion = Conexion::newConnection('MYSQL');
        $sql = "SELECT * FROM ".BD_GENERAL.".usuarios WHERE uss_tipo=".$tipoUsuario." AND uss_documento='".$document."'";
        $consulta = mysqli_query($conexion, $sql);

        if(mysqli_num_rows($consulta) == 0) {
            throw new Exception("No se encontró ningún usuario que coincida con el documento especificado: {$document}.", -1);
        }

        $data = mysqli_fetch_array($consulta, MYSQLI_ASSOC);

        return $data;

    }
}