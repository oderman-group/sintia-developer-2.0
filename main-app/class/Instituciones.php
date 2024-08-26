<?php
require_once(ROOT_PATH."/main-app/class/Conexion.php");

class Instituciones {

    /**
     * Retrieves data of a specific institution from the database.
     *
     * @param int $institutionId The unique identifier of the institution.
     *
     * @return mysqli_result|bool Returns a mysqli_result object containing the institution's data if found,
     *                           or false if no data is found or an error occurred.
     *
     * @throws Exception If the database connection fails.
     */
    public static function getDataInstitution(int $institutionId) {
        $conexionBaseDatosServicios = Conexion::newConnection('MYSQL');

        $institucionConsulta = mysqli_query($conexionBaseDatosServicios, "SELECT * FROM ".BD_ADMIN.".instituciones 
        WHERE ins_estado = 1 AND ins_id='".$institutionId."' AND ins_enviroment='".ENVIROMENT."'");

        if (mysqli_num_rows($institucionConsulta) == 0) {
            throw new Exception("No se encontró un resultado para la institución dada {$institutionId}.", -1);
        }

        return $institucionConsulta;
    }

     /**
     * Retrieves related institutions from the database.
     *
     * This function calls a stored procedure to obtain institutions related to the one specified
     * in the global configuration.
     *
     * @global array $config The global configuration array containing the institution ID.
     *
     * @return mysqli_result|bool Returns a mysqli_result object containing the related institutions' data if found,
     *                            or false if no data is found or an error occurred.
     *
     * @throws Exception If the database connection fails.
     */
    public static function getSites() {
        global $config;

        $conexion = Conexion::newConnection('MYSQL');

        $sql = "CALL obtener_instituciones_relacionadas(".$config['conf_id_institucion'].")";

        $consulta = mysqli_query($conexion, $sql);

        return $consulta;
    }

    /**
     * Checks if two institutions are linked.
     *
     * This function queries the database to determine if there is a linkage between two specified institutions.
     *
     * @param int $idInstitutionOne The unique identifier of the first institution.
     * @param int $idInstitutionTwo The unique identifier of the second institution.
     *
     * @return bool Returns true if the institutions are linked, false otherwise.
     *
     * @throws Exception If the database connection fails.
     */
    public static function areSitesVinculed(int $idInstitutionOne, int $idInstitutionTwo): bool 
    {
        $conexion = Conexion::newConnection('MYSQL');

        $sql      = "SELECT ".BD_ADMIN.".instituciones_vinculadas($idInstitutionOne, $idInstitutionTwo) AS vinculado";
        $consulta = mysqli_query($conexion, $sql);
        $data     = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $data['vinculado'] === '1';
    }

    /**
     * Retrieves general information of a specific institution for a given year from the database.
     *
     * @param int $idInstitution The unique identifier of the institution.
     * @param int $year The year for which the general information is to be retrieved.
     *
     * @return array Returns an associative array containing the general information of the institution for the specified year.
     *               Throws an exception if no data is found or an error occurred.
     *
     * @throws Exception If no information is found for the given institution and year.
     */
    public static function getGeneralInformationFromInstitution(int $idInstitution, int $year) {
        $conexion = Conexion::newConnection('MYSQL');

        $consulta = mysqli_query($conexion, "
        SELECT * FROM ".BD_ADMIN.".general_informacion 
        WHERE info_institucion=$idInstitution AND info_year=$year
        ");

        if (mysqli_num_rows($consulta) == 0) {
            throw new Exception("No se encontró información general para la institución y el año dados {$idInstitution}, {$year}.", -1);
        }

        $data = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $data;
    }

}