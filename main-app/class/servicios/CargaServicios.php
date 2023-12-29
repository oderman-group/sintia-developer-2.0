<?php
require_once("Servicios.php");
class CargaServicios
{
    /**
     * Obtiene la cantidad de cargas para un curso específico.
     *
     * @param int $idCurso Identificador del curso.
     *
     * @return array|false Arreglo con la cantidad de cargas del curso o false si hay un error.
     */
    public static function cantidadCursos($idCurso = 1)
    {
        global $config;

        return Servicios::getSql("SELECT COUNT(*) AS cargas_curso FROM " . BD_ACADEMICA . ".academico_cargas WHERE car_curso='" . $idCurso . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
    }

    /**
     * Lista las cargas académicas con información adicional.
     *
     * @param array|null $parametrosArray Arreglo de parámetros para filtrar la consulta (opcional).
     *
     * @return array|mysqli_result|false Arreglo de datos del resultado, objeto mysqli_result o false si hay un error.
     */
    public static function listar($parametrosArray = null)
    {
        global $config;

        $sqlInicial = "SELECT * FROM " . BD_ACADEMICA . ".academico_cargas car 
        INNER JOIN " . BD_ACADEMICA . ".academico_materias am ON am.mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$_SESSION["bd"]}
        INNER JOIN " . BD_ACADEMICA . ".academico_grados gra ON gra_id=car_curso AND gra.institucion={$config['conf_id_institucion']} AND gra.year={$_SESSION["bd"]}
        INNER JOIN " . BD_GENERAL . ".usuarios uss ON uss_id=car_docente AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}";

        if ($parametrosArray && count($parametrosArray) > 0) {
            $parametrosValidos = array('car_curso', 'car_grupo', 'car.institucion', 'car.year');
            $sqlInicial = Servicios::concatenarWhereAnd($sqlInicial, $parametrosValidos, $parametrosArray);
        };

        $sqlFinal = " ";
        $sql = $sqlInicial . $sqlFinal;
        return Servicios::SelectSql($sql);
    }
}
