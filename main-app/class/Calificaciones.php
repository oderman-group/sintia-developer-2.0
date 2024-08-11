<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
require_once(ROOT_PATH."/main-app/class/BindSQL.php");
require_once ROOT_PATH."/main-app/class/Conexion.php";
require_once ROOT_PATH."/main-app/class/AjaxCalificaciones.php";
require_once(ROOT_PATH."/main-app/class/Actividades.php");

class Calificaciones {

    /**
     * Este metodo me re-calcula los valores de todas las actividades de un indicador
     * @param mysqli    $conexion
     * @param array     $config
     * @param string    $idcarga
     * @param int       $periodo
     * @param array     $datosIndicador
    **/
    public static function actualizarValorCalificacionesDeUnIndicador (
        mysqli  $conexion, 
        array   $config, 
        string  $idcarga, 
        int     $periodo, 
        array   $datosIndicador
    )
    {
        $sql = "SELECT * FROM ".BD_ACADEMICA.".academico_actividades WHERE act_id_tipo=? AND act_id_carga=? AND act_periodo=? AND act_estado=1 AND institucion=? AND year=?";
        $parametros = [$datosIndicador['ipc_indicador'], $idcarga, $periodo, $config['conf_id_institucion'], $_SESSION["bd"]];
        $consultaActividadesNum = BindSQL::prepararSQL($sql, $parametros);
		$actividadesNum = mysqli_num_rows($consultaActividadesNum);

		//Si hay actividades relacionadas al indicador, actualizamos su valor.
		if($actividadesNum>0){
			$valorIgualActividad = ($datosIndicador['ipc_valor']/$actividadesNum);
            
            $sql = "UPDATE ".BD_ACADEMICA.".academico_actividades SET act_valor=? WHERE act_id_tipo=? AND act_id_carga=? AND act_periodo=? AND act_estado=1 AND institucion=? AND year=?";
            $parametros = [$valorIgualActividad, $datosIndicador['ipc_indicador'], $idcarga, $periodo, $config['conf_id_institucion'], $_SESSION["bd"]];
            $resultado = BindSQL::prepararSQL($sql, $parametros);
		}
    }

