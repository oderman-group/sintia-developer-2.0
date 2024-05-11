<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");

class Asignaciones {

    /**
    * Este metodo me trae todas las asignaciones de una evaluación
    * @param mysqli $conexion
    * @param array $config
    * @param array $idEvaluacion
    * @param string $filtro
    * @param string $filtroLimite
    * 
    * @return mysqli_result $consulta
   **/
    public static function consultarAsignacionesEvaluacion (
        mysqli  $conexion, 
        array   $config, 
        int     $idEvaluacion, 
        string  $filtro         = "", 
        string  $filtroLimite   = ""
    )
    {
        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ADMIN.".general_limite_asignacion 
            WHERE gal_id_evaluacion='".$idEvaluacion."' ".$filtro." AND gal_institucion = {$config['conf_id_institucion']} AND gal_year = {$_SESSION["bd"]}
            ".$filtroLimite."");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }

        return $consulta;
    }

    /**
     * Este metodo me trae la informacion de una asignación
     * @param mysqli $conexion
     * @param array $config
     * @param string $idAsignacion
     * 
     * @return array $resultado
    **/
    public static function traerDatosAsignacion (
        mysqli $conexion, 
        array $config,
        string $idAsignacion
    )
    {
        $resultado = [];
        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ADMIN.".general_limite_asignacion 
            WHERE gal_id='{$idAsignacion}' AND gal_institucion = {$config['conf_id_institucion']} AND gal_year = {$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;
    }

    /**
    * Este metodo me trae todas las asignaciones de una evaluación
    * @param mysqli $conexion
    * @param array $config
    * @param array $idAsignacion
    * @param string $filtro
    * @param string $filtroLimite
    * 
    * @return mysqli_result $consulta
   **/
    public static function listarAsignaciones (
        mysqli  $conexion, 
        array   $config, 
        int     $idAsignacion, 
        string  $filtro         = "", 
        string  $filtroLimite   = ""
    )
    {
        try {
            $consulta = mysqli_query($conexion, "SELECT epag_id, uss_id, uss_nombre, uss_nombre2, uss_apellido1, uss_apellido2, epag_id_evaluado, epag_estado, epag_tipo FROM ".BD_ADMIN.".general_evaluacion_asignar 
            LEFT JOIN ".BD_GENERAL.".usuarios ON uss_id=epag_id_evaluador AND institucion = epag_institucion AND year = epag_year
            WHERE epag_id_limite='".$idAsignacion."' ".$filtro." AND epag_institucion = {$config['conf_id_institucion']} AND epag_year = {$_SESSION["bd"]}
            ".$filtroLimite."");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }

        return $consulta;
    }

    /**
     * Este metodo me guarda una Asignación
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
     * 
     * @return string $codigo
    **/
    public static function guardarAsignaciones (
        mysqli $conexion, 
        array $config, 
        array $POST
    ) {

        foreach ($POST['evaluado'] as $idEvaluado){

            try {
                mysqli_query($conexion, "INSERT INTO ".BD_ADMIN.".general_limite_asignacion (gal_limite_evaluadores, gal_id_evaluacion, gal_id_evaluado, gal_tipo, gal_institucion, gal_year)VALUES('".$POST["limiteEvaluadores"]."', '".$POST["idE"]."', '".$idEvaluado."', '".$POST["tipoEncuesta"]."', {$config['conf_id_institucion']}, {$_SESSION["bd"]});");
            } catch (Exception $e) {
                include("../compartido/error-catch-to-report.php");
            }
            $idLimite = mysqli_insert_id($conexion);
        
            if($POST['evaluador'] == ACUDIENTE || $POST['evaluador'] == ESTUDIANTE || $POST['evaluador'] == DIRECTIVO || $POST['evaluador'] == DOCENTE){

                switch ($POST['evaluador']){
                    case DOCENTE:
                        $tipoUsuario = 2;
                    break;

                    case ACUDIENTE:
                        $tipoUsuario = 3;
                    break;

                    case ESTUDIANTE:
                        $tipoUsuario = 4;
                    break;

                    case DIRECTIVO:
                        $tipoUsuario = 5;
                    break;
                }

                $consulta = mysqli_query($conexion, "SELECT uss_id FROM ".BD_GENERAL.".usuarios 
                WHERE uss_tipo='".$tipoUsuario."' AND (uss_usuario!='' OR uss_usuario IS NOT NULL) AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}
                ORDER BY RAND() 
                LIMIT {$POST["limiteEvaluadores"]}");
                while ($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
                    try {
                        mysqli_query($conexion, "INSERT INTO ".BD_ADMIN.".general_evaluacion_asignar (epag_id_evaluacion, epag_id_evaluado, epag_id_evaluador, epag_tipo, epag_id_limite, epag_institucion, epag_year)VALUES('".$POST["idE"]."', '".$idEvaluado."', '".$resultado["uss_id"]."', '".$POST["tipoEncuesta"]."', '".$idLimite."', {$config['conf_id_institucion']}, {$_SESSION["bd"]});");
                    } catch (Exception $e) {
                        include("../compartido/error-catch-to-report.php");
                    }
                }
            }
        
            if($POST['evaluador'] == CURSO){
                foreach ($POST['evaluadorCursos'] as $idCurso){
                    try {
                        mysqli_query($conexion, "UPDATE ".BD_ADMIN.".general_limite_asignacion SET gal_id_curso='".$idCurso."' WHERE gal_id='".$idLimite."'");
                    } catch (Exception $e) {
                        include("../compartido/error-catch-to-report.php");
                    }

                    $consulta = mysqli_query($conexion, "SELECT mat_id_usuario FROM ".BD_ACADEMICA.".academico_matriculas 
                    WHERE mat_grado = '".$idCurso."' AND (mat_id_usuario!='' OR mat_id_usuario IS NOT NULL) AND mat_eliminado=0 AND mat_estado_matricula='".MATRICULADO."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}
                    ORDER BY RAND() 
                    LIMIT {$POST["limiteEvaluadores"]}");
                    while ($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
                        try {
                            mysqli_query($conexion, "INSERT INTO ".BD_ADMIN.".general_evaluacion_asignar (epag_id_evaluacion, epag_id_evaluado, epag_id_evaluador, epag_tipo, epag_id_limite, epag_institucion, epag_year)VALUES('".$POST["idE"]."', '".$idEvaluado."', '".$resultado["mat_id_usuario"]."', '".$POST["tipoEncuesta"]."', '".$idLimite."', {$config['conf_id_institucion']}, {$_SESSION["bd"]});");
                        } catch (Exception $e) {
                            include("../compartido/error-catch-to-report.php");
                        }
                    }
                }
            }
        }
    }

    /**
     * Este metodo me trae la informacion de una asignación
     * @param mysqli $conexion
     * @param array $config
     * @param string $idAsignacion
     * 
     * @return array $resultado
    **/
    public static function traerDatosAsignaciones (
        mysqli $conexion, 
        array $config,
        string $idAsignacion
    )
    {
        $resultado = [];
        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ADMIN.".general_evaluacion_asignar 
            INNER JOIN ".BD_ADMIN.".general_limite_asignacion ON gal_id=epag_id_limite
            WHERE epag_id='{$idAsignacion}' AND epag_institucion = {$config['conf_id_institucion']} AND epag_year = {$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo me actualiza una asignación
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
    **/
    public static function actualizarAsignaciones (
        mysqli $conexion, 
        array $config, 
        array $POST
    )
    {
        $POST["evaluadorCursos"] = !empty($POST["evaluadorCursos"]) ? $POST["evaluadorCursos"] : "";

        try {
            mysqli_query($conexion, "UPDATE ".BD_ADMIN.".general_limite_asignacion SET gal_limite_evaluadores='".$POST["limiteEvaluadores"]."', gal_id_evaluado='".$POST["evaluado"]."', gal_tipo_evaluador='".$POST["evaluador"]."', gal_tipo='".$POST["tipoEncuesta"]."', gal_id_curso='".$POST["evaluadorCursos"]."' WHERE gal_id='".$POST["id"]."' AND gal_institucion={$config['conf_id_institucion']} AND gal_year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }

        if($POST["tipoEncuesta"] != $POST["tipoEncuestaAnterior"] || $POST["evaluado"] != $POST["evaluadoAnterior"]) {
            try {
                mysqli_query($conexion, "UPDATE ".BD_ADMIN.".general_evaluacion_asignar SET epag_tipo='".$POST["tipoEncuesta"]."', epag_id_evaluado='".$POST["evaluado"]."' WHERE epag_id_limite='".$POST["id"]."' AND epag_institucion={$config['conf_id_institucion']} AND epag_year={$_SESSION["bd"]}");
            } catch (Exception $e) {
                include("../compartido/error-catch-to-report.php");
            }
        }

        if($POST["evaluador"] != $POST["evaluadorAnterior"] || (!empty($POST["evaluadorCursos"]) && $POST["evaluadorCursos"] != $POST["evaluadorCursosAnterior"]) || $POST["limiteEvaluadores"] != $POST["limiteEvaluadoresAnterior"]) {
            try {
                mysqli_query($conexion, "DELETE FROM ".BD_ADMIN.".general_evaluacion_asignar WHERE epag_id_limite='{$POST["id"]}' AND epag_institucion={$config['conf_id_institucion']} AND epag_year={$_SESSION["bd"]}");
            } catch (Exception $e) {
                include("../compartido/error-catch-to-report.php");
            }
        
            if($POST['evaluador'] == ACUDIENTE || $POST['evaluador'] == ESTUDIANTE || $POST['evaluador'] == DIRECTIVO || $POST['evaluador'] == DOCENTE){

                switch ($POST['evaluador']){
                    case DOCENTE:
                        $tipoUsuario = 2;
                    break;

                    case ACUDIENTE:
                        $tipoUsuario = 3;
                    break;

                    case ESTUDIANTE:
                        $tipoUsuario = 4;
                    break;

                    case DIRECTIVO:
                        $tipoUsuario = 5;
                    break;
                }

                $consulta = mysqli_query($conexion, "SELECT uss_id FROM ".BD_GENERAL.".usuarios 
                WHERE uss_tipo='".$tipoUsuario."' AND (uss_usuario!='' OR uss_usuario IS NOT NULL) AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}
                ORDER BY RAND() 
                LIMIT {$POST["limiteEvaluadores"]}");
                while ($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
                    try {
                        mysqli_query($conexion, "INSERT INTO ".BD_ADMIN.".general_evaluacion_asignar (epag_id_evaluacion, epag_id_evaluado, epag_id_evaluador, epag_tipo, epag_id_limite, epag_institucion, epag_year)VALUES('".$POST["idE"]."', '".$POST["evaluado"]."', '".$resultado["uss_id"]."', '".$POST["tipoEncuesta"]."', '{$POST["id"]}', {$config['conf_id_institucion']}, {$_SESSION["bd"]});");
                    } catch (Exception $e) {
                        include("../compartido/error-catch-to-report.php");
                    }
                }
            }
        
            if($POST['evaluador'] == CURSO){
                try {
                    mysqli_query($conexion, "UPDATE ".BD_ADMIN.".general_limite_asignacion SET gal_id_curso='".$POST["evaluadorCursos"]."' WHERE gal_id='{$POST["id"]}'");
                } catch (Exception $e) {
                    include("../compartido/error-catch-to-report.php");
                }

                $consulta = mysqli_query($conexion, "SELECT mat_id_usuario FROM ".BD_ACADEMICA.".academico_matriculas 
                WHERE mat_grado = '".$POST["evaluadorCursos"]."' AND (mat_id_usuario!='' OR mat_id_usuario IS NOT NULL) AND mat_eliminado=0 AND mat_estado_matricula='".MATRICULADO."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}
                ORDER BY RAND() 
                LIMIT {$POST["limiteEvaluadores"]}");
                while ($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
                    try {
                        mysqli_query($conexion, "INSERT INTO ".BD_ADMIN.".general_evaluacion_asignar (epag_id_evaluacion, epag_id_evaluado, epag_id_evaluador, epag_tipo, epag_id_limite, epag_institucion, epag_year)VALUES('".$POST["idE"]."', '".$POST["evaluado"]."', '".$resultado["mat_id_usuario"]."', '".$POST["tipoEncuesta"]."', '{$POST["id"]}', {$config['conf_id_institucion']}, {$_SESSION["bd"]});");
                    } catch (Exception $e) {
                        include("../compartido/error-catch-to-report.php");
                    }
                }
            }
        }

    }

    /**
     * Este metodo me elimina una asignación
     * @param mysqli $conexion
     * @param array $config
     * @param string $idAsignacion
    **/
    public static function eliminarAsignaciones (
        mysqli $conexion, 
        array $config, 
        string $idAsignacion
    )
    {

        try {
            mysqli_query($conexion, "DELETE FROM ".BD_ADMIN.".general_evaluacion_asignar WHERE epag_id_limite='{$idAsignacion}' AND epag_institucion={$config['conf_id_institucion']} AND epag_year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }

        try {
            mysqli_query($conexion, "DELETE FROM ".BD_ADMIN.".general_limite_asignacion WHERE gal_id='{$idAsignacion}' AND gal_institucion={$config['conf_id_institucion']} AND gal_year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
    }

    /**
     * Este metodo me elimina una asignación
     * @param mysqli $conexion
     * @param array $config
     * @param string $idAsignacion
    **/
    public static function eliminarAsignacionesAsignados (
        mysqli $conexion, 
        array $config, 
        string $idAsignacion
    )
    {

        try {
            mysqli_query($conexion, "DELETE FROM ".BD_ADMIN.".general_evaluacion_asignar WHERE epag_id='{$idAsignacion}' AND epag_institucion={$config['conf_id_institucion']} AND epag_year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
    }

    /**
     * Consultar asignaciones de un usuario
     * @param mysqli $conexion
     * @param array $config
     * @param string $idUsuario
     * 
     * @return mysqli_result $consulta
    **/
    public static function traerAsignacionesUsuario (
        mysqli $conexion, 
        array $config,
        string $idUsuario
    )
    {
        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ADMIN.".general_evaluacion_asignar  AS asignar
            INNER JOIN ".BD_ADMIN.".general_limite_asignacion ON gal_id=asignar.epag_id_limite
            INNER JOIN ".BD_ADMIN.".general_evaluaciones ON evag_id=asignar.epag_id_evaluacion AND evag_institucion = {$config['conf_id_institucion']} AND evag_year = {$_SESSION["bd"]} 
            WHERE asignar.epag_id_evaluador='{$idUsuario}' AND asignar.epag_estado IN ('".PENDIENTE."', '".PROCESO."') AND asignar.epag_institucion = {$config['conf_id_institucion']} AND asignar.epag_year = {$_SESSION["bd"]} AND (
                    (
                        asignar.epag_id_limite IN (
                            SELECT epag_id_limite FROM ".BD_ADMIN.".general_evaluacion_asignar
                            WHERE epag_estado != '".PENDIENTE."' AND epag_id_evaluador!='{$_SESSION["id"]}' AND epag_institucion = {$config['conf_id_institucion']} AND epag_year = {$_SESSION["bd"]}
                            GROUP BY epag_id_limite
                            HAVING COUNT(*) < gal_limite_evaluadores
                        ) 
                    ) OR (
                        asignar.epag_id_limite NOT IN (
                            SELECT epag_id_limite FROM ".BD_ADMIN.".general_evaluacion_asignar
                            WHERE epag_estado != '".PENDIENTE."' AND epag_id_evaluador!='{$_SESSION["id"]}' AND epag_institucion = {$config['conf_id_institucion']} AND epag_year = {$_SESSION["bd"]}
                            GROUP BY epag_id_limite
                        )
                    ) OR (gal_limite_evaluadores = 0)
            )");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }

        return $consulta;
    }

    /**
     * Actualiza la estado de una asignación
     *
     * @param mysqli $conexion
     * @param array $config
     * @param string $idAsignacion
     * @param string $estado
     */
    public static function actualizarEstadoAsignacion(
        mysqli $conexion, 
        array $config, 
        string $idAsignacion, 
        string $estado
    ) {
        try {
            mysqli_query($conexion, "UPDATE ".BD_ADMIN.".general_evaluacion_asignar SET epag_estado='".$estado."' WHERE epag_id='".$idAsignacion."' AND epag_institucion={$config['conf_id_institucion']} AND epag_year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
    }

    /**
    * Este metodo me trae todas las asignaciones finalizadas de una evaluación con sus resultados
    * @param mysqli $conexion
    * @param array $config
    * @param array $idEvaluacion
    * @param string $filtro
    * @param string $filtroLimite
    * 
    * @return mysqli_result $consulta
   **/
    public static function resultadoEncuestasFinalizadas (
        mysqli  $conexion, 
        array   $config, 
        int     $idEvaluacion, 
        string  $filtro         = "", 
        string  $filtroLimite   = ""
    )
    {
        try {
            $consulta = mysqli_query($conexion, "SELECT epag_id, epag_id_evaluado, epag_tipo, uss_id, uss_nombre, uss_nombre2, uss_apellido1, uss_apellido2, SUM(respu.resg_valor) AS puntos, ROUND(AVG(respu.resg_valor), 2) AS promedio FROM ".BD_ADMIN.".general_evaluacion_asignar 
            INNER JOIN ".BD_ADMIN.".general_resultados resul ON resul.resg_id_asignacion=epag_id AND resul.resg_id_usuario=epag_id_evaluador AND resul.resg_institucion = epag_institucion AND resul.resg_year = epag_year
            INNER JOIN ".BD_ADMIN.".general_respuestas respu ON respu.resg_id=resul.resg_respuesta AND respu.resg_institucion = epag_institucion AND respu.resg_year = epag_year
            LEFT JOIN ".BD_GENERAL.".usuarios ON uss_id=epag_id_evaluador AND institucion = epag_institucion AND year = epag_year
            WHERE epag_id_evaluacion='".$idEvaluacion."' AND epag_estado='".FINALIZADO."' ".$filtro." AND epag_institucion = {$config['conf_id_institucion']} AND epag_year = {$_SESSION["bd"]}
            GROUP BY epag_id
            ORDER BY puntos DESC
            ".$filtroLimite."");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }

        return $consulta;
    }

    /**
     * Este metodo me consulta si una asignacion ya fue empezada por algun usuario
     * @param mysqli $conexion
     * @param array $config
     * @param int $idLimite
     * 
     * @return array $num
    **/
    public static function consultarCantAsignacionesEmpezadas (
        mysqli $conexion, 
        array $config,
        int $idLimite
    )
    {
        try {
            $consulta = mysqli_query($conexion, "SELECT epag_estado FROM ".BD_ADMIN.".general_evaluacion_asignar WHERE epag_estado!='".PENDIENTE."' AND epag_id_limite='{$idLimite}' AND epag_id_evaluador!='{$_SESSION["id"]}' AND epag_institucion = {$config['conf_id_institucion']} AND epag_year = {$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
        $num = mysqli_num_rows($consulta);

        return $num;
    }
}