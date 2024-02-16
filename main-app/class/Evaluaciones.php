<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
class Evaluaciones{
    /**
     * Este metodo me trae las preguntas de una evaluación
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEvaluacion
     * 
     * @return mysqli_result $consulta
     */
    public static function preguntasEvaluacion(mysqli $conexion, array $config, string $idEvaluacion){
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_evaluacion_preguntas aca_eva_pre
            INNER JOIN ".BD_ACADEMICA.".academico_actividad_preguntas preg ON preg.preg_id=aca_eva_pre.evp_id_pregunta AND preg.institucion={$config['conf_id_institucion']} AND preg.year={$_SESSION["bd"]}
            WHERE evp_id_evaluacion='".$idEvaluacion."' AND aca_eva_pre.institucion={$config['conf_id_institucion']} AND aca_eva_pre.year={$_SESSION["bd"]}
            ");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $consulta;
    }
    
    /**
     * Este metodo me trae la cantidad de preguntas de una evaluación
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEvaluacion
     * 
     * @return int $numPreguntas
     */
    public static function numeroPreguntasEvaluacion(mysqli $conexion, array $config, string $idEvaluacion){
        $numPreguntas=0;
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_evaluacion_preguntas aca_eva_pre
            INNER JOIN ".BD_ACADEMICA.".academico_actividad_preguntas preg ON preg.preg_id=aca_eva_pre.evp_id_pregunta AND preg.institucion={$config['conf_id_institucion']} AND preg.year={$_SESSION["bd"]}
            WHERE evp_id_evaluacion='".$idEvaluacion."' AND aca_eva_pre.institucion={$config['conf_id_institucion']} AND aca_eva_pre.year={$_SESSION["bd"]}
            ");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        $numPreguntas = mysqli_num_rows($consulta);

        return $numPreguntas;
    }
    
