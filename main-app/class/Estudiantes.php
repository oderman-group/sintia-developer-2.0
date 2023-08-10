<?php
require_once("../class/servicios/MediaTecnicaServicios.php");

class Estudiantes {

    public static function listarEstudiantes(
        int    $eliminados      = 0, 
        string $filtroAdicional = '', 
        string $filtroLimite    = 'LIMIT 0, 2000',
        $cursoActual=null
    )
    {
        global $conexion, $baseDatosServicios, $config;
        $tipoGrado=$cursoActual?$cursoActual["gra_tipo"]:GRADO_GRUPAL;
        $resultado = [];
        
        try {
            if($tipoGrado==GRADO_GRUPAL){
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
            }else{
                $parametros = [
                    'matcur_id_curso'=>$cursoActual["gra_id"],
                    'matcur_id_institucion'=>$config['conf_id_institucion'],
                    'limite'=>$filtroLimite,
                    'arreglo'=>false
                ];
                $resultado = MediaTecnicaServicios::listarEstudiantes($parametros);
                }
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function listarEstudiantesEnGrados(
        string $filtroAdicional = '', 
        string $filtroLimite    = 'LIMIT 0, 2000',
        $cursoActual=null,
        string $BD    = '',
        $grupoActual=1
    )
    {
        global $conexion, $baseDatosServicios, $config;
        $tipoGrado=$cursoActual?$cursoActual["gra_tipo"]:GRADO_GRUPAL;
        $resultado = [];

        try {
            if($tipoGrado==GRADO_GRUPAL){
                $resultado = mysqli_query($conexion, "SELECT * FROM ".$BD."academico_matriculas
                LEFT JOIN ".$BD."usuarios ON uss_id=mat_id_usuario
                INNER JOIN ".$BD."academico_grados ON gra_id=mat_grado
                INNER JOIN ".$BD."academico_grupos ON gru_id=mat_grupo
                LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat_genero
                WHERE mat_eliminado = 0
                ".$filtroAdicional."
                ORDER BY mat_primer_apellido, mat_segundo_apellido, mat_nombres
                ".$filtroLimite."
                ");
            }else{
                $parametros = [
                    'matcur_id_curso'=>$cursoActual["gra_id"],
                    'matcur_id_grupo'=>$grupoActual,
                    'matcur_id_institucion'=>$config['conf_id_institucion'],
                    'limite'=>$filtroLimite,
                    'arreglo'=>false
                ];
                $resultado = MediaTecnicaServicios::listarEstudiantes($parametros,$BD);
            }
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function listarEstudiantesParaDocentes(string $filtroDocentes = '',string $filtroLimite = '')
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
            $filtroLimite");
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
            WHERE (mat_id='".$estudiante."' || mat_documento='".$estudiante."' || mat_matricula='".$estudiante."') AND mat_eliminado=0
            ");
            $num = mysqli_num_rows($consulta);
            if($num == 0){
                echo "Estás intentando obtener datos de un estudiante que no existe: ".$estudiante."<br>";
            }
            $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;

    }

    public static function NombreCompletoDelEstudiante(array $estudiante){
        return strtoupper($estudiante['mat_primer_apellido']." ".$estudiante['mat_segundo_apellido']." ".$estudiante['mat_nombres']." ".$estudiante['mat_nombre2']);
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

    public static function listarEstudiantesParaEstudiantes(string $filtroEstudiantes = '',$cursoActual=null,$grupoActual=1)
    {
        global $conexion, $baseDatosServicios, $config;
        $resultado = [];
        $tipoGrado=$cursoActual?$cursoActual["gra_tipo"]:GRADO_GRUPAL;
        try {
             if($tipoGrado==GRADO_GRUPAL){
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
            } else{
                $parametros = [
                      'matcur_id_curso'=>$cursoActual["gra_id"],
                      'matcur_id_grupo'=>$grupoActual,
                      'matcur_id_institucion'=>$config['conf_id_institucion'],
                      'and'=>'AND (mat_estado_matricula=1 OR mat_estado_matricula=2)',
                      'arreglo'=>false
                  ];
                  $resultado = MediaTecnicaServicios::listarEstudiantes($parametros);
                  
              }
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
                echo "Este estudiante no existe: ".$estudianteIdUsuario;
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

    public static function listarEstudiantesParaPlanillas(
        int    $eliminados      = 0, 
        string $filtroAdicional = '', 
        string $BD    = ''
    )
    {
        global $conexion, $baseDatosServicios;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM $BD.academico_matriculas
            LEFT JOIN $BD.usuarios ON uss_id=mat_id_usuario
            LEFT JOIN $BD.academico_grados ON gra_id=mat_grado
            LEFT JOIN $BD.academico_grupos ON gru_id=mat_grupo
            LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat_genero
            LEFT JOIN ".$baseDatosServicios.".localidad_ciudades ON ciu_id=mat_lugar_nacimiento
            WHERE mat_eliminado IN (0, '".$eliminados."')
            ".$filtroAdicional."
            ORDER BY mat_grado, mat_grupo, mat_primer_apellido, mat_segundo_apellido, mat_nombres");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function NombreCompletoDelEstudianteParaInformes(array $estudiante, $orden){
        
        $nombre=strtoupper($estudiante['mat_nombres']." ".$estudiante['mat_nombre2']." ".$estudiante['mat_primer_apellido']." ".$estudiante['mat_segundo_apellido']);
        
        if($orden==2){
            $nombre=strtoupper($estudiante['mat_primer_apellido']." ".$estudiante['mat_segundo_apellido']." ".$estudiante['mat_nombres']." ".$estudiante['mat_nombre2']);
        }
        return $nombre;
    }

    public static function ActualizarEstadoMatricula($idEstudiante, $estadoMatricula)
    {
        global $conexion;

        try {
            mysqli_query($conexion, "UPDATE academico_matriculas SET mat_estado_matricula='".$estadoMatricula."' WHERE mat_id='".$idEstudiante."'");
        } catch (Exception $e) {
            echo "Excepción capturada: ".$e->getMessage();
            exit();
        }
    }

    public static function retirarRestaurarEstudiante($idEstudiante, $motivo)
    {
        global $conexion;

        try {
            mysqli_query($conexion, "INSERT INTO academico_matriculas_retiradas (matret_estudiante, matret_fecha, matret_motivo, matret_responsable)VALUES('".$idEstudiante."', now(), '".$motivo."', '".$_SESSION["id"]."')");
        } catch (Exception $e) {
            echo "Excepción capturada: ".$e->getMessage();
            exit();
        }
    }

    public static function estudiantesMatriculados(
        string    $filtro      = '',
        string $BD    = ''
    )
    {
        global $conexion;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM $BD.academico_matriculas 
            INNER JOIN $BD.academico_grupos ON mat_grupo=gru_id
            INNER JOIN $BD.academico_grados ON mat_grado=gra_id 
            WHERE mat_eliminado=0 AND mat_estado_matricula=1 $filtro 
            GROUP BY mat_id
            ORDER BY mat_grupo, mat_primer_apellido");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function obtenerDatosEstudiantesParaBoletin(
        int    $estudiante      = 0,
        string $BD    = ''
    )
    {
        global $conexion;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM $BD.academico_matriculas am
            INNER JOIN $BD.academico_grupos ON mat_grupo=gru_id
            INNER JOIN $BD.academico_grados ON mat_grado=gra_id WHERE mat_id=" . $estudiante);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function validarRepeticionDocumento($documento, $idEstudiante)
    {

        global $conexion;
        $num = 0;

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM academico_matriculas
            WHERE mat_id!='".$idEstudiante."' AND mat_documento='".$documento."' AND mat_eliminado=0
            ");
            $num = mysqli_num_rows($consulta);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $num;

    }
    
    public static function listarEstudiantesParaDocentesMT(array $datosCargaActual = [])
    {
        global $conexion, $baseDatosServicios, $config;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".mediatecnica_matriculas_cursos
            LEFT JOIN academico_matriculas ON mat_eliminado=0 AND (mat_estado_matricula=1 OR mat_estado_matricula=2) AND mat_id=matcur_id_matricula
            LEFT JOIN academico_grados ON gra_id=matcur_id_curso
            LEFT JOIN academico_grupos ON gru_id=matcur_id_grupo
            LEFT JOIN usuarios ON uss_id=mat_id_usuario
            LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat_genero
            WHERE matcur_id_curso='".$datosCargaActual['car_curso']."' AND matcur_id_grupo='".$datosCargaActual['car_grupo']."' AND matcur_id_institucion='".$config['conf_id_institucion']."'
            ORDER BY mat_primer_apellido, mat_segundo_apellido, mat_nombres;
            ");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function contarEstudiantesParaDocentes(string $filtroDocentes = '')
    {
        global $conexion;
        $cantidad = 0;

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM academico_matriculas
            LEFT JOIN usuarios ON uss_id=mat_id_usuario
            LEFT JOIN academico_grados ON gra_id=mat_grado
            LEFT JOIN academico_grupos ON gru_id=mat_grupo
            WHERE mat_eliminado=0 
            AND (mat_estado_matricula=1 OR mat_estado_matricula=2)
            ".$filtroDocentes."
            ORDER BY mat_primer_apellido, mat_segundo_apellido, mat_nombres
            ");
            $cantidad = mysqli_num_rows($consulta);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $cantidad;
    }

    public static function contarEstudiantesParaDocentesMT(array $datosCargaActual = [])
    {
        global $conexion, $baseDatosServicios, $config;
        $cantidad = 0;

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".mediatecnica_matriculas_cursos
            LEFT JOIN academico_matriculas ON mat_eliminado=0 AND (mat_estado_matricula=1 OR mat_estado_matricula=2) AND mat_grupo='".$datosCargaActual['car_grupo']."' AND mat_id=matcur_id_matricula
            LEFT JOIN academico_grados ON gra_id=matcur_id_curso
            LEFT JOIN academico_grupos ON gru_id=matcur_id_grupo
            LEFT JOIN usuarios ON uss_id=mat_id_usuario
            LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat_genero
            WHERE matcur_id_curso='".$datosCargaActual['car_curso']."' AND matcur_id_grupo='".$datosCargaActual['car_grupo']."' AND matcur_id_institucion='".$config['conf_id_institucion']."'
            ORDER BY mat_primer_apellido, mat_segundo_apellido, mat_nombres;
            ");
            $cantidad = mysqli_num_rows($consulta);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $cantidad;
    }

    public static function escogerConsultaParaListarEstudiantesParaDocentes(array $datosCargaActual = [])
    {
        $filtroDocentesParaListarEstudiantes = " AND mat_grado='".$datosCargaActual['car_curso']."' AND mat_grupo='".$datosCargaActual['car_grupo']."'";

        if($datosCargaActual['gra_tipo'] == GRADO_INDIVIDUAL) {
            $consulta = Estudiantes::listarEstudiantesParaDocentesMT($datosCargaActual);
        } else {
            $consulta = Estudiantes::listarEstudiantesParaDocentes($filtroDocentesParaListarEstudiantes);
        }

        return $consulta;
    }

    //METODO PARA BUSCAR TODA LA INFORMACIÓN DE LOS ESTUDIANTES
    public static function reporteEstadoEstudiantes($where="")
    {

        global $conexion, $baseDatosServicios;

        try {
            $consulta = mysqli_query($conexion, "SELECT mat_matricula, mat_primer_apellido, mat_segundo_apellido, mat_nombres, mat_inclusion, mat_extranjero, mat_documento, uss_usuario, uss_email, uss_celular, uss_telefono, gru_nombre, gra_nombre, og.ogen_nombre as Tipo_est, mat_id,
            IF(mat_acudiente is null,'No',uss_nombre) as nom_acudiente,
            IF(mat_foto is null,'No','Si') as foto, 
            og2.ogen_nombre as genero, og3.ogen_nombre as religion, og4.ogen_nombre as estrato, og5.ogen_nombre as tipoDoc,
            CASE mat_estado_matricula 
                WHEN 1 THEN 'Matriculado' 
                WHEN 2 THEN 'Asistente' 
                WHEN 3 THEN 'Cancelado' 
                WHEN 4 
                THEN 'No matriculado' 
            END AS estado
            FROM academico_matriculas am 
            INNER JOIN academico_grupos ag ON am.mat_grupo=ag.gru_id
            INNER JOIN academico_grados agr ON agr.gra_id=am.mat_grado
            INNER JOIN $baseDatosServicios.opciones_generales og ON og.ogen_id=am.mat_tipo
            INNER JOIN $baseDatosServicios.opciones_generales og2 ON og2.ogen_id=am.mat_genero
            INNER JOIN $baseDatosServicios.opciones_generales og3 ON og3.ogen_id=am.mat_religion
            INNER JOIN $baseDatosServicios.opciones_generales og4 ON og4.ogen_id=am.mat_estrato
            INNER JOIN $baseDatosServicios.opciones_generales og5 ON og5.ogen_id=am.mat_tipo_documento
            INNER JOIN usuarios u ON u.uss_id=am.mat_acudiente or am.mat_acudiente is null
            $where
            GROUP BY mat_id
            ORDER BY mat_primer_apellido,mat_estado_matricula;");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $consulta;

    }

}