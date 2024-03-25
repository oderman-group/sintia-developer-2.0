<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
require_once(ROOT_PATH."/main-app/class/BindSQL.php");
class CargaAcademica {

    /**
     * Valida la existencia de una carga académica para un docente en un curso, grupo y asignatura específicos.
     *
     * @param string $docente El identificador del docente.
     * @param string $curso El nivel o curso académico.
     * @param string $grupo El grupo al que pertenece la carga académica.
     * @param string $asignatura El identificador de la asignatura o materia.
     *
     * @return bool Devuelve `true` si existe una carga académica que cumple con los parámetros proporcionados, de lo contrario, devuelve `false`.
     *
     * @throws Exception Si hay algún problema durante la ejecución de la consulta SQL, se captura una excepción y se imprime un mensaje de error.
     *
     * @example
     * ```php
     * // Ejemplo de uso
     * $docenteEjemplo = "ID_DOCENTE";
     * $cursoEjemplo = "10";
     * $grupoEjemplo = "A";
     * $asignaturaEjemplo = "ID_ASIGNATURA";
     * $existeCarga = validarExistenciaCarga($docenteEjemplo, $cursoEjemplo, $grupoEjemplo, $asignaturaEjemplo);
     * if ($existeCarga) {
     *     echo "La carga académica existe para el docente en el curso, grupo y asignatura especificados.\n";
     * } else {
     *     echo "No existe carga académica para el docente en el curso, grupo y asignatura especificados.\n";
     * }
     * ```
     */
    public static function validarExistenciaCarga($docente, $curso, $grupo, $asignatura)
    {

        global $conexion, $config;
        $result = false;

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_cargas
            WHERE car_docente='".$docente."' AND car_curso='".$curso."' AND car_grupo='".$grupo."' AND car_materia='".$asignatura."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}
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
     * @param string $carga
     * @param string $sesion
     * 
     * @return array
     */
    public static function cargasDatosEnSesion(string $carga, string $sesion): array 
    {
        global $conexion, $filtroMT, $config;

        $infoCargaActual = [];
		try{
			$consultaCargaActual = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_cargas car 
			INNER JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$_SESSION["bd"]}
			INNER JOIN ".BD_ACADEMICA.".academico_grados gra ON gra_id=car_curso AND gra.institucion={$config['conf_id_institucion']} AND gra.year={$_SESSION["bd"]} {$filtroMT}
			INNER JOIN ".BD_ACADEMICA.".academico_grupos gru ON gru.gru_id=car_grupo AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$_SESSION["bd"]}
			WHERE car_id='".$carga."' AND car_docente='".$sesion."' AND car_activa=1 AND car.institucion={$config['conf_id_institucion']} AND car.year={$_SESSION["bd"]}");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$datosCargaActual = mysqli_fetch_array($consultaCargaActual, MYSQLI_BOTH);
		$infoCargaActual = [
			'datosCargaActual'  => $datosCargaActual
		];

        return $infoCargaActual;
    }