    /**
     * Este metodo me guarda la relación entre una pregunta y una evaluación
     * @param mysqli $conexion
     * @param array $config
     * @param string $idPregunta
     * @param array $POST
     */
    public static function guardarRelacionPreguntaEvaluacion(mysqli $conexion, array $config, string $idPregunta, array $POST){
        $codigoEVP=Utilidades::generateCode("EVP");
        try{
            mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_actividad_evaluacion_preguntas(evp_id, evp_id_evaluacion, evp_id_pregunta, institucion, year)VALUES('".$codigoEVP."', '".$POST["idE"]."','".$idPregunta."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }
    
    /**
     * Este metodo elimina toda las preguntas de una evaluación
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEvaluacion
     */
    public static function eliminarPreguntasEvaluacion(mysqli $conexion, array $config, string $idEvaluacion){
        try{
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_evaluacion_preguntas WHERE evp_id_evaluacion='".$idEvaluacion."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }
    
    /**
     * Este metodo me elimina una pregunta en una evaluación
     * @param mysqli $conexion
     * @param array $config
     * @param array $GET
     */
    public static function eliminarUnaPreguntaEvaluacion(mysqli $conexion, array $config, array $GET){
        try{
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_evaluacion_preguntas 
            WHERE evp_id_evaluacion='".base64_decode($GET["idE"])."' AND evp_id_pregunta='".base64_decode($GET["idP"])."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }

    /**
     * Este metodo me la cantidad de horas disponibles de una evaluación
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEvaluacion
     * 
     * @return int $horas
     */
    public static function horasEvaluacion(mysqli $conexion, array $config, string $idEvaluacion){
        $horas=0;
        try{
            $consulta=mysqli_query($conexion, "SELECT TIMESTAMPDIFF(HOUR, NOW(), eva_hasta) FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones 
            WHERE eva_id='".$idEvaluacion."' AND eva_estado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        $horas = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $horas;
    }

    /**
     * Este metodo me la cantidad de minutos disponibles de una evaluación
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEvaluacion
     * 
     * @return int $minutos
     */
    public static function minutosEvaluacion(mysqli $conexion, array $config, string $idEvaluacion){
        $minutos=0;
        try{
            $consulta=mysqli_query($conexion, "SELECT TIMESTAMPDIFF(SECOND, NOW(), eva_hasta),60) FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones 
            WHERE eva_id='".$idEvaluacion."' AND eva_estado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        $minutos = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $minutos;
    }

    /**
     * Este metodo me la cantidad de segundos disponibles de una evaluación
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEvaluacion
     * 
     * @return int $segundos
     */
    public static function segundosEvaluacion(mysqli $conexion, array $config, string $idEvaluacion){
        $segundos=0;
        try{
            $consulta=mysqli_query($conexion, "SELECT TIMESTAMPDIFF(HOUR, NOW(), eva_hasta) FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones 
            WHERE eva_id='".$idEvaluacion."' AND eva_estado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        $segundos = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $segundos;
    }

    /**
     * Este metodo me la fecha de una evaluación
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEvaluacion
     * 
     * @return array $fecha
     */
    public static function fechaEvaluacion(mysqli $conexion, array $config, string $idEvaluacion){
        $fecha=0;
        try{
            $consulta=mysqli_query($conexion, "SELECT DATEDIFF(eva_desde, now()), DATEDIFF(eva_hasta, now()), TIMESTAMPDIFF(SECOND, NOW(), eva_desde), TIMESTAMPDIFF(SECOND, NOW(), eva_hasta) FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones 
            WHERE eva_id='".$idEvaluacion."' AND eva_estado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        $fecha = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $fecha;
    }

    /**
     * Este metodo me trae los datos de una evaluación
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEvaluacion
     * 
     * @return array $resultado
     */
    public static function consultaEvaluacion(mysqli $conexion, array $config, string $idEvaluacion){
        $resultado=[];
        try{
            $consulta=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones 
            WHERE eva_id='".$idEvaluacion."' AND eva_estado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo me consulta las evaluación de una carga exceptando la actual
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEvaluacion
     * @param string $idCarga
     * @param int $periodo
     * 
     * @return mysqli_result $consulta
     */
    public static function consultaEvaluacionTodas(mysqli $conexion, array $config, string $idEvaluacion, string $idCarga, int $periodo){
        try{
            $consulta=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones
            WHERE eva_id_carga='".$idCarga."' AND eva_periodo='".$periodo."' AND eva_id!='".$idEvaluacion."' AND eva_estado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}
            ORDER BY eva_id DESC");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $consulta;
    }

    /**
     * Este metodo me consulta las evaluación de una carga
     * @param mysqli $conexion
     * @param array $config
     * @param string $idCarga
     * 
     * @return mysqli_result $consulta
     */
    public static function consultaEvaluacionCargas(mysqli $conexion, array $config, string $idCarga){
        try{
            $consulta=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones 
            WHERE eva_id_carga='".$idCarga."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $consulta;
    }

    /**
     * Este metodo me consulta las evaluación de una carga en un periodo
     * @param mysqli $conexion
     * @param array $config
     * @param string $idCarga
     * @param int $periodo
     * 
     * @return mysqli_result $consulta
     */
    public static function consultaEvaluacionCargasPeriodos(mysqli $conexion, array $config, string $idCarga, string $periodo){
        try{
            $consulta=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones 
            WHERE eva_id_carga='".$idCarga."' AND eva_periodo='".$periodo."' AND eva_estado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}
            ORDER BY eva_id DESC");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $consulta;
    }

    /**
     * Este metodo me guarda una evaluación
     * @param mysqli $conexion
     * @param array $config
     * @param string $idCarga
     * @param int $periodo
     * @param array $POST
     * 
     * @return string $consulta
     */
    public static function guardarEvaluacion(mysqli $conexion, array $config, string $idCarga, string $periodo, array $POST){
        $codigo=Utilidades::generateCode("EVA");
        try{
            mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_actividad_evaluaciones(eva_id, eva_nombre, eva_descripcion, eva_id_carga, eva_periodo, eva_estado, eva_desde, eva_hasta, eva_clave, institucion, year)"." VALUES('".$codigo."', '".mysqli_real_escape_string($conexion,$POST["titulo"])."', '".mysqli_real_escape_string($conexion,$POST["contenido"])."', '".$idCarga."', '".$periodo."', 1, '".$POST["desde"]."', '".$POST["hasta"]."', '".mysqli_real_escape_string($conexion,$POST["clave"])."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        return $codigo;
    }

    /**
     * Este metodo me actualiza una evaluación
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
     */
    public static function actualizarEvaluacion(mysqli $conexion, array $config, array $POST){
        try{
            mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_actividad_evaluaciones SET eva_nombre='".mysqli_real_escape_string($conexion,$POST["titulo"])."', eva_descripcion='".mysqli_real_escape_string($conexion,$POST["contenido"])."', eva_desde='".$POST["desde"]."', eva_hasta='".$POST["hasta"]."', eva_clave='".mysqli_real_escape_string($conexion,$POST["clave"])."' WHERE eva_id='".$POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }

    /**
     * Este metodo me elimina una evaluación
     * @param mysqli $conexion
     * @param array $config
     * @param string $idE
     */
    public static function eliminarEvaluacion(mysqli $conexion, array $config, string $idE){
        try{
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones WHERE eva_id='".$idE."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }

    /**
     * Este metodo me consulta los evaluados
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEvaluacion
     * 
     * @return array $resultado
     */
    public static function consultarEvaluados(mysqli $conexion, array $config, string $idEvaluacion){
        try{
            $consulta = mysqli_query($conexion, "SELECT
            (SELECT count(epe_id) FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones_estudiantes WHERE epe_id_evaluacion='".$idEvaluacion."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]} AND epe_fin IS NULL),
            (SELECT count(epe_id) FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones_estudiantes WHERE epe_id_evaluacion='".$idEvaluacion."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]} AND epe_inicio IS NOT NULL AND epe_fin IS NOT NULL)");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo me consulta si un estudiante ya tiene una sessio abierta
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEvaluacion
     * @param string $idEstudiante
     * 
     * @return int $numDatos
     */
    public static function consultarSessionEstudianteEvaluacion(mysqli $conexion, array $config, string $idEvaluacion, string $idEstudiante){
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones_estudiantes 
            WHERE epe_id_evaluacion='".$idEvaluacion."' AND epe_id_estudiante='".$idEstudiante."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]} AND epe_inicio IS NOT NULL AND epe_fin IS NULL");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        $numDatos = mysqli_num_rows($consulta);

        return $numDatos;
    }

    /**
     * Este metodo me elimina el intento de un estudiante
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEvaluacion
     * @param string $idEstudiante
     */
    public static function eliminarIntentos(mysqli $conexion, array $config, string $idEvaluacion, string $idEstudiante){
        try{
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones_estudiantes 
            WHERE epe_id_evaluacion='".$idEvaluacion."' AND epe_id_estudiante='".$idEstudiante."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }

    /**
     * Este metodo me elimina la relacion entre los estudiantes y una evaluación
     * @param mysqli $conexion
     * @param array $config
     * @param string $idE
     */
    public static function eliminarEstudiantesEvaluacion(mysqli $conexion, array $config, string $idE){
        try{
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones_estudiantes WHERE epe_id_evaluacion='".$idE."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }

    /**
     * Este metodo me consulta el tiempo que demoro el estudiante en realizar la evaluacón
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEvaluacion
     * @param string $idEstudiante
     * 
     * @return array $resultado
     */
    public static function consultarTiempoEvaluacion(mysqli $conexion, array $config, string $idEvaluacion, string $idEstudiante){
        try{
            $consulta = mysqli_query($conexion, "SELECT epe_inicio, epe_fin, MOD(TIMESTAMPDIFF(MINUTE, epe_inicio, epe_fin),60), MOD(TIMESTAMPDIFF(SECOND, epe_inicio, epe_fin),60) FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones_estudiantes 
            WHERE epe_id_estudiante='".$idEstudiante."' AND epe_id_evaluacion='".$idEvaluacion."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo me actualiza el estado de un estudiante al terminar la evaluación
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEvaluacion
     * @param string $idEstudiante
     */
    public static function terminarEvaluacion(mysqli $conexion, array $config, string $idEvaluacion, string $idEstudiante){
        try{
            mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_actividad_evaluaciones_estudiantes SET epe_fin=now() 
            WHERE epe_id_estudiante='".$idEstudiante."' AND epe_id_evaluacion='".$idEvaluacion."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }

    /**
     * Este metodo me guarda el intento de un estudiante
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEvaluacion
     * @param string $idEstudiante
     */
    public static function guardarIntento(mysqli $conexion, array $config, string $idEvaluacion, string $idEstudiante){
        $codigo=Utilidades::generateCode("EPE");
        try{
            mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_actividad_evaluaciones_estudiantes(epe_id, epe_id_estudiante, epe_id_evaluacion, epe_inicio, institucion, year)VALUES('".$codigo."', '".$idEstudiante."', '".$idEvaluacion."', now(), {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }

    /**
     * Este metodo me trae los datos de una evaluación terminada
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEvaluacion
     * @param string $idEstudiante
     * 
     * @return array $resultado
     */
    public static function traerDatosEvaluacionTerminada(mysqli $conexion, array $config, string $idEvaluacion, string $idEstudiante){
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones_estudiantes
            WHERE epe_id_evaluacion='".$idEvaluacion."' AND epe_id_estudiante='".$idEstudiante."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]} AND epe_inicio IS NOT NULL AND epe_fin IS NOT NULL");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;
    }
}