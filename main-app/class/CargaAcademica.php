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

}