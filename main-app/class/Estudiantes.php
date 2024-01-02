<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/servicios/MediaTecnicaServicios.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");

class Estudiantes {

    /**
     * Esta función lista estudiantes según varios parámetros.
     *
     * @param int $eliminados - Indica si se deben incluir estudiantes eliminados (0 o 1).
     * @param string $filtroAdicional - Filtros adicionales para la consulta SQL.
     * @param string $filtroLimite - Límite de resultados para la consulta SQL.
     * @param mixed $cursoActual - Información sobre el curso actual (puede ser nulo).
     *
     * @return mysqli_result - Un array con los resultados de la consulta.
     */
    public static function listarEstudiantes(
        int    $eliminados      = 0, 
        string $filtroAdicional = '', 
        string $filtroLimite    = 'LIMIT 0, 2000',
        $cursoActual=null
    )
    {
        global $conexion, $baseDatosServicios, $config, $arregloModulos;
        $tipoGrado = $cursoActual ? $cursoActual["gra_tipo"] : GRADO_GRUPAL;
        $resultado = [];
        
        try {
            if( $tipoGrado == GRADO_GRUPAL || !array_key_exists(10, $arregloModulos) ){
                $resultado = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_matriculas mat
                LEFT JOIN ".BD_GENERAL.".usuarios uss ON uss_id=mat.mat_id_usuario AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
                LEFT JOIN ".BD_ACADEMICA.".academico_grados gra ON gra_id=mat.mat_grado AND gra.institucion={$config['conf_id_institucion']} AND gra.year={$_SESSION["bd"]}
                LEFT JOIN ".BD_ACADEMICA.".academico_grupos gru ON gru.gru_id=mat.mat_grupo AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$_SESSION["bd"]}
                LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat.mat_genero
                LEFT JOIN ".$baseDatosServicios.".localidad_ciudades ON ciu_id=mat.mat_lugar_nacimiento
                WHERE mat.mat_eliminado IN (0, '".$eliminados."') AND mat.institucion={$config['conf_id_institucion']} AND mat.year={$_SESSION["bd"]}
                ".$filtroAdicional."
                ORDER BY mat.mat_grado, mat.mat_grupo, mat.mat_primer_apellido, mat.mat_segundo_apellido, mat.mat_nombres
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

    /**
     * Función para listar estudiantes en grados.
     *
     * @param string $filtroAdicional Filtro adicional para la consulta SQL.
     * @param string $filtroLimite Filtro de límite para la consulta SQL.
     * @param mixed $cursoActual Curso actual (puede ser nulo).
     * @param int $grupoActual Grupo actual (predeterminado: 1).
     * @param string $yearBd Año de la base de datos (predeterminado: vacío, se toma de la sesión si no se proporciona).
     *
     * @return mysqli_result Arreglo con los resultados de la consulta.
     */
    public static function listarEstudiantesEnGrados(
        string $filtroAdicional = '', 
        string $filtroLimite    = 'LIMIT 0, 2000',
        $cursoActual=null,
        $grupoActual=1,
        string $yearBd    = ''
    )
    {
        global $conexion, $baseDatosServicios, $config;
        $tipoGrado=$cursoActual?$cursoActual["gra_tipo"]:GRADO_GRUPAL;
        $resultado = [];
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        try {
            if($tipoGrado==GRADO_GRUPAL){
                $resultado = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_matriculas mat
                LEFT JOIN ".BD_GENERAL.".usuarios uss ON uss_id=mat.mat_id_usuario AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$year}
                INNER JOIN ".BD_ACADEMICA.".academico_grados gra ON gra_id=mat.mat_grado AND gra.institucion={$config['conf_id_institucion']} AND gra.year={$year}
                INNER JOIN ".BD_ACADEMICA.".academico_grupos gru ON gru.gru_id=mat.mat_grupo AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$year}
                LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat.mat_genero
                WHERE mat.mat_eliminado = 0 AND mat.institucion={$config['conf_id_institucion']} AND mat.year={$year}
                ".$filtroAdicional."
                ORDER BY mat.mat_primer_apellido, mat.mat_segundo_apellido, mat.mat_nombres
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
                $resultado = MediaTecnicaServicios::listarEstudiantes($parametros,$year);
            }
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    /**
     * Función para listar estudiantes con notas faltantes.
     *
     * @param string $carga      ID de la carga académica.
     * @param string $periodo    Período académico.
     * @param string $tipoGrado  Tipo de grado (opcional, por defecto es GRADO_GRUPAL).
     *
     * @return mysqli_result     Resultado de la consulta con la información de los estudiantes.
     */
    public static function listarEstudiantesNotasFaltantes(
        string $carga, 
        string $periodo,
        string $tipoGrado=GRADO_GRUPAL,
    )
    {
        global $conexion, $config;
        $resultado = [];

        if($tipoGrado==GRADO_GRUPAL){
            $sqlString= "SELECT mat.*, sum(act_valor) as acumulado FROM ".BD_ACADEMICA.".academico_matriculas mat
            INNER JOIN ".BD_ACADEMICA.".academico_cargas car ON car_id='".$carga."' AND car.institucion={$config['conf_id_institucion']} AND car.year={$_SESSION["bd"]}
            INNER JOIN ".BD_ACADEMICA.".academico_calificaciones aac ON aac.cal_id_estudiante=mat.mat_id AND aac.institucion={$config['conf_id_institucion']} AND aac.year={$_SESSION["bd"]} 
            LEFT JOIN ".BD_ACADEMICA.".academico_actividades aa ON aa.act_id=aac.cal_id_actividad and aa.act_id_carga=car_id and aa.act_periodo='".$periodo."' and aa.act_registrada=1 and aa.act_estado=1 AND aa.institucion={$config['conf_id_institucion']} AND aa.year={$_SESSION["bd"]}
            WHERE mat.mat_eliminado=0 AND (mat.mat_estado_matricula=1 OR mat.mat_estado_matricula=2) AND mat.mat_grado=car_curso AND mat.mat_grupo=car_grupo AND mat.institucion={$config['conf_id_institucion']} AND mat.year={$_SESSION["bd"]}
            GROUP BY mat.mat_id
            HAVING acumulado < ".PORCENTAJE_MINIMO_GENERAR_INFORME." OR acumulado IS NULL
            ORDER BY mat.mat_primer_apellido, mat.mat_segundo_apellido, mat.mat_nombres";
        }else{
            $sqlString= "SELECT mat.*, sum(act_valor) as acumulado FROM ".BD_ADMIN.".mediatecnica_matriculas_cursos
            INNER JOIN ".BD_ACADEMICA.".academico_matriculas mat ON mat_id=matcur_id_matricula AND mat.mat_eliminado=0 AND (mat.mat_estado_matricula=1 OR mat.mat_estado_matricula=2) AND mat.institucion={$config['conf_id_institucion']} AND mat.year={$_SESSION["bd"]}
            INNER JOIN ".BD_ACADEMICA.".academico_cargas car ON car_id='".$carga."' AND car.institucion={$config['conf_id_institucion']} AND car.year={$_SESSION["bd"]}
            INNER JOIN ".BD_ACADEMICA.".academico_calificaciones aac ON aac.cal_id_estudiante=mat.mat_id AND aac.institucion={$config['conf_id_institucion']} AND aac.year={$_SESSION["bd"]} 
            LEFT JOIN ".BD_ACADEMICA.".academico_actividades aa ON aa.act_id=aac.cal_id_actividad and aa.act_id_carga=car_id and aa.act_periodo='".$periodo."' and aa.act_registrada=1 and aa.act_estado=1 AND aa.institucion={$config['conf_id_institucion']} AND aa.year={$_SESSION["bd"]}
            WHERE matcur_id_curso=car_curso AND matcur_id_grupo=car_grupo AND matcur_id_institucion={$config['conf_id_institucion']} AND matcur_years={$_SESSION["bd"]}
            GROUP BY mat.mat_id
            HAVING acumulado < ".PORCENTAJE_MINIMO_GENERAR_INFORME." OR acumulado IS NULL
            ORDER BY mat.mat_primer_apellido, mat.mat_segundo_apellido, mat.mat_nombres";
        }

        try {
            $resultado = mysqli_query($conexion,$sqlString);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    /**
     * Obtiene la lista de estudiantes para los docentes con los filtros proporcionados.
     *
     * @param string $filtroDocentes Filtro adicional para docentes.
     * @param string $filtroLimite Filtro adicional para limitar resultados.
     *
     * @return mysqli_result|false Resultado de la consulta o false si hay un error.
     */
    public static function listarEstudiantesParaDocentes(string $filtroDocentes = '',string $filtroLimite = '')
    {
        global $conexion, $baseDatosServicios, $config;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_matriculas mat
            LEFT JOIN ".BD_GENERAL.".usuarios uss ON uss_id=mat.mat_id_usuario AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
            LEFT JOIN ".BD_ACADEMICA.".academico_grados gra ON gra_id=mat.mat_grado AND gra.institucion={$config['conf_id_institucion']} AND gra.year={$_SESSION["bd"]}
            LEFT JOIN ".BD_ACADEMICA.".academico_grupos gru ON gru.gru_id=mat.mat_grupo AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$_SESSION["bd"]}
            LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat.mat_genero
            WHERE mat.mat_eliminado=0 AND mat.institucion={$config['conf_id_institucion']} AND mat.year={$_SESSION["bd"]} 
            AND (mat.mat_estado_matricula=1 OR mat.mat_estado_matricula=2)
            ".$filtroDocentes."
            ORDER BY mat.mat_primer_apellido, mat.mat_segundo_apellido, mat.mat_nombres
            $filtroLimite");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    /**
     * Obtiene los datos de un estudiante según su identificación, número de matrícula o documento.
     *
     * @param int|string $estudiante Identificación, número de matrícula o documento del estudiante.
     * @param string $yearBd Año de la base de datos (opcional).
     *
     * @return array|false Arreglo asociativo con los datos del estudiante o false si no se encuentra.
     */
    public static function obtenerDatosEstudiante($estudiante = 0, $yearBd    = '')
    {

        global $conexion, $baseDatosServicios, $config;
        $resultado = [];
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_matriculas mat
            LEFT JOIN ".BD_GENERAL.".usuarios uss ON uss_id=mat.mat_id_usuario AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$year}
            LEFT JOIN ".BD_ACADEMICA.".academico_grados gra ON gra_id=mat.mat_grado AND gra.institucion={$config['conf_id_institucion']} AND gra.year={$year}
            LEFT JOIN ".BD_ACADEMICA.".academico_grupos gru ON gru.gru_id=mat.mat_grupo AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$year}
            LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat.mat_genero
            WHERE (mat.mat_id='".$estudiante."' || mat.mat_documento='".$estudiante."' || mat.mat_matricula='".$estudiante."') AND mat.mat_eliminado=0 AND mat.institucion={$config['conf_id_institucion']} AND mat.year={$year}
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

    /**
     * Este metodo me forma el nombre completo de un estudiante
     * @param array $estudiante
     * 
     * @return string $nombre
     */
    public static function NombreCompletoDelEstudiante(array $estudiante){
        
        $nombre=$estudiante['mat_nombres'];
        if(!empty($estudiante['mat_nombre2'])){
            $nombre.=" ".$estudiante['mat_nombre2'];
        }
        if(!empty($estudiante['mat_segundo_apellido'])){
            $nombre=$estudiante['mat_segundo_apellido']." ".$nombre;
        }
        if(!empty($estudiante['mat_primer_apellido'])){
            $nombre=$estudiante['mat_primer_apellido']." ".$nombre;
        }
        return strtoupper($nombre);
    }

    /**
     * Este metodo me lista los acudidos de un usuario acuiente
     * @param string $acudiente
     * 
     * @return mysqli_result $resultado
     */
    public static function listarEstudiantesParaAcudientes($acudiente)
    {
        global $conexion, $baseDatosServicios, $config;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_matriculas mat
            LEFT JOIN ".BD_GENERAL.".usuarios uss ON uss_id=mat.mat_id_usuario AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
            LEFT JOIN ".BD_ACADEMICA.".academico_grados gra ON gra_id=mat.mat_grado AND gra.institucion={$config['conf_id_institucion']} AND gra.year={$_SESSION["bd"]}
            LEFT JOIN ".BD_ACADEMICA.".academico_grupos gru ON gru.gru_id=mat.mat_grupo AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$_SESSION["bd"]}
            LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat.mat_genero
            INNER JOIN ".BD_GENERAL.".usuarios_por_estudiantes upe ON upe.upe_id_estudiante=mat.mat_id AND upe.upe_id_usuario='".$acudiente."' AND upe.institucion={$config['conf_id_institucion']} AND upe.year={$_SESSION["bd"]}
            WHERE mat.mat_eliminado=0 AND mat.institucion={$config['conf_id_institucion']} AND mat.year={$_SESSION["bd"]} 
            ORDER BY mat.mat_primer_apellido, mat.mat_segundo_apellido, mat.mat_nombres
            ");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    /**
     * Este metodo me lista los estudiante para un estudiante
     * @param string $filtroEstudiante
     * @param array $cursoActual
     * @param string $grupoActual
     * 
     * @return mysqli_result $resultado
     */
    public static function listarEstudiantesParaEstudiantes(string $filtroEstudiantes = '', $cursoActual=null, $grupoActual=1)
    {
        global $conexion, $baseDatosServicios, $config;
        $resultado = [];
        $tipoGrado=$cursoActual?$cursoActual["gra_tipo"]:GRADO_GRUPAL;
        try {
             if($tipoGrado==GRADO_GRUPAL){
            $resultado = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_matriculas mat
            LEFT JOIN ".BD_GENERAL.".usuarios uss ON uss_id=mat.mat_id_usuario AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
            LEFT JOIN ".BD_ACADEMICA.".academico_grados gra ON gra_id=mat.mat_grado AND gra.institucion={$config['conf_id_institucion']} AND gra.year={$_SESSION["bd"]}
            LEFT JOIN ".BD_ACADEMICA.".academico_grupos gru ON gru.gru_id=mat.mat_grupo AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$_SESSION["bd"]}
            LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat.mat_genero
            WHERE mat.mat_eliminado=0 AND mat.institucion={$config['conf_id_institucion']} AND mat.year={$_SESSION["bd"]} 
            AND (mat.mat_estado_matricula=1 OR mat.mat_estado_matricula=2)
            ".$filtroEstudiantes."
            ORDER BY mat.mat_primer_apellido, mat.mat_segundo_apellido, mat.mat_nombres
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

    /**
     * Obtiene los datos de un estudiante según su ID de usuario.
     *
     * @param int $estudianteIdUsuario ID de usuario del estudiante.
     *
     * @return array Arreglo asociativo con los datos del estudiante o un arreglo vacío si no se encuentra.
     */
    public static function obtenerDatosEstudiantePorIdUsuario($estudianteIdUsuario = 0)
    {

        global $conexion, $baseDatosServicios, $config;
        $resultado = [];

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_matriculas mat
            LEFT JOIN ".BD_GENERAL.".usuarios uss ON uss_id=mat.mat_id_usuario AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
            LEFT JOIN ".BD_ACADEMICA.".academico_grados gra ON gra_id=mat.mat_grado AND gra.institucion={$config['conf_id_institucion']} AND gra.year={$_SESSION["bd"]}
            LEFT JOIN ".BD_ACADEMICA.".academico_grupos gru ON gru.gru_id=mat.mat_grupo AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$_SESSION["bd"]}
            LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat.mat_genero
            WHERE mat.mat_id_usuario='".$estudianteIdUsuario."' AND mat.mat_eliminado=0 AND mat.institucion={$config['conf_id_institucion']} AND mat.year={$_SESSION["bd"]}
            ");
            $num = mysqli_num_rows($consulta);
            if($num == 0){
                return $resultado;
            }
            $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;

    }

    /**
     * Este metodo me valida la existencia de un estudiante
     * @param string $estudiante
     * @param string $yearBd
     * 
     * @return int $num
     */
    public static function validarExistenciaEstudiante(
        $estudiante = 0,
        $BD    = '',
        string $yearBd    = ''
    ){

        global $conexion, $config;
        $num = 0;
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_matriculas
            WHERE (mat_id='".$estudiante."' || mat_documento='".$estudiante."') AND mat_eliminado=0 AND institucion={$config['conf_id_institucion']} AND year={$year}");
            $num = mysqli_num_rows($consulta);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $num;

    }

    /**
     * Este metodo me lista los estudiante para la planillas
     * @param int $eliminados
     * @param string $filtroAdicional
     * @param string $yearBd
     * 
     * @return mysqli_result $resultado
     */
    public static function listarEstudiantesParaPlanillas(
        int    $eliminados      = 0, 
        string $filtroAdicional = '', 
        string $yearBd    = ''
    )
    {
        global $conexion, $baseDatosServicios, $config;
        $resultado = [];
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_matriculas mat
            LEFT JOIN ".BD_GENERAL.".usuarios uss ON uss_id=mat.mat_id_usuario AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$year}
            LEFT JOIN ".BD_ACADEMICA.".academico_grados gra ON gra_id=mat.mat_grado AND gra.institucion={$config['conf_id_institucion']} AND gra.year={$year}
            LEFT JOIN ".BD_ACADEMICA.".academico_grupos gru ON gru.gru_id=mat.mat_grupo AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$year}
            LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat.mat_genero
            LEFT JOIN ".$baseDatosServicios.".localidad_ciudades ON ciu_id=mat.mat_lugar_nacimiento
            WHERE mat.mat_eliminado IN (0, '".$eliminados."') AND mat.institucion={$config['conf_id_institucion']} AND mat.year={$year}
            ".$filtroAdicional."
            ORDER BY mat.mat_grado, mat.mat_grupo, mat.mat_primer_apellido, mat.mat_segundo_apellido, mat.mat_nombres");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    /**
     * Este metodo me ordena el nombre de un estudiante
     * @param array $estudiante
     * @param int $orden
     * 
     * @return string $nombre
     */
    public static function NombreCompletoDelEstudianteParaInformes(array $estudiante, $orden){
        
        $nombre=$estudiante['mat_nombres'];
        if(!empty($estudiante['mat_nombre2'])){
            $nombre.=" ".$estudiante['mat_nombre2'];
        }
        if(!empty($estudiante['mat_primer_apellido'])){
            $nombre.=" ".$estudiante['mat_primer_apellido'];
        }
        if(!empty($estudiante['mat_segundo_apellido'])){
            $nombre.=" ".$estudiante['mat_segundo_apellido'];
        }
        
        if($orden==2){
            $nombre=$estudiante['mat_nombres'];
            if(!empty($estudiante['mat_nombre2'])){
                $nombre.=" ".$estudiante['mat_nombre2'];
            }
            if(!empty($estudiante['mat_segundo_apellido'])){
                $nombre=$estudiante['mat_segundo_apellido']." ".$nombre;
            }
            if(!empty($estudiante['mat_primer_apellido'])){
                $nombre=$estudiante['mat_primer_apellido']." ".$nombre;
            }
        }
        return strtoupper($nombre);
    }

    /**
     * Este metodo me actualiza el estado de un estudiante
     * @param string $idEstudiante
     * @param int $estadoMatricula
     */
    public static function ActualizarEstadoMatricula($idEstudiante, $estadoMatricula)
    {
        global $conexion, $config;

        try {
            mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_matriculas SET mat_estado_matricula='".$estadoMatricula."' WHERE mat_id='".$idEstudiante."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción capturada: ".$e->getMessage();
            exit();
        }
    }

    /**
     * Este metodo me registra las matriculas retiradas o restauradas
     * @param string $idEstudiante
     * @param string $motivo
     * @param array $config
     * @param mysqli $conexion
     */
    public static function retirarRestaurarEstudiante($idEstudiante, $motivo, $config, $conexion)
    {
        $codigo=Utilidades::generateCode("MRT");

        try {
            mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_matriculas_retiradas (matret_id, matret_estudiante, matret_fecha, matret_motivo, matret_responsable, institucion, year)VALUES('".$codigo."', '".$idEstudiante."', now(), '".$motivo."', '".$_SESSION["id"]."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
        } catch (Exception $e) {
            echo "Excepción capturada: ".$e->getMessage();
            exit();
        }
    }

    /**
     * Este metodo me trae todos los estudiantes matriculados
     * @param string $filtro
     * @param string $yearBD
     * 
     * @return mysqli_result $resultado
     */
    public static function estudiantesMatriculados(
        string    $filtro      = '',
        string $yearBd    = ''
    )
    {
        global $conexion, $config;
        $resultado = [];
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_matriculas mat 
            INNER JOIN ".BD_ACADEMICA.".academico_grupos gru ON mat.mat_grupo=gru.gru_id AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$year}
            INNER JOIN ".BD_ACADEMICA.".academico_grados gra ON mat.mat_grado=gra_id AND gra.institucion={$config['conf_id_institucion']} AND gra.year={$year} 
            WHERE mat.mat_eliminado=0 AND mat.mat_estado_matricula=1 AND mat.institucion={$config['conf_id_institucion']} AND mat.year={$year} $filtro 
            GROUP BY mat.mat_id
            ORDER BY mat.mat_grupo, mat.mat_primer_apellido");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    /**
     * este metodo me trae los datos de un estudiante para usar en boletines
     * @param string $estudiante
     * @param string $yearBD
     * 
     * @return mysqli_result $resultado
     */
    public static function obtenerDatosEstudiantesParaBoletin(
        string $estudiante      = "",
        string $yearBd    = ''
    )
    {
        global $conexion, $config;
        $resultado = [];
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_matriculas am
            INNER JOIN ".BD_ACADEMICA.".academico_grupos gru ON am.mat_grupo=gru.gru_id AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$year}
            INNER JOIN ".BD_ACADEMICA.".academico_grados gra ON am.mat_grado=gra_id AND gra.institucion={$config['conf_id_institucion']} AND gra.year={$year} 
            WHERE am.mat_id='" . $estudiante."' AND am.institucion={$config['conf_id_institucion']} AND am.year={$year}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    /**
     * Este metodo me valida si un documento esta repetido
     * @param string $documento
     * @param string $idEstudiante
     * 
     * @return int $num
     */
    public static function validarRepeticionDocumento($documento, $idEstudiante)
    {

        global $conexion, $config;
        $num = 0;

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_matriculas
            WHERE mat_id!='".$idEstudiante."' AND mat_documento='".$documento."' AND mat_eliminado=0 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}
            ");
            $num = mysqli_num_rows($consulta);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $num;

    }
    
    /**
     * Este metodo me lista los estudiante MT de un docente
     * @param array $datosCargaActual
     * 
     * @return mysqli_result $resultado
     */
    public static function listarEstudiantesParaDocentesMT(array $datosCargaActual = [])
    {
        global $conexion, $baseDatosServicios, $config;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".mediatecnica_matriculas_cursos
            LEFT JOIN ".BD_ACADEMICA.".academico_matriculas mat ON mat.mat_eliminado=0 AND (mat.mat_estado_matricula=1 OR mat.mat_estado_matricula=2) AND mat.mat_id=matcur_id_matricula AND mat.institucion={$config['conf_id_institucion']} AND mat.year={$_SESSION["bd"]}
            LEFT JOIN ".BD_ACADEMICA.".academico_grados gra ON gra_id=matcur_id_curso AND gra.institucion={$config['conf_id_institucion']} AND gra.year={$_SESSION["bd"]}
            LEFT JOIN ".BD_ACADEMICA.".academico_grupos gru ON gru.gru_id=matcur_id_grupo AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$_SESSION["bd"]}
            LEFT JOIN ".BD_GENERAL.".usuarios uss ON uss_id=mat.mat_id_usuario AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
            LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat.mat_genero
            WHERE matcur_id_curso='".$datosCargaActual['car_curso']."' AND matcur_id_grupo='".$datosCargaActual['car_grupo']."' AND matcur_id_institucion='".$config['conf_id_institucion']."'
            ORDER BY mat.mat_primer_apellido, mat.mat_segundo_apellido, mat.mat_nombres;
            ");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    /**
     * Este metodo me cuenta los estudiante de una carga
     * @param array $datosCargaActual
     * 
     * @return int $cantidad
     */
    public static function contarEstudiantesParaDocentesMT(array $datosCargaActual = [])
    {
        global $conexion, $baseDatosServicios, $config;
        $cantidad = 0;

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".mediatecnica_matriculas_cursos
            LEFT JOIN ".BD_ACADEMICA.".academico_matriculas mat ON mat.mat_eliminado=0 AND (mat.mat_estado_matricula=1 OR mat.mat_estado_matricula=2) AND mat.mat_grupo='".$datosCargaActual['car_grupo']."' AND mat.mat_id=matcur_id_matricula AND mat.institucion={$config['conf_id_institucion']} AND mat.year={$_SESSION["bd"]}
            LEFT JOIN ".BD_ACADEMICA.".academico_grados gra ON gra_id=matcur_id_curso AND gra.institucion={$config['conf_id_institucion']} AND gra.year={$_SESSION["bd"]}
            LEFT JOIN ".BD_ACADEMICA.".academico_grupos gru ON gru.gru_id=matcur_id_grupo AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$_SESSION["bd"]}
            LEFT JOIN ".BD_GENERAL.".usuarios uss ON uss_id=mat.mat_id_usuario AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
            LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat.mat_genero
            WHERE matcur_id_curso='".$datosCargaActual['car_curso']."' AND matcur_id_grupo='".$datosCargaActual['car_grupo']."' AND matcur_id_institucion='".$config['conf_id_institucion']."'
            ORDER BY mat.mat_primer_apellido, mat.mat_segundo_apellido, mat.mat_nombres;
            ");
            $cantidad = mysqli_num_rows($consulta);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $cantidad;
    }

    /**
     * Este metodo me escoge la consulta segun el tipo de curso
     * 
     * @param array $datosCargaActual
     * 
     * @return mysqli_result $consulta
     */
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

    /**
     * Este metodo me lista todos los estudiantes
     * 
     * @param string $where
     * 
     * @return mysqli_result $consulta
     */
    public static function reporteEstadoEstudiantes($where="")
    {

        global $conexion, $baseDatosServicios, $config;

        try {
            $consulta = mysqli_query($conexion, "SELECT mat_matricula, mat_primer_apellido, mat_segundo_apellido, mat_nombres, mat_inclusion, mat_extranjero, mat_documento, uss_usuario, uss_email, uss_celular, uss_telefono, gru_nombre, gra_nombre, og.ogen_nombre as Tipo_est, mat_id,
            IF(mat_acudiente is null,'No',uss_nombre) as nom_acudiente,
            IF(mat_foto is null,'No','Si') as foto, 
            og2.ogen_nombre as genero, og3.ogen_nombre as religion, og4.ogen_nombre as estrato, og5.ogen_nombre as tipoDoc,
            CASE mat_estado_matricula 
                WHEN 1 THEN 'Matriculado' 
                WHEN 2 THEN 'Asistente' 
                WHEN 3 THEN 'Cancelado' 
                WHEN 4 THEN 'No matriculado'
                WHEN 5 THEN 'En inscripción' 
            END AS estado
            FROM ".BD_ACADEMICA.".academico_matriculas am 
            INNER JOIN ".BD_ACADEMICA.".academico_grupos ag ON am.mat_grupo=ag.gru_id AND ag.institucion={$config['conf_id_institucion']} AND ag.year={$_SESSION["bd"]}
            INNER JOIN ".BD_ACADEMICA.".academico_grados gra ON gra.gra_id=am.mat_grado AND gra.institucion={$config['conf_id_institucion']} AND gra.year={$_SESSION["bd"]}
            INNER JOIN $baseDatosServicios.opciones_generales og ON og.ogen_id=am.mat_tipo
            INNER JOIN $baseDatosServicios.opciones_generales og2 ON og2.ogen_id=am.mat_genero
            INNER JOIN $baseDatosServicios.opciones_generales og3 ON og3.ogen_id=am.mat_religion
            INNER JOIN $baseDatosServicios.opciones_generales og4 ON og4.ogen_id=am.mat_estrato
            INNER JOIN $baseDatosServicios.opciones_generales og5 ON og5.ogen_id=am.mat_tipo_documento
            INNER JOIN ".BD_GENERAL.".usuarios uss ON uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]} AND (uss.uss_id=am.mat_acudiente or am.mat_acudiente is null)
            $where AND am.institucion={$config['conf_id_institucion']} AND am.year={$_SESSION["bd"]}
            GROUP BY mat_id
            ORDER BY mat_primer_apellido,mat_estado_matricula;");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $consulta;

    }

    /**
     * Esta función permite insertar los datos de los estudiantes en
     * las matrículas
     * 
     * @param $conexionPDO ConexionPDO
     * @param $POST Array
     * @param $fechaNacimiento String
     * @param $procedencia String
     * @param $pasosMatricula String
     */
    public static function insertarEstudiantes($conexionPDO, $POST, $idEstudianteU, $result_numMat = '', $procedencia = '', $idAcudiente = '')
    {
        global $config;
        $codigoMAT=Utilidades::generateCode("MAT");

        $tipoD = isset($POST["tipoD"]) ? $POST["tipoD"] : "";
        $nDoc = isset($POST["nDoc"]) ? $POST["nDoc"] : "";
        $religion = isset($POST["religion"]) ? $POST["religion"] : "";
        $email = isset($POST["email"]) ? strtolower($POST["email"]) : "";
        $direccion = isset($POST["direccion"]) ? $POST["direccion"] : "";
        $barrio = isset($POST["barrio"]) ? $POST["barrio"] : "";
        $telefono = isset($POST["telefono"]) ? $POST["telefono"] : "";
        $celular = isset($POST["celular"]) ? $POST["celular"] : "";
        $estrato = isset($POST["estrato"]) ? $POST["estrato"] : "";
        $genero = isset($POST["genero"]) ? $POST["genero"] : "";
        $apellido1 = isset($POST["apellido1"]) ? $POST["apellido1"] : "";
        $apellido2 = isset($POST["apellido2"]) ? $POST["apellido2"] : "";
        $nombres = isset($POST["nombres"]) ? $POST["nombres"] : "";
        $grado = isset($POST["grado"]) ? $POST["grado"] : "";
        $grupo = isset($POST["grupo"]) ? $POST["grupo"] : "";
        $tipoEst = isset($POST["tipoEst"]) ? $POST["tipoEst"] : "";
        $lugarD = isset($POST["lugarD"]) ? $POST["lugarD"] : "";
        $matestM = isset($POST["matestM"]) ? $POST["matestM"] : "";
        $folio = isset($POST["folio"]) ? $POST["folio"] : "";
        $codTesoreria = isset($POST["codTesoreria"]) ? $POST["codTesoreria"] : "";
        $va_matricula = isset($POST["va_matricula"]) ? $POST["va_matricula"] : "";
        $inclusion = isset($POST["inclusion"]) ? $POST["inclusion"] : "";
        $extran = isset($POST["extran"]) ? $POST["extran"] : "";
        $tipoSangre = isset($POST["tipoSangre"]) ? $POST["tipoSangre"] : "";
        $eps = isset($POST["eps"]) ? $POST["eps"] : "";
        $celular2 = isset($POST["celular2"]) ? $POST["celular2"] : "";
        $ciudadR = isset($POST["ciudadR"]) ? $POST["ciudadR"] : "";
        $nombre2 = isset($POST["nombre2"]) ? $POST["nombre2"] : "";
        $fNac = isset($POST["fNac"]) ? $POST["fNac"] : "";
        $tipoMatricula = isset($_POST["tipoMatricula"]) ? $POST["tipoMatricula"] : "";

        try{

            $consulta = "INSERT INTO ".BD_ACADEMICA.".academico_matriculas(
                mat_id, mat_matricula, mat_fecha, mat_tipo_documento, 
                mat_documento, mat_religion, mat_email, 
                mat_direccion, mat_barrio, mat_telefono, 
                mat_celular, mat_estrato, mat_genero, 
                mat_fecha_nacimiento, mat_primer_apellido, mat_segundo_apellido, 
                mat_nombres, mat_grado, mat_grupo, 
                mat_tipo, mat_lugar_nacimiento, mat_lugar_expedicion, 
                mat_acudiente, mat_estado_matricula, mat_id_usuario, 
                mat_folio, mat_codigo_tesoreria, mat_valor_matricula, 
                mat_inclusion, mat_extranjero, mat_tipo_sangre, 
                mat_eps, mat_celular2, mat_ciudad_residencia, 
                mat_nombre2, mat_estado_agno, mat_tipo_matricula, institucion, year)
                VALUES(
                :codigo, ".$result_numMat.", now(), :tipoD,
                :nDoc, :religion, :email,
                :direccion, :barrio, :telefono,
                :celular, :estrato, :genero, 
                :fNac, :apellido1, :apellido2, 
                :nombres, :grado, :grupo,
                :tipoEst, '".$procedencia."', :lugarD,
                '".$idAcudiente."', :matestM, '".$idEstudianteU."', 
                :folio, :codTesoreria, :va_matricula, 
                :inclusion, :extran, :tipoSangre, 
                :eps, :celular2, :ciudadR, 
                :nombre2, 3, :tipoMatricula, :idInstitucion, :year
                )";

            $stmt = $conexionPDO->prepare($consulta);

             // Asociar los valores a los marcadores de posición
            $stmt->bindParam(':codigo', $codigoMAT, PDO::PARAM_STR);
            $stmt->bindParam(':tipoD', $tipoD, PDO::PARAM_INT);

            $stmt->bindParam(':nDoc', $nDoc, PDO::PARAM_STR);
            $stmt->bindParam(':religion', $religion);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);

            $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
            $stmt->bindParam(':barrio', $barrio, PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);

            $stmt->bindParam(':celular', $celular, PDO::PARAM_STR);
            $stmt->bindParam(':estrato', $estrato);
            $stmt->bindParam(':genero', $genero);

            $stmt->bindParam(':fNac', $fNac, PDO::PARAM_STR);
            $stmt->bindParam(':apellido1', $apellido1, PDO::PARAM_STR);
            $stmt->bindParam(':apellido2', $apellido2, PDO::PARAM_STR);

            $stmt->bindParam(':nombres', $nombres, PDO::PARAM_STR);
            $stmt->bindParam(':grado', $grado, PDO::PARAM_STR);
            $stmt->bindParam(':grupo', $grupo);

            $stmt->bindParam(':tipoEst', $tipoEst);
            $stmt->bindParam(':lugarD', $lugarD, PDO::PARAM_STR);

            $stmt->bindParam(':matestM', $matestM);

            $stmt->bindParam(':folio', $folio,PDO::PARAM_STR);
            $stmt->bindParam(':codTesoreria', $codTesoreria, PDO::PARAM_STR);
            $stmt->bindParam(':va_matricula', $va_matricula, PDO::PARAM_STR);

            $stmt->bindParam(':inclusion', $inclusion);
            $stmt->bindParam(':extran', $extran);
            $stmt->bindParam(':tipoSangre', $tipoSangre, PDO::PARAM_STR);

            $stmt->bindParam(':eps', $eps, PDO::PARAM_STR);
            $stmt->bindParam(':celular2', $celular2, PDO::PARAM_STR);
            $stmt->bindParam(':ciudadR', $ciudadR, PDO::PARAM_STR);

            $stmt->bindParam(':nombre2', $nombre2, PDO::PARAM_STR);
            $stmt->bindParam(':tipoMatricula', $tipoMatricula, PDO::PARAM_STR);
            $stmt->bindParam(':idInstitucion', $config['conf_id_institucion'], PDO::PARAM_INT);
            $stmt->bindParam(':year', $_SESSION["bd"], PDO::PARAM_STR);

            if ($stmt) {
                $stmt->execute();
                return $codigoMAT;
            } else {
                throw new Exception("Error al preparar la consulta.");
            }
        
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }	

    }


