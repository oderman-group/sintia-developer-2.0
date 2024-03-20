<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");

class Asignaturas {

    /**
     * Este metodo me trae los dotos de una asignatura
     * @param mysqli $conexion
     * @param array $config
     * @param string $idMateria
     * 
     * @return array $resultado
    **/
    public static function consultarDatosAsignatura (
        mysqli $conexion, 
        array $config,
        string $idMateria
    )
    {
        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_materias
            WHERE mat_id='".$idMateria."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo me suma el valor de las materias de un area
     * @param mysqli $conexion
     * @param array $config
     * @param string $idCurso
     * @param string $idGrupo
     * @param string $idArea
     * @param string $yearBd
     * 
     * @return array $resultado
    **/
    public static function sumarValorAsignaturasArea (
        mysqli $conexion, 
        array $config,
        string $idCurso,
        string $idGrupo,
        string $idArea,
        string $yearBd    = ''
    )
    {
        $year = !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        try {
            $consulta = mysqli_query($conexion, "SELECT SUM(mat_valor) FROM ".BD_ACADEMICA.".academico_materias am
                    INNER JOIN ".BD_ACADEMICA.".academico_cargas car ON car_materia=am.mat_id AND car_curso='".$idCurso."' AND car_grupo='".$idGrupo."' AND car.institucion={$config['conf_id_institucion']} AND car.year={$year}
                    WHERE am.mat_area='".$idArea."' AND am.institucion={$config['conf_id_institucion']} AND am.year={$year}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo me consulta las asignaturas de un curso
     * @param mysqli $conexion
     * @param array $config
     * @param string $idCurso
     * @param string $idGrupo
     * @param string $yearBd
     * 
     * @return mysqli_result $consulta
    **/
    public static function consultarAsignaturasCurso (
        mysqli $conexion, 
        array $config,
        string $idCurso,
        string $idGrupo,
        string $yearBd    = ''
    )
    {
        $year = !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        try {
            $consulta = mysqli_query($conexion, "SELECT ar_id, ar_nombre, count(*) AS numMaterias, car_curso, car_grupo FROM ".BD_ACADEMICA.".academico_materias am
            INNER JOIN ".BD_ACADEMICA.".academico_areas a ON a.ar_id = am.mat_area AND a.institucion={$config['conf_id_institucion']} AND a.year={$year}
            INNER JOIN ".BD_ACADEMICA.".academico_cargas car on car_materia = am.mat_id and car_curso = '".$idCurso."' AND car_grupo = '".$idGrupo."' AND car.institucion={$config['conf_id_institucion']} AND car.year={$year}
            WHERE am.institucion={$config['conf_id_institucion']} AND am.year={$year}
            GROUP by am.mat_area
            ORDER BY a.ar_posicion");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }

        return $consulta;
    }

    /**
     * Este metodo me consulta las asignaturas de una area
     * @param mysqli $conexion
     * @param array $config
     * @param string $idCurso
     * @param string $idGrupo
     * @param string $idArea
     * @param string $yearBd
     * 
     * @return mysqli_result $consulta
    **/
    public static function consultarAsignaturasArea (
        mysqli $conexion, 
        array $config,
        string $idCurso,
        string $idGrupo,
        string $idArea,
        string $yearBd    = ''
    )
    {
        $year = !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        try {
            $consulta = mysqli_query($conexion, "SELECT car_id FROM ".BD_ACADEMICA.".academico_materias am, ".BD_ACADEMICA.".academico_cargas car WHERE am.mat_area='".$idArea."' AND am.mat_id=car_materia AND car_curso='".$idCurso."' AND car_grupo='".$idGrupo."' AND am.institucion={$config['conf_id_institucion']} AND am.year={$year} AND car.institucion={$config['conf_id_institucion']} AND car.year={$year}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }

        return $consulta;
    }

    /**
     * Este metodo me consulta las asignaturas, Definitiva  e intensidad de una carga
     * @param mysqli $conexion
     * @param array $config
     * @param string $idCurso
     * @param string $idCursoEst
     * @param string $idGrupo
     * @param string $idArea
     * @param string $yearBd
     * 
     * @return mysqli_result $consulta
    **/
    public static function consultarAsignaturaDefinitivaIntensidad (
        mysqli $conexion, 
        array $config,
        string $idCurso,
        string $idCursoEst,
        string $idGrupo,
        string $idArea,
        string $yearBd    = ''
    )
    {
        $year = !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        try {
            $consulta = mysqli_query($conexion, "SELECT car_id, am.mat_nombre, ipc.ipc_intensidad FROM ".BD_ACADEMICA.".academico_materias am, ".BD_ACADEMICA.".academico_cargas car, ".BD_ACADEMICA.".academico_intensidad_curso ipc WHERE am.mat_area='".$idArea."' AND am.mat_id=car_materia AND car_curso='".$idCurso."' AND car_grupo='".$idGrupo."' AND ipc.ipc_curso='".$idCursoEst."' AND ipc.ipc_materia=am.mat_id AND ipc.institucion={$config['conf_id_institucion']} AND ipc.year={$year} AND am.institucion={$config['conf_id_institucion']} AND am.year={$year} AND car.institucion={$config['conf_id_institucion']} AND car.year={$year}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }

        return $consulta;
    }

    /**
     * Este metodo me consulta las asignaturas, el curso y el docente de una carga
     * @param mysqli $conexion
     * @param array $config
     * @param string $idCurso
     * @param string $idMateria
     * @param string $idUsuario
     * @param string $yearBd
     * 
     * @return array $resultado
    **/
    public static function consultarAsignaturaCursoUsuario (
        mysqli $conexion, 
        array $config,
        string $idCurso,
        string $idMateria,
        string $idUsuario,
        string $yearBd    = ''
    )
    {
        $year = !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        try {
            $consulta = mysqli_query($conexion, "SELECT mat_id, mat_nombre, gra_codigo, gra_nombre, uss_id, uss_nombre FROM ".BD_ACADEMICA.".academico_materias am, ".BD_ACADEMICA.".academico_grados gra, ".BD_GENERAL.".usuarios uss WHERE am.mat_id='".$idMateria."' AND gra_id='".$idCurso."' AND uss_id='".$idUsuario."' AND am.institucion={$config['conf_id_institucion']} AND am.year={$year} AND gra.institucion={$config['conf_id_institucion']} AND gra.year={$year} AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$year}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo me busca una asignatura segun su nombre o siglas
     * @param mysqli $conexion
     * @param array $config
     * @param string $buscar
     * 
     * @return mysqli_result $consulta
    **/
    public static function buscadorAsignatura (
        mysqli $conexion, 
        array $config,
        string $buscar
    )
    {

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_materias 
            WHERE mat_nombre LIKE '%".$buscar."%' OR mat_siglas LIKE '%".$buscar."%' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }

        return $consulta;
    }

    /**
     * Este metodo me trae el nombre y docente de las materias de una carga
     * @param mysqli $conexion
     * @param array $config
     * @param string $idCurso
     * @param string $idGrupo
     * @param string $yearBd
     * 
     * @return mysqli_result $consulta
    **/
    public static function consultarNombreAsignatura (
        mysqli $conexion, 
        array $config,
        string $idCurso,
        string $idGrupo,
        string $yearBd    = ''
    )
    {
        $year = !empty($yearBd) ? $yearBd : $_SESSION["bd"];
        try {
            $consulta = mysqli_query($conexion, "SELECT mat_nombre, car_docente, car_director_grupo FROM ".BD_ACADEMICA.".academico_materias am
            INNER JOIN ".BD_ACADEMICA.".academico_areas a ON a.ar_id = am.mat_area AND a.institucion={$config['conf_id_institucion']} AND a.year={$year}
            INNER JOIN ".BD_ACADEMICA.".academico_cargas car on car_materia = am.mat_id and car_curso = '" . $idCurso . "' AND car_grupo = '" . $idGrupo . "' AND car.institucion={$config['conf_id_institucion']} AND car.year={$year}
            WHERE am.institucion={$config['conf_id_institucion']} AND am.year={$year}
            ORDER BY am.mat_id");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }

        return $consulta;
    }

    /**
     * Este metodo me trae la nota de una materia
     * @param mysqli $conexion
     * @param array $config
     * @param string $idCurso
     * @param string $idGrupo
     * @param int    $periodo
     * @param string $idEstudiante
     * @param string $yearBd
     * 
     * @return mysqli_result $consulta
    **/
    public static function consultarNotaAsignatura (
        mysqli $conexion, 
        array $config,
        string $idCurso,
        string $idGrupo,
        int    $periodo,
        string $idEstudiante,
        string $yearBd    = ''
    )
    {
        $year = !empty($yearBd) ? $yearBd : $_SESSION["bd"];
        try {
            $consulta = mysqli_query($conexion, "SELECT bol_nota FROM ".BD_ACADEMICA.".academico_materias am
            INNER JOIN ".BD_ACADEMICA.".academico_areas a ON a.ar_id = am.mat_area AND a.institucion={$config['conf_id_institucion']} AND a.year={$year}
            INNER JOIN ".BD_ACADEMICA.".academico_cargas car on car_materia = am.mat_id and car_curso = '".$idCurso."' AND car_grupo = '".$idGrupo."' AND car.institucion={$config['conf_id_institucion']} AND car.year={$year}
            LEFT JOIN ".BD_ACADEMICA.".academico_boletin bol ON bol_carga=car_id AND bol_periodo = '".$periodo."' AND bol_estudiante = '".$idEstudiante."' AND bol.institucion={$config['conf_id_institucion']} AND bol.year={$year}
            WHERE am.institucion={$config['conf_id_institucion']} AND am.year={$year}
            ORDER BY am.mat_id");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }

        return $consulta;
    }

    /**
     * Obtiene los indicadores y notas asociados a una materia y estudiante específicos, considerando un área, grados, grupos y condiciones de periodo académico.
     *
     * @param string $grado El identificador del grado.
     * @param string $grupo El identificador del grupo.
     * @param string $materia El identificador del área.
     * @param int    $periodo
     * @param string $estudiante La identificación del estudiante.
     * @param string $yearBd (Opcional) El año académico para el cual se desea obtener la información. Si no se proporciona, se utiliza el año académico actual de la sesión.
     *
     * @return mysqli_result Un conjunto de resultados (`mysqli_result`) que contiene los indicadores, notas y periodos asociados a una materia y estudiante específicos.
     */
    public static function obtenerIndicadoresPorMateriaPeriodo(
        string    $grado,
        string    $grupo,
        string    $materia,
        int       $periodo,
        string    $estudiante,
        string    $yearBd    = ''
    )
    {
        global $conexion, $config;
        $resultado = [];
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        try {
            $resultado = mysqli_query($conexion, "SELECT mat_nombre,mat_area,mat_id,ind_nombre,ipc_periodo,
            ROUND(SUM(cal_nota*(act_valor/100)) / SUM(act_valor/100),2) as nota, ind_id FROM ".BD_ACADEMICA.".academico_materias am
            INNER JOIN ".BD_ACADEMICA.".academico_areas a ON a.ar_id=am.mat_area AND a.institucion={$config['conf_id_institucion']} AND a.year={$year}
            INNER JOIN ".BD_ACADEMICA.".academico_cargas car ON car.car_materia=am.mat_id AND car.institucion={$config['conf_id_institucion']} AND car.year={$year}
            INNER JOIN ".BD_ACADEMICA.".academico_indicadores_carga aic ON aic.ipc_carga=car.car_id AND aic.institucion={$config['conf_id_institucion']} AND aic.year={$year}
            INNER JOIN ".BD_ACADEMICA.".academico_indicadores ai ON aic.ipc_indicador=ai.ind_id AND ai.institucion={$config['conf_id_institucion']} AND ai.year={$year}
            INNER JOIN ".BD_ACADEMICA.".academico_actividades aa ON aa.act_id_tipo=aic.ipc_indicador AND act_id_carga=car_id AND act_estado=1 AND act_registrada=1 AND aa.institucion={$config['conf_id_institucion']} AND aa.year={$year}
            INNER JOIN ".BD_ACADEMICA.".academico_calificaciones aac ON aac.cal_id_actividad=aa.act_id AND aac.institucion={$config['conf_id_institucion']} AND aac.year={$year}
            WHERE car_curso='".$grado."'  and car_grupo='".$grupo."' and mat_id='".$materia."'  AND ipc_periodo='".$periodo."' AND cal_id_estudiante='".$estudiante."' and act_periodo='".$periodo."' AND am.institucion={$config['conf_id_institucion']} AND am.year={$year}
            group by act_id_tipo, act_id_carga
            order by mat_id,ipc_periodo,ind_id");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    /**
     * Este metodo me trae la todas las materias de la institucion
     * @param mysqli $conexion
     * @param array $config
     * @param string $filtro
     * 
     * @return mysqli_result $consulta
    **/
    public static function consultarTodasAsignaturas (
        mysqli $conexion, 
        array $config, 
        string $filtro = ""
    )
    {
        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_materias am
            INNER JOIN ".BD_ACADEMICA.".academico_areas ar ON ar.ar_id=am.mat_area AND ar.institucion={$config['conf_id_institucion']} AND ar.year={$_SESSION["bd"]}
            WHERE am.institucion={$config['conf_id_institucion']} AND am.year={$_SESSION["bd"]} {$filtro}
            ORDER BY am.mat_nombre");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }

        return $consulta;
    }

    /**
     * Este metodo me cuenta todas las materias de un area
     * @param mysqli $conexion
     * @param array     $config
     * @param string    $idArea
     * 
     * @return array $resultado
    **/
    public static function contarAsignaturasArea (
        mysqli $conexion, 
        array $config, 
        string $idArea
    )
    {
        try {
            $consulta = mysqli_query($conexion, "SELECT COUNT(mat_id) FROM ".BD_ACADEMICA.".academico_materias WHERE mat_area='".$idArea."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo me guarda una asignatura
     * @param mysqli    $conexion
     * @param PDO       $conexionPDO
     * @param array     $config
     * @param array     $POST
     * 
     * @return string $codigo
    **/
    public static function guardarAsignatura (
        mysqli $conexion,
        PDO    $conexionPDO, 
        array  $config, 
        array  $POST
    )
    {
        $codigo = Utilidades::getNextIdSequence($conexionPDO, BD_ACADEMICA, 'academico_areas');
    
        if(empty($POST["siglasM"])) {$POST["siglasM"] = substr($POST["nombreM"], 0, 3);}
        if(empty($POST["porcenAsigna"])) {$POST["porcenAsigna"] = '';}
        $_POST["sumarPromedio"] = !empty($_POST["sumarPromedio"]) ? $_POST["sumarPromedio"] : SI;
        $codigoAsignatura = "ASG".strtotime("now");

        try {
            mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_materias(
                mat_id, 
                mat_codigo, 
                mat_nombre, 
                mat_siglas, 
                mat_area, 
                mat_oficial, 
                mat_valor, 
                mat_sumar_promedio,
                institucion, 
                year
            )
            VALUES (
                '".$codigo."', 
                '".$codigoAsignatura."', 
                '".$POST["nombreM"]."', 
                '".strtoupper($POST["siglasM"])."', 
                '".$POST["areaM"]."', 
                1, 
                '".$POST["porcenAsigna"]."', 
                '".$_POST["sumarPromedio"]."', 
                {$config['conf_id_institucion']}, 
                {$_SESSION["bd"]}
            )");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }

        return $codigo;
    }

    /**
     * Este metodo me actualiza una asignatura
     * @param mysqli    $conexion
     * @param array     $config
     * @param array     $POST
    **/
    public static function actualizarAsignatura (
        mysqli $conexion,
        array  $config, 
        array  $POST
    )
    {
        if(empty($POST["porcenAsigna"])) {$POST["porcenAsigna"] = '';}
        $_POST["sumarPromedio"] = !empty($_POST["sumarPromedio"]) ? $_POST["sumarPromedio"] : SI;

        try {
            mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_materias SET mat_codigo='".$POST["codigoM"]."', mat_nombre='".$POST["nombreM"]."', mat_siglas='".$POST["siglasM"]."', mat_area='".$POST["areaM"]."', mat_oficial=1, mat_valor='".$POST["porcenAsigna"]."', mat_sumar_promedio='".$_POST["sumarPromedio"]."' WHERE mat_id='".$POST["idM"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
    }

    /**
     * Este metodo me elimina una asignatura
     * @param mysqli    $conexion
     * @param array     $config
     * @param string    $idMateria
    **/
    public static function eliminarAsignatura (
        mysqli $conexion,
        array  $config, 
        string $idMateria
    )
    {
        try {
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_materias WHERE mat_id='".$idMateria."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
    }
}