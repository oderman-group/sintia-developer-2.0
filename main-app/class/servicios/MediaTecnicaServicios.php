<?php
require_once("Servicios.php");
class MediaTecnicaServicios extends Servicios
{

  /**
   * Consulta la información de una matricula curso específico.
   *
  * @param array|null $parametrosArray Arreglo de parámetros para filtrar la consulta (opcional).
   *
   * @return array|false Arreglo con la información del curso o false si hay un error.
   */
  public static function consultar($parametrosArray = null)
  {
    $sqlInicial = "SELECT * FROM " . BD_ADMIN . ".mediatecnica_matriculas_cursos";
    if ($parametrosArray && count($parametrosArray) > 0) {
      $parametrosValidos = array('matcur_id_matricula', 'matcur_id_curso', 'matcur_id_institucion', 'matcur_years', 'matcur_id_grupo', 'matcur_estado');
      $sqlInicial = Servicios::concatenarWhereAnd($sqlInicial, $parametrosValidos, $parametrosArray);
    };

    return Servicios::getSql($sqlInicial);
  }

  /**
   * Lista las matrículas de cursos de Media Técnica con información adicional.
   *
   * @param array|null $parametrosArray Arreglo de parámetros para filtrar la consulta (opcional).
   *
   * @return array|mysqli_result|false Arreglo de datos del resultado, objeto mysqli_result o false si hay un error.
   */
  public static function listar($parametrosArray = null)
  {

    $sqlInicial = "SELECT * FROM " . BD_ADMIN . ".mediatecnica_matriculas_cursos";
    if ($parametrosArray && count($parametrosArray) > 0) {
      $parametrosValidos = array('matcur_id_curso', 'matcur_id_matricula', 'matcur_id_institucion', 'matcur_years');
      $sqlInicial = Servicios::concatenarWhereAnd($sqlInicial, $parametrosValidos, $parametrosArray);
    };
    $sqlFinal = "";
    $sql = $sqlInicial . $sqlFinal;
    return Servicios::SelectSql($sql);
  }

  /**
   * Realiza un conteo teniendo encuenta los parametros ingresados.
   *
   * @param array|null $parametrosArray Arreglo de parámetros para filtrar la consulta (opcional).
   *
   * @return array|mysqli_result|false Arreglo de datos del resultado, objeto mysqli_result o false si hay un error.
   */
  public static function contar($parametrosArray = null)
  {
    
    $sqlInicial = "SELECT Count(matcur_id) as cantidad FROM " . BD_ADMIN . ".mediatecnica_matriculas_cursos";
    if ($parametrosArray && count($parametrosArray) > 0) {
      $parametrosValidos = array('matcur_id_curso', 'matcur_id_matricula', 'matcur_id_institucion', 'matcur_years');
      $sqlInicial = Servicios::concatenarWhereAnd($sqlInicial, $parametrosValidos, $parametrosArray);
    };
    $sqlFinal = "";
    $sql = $sqlInicial . $sqlFinal;
    return Servicios::SelectSql($sql)[0]["cantidad"];
  }