    /**
     * Este metodo me re-calcula los valores de todas las actividades de una carga
     * @param mysqli    $conexion
     * @param array     $config
     * @param string    $idcarga
     * @param int       $periodo
    **/
    public static function actualizarValorCalificacionesDeUnaCarga (
        mysqli  $conexion, 
        array   $config, 
        string  $idcarga, 
        int     $periodo
    )
    {
        try{
            $indicadoresConsultaActualizado = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_indicadores_carga 
            WHERE ipc_carga='".$idcarga."' AND ipc_periodo='".$periodo."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
    
        while($indicadoresDatos = mysqli_fetch_array($indicadoresConsultaActualizado, MYSQLI_BOTH)){
            Calificaciones::actualizarValorCalificacionesDeUnIndicador($conexion, $config, $idcarga, $periodo, $indicadoresDatos);
        }	
    }

    /**
     * Este metodo me elimina la nota de recuperación de un estudiante
     * @param mysqli $conexion
     * @param array $config
     * @param string $idE
    **/
    public static function eliminarNotaRecuperacionEstudiante (
        mysqli $conexion, 
        array $config, 
        string $idE
    )
    {

        $sql = "DELETE FROM ".BD_ACADEMICA.".academico_recuperaciones_notas WHERE rec_cod_estudiante=? AND institucion=? AND year=?";

        $parametros = [$idE, $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }

    /**
     * Este metodo me elimina todas las notas de recuperacion
     * @param mysqli $conexion
     * @param array $config
    **/
    public static function eliminarTodasNotaRecuperacion (
        mysqli $conexion, 
        array $config
    )
    {

        $sql = "DELETE FROM ".BD_ACADEMICA.".academico_recuperaciones_notas WHERE institucion=? AND year=?";

        $parametros = [$config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }

    /**
     * Este metodo me trael la nivelacion de un estudiante en una materia
     * @param mysqli    $conexion
     * @param array     $config
     * @param string    $idEstudiante
     * @param string    $idCarga
     * @param string    $year
     * 
     * @return mysqli_result $consulta
    **/
    public static function nivelacionEstudianteCarga (
        mysqli  $conexion, 
        array   $config, 
        string  $idEstudiante, 
        string  $idCarga, 
        string  $year   =   ""
    )
    {
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];
        $sql = "SELECT * FROM ".BD_ACADEMICA.".academico_nivelaciones WHERE niv_cod_estudiante=? AND niv_id_asg=? AND institucion=? AND year=?";

        $parametros = [$idEstudiante, $idCarga, $config['conf_id_institucion'], $year];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);

        return $resultado;
    }

    /**
     * Este metodo me elimina una nivelacion
     * @param mysqli    $conexion
     * @param array     $config
     * @param string    $idNivelacion
    **/
    public static function eliminarNivelacion (
        mysqli  $conexion, 
        array   $config, 
        string  $idNivelacion
    )
    {
        $sql = "DELETE FROM ".BD_ACADEMICA.".academico_nivelaciones WHERE niv_id=? AND institucion=? AND year=?";

        $parametros = [$idNivelacion, $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }

    /**
     * Este metodo me elimina todas las nivelaciones de un estudiante
     * @param mysqli    $conexion
     * @param array     $config
     * @param string    $idEstudiante
    **/
    public static function eliminarNivelacionEstudiante (
        mysqli  $conexion, 
        array   $config, 
        string  $idEstudiante
    )
    {
        $sql = "DELETE FROM ".BD_ACADEMICA.".academico_nivelaciones WHERE niv_cod_estudiante=? AND institucion=? AND year=?";

        $parametros = [$idEstudiante, $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }

    /**
     * Este metodo me elimina todas las nivelaciones
     * @param mysqli    $conexion
     * @param array     $config
    **/
    public static function eliminarTodasNivelaciones (
        mysqli  $conexion, 
        array   $config
    )
    {
        $sql = "DELETE FROM ".BD_ACADEMICA.".academico_nivelaciones WHERE institucion=? AND year=?";

        $parametros = [$config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }

    /**
     * Este metodo me guarda una nivelición
     * @param mysqli    $conexion
     * @param PDO       $conexionPDO
     * @param array     $config
     * @param array     $POST
    **/
    public static function guardarNivelacion (
        mysqli $conexion,
        PDO    $conexionPDO, 
        array  $config, 
        array  $POST
    )
    {
        $codigo = Utilidades::getNextIdSequence($conexionPDO, BD_ACADEMICA, 'academico_nivelaciones');

        $sql = "INSERT INTO ".BD_ACADEMICA.".academico_nivelaciones(niv_id, niv_id_asg, niv_cod_estudiante, niv_definitiva, niv_fecha, institucion, year)VALUES(?, ?, ?, ?, now(), ?, ?)";

        $parametros = [$codigo, $POST["carga"], $POST["codEst"], $POST["nota"], $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }

    /**
     * Este metodo me actualiza la definitiva de una nivelación
     * @param mysqli    $conexion
     * @param array     $config
     * @param string    $nota
     * @param string    $idNivelacion
    **/
    public static function actualizarDefinitivaNivelacion (
        mysqli $conexion, 
        array  $config,
        string $nota,
        string $idNivelacion
    )
    {

        $sql = "UPDATE ".BD_ACADEMICA.".academico_nivelaciones SET niv_definitiva=? WHERE niv_id=? AND institucion=? AND year=?";

        $parametros = [$nota, $idNivelacion, $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }

    /**
     * Este metodo me actualiza el acta de una nivelación
     * @param mysqli    $conexion
     * @param array     $config
     * @param string    $acta
     * @param string    $idNivelacion
    **/
    public static function actualizarActaNivelacion (
        mysqli $conexion, 
        array  $config,
        string $acta,
        string $idNivelacion
    )
    {

        $sql = "UPDATE ".BD_ACADEMICA.".academico_nivelaciones SET niv_acta=? WHERE niv_id=? AND institucion=? AND year=?";

        $parametros = [$acta, $idNivelacion, $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }

    /**
     * Este metodo me actualiza la fecha de una nivelación
     * @param mysqli    $conexion
     * @param array     $config
     * @param string    $fecha
     * @param string    $idNivelacion
    **/
    public static function actualizarFechaNivelacion (
        mysqli $conexion, 
        array  $config,
        string $fecha,
        string $idNivelacion
    )
    {

        $sql = "UPDATE ".BD_ACADEMICA.".academico_nivelaciones SET niv_fecha_nivelacion=? WHERE niv_id=? AND institucion=? AND year=?";

        $parametros = [$fecha, $idNivelacion, $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }

    /**
     * Este metodo me transfiere la nivelacion de un estudiante a otra carga
     * @param mysqli    $conexion
     * @param array     $config
     * @param string    $idCargaNueva
     * @param string    $idCarga
     * @param string    $idEstudiante
    **/
    public static function transferirNivelacion (
        mysqli $conexion, 
        array  $config,
        string $idCargaNueva,
        string $idCarga,
        string $idEstudiante
    )
    {

        $sql = "UPDATE ".BD_ACADEMICA.".academico_nivelaciones SET niv_id_asg=? WHERE niv_cod_estudiante=? AND niv_id_asg=? AND institucion=? AND year=?";

        $parametros = [$idCargaNueva, $idEstudiante, $idCarga, $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }

    /**
     * Este metodo me valida si una materia fue nivelada
     * @param mysqli    $conexion
     * @param array     $config
     * @param string    $idEstudiante
     * @param string    $idCarga
     * @param string    $year
     * 
     * @return mysqli_result $consulta
    **/
    public static function validarMateriaNivelada (
        mysqli  $conexion, 
        array   $config, 
        string  $idEstudiante, 
        string  $idCarga, 
        string  $year   =   ""
    )
    {
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        $sql = "SELECT * FROM ".BD_ACADEMICA.".academico_nivelaciones WHERE niv_cod_estudiante=? AND niv_id_asg=? AND niv_definitiva>=? AND institucion=? AND year=?";

        $parametros = [$idEstudiante, $idCarga, $config['conf_nota_minima_aprobar'], $config['conf_id_institucion'], $year];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);

        return $resultado;
    }

    /**
     * Este metodo me todas las nivelaciones de un estudiante
     * @param mysqli    $conexion
     * @param array     $config
     * @param string    $idEstudiante
     * @param string    $year
     * 
     * @return mysqli_result $consulta
    **/
    public static function consultarNivelacionesEstudiante (
        mysqli  $conexion, 
        array   $config, 
        string  $idEstudiante, 
        string  $year   =   ""
    )
    {
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        $sql = "SELECT niv_definitiva, niv_acta, niv_fecha_nivelacion, mat_nombre FROM ".BD_ACADEMICA.".academico_nivelaciones niv 
        INNER JOIN ".BD_ACADEMICA.".academico_cargas car ON car_id=niv.niv_id_asg AND car.institucion=niv.institucion AND car.year=niv.year
        INNER JOIN ".BD_ACADEMICA.".academico_materias am ON mat_id=car_materia AND am.institucion=niv.institucion AND am.year=niv.year
        WHERE niv.niv_cod_estudiante=? AND niv.institucion=? AND niv.year=?";

        $parametros = [$idEstudiante, $config['conf_id_institucion'], $year];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);

        return $resultado;
    }

    /**
     * Este metodo me trae la calificacion de un estudiante en una actividad
     */
    public static function traerCalificacionActividadEstudiante(
        array  $config,
        string $idActividad, 
        string $idEstudiante,
        string $yearBd = ""
    ){
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        $sql = "SELECT * FROM ".BD_ACADEMICA.".academico_calificaciones WHERE cal_id_actividad=? AND cal_id_estudiante=? AND institucion=? AND year=?";

        $parametros = [$idActividad, $idEstudiante, $config['conf_id_institucion'], $year];

        $resultado = BindSQL::prepararSQL($sql, $parametros);

        $resultado = mysqli_fetch_array($resultado, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo me guarda la nota de un estudiante en una actividad
    **/
    public static function guardarNotaActividadEstudiante (
        PDO     $conexionPDO,
        string  $insert,
        array   $parametros
    )
    {
        $campos = explode(',', $insert);
        $numCampos = count($campos);
        $signosPreguntas = str_repeat('?,', $numCampos);
        $signosPreguntas = rtrim($signosPreguntas, ',');

        $codigo = Utilidades::getNextIdSequence($conexionPDO, BD_ACADEMICA, 'academico_calificaciones');
        $parametros[] = $codigo;

        $sql = "INSERT INTO ".BD_ACADEMICA.".academico_calificaciones({$insert}) VALUES ({$signosPreguntas})";

        $resultado = BindSQL::prepararSQL($sql, $parametros);

    }

    /**
     * Este metodo me actualiza la nota de un estudiante
    **/
    public static function actualizarNotaActividadEstudiante (
        array   $config,
        string  $idActividad,
        string  $idEstudiante,
        array   $update,
        string  $yearBd = ""
    )
    {
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        [$updateSql, $updateValues] = BindSQL::prepararUpdateConArray($update);

        $sql = "UPDATE ".BD_ACADEMICA.".academico_calificaciones SET {$updateSql}, cal_fecha_modificada=now(), cal_cantidad_modificaciones=cal_cantidad_modificaciones+1 WHERE cal_id_actividad=? AND cal_id_estudiante=? AND institucion=? AND year=?";

        $parametros = array_merge($updateValues, [$idActividad, $idEstudiante, $config['conf_id_institucion'], $year]);

        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }

    /**
     * Este metodo me elimina la calificacion de un estudiante en una actividad
     */
    public static function eliminarCalificacionActividadEstudiante(
        array  $config,
        string $idActividad, 
        string $idEstudiante,
        string $yearBd = ""
    ){
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        $sql = "DELETE FROM ".BD_ACADEMICA.".academico_calificaciones WHERE cal_id_actividad=? AND cal_id_estudiante=? AND institucion=? AND year=?";

        $parametros = [$idActividad, $idEstudiante, $config['conf_id_institucion'], $year];

        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }

    /**
     * Este metodo me trae todas las calificacion de una actividad
     */
    public static function traerCalificacionActividad(
        array  $config,
        string $idActividad, 
        string $yearBd = ""
    ){
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        $sql = "SELECT * FROM ".BD_ACADEMICA.".academico_calificaciones WHERE cal_id_actividad=? AND institucion=? AND year=?";

        $parametros = [$idActividad, $config['conf_id_institucion'], $year];

        $resultado = BindSQL::prepararSQL($sql, $parametros);

        return $resultado;
    }

    /**
     * Este metodo me trae las notas por indicador
     */
    public static function traerNotasPorIndicador(
        array  $config,
        string $idCarga, 
        string $idEstudiante,
        int    $periodo,
        string $yearBd = ""
    ){
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        $sql = "SELECT SUM((cal_nota*(act_valor/100))), act_id_tipo, ipc_valor FROM ".BD_ACADEMICA.".academico_calificaciones aac
        INNER JOIN ".BD_ACADEMICA.".academico_actividades aa ON aa.act_id=aac.cal_id_actividad AND aa.act_estado=1 AND aa.act_registrada=1 AND aa.act_periodo=? AND aa.act_id_carga=? AND aa.institucion=? AND aa.year=?
        INNER JOIN ".BD_ACADEMICA.".academico_indicadores_carga ipc ON ipc.ipc_indicador=aa.act_id_tipo AND ipc.ipc_carga=? AND ipc.ipc_periodo=? AND ipc.institucion=? AND ipc.year=?
        WHERE aac.cal_id_estudiante=? AND aac.institucion=? AND aac.year=?
        GROUP BY aa.act_id_tipo";

        $parametros = [$periodo, $idCarga, $config['conf_id_institucion'], $year, $idCarga, $periodo, $config['conf_id_institucion'], $year, $idEstudiante, $config['conf_id_institucion'], $year];

        $resultado = BindSQL::prepararSQL($sql, $parametros);

        return $resultado;
    }

    /**
     * Este metodo me trae la nota de un indicador en un periodo
     */
    public static function consultaNotaIndicadoresPeriodos(
        array  $config,
        string $idIndicador, 
        string $idEstudiante,
        string $yearBd = ""
    ){
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        $sql = "SELECT ROUND(AVG(cal_nota),1) FROM ".BD_ACADEMICA.".academico_calificaciones aac
        INNER JOIN ".BD_ACADEMICA.".academico_actividades aa ON aa.act_id=aac.cal_id_actividad AND aa.act_id_tipo=? AND aa.institucion=? AND aa.year=?
        WHERE aac.cal_id_estudiante=? AND aac.institucion=? AND aac.year=?";

        $parametros = [$idIndicador, $config['conf_id_institucion'], $year, $idEstudiante, $config['conf_id_institucion'], $year];

        $resultado = BindSQL::prepararSQL($sql, $parametros);

        $resultado = mysqli_fetch_array($resultado, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo me trae la nota de un indicador
     */
    public static function consultaNotaIndicadores(
        array  $config,
        string $idIndicador, 
        string $idCarga, 
        string $idEstudiante,
        int    $periodo,
        string $yearBd = ""
    ){
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        $sql = "SELECT ROUND(AVG(cal_nota),1) FROM ".BD_ACADEMICA.".academico_calificaciones aac
        INNER JOIN ".BD_ACADEMICA.".academico_actividades aa ON aa.act_id=aac.cal_id_actividad AND aa.act_id_tipo=? AND aa.act_id_carga=? AND aa.act_periodo=? AND aa.act_estado=1 AND aa.institucion=? AND aa.year=?
        WHERE aac.cal_id_estudiante=? AND aac.institucion=? AND aac.year=?";

        $parametros = [$idIndicador, $idCarga, $periodo, $config['conf_id_institucion'], $year, $idEstudiante, $config['conf_id_institucion'], $year];

        $resultado = BindSQL::prepararSQL($sql, $parametros);

        $resultado = mysqli_fetch_array($resultado, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo me suma las notas de un indicador
     */
    public static function consultaSumaNotaIndicadores(
        array  $config,
        string $idIndicador, 
        string $idCarga, 
        string $idEstudiante,
        int    $periodo,
        string $yearBd = ""
    ){
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        $sql = "SELECT SUM(cal_nota * (act_valor/100)), SUM(act_valor) FROM ".BD_ACADEMICA.".academico_calificaciones aac
        INNER JOIN ".BD_ACADEMICA.".academico_actividades aa ON aa.act_id=aac.cal_id_actividad AND aa.act_id_tipo=? AND aa.act_id_carga=? AND aa.act_periodo=? AND aa.act_estado=1 AND aa.institucion=? AND aa.year=?
        WHERE aac.cal_id_estudiante=? AND aac.institucion=? AND aac.year=?";

        $parametros = [$idIndicador, $idCarga, $periodo, $config['conf_id_institucion'], $year, $idEstudiante, $config['conf_id_institucion'], $year];

        $resultado = BindSQL::prepararSQL($sql, $parametros);

        $resultado = mysqli_fetch_array($resultado, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo me trae la nota de un indicador
     */
    public static function consultaNotaIndicadoresPromedio(
        array  $config,
        string $idIndicador, 
        string $idCarga, 
        string $idEstudiante,
        int    $periodo,
        string $yearBd = ""
    ){
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        $sql = "SELECT ROUND(SUM(cal_nota*(act_valor/100)) / SUM(act_valor/100),2) FROM ".BD_ACADEMICA.".academico_calificaciones aac
        INNER JOIN ".BD_ACADEMICA.".academico_actividades aa ON aa.act_id=aac.cal_id_actividad AND aa.act_id_tipo=? AND aa.act_id_carga=? AND aa.act_periodo=? AND aa.act_estado=1 AND aa.institucion=? AND aa.year=?
        WHERE aac.cal_id_estudiante=? AND aac.institucion=? AND aac.year=?";

        $parametros = [$idIndicador, $idCarga, $periodo, $config['conf_id_institucion'], $year, $idEstudiante, $config['conf_id_institucion'], $year];

        $resultado = BindSQL::prepararSQL($sql, $parametros);

        $resultado = mysqli_fetch_array($resultado, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo me elimina unas calificacion
     */
    public static function eliminarCalificacion(
        array  $config,
        string $idCalificacion,
        string $yearBd = ""
    ){
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        $sql = "DELETE FROM ".BD_ACADEMICA.".academico_calificaciones WHERE cal_id=? AND institucion=? AND year=?";

        $parametros = [$idCalificacion, $config['conf_id_institucion'], $year];

        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }

    /**
     * Este metodo me elimina todas las calificaciones de un estudiante
     */
    public static function eliminarCalificacionEstudiante(
        array  $config,
        string $idEstudiante,
        string $yearBd = ""
    ){
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        $sql = "DELETE FROM ".BD_ACADEMICA.".academico_calificaciones WHERE cal_id_estudiante=? AND institucion=? AND year=?";

        $parametros = [$idEstudiante, $config['conf_id_institucion'], $year];

        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }

    /**
     * Este metodo me elimina todas las calificaciones de una institución
     */
    public static function eliminarCalificacionesInstitucion(
        array  $config,
        string $yearBd = ""
    ){
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        $sql = "DELETE FROM ".BD_ACADEMICA.".academico_calificaciones WHERE institucion=? AND year=?";

        $parametros = [$config['conf_id_institucion'], $year];

        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }

    /**
     * Este metodo me consulta el numero de estudiantes calificados
     */
    public static function consultaNumEstudiantesCalificados(
        array  $config,
        array  $datosCargaActual, 
        string $idActividad, 
        string $yearBd = ""
    ){
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        if($datosCargaActual['gra_tipo'] == GRADO_INDIVIDUAL) {
            $sql = "SELECT count(*) FROM ".BD_ACADEMICA.".academico_calificaciones aac
            INNER JOIN ".BD_ADMIN.".mediatecnica_matriculas_cursos ON matcur_id_curso=? AND matcur_id_grupo=? AND matcur_estado='".ACTIVO."' AND matcur_id_institucion=? AND matcur_years=?
            INNER JOIN ".BD_ACADEMICA.".academico_matriculas mat ON mat_eliminado=0 AND (mat_estado_matricula=1 OR mat_estado_matricula=2) AND mat_id=aac.cal_id_estudiante AND mat_id=matcur_id_matricula AND mat.institucion=? AND mat.year=?
            WHERE aac.cal_id_actividad=? AND aac.institucion=? AND aac.year=?";

            $parametros = [$datosCargaActual['car_curso'], $datosCargaActual['car_grupo'], $config['conf_id_institucion'], $year, $config['conf_id_institucion'], $year, $idActividad, $config['conf_id_institucion'], $year];
        } else {
            $sql = "SELECT count(*) FROM ".BD_ACADEMICA.".academico_calificaciones aac
            INNER JOIN ".BD_ACADEMICA.".academico_matriculas mat ON mat_grado=? AND mat_grupo=? AND (mat_estado_matricula=1 OR mat_estado_matricula=2) AND mat_eliminado=0 AND mat_id=aac.cal_id_estudiante AND mat.institucion=? AND mat.year=?
            WHERE aac.cal_id_actividad=? AND aac.institucion=? AND aac.year=?";

            $parametros = [$datosCargaActual['car_curso'], $datosCargaActual['car_grupo'], $config['conf_id_institucion'], $year, $idActividad, $config['conf_id_institucion'], $year];
        }

        $resultado = BindSQL::prepararSQL($sql, $parametros);

        $resultado = mysqli_fetch_array($resultado, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo me consulta las actividades y los indicadores a los que pertenecen
     */
    public static function consultarActividadesIndicador(
        array  $config,
        string $idCarga,
        int    $periodo,
        string $yearBd = ""
    ){
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        $sql = "SELECT aa.id_nuevo AS id_nuevo_act, aa.*, ai.* FROM ".BD_ACADEMICA.".academico_actividades aa
        INNER JOIN ".BD_ACADEMICA.".academico_indicadores ai ON ai.ind_id=aa.act_id_tipo AND ai.institucion=aa.institucion AND ai.year=aa.year
        WHERE aa.act_id_carga=? AND aa.act_periodo=? AND aa.act_estado=1 AND aa.institucion=? AND aa.year=?";
        $parametros = [$idCarga, $periodo, $config['conf_id_institucion'], $year];
        $resultado = BindSQL::prepararSQL($sql, $parametros);

        return $resultado;
    }

    /**
     * Este metodo me consultas las evidencias de una institucion
     * @param array     $config
     * @param string    $yearBd
    **/
    public static function traerEvidenciasInstitucion (
        array   $config, 
        string  $yearBd = ""
    )
    {
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        $sql = "SELECT * FROM ".BD_ACADEMICA.".academico_evidencias WHERE institucion=? AND year=?";

        $parametros = [$config['conf_id_institucion'], $year];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);

        return $resultado;
    }

    /**
     * Este metodo me trae la información de una evidencia
     * @param array     $config
     * @param string    $idEvidencia
     * @param string    $yearBd
    **/
    public static function traerDatosEvidencias (
        array   $config, 
        string  $idEvidencia,
        string  $yearBd = ""
    )
    {
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        $sql = "SELECT * FROM ".BD_ACADEMICA.".academico_evidencias WHERE evid_id=? AND institucion=? AND year=?";

        $parametros = [$idEvidencia, $config['conf_id_institucion'], $year];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);

        $resultado = mysqli_fetch_array($resultado, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Validates a given grade (nota) to ensure it falls within the acceptable range.
     *
     * @param mixed $nota The grade to be validated. It should be a numeric value.
     *
     * @return array An associative array containing:
     *               - 'success': A boolean indicating if the grade is valid.
     *               - 'heading': A string message heading.
     *               - 'estado': A string indicating the status ('danger', 'warning', 'success').
     *               - 'mensaje': A string message providing details about the validation result.
     */
    public static function esCalificacionValida($nota): array 
    {

        $config = RedisInstance::getSystemConfiguration();

        if (!is_numeric($nota)) {
            return [
                'success' => false,
                'heading' => "Nota inválida",
                'estado'  => 'danger',
                'mensaje' => "La nota {$nota} no es válida. Ingrese un valor entero o decimal entre {$config['conf_nota_desde']} y {$config['conf_nota_hasta']}",
            ];
        }

        if ($nota > $config['conf_nota_hasta']) {
            return [
                'success' => false,
                'heading' => 'Nota superada',
                'estado'  => 'warning',
                'mensaje'   => 'La calificación supera el máximo permitido: '.$config['conf_nota_hasta'],
            ];
        }

        if ($nota < $config['conf_nota_desde']) {
            return [
                'success' => false,
                'heading' => 'Nota inferior',
                'estado'  => 'warning',
                'mensaje' => 'La calificación esta por debajo del mínimo permitido: '.$config['conf_nota_desde'],
            ];
        }

        return [
            'success' => true,
            'heading' => 'Nota valida',
            'estado'  => 'success',
            'mensaje' => 'La calificación es valida: '.$nota,
        ];
    }

    /**
     * Directs the grade processing based on the provided data.
     *
     * This function validates the grade and determines whether to save a new grade or update an existing one.
     *
     * @param array $data An associative array containing the following keys:
     *                    - 'target': The target for redirection.
     *                    - 'nota': The grade to be processed.
     *                    - 'codNota': The code of the activity.
     *                    - 'codEst': The code of the student.
     *                    - 'nombreEst': The name of the student.
     *                    - 'notaAnterior' (optional): The previous grade.
     *
     * @return array An associative array containing:
     *               - 'success': A boolean indicating if the operation was successful.
     *               - 'heading': A string message heading.
     *               - 'estado': A string indicating the status ('danger', 'warning', 'success').
     *               - 'mensaje': A string message providing details about the operation result.
    */
    public static function direccionarCalificacion($data) {

        if (!is_array($data) || !array_key_exists('target', $data)) {
            return [
                'success' => false,
                'heading' => 'Error en la redirección',
                'estado'  => 'danger',
                'mensaje' => 'No se ha encontrado el target de la redirección',
            ];
        }

        $esCalificacionValida = self::esCalificacionValida($data['nota']);

        if (!$esCalificacionValida['success']) {
            return $esCalificacionValida;
        }

        $hayRegistrosCalificaciones = self::hayRegistrosCalificaciones($data['codNota'], $data['codEst']);

        if ($hayRegistrosCalificaciones == true || $hayRegistrosCalificaciones == 1) {
            return self::actualizarCalificacion($data);
        } else {
            return AjaxCalificaciones::ajaxGuardarNota($data);
        }

        return [
            'success' => false,
            'heading' => 'Error en la redirección',
            'estado'  => 'danger',
            'mensaje' => 'El target de la redirección no es válido',
        ];

    }

    /**
     * Checks if there are existing grade records for a given activity and student.
     *
     * @param string $codNota The code of the activity.
     * @param string $codEstudiante The code of the student.
     *
     * @return bool Returns true if there are existing grade records, false otherwise.
     *
     * @throws Exception If either the activity code or student code is empty.
     */
    public static function hayRegistrosCalificaciones($codNota, $codEstudiante): bool 
    {
        if (empty($codNota) || empty($codEstudiante)) {
            throw new Exception('Debe ingresar código de actividad y código de estudiante');
        }

        $config = RedisInstance::getSystemConfiguration();

        $sql = "
        SELECT 
            cal_id
        FROM ".BD_ACADEMICA.".academico_calificaciones 
        WHERE 
            cal_id_actividad=:codNota
        AND cal_id_estudiante=:codEst
        AND institucion=:institucion
        AND year=:year
        ";

        $conexionPDO = Conexion::newConnection('PDO');
        $conexionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $asp = $conexionPDO->prepare($sql);

        $asp->bindParam(':codNota',      $codNota, PDO::PARAM_STR);
        $asp->bindParam(':codEst',       $codEstudiante, PDO::PARAM_STR);
        $asp->bindParam(':institucion',  $config['conf_id_institucion'], PDO::PARAM_INT);
        $asp->bindParam(':year',         $_SESSION["bd"], PDO::PARAM_INT);

        
        $asp->execute();

        $rowCount = $asp->rowCount();

        if ($rowCount > 0) return true; else return false;
    }

    /**
     * Updates the grade (calificación) of a student for a specific activity.
     *
     * This function updates the grade in the database, sets the modification date to the current date,
     * increments the modification count, and updates the previous grade. It also marks the activity as registered.
     *
     * @param array $data An associative array containing the following keys:
     *                    - 'nota': The new grade to be set.
     *                    - 'notaAnterior': The previous grade (optional, defaults to "0.0" if not provided).
     *                    - 'codNota': The code of the activity.
     *                    - 'codEst': The code of the student.
     *                    - 'nombreEst': The name of the student.
     *
     * @return array An associative array containing:
     *               - 'heading': A string message heading.
     *               - 'estado': A string indicating the status ('success').
     *               - 'mensaje': A string message providing details about the operation result.
     */
    public static function actualizarCalificacion($data): array 
    {
        $config = RedisInstance::getSystemConfiguration();

        $sql = "
        UPDATE ".BD_ACADEMICA.".academico_calificaciones 
        SET cal_nota                    = :nota, 
            cal_fecha_modificada        = now(), 
            cal_cantidad_modificaciones = cal_cantidad_modificaciones+1, 
            cal_nota_anterior           = :notaAnterior, 
            cal_tipo                    = 1 
        WHERE cal_id_actividad          = :codNota 
        AND cal_id_estudiante           = :codEst 
        AND institucion                 = :institucion  
        AND year                        = :year  
        ";

        $data['notaAnterior'] = empty($data['notaAnterior']) ? "0.0" : $data['notaAnterior'];

        $conexionPDO = Conexion::newConnection('PDO');
        $conexionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $asp = $conexionPDO->prepare($sql);

        $asp->bindParam(':nota',         $data['nota'], PDO::PARAM_STR);
        $asp->bindParam(':notaAnterior', $data['notaAnterior'], PDO::PARAM_STR);
        $asp->bindParam(':codNota',      $data['codNota'], PDO::PARAM_STR);
        $asp->bindParam(':codEst',       $data['codEst'], PDO::PARAM_STR);
        $asp->bindParam(':institucion',  $config['conf_id_institucion'], PDO::PARAM_INT);
        $asp->bindParam(':year',         $_SESSION["bd"], PDO::PARAM_INT);

        $asp->execute();

        $rowCount = $asp->rowCount();

        Actividades::marcarActividadRegistrada($config, $data['codNota'], $_SESSION["bd"]);

        return [
            'success' => true,
            "heading" => "Cambios actualizados",
            "estado"  => "success",
            "mensaje" => "La nota se ha actualizado correctamente para el estudiante <b>".strtoupper($data['nombreEst'])."</b>"
        ];
    }

}