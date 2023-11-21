<?php
class CargaAcademica {

    public static function validarExistenciaCarga($docente, $curso, $grupo, $asignatura)
    {

        global $conexion;
        $result = false;

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM academico_cargas
            WHERE car_docente='".$docente."' AND car_curso='".$curso."' AND car_grupo='".$grupo."' AND car_materia='".$asignatura."'
            ");
            $num = mysqli_num_rows($consulta);
            if($num > 0) {
                $result = true;
            }
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $result;

    }

    /**
     * Este función consulta los datos de la carga académica actual y
     * los almacena en sesion
     * 
     * @param int $carga
     * @param int $sesion
     * 
     * @return array
     */
    public static function cargasDatosEnSesion(Int $carga, Int $sesion): array 
    {
        global $conexion, $filtroMT, $config;

        $infoCargaActual = [];
		try{
			$consultaCargaActual = mysqli_query($conexion, "SELECT * FROM academico_cargas 
			INNER JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$_SESSION["bd"]}
			INNER JOIN ".BD_ACADEMICA.".academico_grados gra ON gra_id=car_curso AND gra.institucion={$config['conf_id_institucion']} AND gra.year={$_SESSION["bd"]} {$filtroMT}
			INNER JOIN ".BD_ACADEMICA.".academico_grupos gru ON gru.gru_id=car_grupo AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$_SESSION["bd"]}
			WHERE car_id='".$carga."' AND car_docente='".$sesion."' AND car_activa=1");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$datosCargaActual = mysqli_fetch_array($consultaCargaActual, MYSQLI_BOTH);
		$infoCargaActual = [
			'datosCargaActual'  => $datosCargaActual
		];

        return $infoCargaActual;
    }

    /**
     * Validar Permiso para Acceso a Períodos Diferentes
     *
     * Esta función se utiliza para determinar si un usuario tiene permiso para acceder
     * a un período de carga diferente en una aplicación o sistema. Verifica si el usuario
     * tiene los derechos necesarios para acceder a un período diferente en función de ciertas
     * condiciones.
     *
     * @param Array $datosCargaActual Un array de datos que contiene información sobre la carga actual.
     * @param Int $periodoConsultaActual El período al que el usuario intenta acceder.
     *
     * @return bool Devuelve `true` si el usuario tiene permiso para acceder al período especificado,
     *              y `false` en caso contrario.
     */
    public static function validarPermisoPeriodosDiferentes(Array $datosCargaActual, Int $periodoConsultaActual): bool 
    {

        if(
            $periodoConsultaActual <= $datosCargaActual['gra_periodos'] 
            && ($periodoConsultaActual == $datosCargaActual['car_periodo'] || $datosCargaActual['car_permiso2'] == PERMISO_EDICION_PERIODOS_DIFERENTES)
        ) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * Validar Acción para Agregar Calificaciones
     *
     * Esta función se utiliza para validar si se puede realizar la acción de agregar calificaciones
     * en un sistema o aplicación. Comprueba si se cumplen ciertas condiciones, como la configuración
     * de calificaciones, el valor de calificación a agregar y el permiso para acceder a períodos diferentes.
     *
     * @param Array $datosCargaActual Un array de datos que contiene información sobre la carga actual.
     * @param Array $valores Un array que contiene valores relacionados con la acción de agregar calificaciones.
     * @param Int $periodoConsultaActual El período al que el usuario intenta acceder.
     * @param Float $porcentajeRestante El porcentaje restante de calificaciones disponibles.
     *
     * @return bool Devuelve `true` si se permite la acción de agregar calificaciones,
     *              y `false` en caso contrario.
     */
    public static function validarAccionAgregarCalificaciones(
        Array $datosCargaActual, 
        Array $valores, 
        Int $periodoConsultaActual,
        Float $porcentajeRestante
    ): bool {

        if(
            (
                (
                    $datosCargaActual['car_configuracion'] == CONFIG_AUTOMATICO_CALIFICACIONES 
                    && $valores[1] < $datosCargaActual['car_maximas_calificaciones'] 
                )
                || 
                ( $datosCargaActual['car_configuracion'] == CONFIG_MANUAL_CALIFICACIONES 
                && $valores[1] < $datosCargaActual['car_maximas_calificaciones'] 
                && $porcentajeRestante > 0 )
            )

            && self::validarPermisoPeriodosDiferentes($datosCargaActual, $periodoConsultaActual)
        ) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * Obtiene datos relacionados con una carga académica a partir de su ID.
     *
     * Esta función realiza una consulta a la base de datos para obtener información relacionada con una carga académica, como el grado, grupo, materia y docente asociados.
     *
     * @param int $idCarga - El ID de la carga académica que se desea consultar.
     *
     * @return array - Un array asociativo con los datos relacionados o un array vacío si no se encuentran datos.
     */
    public static function datosRelacionadosCarga($idCarga)
    {

        global $conexion, $config;
        $result = [];

        try {
            $consulta = mysqli_query($conexion,"SELECT * FROM academico_cargas
            LEFT JOIN ".BD_ACADEMICA.".academico_grados gra ON gra_id=car_curso AND gra.institucion={$config['conf_id_institucion']} AND gra.year={$_SESSION["bd"]}
            LEFT JOIN ".BD_ACADEMICA.".academico_grupos gru ON gru.gru_id=car_grupo AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$_SESSION["bd"]}
            LEFT JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$_SESSION["bd"]}
            LEFT JOIN usuarios ON uss_id=car_docente
            WHERE car_id={$idCarga}");
            $result = mysqli_fetch_array($consulta, MYSQLI_BOTH);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $result;

    }

}