    /**
     * Validar Permiso para Acceso a Períodos Diferentes
     *
     * Esta función se utiliza para determinar si un usuario tiene permiso para acceder
     * a un período de carga diferente en una aplicación o sistema. Verifica si el usuario
     * tiene los derechos necesarios para acceder a un período diferente en función de ciertas
     * condiciones.
     *
     * @param Array $datosCargaActual Un array de datos que contiene información sobre la carga actual.
     * @param Int $periodoConsultaActual El período al que el usuario intenta acceder.
     *
     * @return bool Devuelve `true` si el usuario tiene permiso para acceder al período especificado,
     *              y `false` en caso contrario.
     */
    public static function validarPermisoPeriodosDiferentes(Array $datosCargaActual, Int $periodoConsultaActual): bool 
    {

        if(
            $periodoConsultaActual <= $datosCargaActual['gra_periodos'] 
            && ($periodoConsultaActual == $datosCargaActual['car_periodo'] || $datosCargaActual['car_permiso2'] == PERMISO_EDICION_PERIODOS_DIFERENTES)
        ) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * Validar Acción para Agregar Calificaciones
     *
     * Esta función se utiliza para validar si se puede realizar la acción de agregar calificaciones
     * en un sistema o aplicación. Comprueba si se cumplen ciertas condiciones, como la configuración
     * de calificaciones, el valor de calificación a agregar y el permiso para acceder a períodos diferentes.
     *
     * @param Array $datosCargaActual Un array de datos que contiene información sobre la carga actual.
     * @param Array $valores Un array que contiene valores relacionados con la acción de agregar calificaciones.
     * @param Int $periodoConsultaActual El período al que el usuario intenta acceder.
     * @param Float $porcentajeRestante El porcentaje restante de calificaciones disponibles.
     *
     * @return bool Devuelve `true` si se permite la acción de agregar calificaciones,
     *              y `false` en caso contrario.
     */
    public static function validarAccionAgregarCalificaciones(
        Array $datosCargaActual, 
        Array $valores, 
        Int $periodoConsultaActual,
        Float $porcentajeRestante
    ): bool {

        if(
            (
                (
                    $datosCargaActual['car_configuracion'] == CONFIG_AUTOMATICO_CALIFICACIONES 
                    && $valores[1] < $datosCargaActual['car_maximas_calificaciones'] 
                )
                || 
                ( $datosCargaActual['car_configuracion'] == CONFIG_MANUAL_CALIFICACIONES 
                && $valores[1] < $datosCargaActual['car_maximas_calificaciones'] 
                && $porcentajeRestante > 0 )
            )

            && self::validarPermisoPeriodosDiferentes($datosCargaActual, $periodoConsultaActual)
        ) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * Obtiene datos relacionados con una carga académica a partir de su ID.
     *
     * Esta función realiza una consulta a la base de datos para obtener información relacionada con una carga académica, como el grado, grupo, materia y docente asociados.
     *
     * @param string $idCarga - El ID de la carga académica que se desea consultar.
     *
     * @return array - Un array asociativo con los datos relacionados o un array vacío si no se encuentran datos.
     */
    public static function datosRelacionadosCarga($idCarga)
    {

        global $conexion, $config;
        $result = [];

        try {
            $consulta = mysqli_query($conexion,"SELECT * FROM ".BD_ACADEMICA.".academico_cargas car
            LEFT JOIN ".BD_ACADEMICA.".academico_grados gra ON gra_id=car_curso AND gra.institucion={$config['conf_id_institucion']} AND gra.year={$_SESSION["bd"]}
            LEFT JOIN ".BD_ACADEMICA.".academico_grupos gru ON gru.gru_id=car_grupo AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$_SESSION["bd"]}
            LEFT JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$_SESSION["bd"]}
            LEFT JOIN ".BD_GENERAL.".usuarios uss ON uss_id=car_docente AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
            WHERE car_id='{$idCarga}' AND car.institucion={$config['conf_id_institucion']} AND car.year={$_SESSION["bd"]}");
            $result = mysqli_fetch_array($consulta, MYSQLI_BOTH);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $result;

    }

    /**
     * Verifica el acceso de un estudiante a una carga académica.
     *
     * @param mysqli $conexion Objeto de conexión a la base de datos.
     * @param array $config Configuraciones de la aplicación.
     * @param string $idCarga Identificador de la carga académica.
     * @param string $idEstudiante Identificador del estudiante.
     *
     * @return mysqli_result Devuelve el objeto de la consulta preparada o false en caso de error.
     */
    public static function accesoCargasEstudiante(
        mysqli $conexion, 
        array $config, 
        string $idCarga, 
        string $idEstudiante
    ) {
        $sql = "SELECT * FROM " . BD_ACADEMICA . ".academico_cargas_acceso WHERE carpa_id_carga=? AND carpa_id_estudiante=? AND institucion=? AND year=?";

        $parametros = [$idCarga, $idEstudiante, $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);

        return $resultado;
    }
    
    /**
     * Guardar el acceso de un estudiante a una carga académica.
     *
     * @param mysqli $conexion Objeto de conexión a la base de datos.
     * @param PDO $conexionPDO Objeto de conexión a la base de datos.
     * @param array $config Configuraciones de la aplicación.
     * @param string $idCarga Identificador de la carga académica.
     * @param string $idEstudiante Identificador del estudiante.
     *
     */
    public static function guardarAccesoCargasEstudiante(
        mysqli $conexion, 
        PDO $conexionPDO, 
        array $config, 
        string $idCarga, 
        string $idEstudiante
    ){
        $idInsercion = Utilidades::getNextIdSequence($conexionPDO, BD_ACADEMICA, 'academico_cargas_acceso');

        $sql = "INSERT INTO " . BD_ACADEMICA . ".academico_cargas_acceso (carpa_id, carpa_id_carga, carpa_id_estudiante, carpa_primer_acceso, carpa_ultimo_acceso, carpa_cantidad, institucion, year) VALUES (?, ?, ?, NOW(), NOW(), 1, ?, ?)";

        $parametros = [$idInsercion, $idCarga, $idEstudiante, $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }
    
    /**
     * Actualizar el acceso de un estudiante a una carga académica.
     *
     * @param mysqli $conexion Objeto de conexión a la base de datos.
     * @param array $config Configuraciones de la aplicación.
     * @param string $idCarga Identificador de la carga académica.
     * @param string $idEstudiante Identificador del estudiante.
     *
     */
    public static function actualizarAccesoCargasEstudiante(
        mysqli $conexion, 
        array $config, 
        string $idCarga, 
        string $idEstudiante
    ){
        $sql = "UPDATE " . BD_ACADEMICA . ".academico_cargas_acceso SET carpa_ultimo_acceso=NOW(), carpa_cantidad=carpa_cantidad+1 WHERE carpa_id_carga=? AND carpa_id_estudiante=? AND institucion=? AND year=?";

        $parametros = [$idCarga, $idEstudiante, $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }
    
    /**
     * Traer horarios de una carga académica.
     *
     * @param mysqli $conexion Objeto de conexión a la base de datos.
     * @param array $config Configuraciones de la aplicación.
     * @param string $idCarga Identificador de la carga académica.
     *
     */
    public static function traerHorariosCargas(
        mysqli $conexion, 
        array $config, 
        string $idCarga
    ){
        $sql = "SELECT * FROM " . BD_ACADEMICA . ".academico_horarios WHERE hor_id_carga=? AND hor_estado=1 AND institucion=? AND year=?";

        $parametros = [base64_decode($idCarga), $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);

        return $resultado;
    }
    
    /**
     * Guardar horarios de una carga académica.
     *
     * @param mysqli $conexion Objeto de conexión a la base de datos.
     * @param array $config Configuraciones de la aplicación.
     * @param string $dia Identificador de la carga académica.
     * @param array $POST Configuraciones de la aplicación.
     *
     */
    public static function guardarHorariosCargas(
        mysqli $conexion, 
        PDO $conexionPDO, 
        array $config, 
        string $dia, 
        array $POST
    ){
        $codigo = Utilidades::getNextIdSequence($conexionPDO, BD_ACADEMICA, 'academico_horarios');

        $sql = "INSERT INTO " . BD_ACADEMICA . ".academico_horarios(hor_id, hor_id_carga, hor_dia, hor_desde, hor_hasta, institucion, year) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $parametros = [$codigo, $POST["idH"], $dia, $POST["inicioH"], $POST["finH"], $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
        
        return $codigo;
    }
    
    /**
     * Traer datos de un horario.
     *
     * @param mysqli $conexion Objeto de conexión a la base de datos.
     * @param array $config Configuraciones de la aplicación.
     * @param string $idHorario Identificador del horario.
     *
     * @return mysqli_result|false Devuelve el resultado de la consulta o false en caso de error.
     */
    public static function traerDatosHorarios(
        mysqli $conexion, 
        array $config, 
        string $idHorario
    ){
        $sql = "SELECT hor_id_carga, hor_dia, hor_desde, hor_hasta FROM " . BD_ACADEMICA . ".academico_horarios WHERE hor_id=? AND institucion=? AND year=?";

        $parametros = [base64_decode($idHorario), $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
        
        return $resultado;
    }
    
    /**
     * Actualizar horarios de una carga académica.
     *
     * @param mysqli $conexion Objeto de conexión a la base de datos.
     * @param array $config Configuraciones de la aplicación.
     * @param array $POST Configuraciones de la aplicación.
     *
     */
    public static function actualizarHorariosCargas(
        mysqli $conexion, 
        array $config, 
        array $POST
    ){
        $sql = "UPDATE " . BD_ACADEMICA . ".academico_horarios SET hor_dia=?, hor_desde=?, hor_hasta=? WHERE hor_id=? AND institucion=? AND year=?";

        $parametros = [$POST["diaH"], $POST["inicioH"], $POST["finH"], $POST["idH"], $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }
    
    /**
     * Eliminar un horario.
     *
     * @param mysqli $conexion Objeto de conexión a la base de datos.
     * @param array $config Configuraciones de la aplicación.
     * @param string $idHorario Identificador del horario.
     *
     */
    public static function eliminarHorarios(
        mysqli $conexion, 
        array $config, 
        string $idHorario
    ){
        $sql = "UPDATE " . BD_ACADEMICA . ".academico_horarios SET hor_estado=0 WHERE hor_id=? AND institucion=? AND year=?";

        $parametros = [base64_decode($idHorario), $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }
    
    /**
     * Listar todas las cargas.
     *
     * @param mysqli $conexion
     * @param array $config
     * @param string $filtroMT
     * @param string $filtro
     * @param string $order
     * @param string $limit
     * @param string $valueIlike - Valor String que se utilizara para biuscar por cualuqier parametro definido (puede ser nulo).
     *
     */
    public static function listarCargas(
        mysqli $conexion, 
        array $config, 
        string $filtroMT = "", 
        string $filtro = "", 
        string $order = "car_id", 
        string $limit = "LIMIT 0, 2000",
        string $valueIlike = "" 

    ){
        if(!empty($valueIlike)){
            $busqueda=$valueIlike;
            $filtro .= " AND (
                 car_id LIKE '%" . $busqueda . "%' 
                OR uss_nombre LIKE '%".$busqueda."%' 
                OR uss_nombre2 LIKE '%".$busqueda."%' 
                OR uss_apellido1 LIKE '%".$busqueda."%' 
                OR uss_apellido2 LIKE '%".$busqueda."%' 
                OR gra_nombre LIKE '%" . $busqueda . "%' 
                OR mat_nombre LIKE '%" . $busqueda . "%'
                OR CONCAT(TRIM(uss_nombre), ' ',TRIM(uss_apellido1), ' ', TRIM(uss_apellido2)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(uss_nombre), TRIM(uss_apellido1), TRIM(uss_apellido2)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(uss_nombre), ' ', TRIM(uss_apellido1)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(uss_nombre), TRIM(uss_apellido1)) LIKE '%".$busqueda."%'
            )";
        }
        try {
            $sql="SELECT * FROM ".BD_ACADEMICA.".academico_cargas car
            INNER JOIN ".BD_ACADEMICA.".academico_grados gra ON gra_id=car_curso AND gra.institucion={$config['conf_id_institucion']} AND gra.year={$_SESSION["bd"]} {$filtroMT}
            LEFT JOIN ".BD_ACADEMICA.".academico_grupos gru ON gru.gru_id=car_grupo AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$_SESSION["bd"]}
            LEFT JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$_SESSION["bd"]}
            LEFT JOIN ".BD_GENERAL.".usuarios uss ON uss_id=car_docente AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
            WHERE car.institucion={$config['conf_id_institucion']} AND car.year={$_SESSION["bd"]} {$filtro}
            ORDER BY {$order}
            {$limit};";
            $consulta=mysqli_query($conexion,$sql);
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
        return $consulta;
    }
    
    /**
     * Este metodo verifica si el estudiante está matriculado en cursos de extensión o complementarios
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEstudiante
     * @param string $idCarga
     * 
     * @return int $num
     */
    public static function validarCursosComplementario(mysqli $conexion, array $config, string $idEstudiante, string $idCarga){
        $num=0;
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_cargas_estudiantes WHERE carpest_carga='".$idCarga."' AND carpest_estudiante='".$idEstudiante."' AND carpest_estado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        $num = mysqli_num_rows($consulta);

        return $num;
    }

    /**
     * Este metodo me elimina un tipo de nota
     * @param mysqli    $conexion
     * @param array     $config
     * @param string    $idTipoNota
    **/
    public static function eliminarTiposNotas (
        mysqli  $conexion, 
        array   $config, 
        string  $idTipoNota
    )
    {
        try {
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_notas_tipos WHERE notip_id='" . $idTipoNota . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
    }

    /**
     * Este metodo me elimina todos los tipos de nota de una categoria
     * @param mysqli    $conexion
     * @param array     $config
     * @param string    $idCategoria
    **/
    public static function eliminarTiposNotasCategoria (
        mysqli  $conexion, 
        array   $config, 
        string  $idCategoria
    )
    {
        try {
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_notas_tipos WHERE notip_categoria='" . $idCategoria . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
    }

    /**
     * Este metodo me guarda un tipo de nota
     * @param mysqli    $conexion
     * @param PDO       $conexionPDO
     * @param array     $config
     * @param array     $POST
    **/
    public static function guardarTipoNota (
        mysqli $conexion,
        PDO    $conexionPDO, 
        array  $config, 
        array  $POST
    )
    {
        $codigo = Utilidades::getNextIdSequence($conexionPDO, BD_ACADEMICA, 'academico_notas_tipos');

        try {
            mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_notas_tipos (notip_id, notip_nombre, notip_desde, notip_hasta,notip_categoria, institucion, year)VALUES('".$codigo."', '" . $POST["nombreCN"] . "'," . $POST["ndesdeCN"] . "," . $POST["nhastaCN"] . ",'" . $POST["idCN"] . "', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
    }

    /**
     * Este metodo me actualiza un tipo de nota
     * @param mysqli    $conexion
     * @param array     $config
     * @param array     $POST
    **/
    public static function actualizarTipoNota (
        mysqli $conexion,
        array  $config, 
        array  $POST
    )
    {

        try {
            mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_notas_tipos SET notip_nombre='" . $POST["nombreCN"] . "', notip_desde=" . $POST["ndesdeCN"] . ", notip_hasta=" . $POST["nhastaCN"] . " WHERE notip_id='" . $POST["idN"] . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
    }

    /**
     * Este metodo me trae los datos de un tipo de nota
     * @param mysqli    $conexion
     * @param array     $config
     * @param string    $idTipoNota
     * 
     * @return array    $resultado
    **/
    public static function traerDatosNotasTipo (
        mysqli  $conexion, 
        array   $config,  
        string  $idTipoNota
    )
    {
        try{
            $consulta=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_notas_tipos WHERE notip_id='".$idTipoNota."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;
    }

}