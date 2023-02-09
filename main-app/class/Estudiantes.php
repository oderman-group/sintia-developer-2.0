<?php
class Estudiantes {

    public static function listarEstudiantes(
        int $eliminados = 0, 
        string $filtroAdicional = '', 
        $filtroLimite = 'LIMIT 0, 2000'
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
            WHERE mat_eliminado IN (0, '".$eliminados."')
            ".$filtroAdicional."
            ORDER BY mat_grado, mat_grupo, mat_primer_apellido
            ".$filtroLimite."
            ");
        } catch (Exception $e) {
            return "Excepción catpurada: ".$e->getMessage();
        }

        return $resultado;
    }

}