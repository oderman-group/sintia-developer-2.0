<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH . "/main-app/class/Utilidades.php");
require_once(ROOT_PATH . "/main-app/class/BindSQL.php");

class Informes
{

    /**
     * Esta función lista estudiantes según varios parámetros.
     *
     * @param array $cursos - Indica si se deben incluir estudiantes eliminados (0 o 1).
     * @param array $grupos - Filtros adicionales para la consulta SQL.
     * @param array $materias - Límite de resultados para la consulta SQL.
     *
     * @return array - Un array con los resultados de la consulta.
     */
    public static function informePeriodico(
        array $cursos,
        array $grupos,
        array $materias
    ) {
        global $config;
        $resultado = [];

        // Preparar los placeholders para la consulta
        $in_cursos = implode(', ', array_fill(0, count($cursos), '?'));
        $in_grupos = implode(', ', array_fill(0, count($grupos), '?'));
        $in_materias = implode(', ', array_fill(0, count($materias), '?'));
        try {
            $sql = "SELECT 
            mat_id,
            mat_matricula,
            mat_tipo_documento,
            ogen_nombre,
            mat_documento,
            mat_primer_apellido,
            mat_segundo_apellido,
            mat_nombres,
            mat_nombre2,
            mat_grado,
            gra_nombre,
            mat_grupo,
            gru_nombre,
            car_id,
            car_materia,
            bol_periodo,
            bol_nota
            FROM " . BD_ACADEMICA . ".academico_matriculas mat
            LEFT JOIN " . BD_ACADEMICA . ".academico_cargas car ON
            (
                car.institucion=mat.institucion
                AND car.year=mat.year
                AND car_curso=mat_grado
                AND car_grupo=mat_grupo
                AND car_activa=1	
            )
            LEFT JOIN " . BD_ACADEMICA . ".academico_boletin bol ON
            (
                bol.institucion=mat.institucion
                AND bol.year=mat.year
                AND bol_carga=car_id
                AND bol_estudiante=mat_id
            )
            LEFT JOIN " . BD_ACADEMICA . ".academico_grados gra ON 
            (
                gra_id=mat.mat_grado 
                AND gra.institucion=mat.institucion 
                AND gra.year=mat.year
            )
            LEFT JOIN " . BD_ACADEMICA . ".academico_grupos gru ON 
            (
                gru.gru_id=mat.mat_grupo 
                AND gru.institucion=mat.institucion 
                AND gru.year=mat.year
            )
            LEFT JOIN ".BD_ADMIN.".opciones_generales opc ON ogen_id=mat.mat_tipo_documento AND ogen_grupo=1
            
            WHERE mat.institucion=? 
            AND mat.mat_eliminado=0 
            AND (mat.mat_estado_matricula=1 OR mat.mat_estado_matricula=2)
            AND mat.year=?
            AND mat_grado IN($in_cursos)
            AND mat_grupo IN($in_grupos)
            AND car_materia IN($in_materias)
            AND bol_periodo IS NOT NULL
            ORDER BY mat.mat_grado, car_materia, mat.mat_grupo, mat.mat_primer_apellido, mat.mat_segundo_apellido, mat.mat_nombres,bol_periodo";

            $parametros = [$config['conf_id_institucion'], $_SESSION["bd"], $cursos, $grupos, $materias];

            $resultado = BindSQL::prepararSQL($sql, $parametros);
            $resultadoArray = BindSQL::resultadoArray($resultado);
        } catch (Exception $e) {
            echo "Excepción catpurada: " . $e->getMessage();
            exit();
        }

        return $resultadoArray;
    }
}
