<?php

class Inscripciones {

    /**
     * Este metodo me busca la configuración de la institución para admisiones
     * @param mysqli $conexion
     * @param string $baseDatosAdmisiones
     * @param int $idInsti
     * @param int $year
     * 
     * @return array $resultado
    **/
    public static function configuracionAdmisiones($conexion,$baseDatosAdmisiones,$idInsti,$year){
        $resultado = [];

        try {
            $configConsulta = mysqli_query($conexion,"SELECT * FROM {$baseDatosAdmisiones}.config_instituciones WHERE cfgi_id_institucion = ".$idInsti." AND cfgi_year = ".$year);
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
        $resultado = mysqli_fetch_array($configConsulta, MYSQLI_BOTH);

        return $resultado;
    }
}