    /**
     * Esta función permite actualizar los datos de los estudiantes
     * 
     * @param $conexionPDO ConexionPDO
     * @param $POST Array
     * @param $fechaNacimiento String
     * @param $procedencia String
     * @param $pasosMatricula String
     */
    public static function actualizarEstudiantes($conexionPDO, $POST, $fechaNacimiento = '', $procedencia = '', $pasosMatricula = '')
    {
        global $config;

        $tipoD = isset($POST["tipoD"]) ? $POST["tipoD"] : "";
        $nDoc = isset($POST["nDoc"]) ? $POST["nDoc"] : "";
        $religion = isset($POST["religion"]) ? $POST["religion"] : "";
        $email = isset($POST["email"]) ? strtolower($POST["email"]) : "";
        $direccion = isset($POST["direccion"]) ? $POST["direccion"] : "";
        $barrio = isset($POST["barrio"]) ? $POST["barrio"] : "";
        $telefono = isset($POST["telefono"]) ? $POST["telefono"] : "";
        $celular = isset($POST["celular"]) ? $POST["celular"] : "";
        $estrato = isset($POST["estrato"]) ? $POST["estrato"] : "";
        $genero = isset($POST["genero"]) ? $POST["genero"] : "";
        $apellido1 = isset($POST["apellido1"]) ? $POST["apellido1"] : "";
        $apellido2 = isset($POST["apellido2"]) ? $POST["apellido2"] : "";
        $nombres = isset($POST["nombres"]) ? $POST["nombres"] : "";
        $grado = isset($POST["grado"]) ? $POST["grado"] : "";
        $grupo = isset($POST["grupo"]) ? $POST["grupo"] : "";
        $tipoEst = isset($POST["tipoEst"]) ? $POST["tipoEst"] : "";
        $lugarD = isset($POST["lugarD"]) ? $POST["lugarD"] : "";
        $matestM = isset($POST["matestM"]) ? $POST["matestM"] : "";
        $matricula = isset($POST["matricula"]) ? $POST["matricula"] : "";
        $folio = isset($POST["folio"]) ? $POST["folio"] : "";
        $codTesoreria = isset($POST["codTesoreria"]) ? $POST["codTesoreria"] : "";
        $va_matricula = isset($POST["va_matricula"]) ? $POST["va_matricula"] : "";
        $inclusion = isset($POST["inclusion"]) ? $POST["inclusion"] : "";
        $extran = isset($POST["extran"]) ? $POST["extran"] : "";
        $NumMatricula = isset($POST["NumMatricula"]) ? $POST["NumMatricula"] : "";
        $estadoAgno = isset($POST["estadoAgno"]) ? $POST["estadoAgno"] : "";
        $tipoSangre = isset($POST["tipoSangre"]) ? $POST["tipoSangre"] : "";
        $eps = isset($POST["eps"]) ? $POST["eps"] : "";
        $celular2 = isset($POST["celular2"]) ? $POST["celular2"] : "";
        $ciudadR = isset($POST["ciudadR"]) ? $POST["ciudadR"] : "";
        $nombre2 = isset($POST["nombre2"]) ? $POST["nombre2"] : "";
        $id = isset($POST["id"]) ? $POST["id"] : "";
        $tipoMatricula = isset($POST["tipoMatricula"]) ? $_POST["tipoMatricula"] : GRADO_GRUPAL;

        try{
            
            $consulta = "UPDATE ".BD_ACADEMICA.".academico_matriculas SET 
            mat_tipo_documento    = :tipoD, 
            mat_documento         = :nDoc, 
            mat_religion          = :religion, 
            mat_email             = :email, 
            mat_direccion         = :direccion, 
            mat_barrio            = :barrio, 
            mat_telefono          = :telefono, 
            mat_celular           = :celular, 
            mat_estrato           = :estrato, 
            mat_genero            = :genero,
            mat_primer_apellido   = :apellido1, 
            mat_segundo_apellido  = :apellido2, 
            mat_nombres           = :nombres, 
            mat_grado             = :grado, 
            mat_grupo             = :grupo, 
            mat_tipo              = :tipoEst,
            mat_lugar_expedicion  = :lugarD,
            mat_estado_matricula  = :matestM, 
            mat_matricula         = :matricula, 
            mat_folio             = :folio, 
            mat_codigo_tesoreria  = :codTesoreria, 
            mat_valor_matricula   = :va_matricula, 
            mat_inclusion         = :inclusion, 
            mat_extranjero        = :extran, 
            mat_fecha             = NOW(), 
            mat_numero_matricula  = :NumMatricula, 
            mat_estado_agno       = :estadoAgno,
            mat_tipo_sangre       = :tipoSangre, 
            mat_eps               = :eps, 
            mat_celular2          = :celular2, 
            mat_ciudad_residencia = :ciudadR,
            mat_lugar_nacimiento  = :procedencia,
            $pasosMatricula
            $fechaNacimiento
            mat_nombre2           = :nombre2,
            mat_tipo_matricula    = :tipoMatricula

            WHERE mat_id = :id AND institucion= :idInstitucion AND year= :year";

            $stmt = $conexionPDO->prepare($consulta);

             // Asociar los valores a los marcadores de posición
            $stmt->bindParam(':tipoD', $tipoD, PDO::PARAM_INT);
            $stmt->bindParam(':nDoc', $nDoc, PDO::PARAM_STR);
            $stmt->bindParam(':religion', $religion);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
            $stmt->bindParam(':barrio', $barrio, PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
            $stmt->bindParam(':celular', $celular, PDO::PARAM_STR);
            $stmt->bindParam(':estrato', $estrato);
            $stmt->bindParam(':genero', $genero);
            $stmt->bindParam(':apellido1', $apellido1, PDO::PARAM_STR);
            $stmt->bindParam(':apellido2', $apellido2, PDO::PARAM_STR);
            $stmt->bindParam(':nombres', $nombres, PDO::PARAM_STR);
            $stmt->bindParam(':grado', $grado, PDO::PARAM_STR);
            $stmt->bindParam(':grupo', $grupo);
            $stmt->bindParam(':tipoEst', $tipoEst);
            $stmt->bindParam(':lugarD', $lugarD, PDO::PARAM_STR);
            $stmt->bindParam(':matestM', $matestM);
            $stmt->bindParam(':matricula', $matricula);
            $stmt->bindParam(':folio', $folio,PDO::PARAM_STR);
            $stmt->bindParam(':codTesoreria', $codTesoreria, PDO::PARAM_STR);
            $stmt->bindParam(':va_matricula', $va_matricula, PDO::PARAM_STR);
            $stmt->bindParam(':inclusion', $inclusion);
            $stmt->bindParam(':extran', $extran);
            $stmt->bindParam(':NumMatricula', $NumMatricula);
            $stmt->bindParam(':estadoAgno', $estadoAgno, PDO::PARAM_INT);
            $stmt->bindParam(':tipoSangre', $tipoSangre, PDO::PARAM_STR);
            $stmt->bindParam(':eps', $eps, PDO::PARAM_STR);
            $stmt->bindParam(':celular2', $celular2, PDO::PARAM_STR);
            $stmt->bindParam(':ciudadR', $ciudadR, PDO::PARAM_STR);
            $stmt->bindParam(':procedencia', $procedencia);
            $stmt->bindParam(':nombre2', $nombre2, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->bindParam(':tipoMatricula', $tipoMatricula, PDO::PARAM_STR);
            $stmt->bindParam(':idInstitucion', $config['conf_id_institucion'], PDO::PARAM_INT);
            $stmt->bindParam(':year', $_SESSION["bd"], PDO::PARAM_STR);

            if ($stmt) {
                $stmt->execute();

                return $stmt;
            } else {
                throw new Exception("Error al preparar la consulta.");
            }
        
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }	

    }

    /**
     * Cuenta el número de estudiantes disponibles para un grupo de docentes, opcionalmente aplicando un filtro.
     *
     * @param string $filtroDocentes (Opcional) - Un filtro para limitar la cuenta de estudiantes a un grupo específico de docentes.
     *
     * @return int - El número de estudiantes disponibles para los docentes después de aplicar el filtro (o el número total de estudiantes si no se proporciona un filtro).
     */
    public static function contarEstudiantesParaDocentes(string $filtroDocentes = '')
    {
        $consulta = self::listarEstudiantesParaDocentes($filtroDocentes);
        $num = mysqli_num_rows($consulta);
        return $num;
    }

    /**
     * Obtiene un listado de estudiantes matriculados en base a un predicado opcional.
     *
     * Esta función realiza una consulta a la base de datos para obtener un listado de estudiantes matriculados.
     *
     * @param string $predicado (Opcional) Una cadena que puede contener condiciones SQL adicionales para filtrar los resultados. Por ejemplo, "AND estado = 'activo'".
     *
     * @return mysqli_result|false Devuelve un objeto `mysqli_result` que contiene el resultado de la consulta si la consulta se realiza con éxito. Devuelve `false` si se produce un error.
     */
    public static function obtenerListadoDeEstudiantes($predicado="")
    {

        global $conexion, $config;

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_matriculas WHERE mat_id=mat_id AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]} $predicado");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $consulta;

    }

