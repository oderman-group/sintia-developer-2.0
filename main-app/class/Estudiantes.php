<?php
class Estudiantes {

    public static function listarEstudiantes(
        int    $eliminados      = 0, 
        string $filtroAdicional = '', 
        string $filtroLimite    = 'LIMIT 0, 2000'
    )
    {
        global $conexion, $baseDatosServicios;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM academico_matriculas
            LEFT JOIN usuarios ON uss_id=mat_id_usuario
            LEFT JOIN academico_grados ON gra_id=mat_grado
            LEFT JOIN academico_grupos ON gru_id=mat_grupo
            LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat_genero
            LEFT JOIN ".$baseDatosServicios.".localidad_ciudades ON ciu_id=mat_lugar_nacimiento
            WHERE mat_eliminado IN (0, '".$eliminados."')
            ".$filtroAdicional."
            ORDER BY mat_grado, mat_grupo, mat_primer_apellido, mat_segundo_apellido, mat_nombres
            ".$filtroLimite."
            ");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function listarEstudiantesEnGrados(
        string $filtroAdicional = '', 
        string $filtroLimite    = 'LIMIT 0, 2000'
    )
    {
        global $conexion, $baseDatosServicios;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM academico_matriculas
            LEFT JOIN usuarios ON uss_id=mat_id_usuario
            INNER JOIN academico_grados ON gra_id=mat_grado
            INNER JOIN academico_grupos ON gru_id=mat_grupo
            LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat_genero
            WHERE mat_eliminado = 0
            ".$filtroAdicional."
            ORDER BY mat_primer_apellido, mat_segundo_apellido, mat_nombres
            ".$filtroLimite."
            ");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function listarEstudiantesParaDocentes(string $filtroDocentes = '')
    {
        global $conexion, $baseDatosServicios;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM academico_matriculas
            LEFT JOIN usuarios ON uss_id=mat_id_usuario
            LEFT JOIN academico_grados ON gra_id=mat_grado
            LEFT JOIN academico_grupos ON gru_id=mat_grupo
            LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat_genero
            WHERE mat_eliminado=0 
            AND (mat_estado_matricula=1 OR mat_estado_matricula=2)
            ".$filtroDocentes."
            ORDER BY mat_primer_apellido, mat_segundo_apellido, mat_nombres
            ");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function obtenerDatosEstudiante($estudiante = 0)
    {

        global $conexion, $baseDatosServicios;
        $resultado = [];

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM academico_matriculas
            LEFT JOIN usuarios ON uss_id=mat_id_usuario
            LEFT JOIN academico_grados ON gra_id=mat_grado
            LEFT JOIN academico_grupos ON gru_id=mat_grupo
            LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat_genero
            WHERE (mat_id='".$estudiante."' || mat_documento='".$estudiante."') AND mat_eliminado=0
            ");
            $num = mysqli_num_rows($consulta);
            if($num == 0){
                echo "Este estudiante no existe";
                exit();
            }
            $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;

    }

    public static function NombreCompletoDelEstudiante($estudiante = 0){

        global $conexion, $baseDatosServicios;
        $resultado = [];

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM academico_matriculas
            LEFT JOIN usuarios ON uss_id=mat_id_usuario
            LEFT JOIN academico_grados ON gra_id=mat_grado
            LEFT JOIN academico_grupos ON gru_id=mat_grupo
            LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat_genero
            WHERE (mat_id='".$estudiante."' || mat_documento='".$estudiante."') AND mat_eliminado=0
            ");
            $num = mysqli_num_rows($consulta);
            if($num == 0){
                echo "Este estudiante no existe";
                exit();
            }
            $datos = mysqli_fetch_array($consulta, MYSQLI_BOTH);
            $resultado = strtoupper($datos['mat_primer_apellido']." ".$datos['mat_segundo_apellido']." ".$datos['mat_nombres']." ".$datos['mat_nombre2']);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;

    }

    public static function listarEstudiantesParaAcudientes($acudiente)
    {
        global $conexion, $baseDatosServicios;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM academico_matriculas
            LEFT JOIN usuarios ON uss_id=mat_id_usuario
            LEFT JOIN academico_grados ON gra_id=mat_grado
            LEFT JOIN academico_grupos ON gru_id=mat_grupo
            LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat_genero
            INNER JOIN usuarios_por_estudiantes ON upe_id_estudiante=mat_id AND upe_id_usuario='".$acudiente."'
            WHERE mat_eliminado=0 
            ORDER BY mat_primer_apellido, mat_segundo_apellido, mat_nombres
            ");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function listarEstudiantesParaEstudiantes(string $filtroEstudiantes = '')
    {
        global $conexion, $baseDatosServicios;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM academico_matriculas
            LEFT JOIN usuarios ON uss_id=mat_id_usuario
            LEFT JOIN academico_grados ON gra_id=mat_grado
            LEFT JOIN academico_grupos ON gru_id=mat_grupo
            LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat_genero
            WHERE mat_eliminado=0 
            AND (mat_estado_matricula=1 OR mat_estado_matricula=2)
            ".$filtroEstudiantes."
            ORDER BY mat_primer_apellido, mat_segundo_apellido, mat_nombres
            ");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function obtenerDatosEstudiantePorIdUsuario($estudianteIdUsuario = 0)
    {

        global $conexion, $baseDatosServicios;
        $resultado = [];

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM academico_matriculas
            LEFT JOIN usuarios ON uss_id=mat_id_usuario
            LEFT JOIN academico_grados ON gra_id=mat_grado
            LEFT JOIN academico_grupos ON gru_id=mat_grupo
            LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat_genero
            WHERE mat_id_usuario='".$estudianteIdUsuario."' AND mat_eliminado=0
            ");
            $num = mysqli_num_rows($consulta);
            if($num == 0){
                echo "Este estudiante no existe";
                exit();
            }
            $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;

    }

    public static function validarExistenciaEstudiante($estudiante = 0)
    {

        global $conexion;
        $num = 0;

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM academico_matriculas
            WHERE (mat_id='".$estudiante."' || mat_documento='".$estudiante."') AND mat_eliminado=0
            ");
            $num = mysqli_num_rows($consulta);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $num;

    }

}