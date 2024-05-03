<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
require_once(ROOT_PATH."/main-app/class/BindSQL.php");
require_once(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
class Evaluaciones extends BindSQL{
    /**
     * Este metodo me trae las preguntas de una evaluación
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEvaluacion
     * 
     * @return mysqli_result $consulta
     */
    public static function preguntasEvaluacion(mysqli $conexion, array $config, string $idEvaluacion)
    {
        $sql = "SELECT * FROM " . BD_ACADEMICA . ".academico_actividad_evaluacion_preguntas aca_eva_pre
            INNER JOIN " . BD_ACADEMICA . ".academico_actividad_preguntas preg ON preg.preg_id=aca_eva_pre.evp_id_pregunta AND preg.institucion=? AND preg.year=?
            WHERE evp_id_evaluacion=? AND aca_eva_pre.institucion=? AND aca_eva_pre.year=?";

        $parametros = [$config['conf_id_institucion'], $_SESSION["bd"], $idEvaluacion, $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);

        return $resultado;
    }
    
    /**
     * Este metodo me trae la cantidad de preguntas de una evaluación
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEvaluacion
     * 
     * @return int $numPreguntas
     */
    public static function numeroPreguntasEvaluacion(mysqli $conexion, array $config, string $idEvaluacion)
    {
        $sql = "SELECT * FROM " . BD_ACADEMICA . ".academico_actividad_evaluacion_preguntas aca_eva_pre
        INNER JOIN " . BD_ACADEMICA . ".academico_actividad_preguntas preg ON preg.preg_id=aca_eva_pre.evp_id_pregunta AND preg.institucion=? AND preg.year=?
        WHERE evp_id_evaluacion=? AND aca_eva_pre.institucion=? AND aca_eva_pre.year=?";

        $parametros = [$config['conf_id_institucion'], $_SESSION["bd"], $idEvaluacion, $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);

        // Obtener el número de filas del resultado
        $numPreguntas = mysqli_num_rows($resultado);

        return $numPreguntas;
    }
    
    /**
     * Este metodo me guarda la relación entre una pregunta y una evaluación
     * @param mysqli $conexion
     * @param array $config
     * @param string $idPregunta
     * @param array $POST
     */
    public static function guardarRelacionPreguntaEvaluacion(mysqli $conexion, PDO $conexionPDO, array $config, string $idPregunta, array $POST,$finalizarTransacion=true)
    {
        $codigoEVP = Utilidades::getNextIdSequence($conexionPDO, BD_ACADEMICA, 'academico_actividad_evaluacion_preguntas');

        $sql = "INSERT INTO " . BD_ACADEMICA . ".academico_actividad_evaluacion_preguntas (evp_id, evp_id_evaluacion, evp_id_pregunta, institucion, year) VALUES (?, ?, ?, ?, ?)";

        $parametros = [$codigoEVP, $POST["idE"], $idPregunta, $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros,$finalizarTransacion);
    }
    
    /**
     * Este metodo elimina todas las preguntas de una evaluación
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEvaluacion
     */
    public static function eliminarPreguntasEvaluacion(mysqli $conexion, array $config, string $idEvaluacion)
    {
        $sql = "DELETE FROM " . BD_ACADEMICA . ".academico_actividad_evaluacion_preguntas WHERE evp_id_evaluacion=? AND institucion=? AND year=?";

        $parametros = [$idEvaluacion, $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }
    
    /**
     * Este metodo me elimina una pregunta en una evaluación
     * @param mysqli $conexion
     * @param array $config
     * @param array $GET
     */
    public static function eliminarUnaPreguntaEvaluacion(mysqli $conexion, array $config, array $GET)
    {
        $sql = "DELETE FROM " . BD_ACADEMICA . ".academico_actividad_evaluacion_preguntas WHERE evp_id_evaluacion=? AND evp_id_pregunta=? AND institucion=? AND year=?";

        $parametros = [base64_decode($GET["idE"]), base64_decode($GET["idP"]), $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }

    /**
     * Este metodo me la cantidad de horas disponibles de una evaluación
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEvaluacion
     * 
     * @return int $horas
     */
    public static function horasEvaluacion(mysqli $conexion, array $config, string $idEvaluacion)
    {
        $sql = "SELECT TIMESTAMPDIFF(HOUR, NOW(), eva_hasta) FROM " . BD_ACADEMICA . ".academico_actividad_evaluaciones WHERE eva_id=? AND eva_estado=1 AND institucion=? AND year=?";

        $parametros = [$idEvaluacion, $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
        $horas = mysqli_fetch_array($resultado, MYSQLI_BOTH);

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
    public static function minutosEvaluacion(mysqli $conexion, array $config, string $idEvaluacion)
    {
        $sql = "SELECT TIMESTAMPDIFF(SECOND, NOW(), eva_hasta) / 60 FROM " . BD_ACADEMICA . ".academico_actividad_evaluaciones WHERE eva_id=? AND eva_estado=1 AND institucion=? AND year=?";

        $parametros = [$idEvaluacion, $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
        $minutos = mysqli_fetch_array($resultado, MYSQLI_BOTH);

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
    public static function segundosEvaluacion(mysqli $conexion, array $config, string $idEvaluacion)
    {
        $sql = "SELECT TIMESTAMPDIFF(SECOND, NOW(), eva_hasta) FROM " . BD_ACADEMICA . ".academico_actividad_evaluaciones WHERE eva_id=? AND eva_estado=1 AND institucion=? AND year=?";

        $parametros = [$idEvaluacion, $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
        $segundos = mysqli_fetch_array($resultado, MYSQLI_BOTH);

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
    public static function fechaEvaluacion(mysqli $conexion, array $config, string $idEvaluacion)
    {
        $sql = "SELECT DATEDIFF(eva_desde, now()), DATEDIFF(eva_hasta, now()), TIMESTAMPDIFF(SECOND, NOW(), eva_desde), TIMESTAMPDIFF(SECOND, NOW(), eva_hasta) FROM " . BD_ACADEMICA . ".academico_actividad_evaluaciones WHERE eva_id=? AND eva_estado=1 AND institucion=? AND year=?";

        $parametros = [$idEvaluacion, $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
        $fecha = mysqli_fetch_array($resultado, MYSQLI_BOTH);

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
    public static function consultaEvaluacion(mysqli $conexion, array $config, string $idEvaluacion)
    {
        $sql = "SELECT * FROM " . BD_ACADEMICA . ".academico_actividad_evaluaciones WHERE eva_id=? AND eva_estado=1 AND institucion=? AND year=?";

        $parametros = [$idEvaluacion, $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultadoC = BindSQL::prepararSQL($sql, $parametros);
        $resultado = mysqli_fetch_array($resultadoC, MYSQLI_BOTH);

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
    public static function consultaEvaluacionTodas(mysqli $conexion, array $config, string $idEvaluacion, string $idCarga, int $periodo)
    {
        $sql = "SELECT * FROM " . BD_ACADEMICA . ".academico_actividad_evaluaciones WHERE eva_id_carga=? AND eva_periodo=? AND eva_id!=? AND eva_estado=1 AND institucion=? AND year=? ORDER BY eva_id DESC";

        $parametros = [$idCarga, $periodo, $idEvaluacion, $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);

        return $resultado;
    }

    /**
     * Este metodo me consulta las evaluación de una carga
     * @param mysqli $conexion
     * @param array $config
     * @param string $idCarga
     * 
     * @return mysqli_result $consulta
     */
    public static function consultaEvaluacionCargas(mysqli $conexion, array $config, string $idCarga)
    {
        $sql = "SELECT * FROM " . BD_ACADEMICA . ".academico_actividad_evaluaciones WHERE eva_id_carga=? AND institucion=? AND year=?";

        $parametros = [$idCarga, $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);

        return $resultado;
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
    public static function consultaEvaluacionCargasPeriodos(mysqli $conexion, array $config, string $idCarga, string $periodo)
    {
        $sql = "SELECT * FROM " . BD_ACADEMICA . ".academico_actividad_evaluaciones WHERE eva_id_carga=? AND eva_periodo=? AND eva_estado=1 AND institucion=? AND year=? ORDER BY eva_id DESC";

        $parametros = [$idCarga, $periodo, $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);

        return $resultado;
    }

    /**
     * Este metodo me guarda una evaluación
     * @param mysqli $conexion
     * @param array $config
     * @param string $idCarga
     * @param int $periodo
     * @param array $POST
     * 
     * @return string $codigo
     */
    public static function guardarEvaluacion(mysqli $conexion, PDO $conexionPDO, array $config, string $idCarga, string $periodo, array $POST)
    {
        $codigo = Utilidades::getNextIdSequence($conexionPDO, BD_ACADEMICA, 'academico_actividad_evaluaciones');

        $sql = "INSERT INTO " . BD_ACADEMICA . ".academico_actividad_evaluaciones (eva_id, eva_nombre, eva_descripcion, eva_id_carga, eva_periodo, eva_estado, eva_desde, eva_hasta, eva_clave, institucion, year) VALUES (?, ?, ?, ?, ?, 1, ?, ?, ?, ?, ?)";

        $parametros = [$codigo, mysqli_real_escape_string($conexion,$POST["titulo"]), mysqli_real_escape_string($conexion,$POST["contenido"]), $idCarga, $periodo, $POST["desde"], $POST["hasta"], mysqli_real_escape_string($conexion,$POST["clave"]), $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);

        return $codigo;
    }

    /**
     * Este metodo me actualiza una evaluación
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
     */
    public static function actualizarEvaluacion(mysqli $conexion, array $config, array $POST)
    {
        $sql = "UPDATE " . BD_ACADEMICA . ".academico_actividad_evaluaciones SET eva_nombre=?, eva_descripcion=?, eva_desde=?, eva_hasta=?, eva_clave=? WHERE eva_id=? AND institucion=? AND year=?";

        $parametros = [mysqli_real_escape_string($conexion,$POST["titulo"]), mysqli_real_escape_string($conexion,$POST["contenido"]), $POST["desde"], $POST["hasta"], mysqli_real_escape_string($conexion,$POST["clave"]), $POST["idR"], $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }

    /**
     * Este metodo me elimina una evaluación
     * @param mysqli $conexion
     * @param array $config
     * @param string $idE
     */
    public static function eliminarEvaluacion(mysqli $conexion, array $config, string $idE)
    {
        $sql = "DELETE FROM " . BD_ACADEMICA . ".academico_actividad_evaluaciones WHERE eva_id=? AND institucion=? AND year=?";

        $parametros = [$idE, $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
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

    /**
     * Este metodo me trae las respuestas de una evaluación terminada
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEvaluacion
     * @param string $idEstudiante
     * 
     * @return array $resultado
     */
    public static function traerRespuestaEvaluacion(mysqli $conexion, array $config, string $idEvaluacion, string $idEstudiante){
        try{
            $consulta = mysqli_query($conexion, "SELECT
            (SELECT count(res.res_id) FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones_resultados res 
            INNER JOIN ".BD_ACADEMICA.".academico_actividad_respuestas resp ON resp.resp_id_pregunta=res.res_id_pregunta AND resp.resp_id=res.res_id_respuesta AND resp.resp_correcta=1 AND resp.institucion={$config['conf_id_institucion']} AND resp.year={$_SESSION["bd"]} 
            WHERE res.res_id_evaluacion='".$idEvaluacion."' AND res.res_id_estudiante='".$idEstudiante."' AND res.institucion={$config['conf_id_institucion']} AND res.year={$_SESSION["bd"]}),
            (SELECT count(res.res_id) FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones_resultados res 
            INNER JOIN ".BD_ACADEMICA.".academico_actividad_respuestas resp ON resp.resp_id_pregunta=res.res_id_pregunta AND resp.resp_id=res.res_id_respuesta AND resp.resp_correcta=0 AND resp.institucion={$config['conf_id_institucion']} AND resp.year={$_SESSION["bd"]}
            WHERE res.res_id_evaluacion='".$idEvaluacion."' AND res.res_id_estudiante='".$idEstudiante."' AND res.institucion={$config['conf_id_institucion']} AND res.year={$_SESSION["bd"]}),
            (SELECT count(res_id) FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones_resultados 
            WHERE res_id_evaluacion='".$idEvaluacion."' AND res_id_estudiante='".$idEstudiante."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]} AND res_id_respuesta=0)");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo me consulta si un estudiante ya hizo la evaluación
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEvaluacion
     * @param string $idEstudiante
     * 
     * @return int $numDatos
     */
    public static function verificarEstudianteEvaluacion(mysqli $conexion, array $config, string $idEvaluacion, string $idEstudiante){
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones_resultados 
            WHERE res_id_evaluacion='".$idEvaluacion."' AND res_id_estudiante='".$idEstudiante."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        $numDatos = mysqli_num_rows($consulta);

        return $numDatos;
    }

    /**
     * Este metodo me trae las respuestas por preguntas
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEvaluacion
     * @param string $idPregunta
     * 
     * @return array $resultado
     */
    public static function respuestasXPreguntas(mysqli $conexion, array $config, string $idEvaluacion, string $idPregunta){
        try{
            $consulta = mysqli_query($conexion, "SELECT
            (SELECT count(res_id) FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones_resultados res
            INNER JOIN ".BD_ACADEMICA.".academico_actividad_respuestas resp ON resp.resp_id_pregunta=res.res_id_pregunta AND resp.resp_id=res.res_id_respuesta AND resp.resp_correcta=1 AND resp.institucion={$config['conf_id_institucion']} AND resp.year={$_SESSION["bd"]}
            WHERE res.res_id_evaluacion='".$idEvaluacion."' AND res.res_id_pregunta='".$idPregunta."' AND res.institucion={$config['conf_id_institucion']} AND res.year={$_SESSION["bd"]}),
            (SELECT count(res_id) FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones_resultados res
            INNER JOIN ".BD_ACADEMICA.".academico_actividad_respuestas resp ON resp.resp_id_pregunta=res.res_id_pregunta AND resp.resp_id=res.res_id_respuesta AND resp.resp_correcta=0 AND resp.institucion={$config['conf_id_institucion']} AND resp.year={$_SESSION["bd"]}
            WHERE res.res_id_evaluacion='".$idEvaluacion."' AND res.res_id_pregunta='".$idPregunta."' AND res.institucion={$config['conf_id_institucion']} AND res.year={$_SESSION["bd"]})");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo me valida si es la misma respuesta
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEvaluacion
     * @param string $idEstudiante
     * @param string $idPregunta
     * @param string $idRespuesta
     * 
     * @return array $resultado
     */
    public static function compararRespuestas(mysqli $conexion, array $config, string $idEvaluacion, string $idEstudiante, string $idPregunta, string $idRespuesta){
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones_resultados
            WHERE res_id_evaluacion='".$idEvaluacion."' AND res_id_estudiante='".$idEstudiante."' AND res_id_pregunta='".$idPregunta."' AND res_id_respuesta='".$idRespuesta."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo me elimina todos los resultados
     * @param mysqli $conexion
     * @param array $config
     */
    public static function eliminarResultados(mysqli $conexion, array $config){
        try{
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones_resultados WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }

    /**
     * Este metodo me elimina los resultados de un estudiante
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEstudiante
     */
    public static function eliminarResultadosEstudiante(mysqli $conexion, array $config, string $idEstudiante){
        try{
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones_resultados WHERE res_id_estudiante='" . $idEstudiante . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }

    /**
     * Este metodo me elimina los intentos de un estudiante
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEvaluacion
     * @param string $idEstudiante
     */
    public static function eliminarIntentosEstudiante(mysqli $conexion, array $config, string $idEvaluacion, string $idEstudiante){
        try{
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones_resultados 
            WHERE res_id_evaluacion='".$idEvaluacion."' AND res_id_estudiante='".$idEstudiante."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }

    /**
     * Este metodo me elimina los resultados de una evaluacion
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEvaluacion
     */
    public static function eliminarResultadosEvaluacion(mysqli $conexion, array $config, string $idEvaluacion){
        try{
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones_resultados WHERE res_id_evaluacion='" . $idEvaluacion . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }

    /**
     * Este metodo me trae los resultados de una evaluacion
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEvaluacion
     * @param string $idEstudiante
     * 
     * @return mysqli_result $consulta
     */
    public static function traerResultadoEvaluacion(mysqli $conexion, array $config, string $idEvaluacion){
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones_resultados WHERE res_id_evaluacion='".$idEvaluacion."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $consulta;
    }

    /**
     * Este metodo me guarda el resultado de un estudiante
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEvaluacion
     * @param string $idEstudiante
     * @param string $idPregunta
     * @param string $idRespuesta
     * @param string $archivo
     */
    public static function guardarResultado(mysqli $conexion, array $config, string $idEvaluacion, string $idEstudiante, string $idPregunta, string $idRespuesta, string $archivo){
        $codigo=Utilidades::generateCode("RES");
        try{
            mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_actividad_evaluaciones_resultados(res_id, res_id_pregunta, res_id_respuesta, res_id_estudiante, res_id_evaluacion, res_archivo, institucion, year)
            VALUES('".$codigo."', '".$idPregunta."', '".$idRespuesta."', '".$idEstudiante."', '".$idEvaluacion."', '".$archivo."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }

    /**
     * Este metodo me trae el conteo de las preguntas de una evaluacion
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEvaluacion
     * @param string $idEstudiante
     * 
     * @return array $resultado
     */
    public static function traerConteoPreguntas(mysqli $conexion, array $config, string $idEvaluacion, string $idEstudiante){
        try{
            $consulta=mysqli_query($conexion, "SELECT
            (SELECT sum(preg_valor) FROM ".BD_ACADEMICA.".academico_actividad_preguntas preg
            INNER JOIN ".BD_ACADEMICA.".academico_actividad_evaluacion_preguntas aca_eva_pre ON aca_eva_pre.evp_id_pregunta=preg.preg_id AND aca_eva_pre.evp_id_evaluacion='".$idEvaluacion."' AND aca_eva_pre.institucion={$config['conf_id_institucion']} AND aca_eva_pre.year={$_SESSION["bd"]}
            WHERE preg.institucion={$config['conf_id_institucion']} AND preg.year={$_SESSION["bd"]}),

            (SELECT sum(preg_valor) FROM ".BD_ACADEMICA.".academico_actividad_preguntas preg
            INNER JOIN ".BD_ACADEMICA.".academico_actividad_evaluaciones_resultados res ON res.res_id_pregunta=preg.preg_id AND res.res_id_evaluacion='".$idEvaluacion."' AND res.res_id_estudiante='".$idEstudiante."' AND res.institucion={$config['conf_id_institucion']} AND res.year={$_SESSION["bd"]}
            INNER JOIN ".BD_ACADEMICA.".academico_actividad_respuestas resp ON resp.resp_id=res.res_id_respuesta AND resp.resp_correcta=1 AND resp.institucion={$config['conf_id_institucion']} AND resp.year={$_SESSION["bd"]}
            WHERE preg.institucion={$config['conf_id_institucion']} AND preg.year={$_SESSION["bd"]}),
            
            (SELECT count(preg_id) FROM ".BD_ACADEMICA.".academico_actividad_preguntas preg
            INNER JOIN ".BD_ACADEMICA.".academico_actividad_evaluaciones_resultados res ON res.res_id_pregunta=preg.preg_id AND res.res_id_evaluacion='".$idEvaluacion."' AND res.res_id_estudiante='".$idEstudiante."' AND res.institucion={$config['conf_id_institucion']} AND res.year={$_SESSION["bd"]}
            INNER JOIN ".BD_ACADEMICA.".academico_actividad_respuestas resp ON resp.resp_id=res.res_id_respuesta AND resp.resp_correcta=1 AND resp.institucion={$config['conf_id_institucion']} AND resp.year={$_SESSION["bd"]}
            WHERE preg.institucion={$config['conf_id_institucion']} AND preg.year={$_SESSION["bd"]})");
            $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    /**
     * Este metodo guarda una pregunta
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
     * @param array $FILES
     * 
     * @return string $codigo
     */
    public static function guardarPreguntasEvaluacion(mysqli $conexion, array $config, array $POST, array $FILES,$finalizarTransacion=true){

        $archivoSubido = new Archivos;
        $codigo=Utilidades::generateCode("PRE");
        $destino = ROOT_PATH."/main-app/files/evaluaciones";
        $archivo = "";
        if(!empty($FILES['file']['name'])){
            $archivoSubido->validarArchivo($FILES['file']['size'], $FILES['file']['name']);
            $explode=explode(".", $FILES['file']['name']);
            $extension = end($explode);
            $archivo = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_eva_').".".$extension;
            @unlink($destino."/".$archivo);
            move_uploaded_file($FILES['file']['tmp_name'], $destino ."/".$archivo);
        }
        try{
            $sql = "INSERT INTO ".BD_ACADEMICA.".academico_actividad_preguntas(preg_id, preg_descripcion, preg_valor, preg_id_carga, preg_tipo_pregunta, preg_archivo, institucion, year)VALUES(?,?,?,?,?,?,?,?)";

            $parametros = [$codigo, mysqli_real_escape_string($conexion,$POST["contenido"]), $POST["valor"],$_COOKIE["carga"],$POST["opcionR"], $archivo, $config['conf_id_institucion'], $_SESSION["bd"]];
            
            $resultado = BindSQL::prepararSQL($sql, $parametros,$finalizarTransacion);
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }

        return $codigo;
    }

    /**
     * Este metodo guarda una pregunta del banco de datos
     * @param mysqli $conexion
     * @param array $config
     * @param array $datosPregunta
     * @param string $idCarga
     * 
     * @return string $codigo
     */
    public static function guardarPreguntasBDEvaluacion(mysqli $conexion, array $config, array $datosPregunta, string $idCarga){

        $codigo=Utilidades::generateCode("PRE");
        try{
            mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_actividad_preguntas(preg_id, preg_descripcion, preg_valor, preg_id_carga, preg_imagen1, preg_imagen2, preg_tipo_pregunta, preg_archivo, institucion, year)VALUES('".$codigo."', '".$datosPregunta['preg_descripcion']."', '".$datosPregunta['preg_valor']."', '".$idCarga."', '".$datosPregunta['preg_imagen1']."', '".$datosPregunta['preg_imagen2']."', '".$datosPregunta['preg_tipo_pregunta']."', '".$datosPregunta['preg_archivo']."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }

        return $codigo;
    }

    /**
     * Este metodo actualiza una pregunta
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
     * @param array $FILES
     */
    public static function actualizarPreguntasEvaluacion(mysqli $conexion, array $config, array $POST, array $FILES){

        $archivoSubido = new Archivos;
        try{
            mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_actividad_preguntas SET preg_descripcion='".mysqli_real_escape_string($conexion,$POST["contenido"])."', preg_valor='".$POST["valor"]."' WHERE preg_id='".$POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        
        //Archivos para evaluaciones
        $destino = ROOT_PATH."/main-app/files/evaluaciones";
        if(!empty($FILES['file']['name'])){
            $archivoSubido->validarArchivo($FILES['file']['size'], $FILES['file']['name']);
            $explode=explode(".", $FILES['file']['name']);
            $extension = end($explode);
            $archivo = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_eva_').".".$extension;
            @unlink($destino."/".$archivo);
            move_uploaded_file($FILES['file']['tmp_name'], $destino ."/".$archivo);
            try{
                mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_actividad_preguntas SET preg_archivo='".$archivo."' WHERE preg_id='".$POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
            } catch (Exception $e) {
                echo "Excepción catpurada: ".$e->getMessage();
                exit();
            }
        }
    }

    /**
     * Este metodo me trae las preguntas de una carga
     * @param mysqli $conexion
     * @param array $config
     * @param string $idCargas
     * 
     * @return mysqli_result $consulta
     */
    public static function traerPreguntasCargas(mysqli $conexion, array $config, string $idCargas){
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_preguntas 
            WHERE preg_id_carga='".$idCargas."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $consulta;
    }

    /**
     * Este metodo me trae el conteo de las preguntas de una evaluacion
     * @param mysqli $conexion
     * @param array $config
     * @param string $idPregunta
     * 
     * @return array $resultado
     */
    public static function traerDatosPreguntas(mysqli $conexion, array $config, string $idPregunta){
        try{
            $consulta=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_preguntas WHERE preg_id='".$idPregunta."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
            $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    /**
     * Este metodo me trae las respuesta de una pregunta
     * @param mysqli $conexion
     * @param array $config
     * @param string $idPregunta
     * 
     * @return mysqli_result $consulta
     */
    public static function traerRespuestaPregunta(mysqli $conexion, array $config, string $idPregunta){
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_respuestas
            WHERE resp_id_pregunta='".$idPregunta."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $consulta;
    }

    /**
     * Este metodo guarda una respuesta del banco de datos
     * @param mysqli $conexion
     * @param array $config
     * @param array $datosRespuesta
     * @param string $idPregunta
     */
    public static function guardarRespuestaBD(mysqli $conexion, array $config, array $datosRespuesta, string $idPregunta){

        $codigoR=Utilidades::generateCode("RES");
        try{
            mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_actividad_respuestas(resp_id, resp_descripcion, resp_correcta, resp_id_pregunta, resp_imagen, institucion, year)VALUES('".$codigoR."', '".$datosRespuesta['resp_descripcion']."', '".$datosRespuesta['resp_correcta']."', '".$idPregunta."', '".$datosRespuesta['resp_imagen']."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
    }

    /**
     * Este metodo me actualiza el estado de una respuesta
     * @param mysqli $conexion
     * @param array $config
     * @param string $idRespuesta
     * @param int $estado
     */
    public static function actualizarEstadoRespuesta(mysqli $conexion, array $config, string $idRespuesta, int $estado){

        try{
            mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_actividad_respuestas SET resp_correcta='".$estado."' WHERE resp_id='".$idRespuesta."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
    }

    /**
     * Este metodo me actualiza el estado de una respuesta
     * @param mysqli $conexion
     * @param array $config
     * @param string $idRespuesta
     */
    public static function eliminarRespuesta(mysqli $conexion, array $config, string $idRespuesta){

        try{
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_respuestas WHERE resp_id='".$idRespuesta."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
    }

    /**
     * Este metodo me actualiza una respuesta
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
     */
    public static function actualizarRespuesta(mysqli $conexion, array $config, array $POST){

        try{
            mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_actividad_respuestas SET resp_descripcion='".$POST["valor"]."' WHERE resp_id='".$POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
    }

    /**
     * Este metodo me guarda una respuesta
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
     */
    public static function guardarRespuesta(mysqli $conexion, array $config, array $POST){
        $codigo=Utilidades::generateCode("RES");

        try{
            mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_actividad_respuestas(resp_id, resp_descripcion, resp_correcta, resp_id_pregunta, institucion, year)VALUES('".$codigo."', '".$POST["valor"]."', 0, '".$POST["pregunta"]."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
    }
}