    /**
     * Obtiene los datos de estudiante retirado.
     * @param mysqli $conexion
     * @param array $config
     * @param string $id
     * 
     * @return array $resultado
     */
    public static function traerDatosEstudiantesretirados(mysqli $conexion, array $config, string $id)
    {
        $resultado=[];

        try {
            $consulta=mysqli_query($conexion, "SELECT mat_id, mat_estado_matricula, mat_documento, mat_primer_apellido, mat_segundo_apellido, mat_nombres, mat_nombre2, matret_motivo, matret_fecha, uss_nombre, uss_nombre2, uss_apellido1, uss_apellido2, uss_usuario FROM ".BD_ACADEMICA.".academico_matriculas mat
            LEFT JOIN (SELECT * FROM ".BD_ACADEMICA.".academico_matriculas_retiradas matret WHERE matret.institucion={$config['conf_id_institucion']} AND matret.year={$_SESSION["bd"]} ORDER BY matret_id DESC LIMIT 1) AS tabla_retiradas ON tabla_retiradas.matret_estudiante=mat.mat_id
            LEFT JOIN ".BD_GENERAL.".usuarios uss ON uss_id=matret_responsable AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
            WHERE mat_id='".$id."' AND mat.institucion={$config['conf_id_institucion']} AND mat.year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;

    }

}