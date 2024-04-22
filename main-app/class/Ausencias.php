<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
require_once ROOT_PATH."/main-app/class/Conexion.php";
require_once(ROOT_PATH."/main-app/class/BindSQL.php");

class Ausencias {

    /**
     * Este metodo me trae las ausencias de un estudiante en una clase
    **/
    public static function traerAusenciasClaseEstudiante (
        array   $config,
        string  $idClase,  
        string  $idEstudiante,
        string  $yearBd = ""
    )
    {
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        $sql = "SELECT * FROM ".BD_ACADEMICA.".academico_ausencias WHERE aus_id_clase=? AND aus_id_estudiante=? AND institucion=? AND year=?";

        $parametros = [$idClase, $idEstudiante, $config['conf_id_institucion'], $year];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
        $resultado = mysqli_fetch_array($resultado, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo me suma todas las ausencias de un estudiante en una materia en un periodo
    **/
    public static function sumarAusenciasCarga (
        array   $config,
        string  $idCurso,  
        string  $idMateria,
        int     $periodo,
        string  $idEstudiante,
        string  $yearBd = ""
    )
    {
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        $sql = "SELECT sum(aus_ausencias) as sumAus FROM ".BD_ACADEMICA.".academico_ausencias aus
        INNER JOIN ".BD_ACADEMICA.".academico_cargas car ON car_curso=? AND car_materia=? AND car.institucion=aus.institucion AND car.year=aus.year
        INNER JOIN ".BD_ACADEMICA.".academico_clases cls ON cls.cls_id=aus.aus_id_clase AND cls.cls_id_carga=car_id AND cls.cls_periodo=? AND cls.institucion=aus.institucion AND cls.year=aus.year
        WHERE aus.aus_id_estudiante=? AND aus.institucion=? AND aus.year=?";

        $parametros = [$idCurso, $idMateria, $periodo, $idEstudiante, $config['conf_id_institucion'], $year];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
        $resultado = mysqli_fetch_array($resultado, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo me guarda una ausencia
    **/
    public static function guardarAusencia (
        PDO     $conexionPDO,
        string  $insert,
        array   $parametros
    )
    {
        $campos = explode(',', $insert);
        $numCampos = count($campos);
        $signosPreguntas = str_repeat('?,', $numCampos);
        $signosPreguntas = rtrim($signosPreguntas, ',');

        $codigo = Utilidades::getNextIdSequence($conexionPDO, BD_ACADEMICA, 'academico_ausencias');
        $parametros[] = $codigo;

        $sql = "INSERT INTO ".BD_ACADEMICA.".academico_ausencias({$insert}) VALUES ({$signosPreguntas})";
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }

    /**
     * Este metodo me actualiza una ausencia
    **/
    public static function actualizarAusencia (
        array   $config,
        string  $idAusencia,
        string  $update,
        string  $yearBd = ""
    )
    {
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        [$updateSql, $updateValues] = BindSQL::prepararUpdate($update);

        $sql = "UPDATE ".BD_ACADEMICA.".academico_ausencias SET {$updateSql} WHERE aus_id=? AND institucion=? AND year=?";

        $parametros = array_merge($updateValues, [$idAusencia, $config['conf_id_institucion'], $year]);
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }

    /**
     * Este metodo me elimina una ausencia por su ID
    **/
    public static function eliminarAusenciasID (
        array   $config,
        string  $idAusencia,
        string  $yearBd = ""
    )
    {
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        $sql = "DELETE FROM ".BD_ACADEMICA.".academico_ausencias WHERE aus_id=? AND institucion=? AND year=?";

        $parametros = [$idAusencia, $config['conf_id_institucion'], $year];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }

    /**
     * Este metodo me elimina todas las ausencias de un estudiante
    **/
    public static function eliminarAusenciasEstudiantes (
        array   $config,
        string  $idEstudiante,
        string  $yearBd = ""
    )
    {
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        $sql = "DELETE FROM ".BD_ACADEMICA.".academico_ausencias WHERE aus_id_estudiante=? AND institucion=? AND year=?";

        $parametros = [$idEstudiante, $config['conf_id_institucion'], $year];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }

    /**
     * Este metodo me elimina todas las ausencias de una clase
    **/
    public static function eliminarAusenciasClases (
        array   $config,
        string  $idClase,
        string  $yearBd = ""
    )
    {
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        $sql = "DELETE FROM ".BD_ACADEMICA.".academico_ausencias WHERE aus_id_clase=? AND institucion=? AND year=?";

        $parametros = [$idClase, $config['conf_id_institucion'], $year];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }

    /**
     * Este metodo me elimina todas las ausencias de una institución
    **/
    public static function eliminarAusenciasInstitucion (
        array   $config,
        string  $yearBd = ""
    )
    {
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        $sql = "DELETE FROM ".BD_ACADEMICA.".academico_ausencias WHERE institucion=? AND year=?";

        $parametros = [$config['conf_id_institucion'], $year];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }

    /**
     * Este metodo me consulta el numero de estudiantes registrados
     */
    public static function consultaNumEstudiantesAusencias(
        array  $config,
        array  $datosCargaActual, 
        string $idClase, 
        string $yearBd = ""
    ){
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        if($datosCargaActual['gra_tipo'] == GRADO_INDIVIDUAL) {
            $sql = "SELECT count(*) FROM ".BD_ACADEMICA.".academico_ausencias aus
            INNER JOIN ".BD_ADMIN.".mediatecnica_matriculas_cursos ON matcur_id_curso=? AND matcur_id_grupo=? AND matcur_estado='".ACTIVO."' AND matcur_id_institucion=aus.institucion AND matcur_years=aus.year AND matcur_id_matricula=aus.aus_id_estudiante
            INNER JOIN ".BD_ACADEMICA.".academico_matriculas mat ON (mat_estado_matricula=1 OR mat_estado_matricula=2) AND mat_eliminado=0 AND mat_id=matcur_id_matricula AND mat.institucion=aus.institucion AND mat.year=aus.year
            WHERE aus.aus_id_clase=? AND aus.institucion=? AND aus.year=?";

            $parametros = [$datosCargaActual['car_curso'], $datosCargaActual['car_grupo'], $idClase, $config['conf_id_institucion'], $year];
        } else {
            $sql = "SELECT count(*) FROM ".BD_ACADEMICA.".academico_ausencias aus
            INNER JOIN ".BD_ACADEMICA.".academico_matriculas mat ON mat_grado=? AND mat_grupo=? AND (mat_estado_matricula=1 OR mat_estado_matricula=2) AND mat_eliminado=0 AND mat_id=aus.aus_id_estudiante AND mat.institucion=aus.institucion AND mat.year=aus.year
            WHERE aus.aus_id_clase=? AND aus.institucion=? AND aus.year=?";

            $parametros = [$datosCargaActual['car_curso'], $datosCargaActual['car_grupo'], $idClase, $config['conf_id_institucion'], $year];
        }

        $resultado = BindSQL::prepararSQL($sql, $parametros);

        $resultado = mysqli_fetch_array($resultado, MYSQLI_BOTH);

        return $resultado;
    }
}