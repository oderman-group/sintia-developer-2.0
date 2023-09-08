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
        global $conexion;

        $infoCargaActual = [];
		try{
			$consultaCargaActual = mysqli_query($conexion, "SELECT * FROM academico_cargas 
			INNER JOIN academico_materias ON mat_id=car_materia
			INNER JOIN academico_grados ON gra_id=car_curso
			INNER JOIN academico_grupos ON gru_id=car_grupo
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

}