  /**
   * Lista las materias de Media Técnica con información adicional.
   *
   * @param array|null $parametrosArray Arreglo de parámetros para filtrar la consulta (opcional).
   *
   * @return array|mysqli_result|false Arreglo de datos del resultado, objeto mysqli_result o false si hay un error.
   */
  public static function listarMaterias($parametrosArray = null)
  {
    global  $config;
    $sqlInicial = "SELECT * FROM " . BD_ADMIN . ".mediatecnica_matriculas_cursos 
      INNER JOIN " . BD_ACADEMICA . ".academico_matriculas mat ON matcur_id_matricula=mat.mat_id AND mat.institucion={$config['conf_id_institucion']} AND mat.year={$_SESSION["bd"]}
      INNER JOIN " . BD_ACADEMICA . ".academico_cargas car ON car_curso=matcur_id_curso AND car_grupo=matcur_id_grupo AND car.institucion={$config['conf_id_institucion']} AND car.year={$_SESSION["bd"]}
      INNER JOIN " . BD_ACADEMICA . ".academico_materias am ON am.mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$_SESSION["bd"]}
      INNER JOIN " . BD_ACADEMICA . ".academico_grados gra ON gra_id=car_curso AND gra.institucion={$config['conf_id_institucion']} AND gra.year={$_SESSION["bd"]}
	    INNER JOIN " . BD_GENERAL . ".usuarios uss ON uss_id=car_docente AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
      ";
    if ($parametrosArray && count($parametrosArray) > 0) {
      $parametrosValidos = array('matcur_id_matricula', 'matcur_id_curso', 'matcur_id_institucion', 'matcur_years');
      $sqlInicial = Servicios::concatenarWhereAnd($sqlInicial, $parametrosValidos, $parametrosArray);
    };
    $sqlFinal = "";
    $sql = $sqlInicial . $sqlFinal;
    return Servicios::SelectSql($sql);
  }

  /**
   * Lista los estudiantes de Media Técnica con información adicional.
   *
   * @param array|null $parametrosArray Arreglo de parámetros para filtrar la consulta (opcional).
   * @param string $yearBd Año de la base de datos (opcional).
   *
   * @return array|mysqli_result|false Arreglo de datos del resultado, objeto mysqli_result o false si hay un error.
   */
  public static function listarEstudiantes(
    $parametrosArray = null,
    string $yearBd    = ''
  ) {
    global  $config;
    $year = !empty($yearBd) ? $yearBd : $_SESSION["bd"];
    
    $select = !empty($parametrosArray['select']) ? $parametrosArray['select'] : "*";

    $sqlInicial = "SELECT 
                   $select
                   FROM " . BD_ADMIN . ".mediatecnica_matriculas_cursos
                   
                   LEFT JOIN " . BD_ACADEMICA . ".academico_matriculas mat 
                   ON  mat.mat_id          = matcur_id_matricula
                   AND mat.institucion     = matcur_id_institucion
                   AND mat.year            = matcur_years

                   LEFT JOIN " . BD_ACADEMICA . ".academico_grados gra 
                   ON gra_id           = matcur_id_curso 
                   AND gra.institucion = matcur_id_institucion 
                   AND gra.year        = matcur_years

                   LEFT JOIN " . BD_ACADEMICA . ".academico_grupos gru 
                   ON gru.gru_id       = matcur_id_grupo 
                   AND gru.institucion = matcur_id_institucion
                   AND gru.year        = matcur_years

                   LEFT JOIN " . BD_GENERAL . ".usuarios uss 
                   ON uss_id           = mat.mat_id_usuario 
                   AND uss.institucion = matcur_id_institucion
                   AND uss.year        = matcur_years

                   LEFT JOIN ".BD_GENERAL.".usuarios  acud
                   ON acud.institucion          = matcur_id_institucion
						       AND acud.year                = matcur_years
						       AND acud.uss_id              = mat.mat_acudiente

                   LEFT JOIN " . BD_ADMIN . ".opciones_generales 
                   ON ogen_id          = mat.mat_genero";
    if ($parametrosArray && count($parametrosArray) > 0) {
      $grupo = "";
      if (!empty($parametrosArray['matcur_id_grupo'])) {
        $grupo = 'matcur_id_grupo';
      }
      $parametrosValidos = array('matcur_id_matricula', 'matcur_id_curso', 'matcur_id_institucion', 'matcur_years','mat_eliminado', $grupo);
      $sqlInicial = Servicios::concatenarWhereAnd($sqlInicial, $parametrosValidos, $parametrosArray);
    };
    $andPersonalizado = !empty($parametrosArray['and']) ? $parametrosArray['and'] : "";
    $sqlFinal = "ORDER BY mat_grado, mat_grupo, mat_primer_apellido, mat_segundo_apellido, mat_nombres";
    $limite = !empty($parametrosArray['limite']) ? $parametrosArray['limite'] : "";
    $esArreglo = !empty($parametrosArray['arreglo']) ? $parametrosArray['arreglo'] : "";
    $sql = $sqlInicial . " " . $andPersonalizado . " " . $sqlFinal;
    return Servicios::SelectSql($sql, $limite, $esArreglo);
  }

  /**
   * Edita la información de matrículas y cursos de Media Técnica.
   *
   * @param string $idMatricula ID de la matrícula.
   * @param array $cursosId Arreglo con los IDs de los cursos.
   * @param array $config Configuración de la aplicación.
   * @param int|null $idGrupo ID del grupo (opcional).
   *
   * @return void
   */
  public static function editar($idMatricula, $cursosId, $config, $idGrupo = NULL)
  {
    
    Servicios::UpdateSql(
      "DELETE FROM " . BD_ADMIN . ".mediatecnica_matriculas_cursos 
            WHERE matcur_id_matricula ='" . $idMatricula . "'
            AND matcur_id_institucion =" . (int)$config['conf_id_institucion'] . "
            AND matcur_years          =" . (int)$config['conf_agno'] . "
            "
    );
    MediaTecnicaServicios::guardar($idMatricula, $cursosId, $config, $idGrupo);
  }

  /**
   * Guarda la información de matrículas y cursos de Media Técnica.
   *
   * @param string $idMatricula ID de la matrícula.
   * @param array $arregloCursos Arreglo con los IDs de los cursos.
   * @param array $config Configuración de la aplicación.
   * @param int|null $idGrupo ID del grupo (opcional).
   *
   * @return void
   */
  public static function guardar($idMatricula, $arregloCursos, $config, $idGrupo = NULL, $estado = ESTADO_CURSO_PRE_INSCRITO)
  {
    
    foreach ($arregloCursos as $clave => $curso) {
      Servicios::InsertSql(
        " INSERT INTO " . BD_ADMIN . ".mediatecnica_matriculas_cursos(
                 matcur_id_curso, 
                 matcur_id_matricula,
                 matcur_id_institucion,
                 matcur_years,
                 matcur_id_grupo,
                 matcur_estado
                 )
                 VALUES
                 (
                  '" . $curso . "',
                  '" . $idMatricula . "',
                  '" . $config['conf_id_institucion'] . "',
                  '" . $config['conf_agno'] . "',
                  '" . $idGrupo . "',
                  '" . $estado . "'
                 )"
      );
    }
  }

  /**
   * Guarda la información de matrículas y cursos de Media resiviendo un array.
   *
   * @param string $idMatricula ID de la matrícula.
   * @param array $arregloCursos Arreglo con los IDs de los cursos.
   * @param array $config Configuración de la aplicación.
   *
   * @return void
   */
  public static function guardarJson($arreglo, $config)
  {
    
    foreach ($arreglo as $clave => $dato) {
      Servicios::InsertSql(
        " INSERT INTO " . BD_ADMIN . ".mediatecnica_matriculas_cursos(
                 matcur_id_curso, 
                 matcur_id_matricula,
                 matcur_id_institucion,
                 matcur_years,
                 matcur_id_grupo,
                 matcur_estado
                 )
                 VALUES
                 (
                  '" . $dato["curso"] . "',
                  '" . $dato["matricula"] . "',
                  '" . $config['conf_id_institucion'] . "',
                  '" . $config['conf_agno'] . "',
                  '" . $dato["grupo"] . "',
                  '" . $dato["estado"] . "'
                 )"
      );
    }
  }
  /**
   * Elimina la existencia de una matrícula en un curso de Media Técnica.
   *
   * @param int $idCursos ID del curso.
   * @param array $config Configuración de la aplicación.
   *
   * @return void
   */
  public static function eliminarPorCurso($idMatricula, $idCursos, $institucion = null, $year = null)
  {
    global  $config;
    $institucion = empty($institucion) ? $config['conf_id_institucion'] : $institucion;
    $year = empty($year) ? $config['conf_agno'] : $year;

    Servicios::UpdateSql(
      "DELETE FROM " . BD_ADMIN . ".mediatecnica_matriculas_cursos 
            WHERE matcur_id_matricula ='" . $idMatricula . "'
            AND matcur_id_curso ='" . $idCursos . "'
            AND matcur_id_institucion =" . (int)$institucion . "
            AND matcur_years          =" . (int)$year . "
            "
    );
  }
  /**
   * Edita la información de matrículas y cursos de Media Técnica.
   *
   * @param string $idMatricula ID de la matrícula.
   * @param array $cursosId Arreglo con los IDs de los cursos.
   * @param array $config Configuración de la aplicación.
   * @param int|null $idGrupo ID del grupo (opcional).
   *  @param string|null $estado estado de la asignacion (opcional).
   *
   * @return void
   */
  public static function editarporCurso($idMatricula, $idCurso, $idGrupo = 1, $estado = ESTADO_CURSO_PRE_INSCRITO, $institucion = null, $year = null)
  {
    global  $config;
    $institucion = empty($institucion) ? $config['conf_id_institucion'] : $institucion;
    $year = empty($year) ? $config['conf_agno'] : $year;
    Servicios::UpdateSql(
      "UPDATE " . BD_ADMIN . ".mediatecnica_matriculas_cursos
            SET matcur_id_grupo ='" . $idGrupo . "',
            matcur_estado ='" . $estado . "'
            WHERE matcur_id_matricula ='" . $idMatricula . "'
            AND matcur_id_curso ='" . $idCurso . "'
            AND matcur_id_institucion =" . (int)$institucion . "
            AND matcur_years          =" . (int)$year . "
            "
    );
  }
  /**
   * Elimina la existencia de una matrícula en un curso de Media Técnica.
   *
   * @param int $idCursos ID del curso.
   * @param array $config Configuración de la aplicación.
   *
   * @return void
   */
  public static function eliminarExistenciaEnCursoMT($idCursos, $config)
  {
    global  $conexion;

    try {
      $consulta = mysqli_query($conexion, "DELETE FROM " . BD_ADMIN . ".mediatecnica_matriculas_cursos WHERE matcur_id_curso='" . $idCursos . "' AND matcur_id_institucion='" . $config['conf_id_institucion'] . "' AND matcur_years='" . $config['conf_agno'] . "'");
    } catch (Exception $e) {
      echo "Excepción catpurada: " . $e->getMessage();
      exit();
    }
  }

  /**
   * Guarda la información de matrículas y cursos de Media Técnica por curso específico.
   *
   * @param int $idMatricula ID de la matrícula.
   * @param int $idCurso ID del curso.
   * @param array $config Configuración de la aplicación.
   * @param int|null $idGrupo ID del grupo (opcional).
   *
   * @return void
   */
  public static function guardarPorCurso($idMatricula, $idCurso, $idGrupo = 1, $estado = ESTADO_CURSO_PRE_INSCRITO, $institucion = null, $year = null)
  {

    global  $conexion, $config;
    $institucion = empty($institucion) ? $config['conf_id_institucion'] : $institucion;
    $year = empty($year) ? $config['conf_agno'] : $year;
    mysqli_query(
      $conexion,
      " INSERT INTO " . BD_ADMIN . ".mediatecnica_matriculas_cursos(
                 matcur_id_curso, 
                 matcur_id_matricula,
                 matcur_id_institucion,
                 matcur_years,
                 matcur_id_grupo,
                 matcur_estado
                 )
                 VALUES
                 (
                  '" . $idCurso . "',
                  '" . $idMatricula . "',
                  '" . $institucion . "',
                  '" . $year . "',
                  '" . $idGrupo . "',
                  '" . $estado . "'
                 )"
    );
  }

  /**
   * Verifica la existencia de un estudiante en cursos de Media Técnica.
   *
   * @param array $config Configuración de la aplicación.
   * @param int $year Año académico.
   * @param int $estudiante ID del estudiante.
   *
   * @return mysqli_result|false Resultado de la consulta o false si hay un error.
   */
  public static function existeEstudianteMT($config, $year, $estudiante = 0)
  {

    global $conexion;
    $resultado = [];

    try {
      $consulta = mysqli_query($conexion, "SELECT * FROM " . BD_ADMIN . ".mediatecnica_matriculas_cursos 
            INNER JOIN " . BD_ACADEMICA . ".academico_matriculas mat ON mat.mat_id=matcur_id_matricula AND mat.institucion={$config['conf_id_institucion']} AND mat.year={$year}
            WHERE matcur_id_matricula='" . $estudiante . "' AND matcur_id_institucion='" . $config['conf_id_institucion'] . "' AND matcur_years='" . $year . "'");
    } catch (Exception $e) {
      echo "Excepción catpurada: " . $e->getMessage();
      exit();
    }

    return $consulta;
  }

  /**
   * Verifica la existencia de un estudiante en cursos de Media Técnica.
   *

   * @param string $estudiante ID del estudiante.
   * @param string $curso ID del curso.
   * @param array $config Configuración de la aplicación.
   * @param int $year Año académico.
   *
   * @return boolean  Resultado de la consulta o false si hay un error.
   */
  public static function existeEstudianteMTCursos($estudiante, $curso, $config, $year)
  {

    global $conexion;
    $result = false;

    try {
      $consulta = mysqli_query($conexion, "SELECT * FROM " . BD_ADMIN . ".mediatecnica_matriculas_cursos 
            WHERE matcur_id_matricula='" . $estudiante . "' AND matcur_id_curso='" . $curso . "' AND matcur_id_institucion='" . $config['conf_id_institucion'] . "' AND matcur_years='" . $year . "'");

      if (mysqli_num_rows($consulta) > 0) {
        $result = true;
      }
    } catch (Exception $e) {
      echo "Excepción catpurada: " . $e->getMessage();
      exit();
    }

    return $result;
  }

  /**
   * Genera un informe del estado de los estudiantes de Media Técnica.
   *
   * @param array $config Configuración de la aplicación.
   * @param string $filtro Filtro opcional para limitar los resultados.
   *
   * @return mysqli_result|false Resultado de la consulta o false si hay un error.
   */
  public static function reporteEstadoEstudiantesMT($config, $filtro = "")
  {

    global $conexion;

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
            FROM ".BD_ADMIN.".mediatecnica_matriculas_cursos mt 
            INNER JOIN ".BD_ACADEMICA.".academico_matriculas am ON mt.matcur_id_matricula=am.mat_id AND am.institucion={$config['conf_id_institucion']} AND am.year={$_SESSION["bd"]}
            LEFT JOIN ".BD_ACADEMICA.".academico_grupos ag ON mt.matcur_id_grupo=ag.gru_id AND ag.institucion={$config['conf_id_institucion']} AND ag.year={$_SESSION["bd"]}
            LEFT JOIN ".BD_ACADEMICA.".academico_grados gra ON gra.gra_id=mt.matcur_id_curso AND gra.institucion={$config['conf_id_institucion']} AND gra.year={$_SESSION["bd"]}
            LEFT JOIN ".BD_ADMIN.".opciones_generales og ON og.ogen_id=am.mat_tipo
            LEFT JOIN ".BD_ADMIN.".opciones_generales og2 ON og2.ogen_id=am.mat_genero
            LEFT JOIN ".BD_ADMIN.".opciones_generales og3 ON og3.ogen_id=am.mat_religion
            LEFT JOIN ".BD_ADMIN.".opciones_generales og4 ON og4.ogen_id=am.mat_estrato
            LEFT JOIN ".BD_ADMIN.".opciones_generales og5 ON og5.ogen_id=am.mat_tipo_documento
            LEFT JOIN ".BD_GENERAL.".usuarios uss ON uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]} AND (uss.uss_id=am.mat_acudiente or am.mat_acudiente is null)
            WHERE matcur_id_institucion='" . $config['conf_id_institucion'] . "' AND matcur_estado='" . ACTIVO . "' AND matcur_years='" . $config['conf_agno'] . "' {$filtro}
            GROUP BY mat_id
            ORDER BY mat_primer_apellido,mat_estado_matricula;");
    } catch (Exception $e) {
      echo "Excepción catpurada: " . $e->getMessage();
      exit();
    }

    return $consulta;
  }
}
