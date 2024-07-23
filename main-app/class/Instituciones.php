<?php
require_once(ROOT_PATH."/main-app/class/Conexion.php");

class Instituciones {

    public static function getDataInstitution($institutionId) {
        $conexionBaseDatosServicios = Conexion::newConnection('MYSQL');

        $institucionConsulta = mysqli_query($conexionBaseDatosServicios, "SELECT * FROM ".BD_ADMIN.".instituciones 
        WHERE ins_estado = 1 AND ins_id='".$institutionId."' AND ins_enviroment='".ENVIROMENT."'");

        return $institucionConsulta;
    }

}