<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
require_once ROOT_PATH."/main-app/class/Conexion.php";
require_once(ROOT_PATH."/main-app/class/BindSQL.php");

class Areas {

    /**
     * Este metodo me trae datos de una area
    **/
    public static function traerDatosArea (
        array   $config,
        string  $idArea,  
        string  $yearBd = ""
    )
    {
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        $sql = "SELECT * FROM ".BD_ACADEMICA.".academico_areas WHERE ar_id=? AND institucion=? AND year=?";

        $parametros = [$idArea, $config['conf_id_institucion'], $year];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
        $resultado = mysqli_fetch_array($resultado, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo me trae todas las areas de una institución
    **/
    public static function traerAreasInstitucion (
        array   $config,
        string  $idArea     =   "",  
        string  $yearBd     =   ""
    )
    {
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];
        $filtroArea= !empty($idArea) ? "AND ar_id NOT IN ('".$idArea."')" : "";

        $sql = "SELECT * FROM ".BD_ACADEMICA.".academico_areas WHERE institucion=? AND year=? {$filtroArea}  ORDER BY ar_posicion";

        $parametros = [$config['conf_id_institucion'], $year];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);

        return $resultado;
    }

    /**
     * Este metodo me guarda una area
    **/
    public static function guardarArea (
        PDO     $conexionPDO,
        string  $insert,
        array   $parametros
    )
    {
        $campos = explode(',', $insert);
        $numCampos = count($campos);
        $signosPreguntas = str_repeat('?,', $numCampos);
        $signosPreguntas = rtrim($signosPreguntas, ',');

        $codigo = Utilidades::getNextIdSequence($conexionPDO, BD_ACADEMICA, 'academico_areas');
        $parametros[] = $codigo;

        $sql = "INSERT INTO ".BD_ACADEMICA.".academico_areas ({$insert}) VALUES ({$signosPreguntas})";
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);

        return $codigo;
    }

    /**
     * Este metodo me actualiza una area
    **/
    public static function actualizarAreas (
        array   $config,
        string  $idArea,
        string  $update,
        string  $yearBd = ""
    )
    {
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        [$updateSql, $updateValues] = BindSQL::prepararUpdate($update);

        $sql = "UPDATE ".BD_ACADEMICA.".academico_areas SET {$updateSql} WHERE ar_id=? AND institucion=? AND year=?";

        $parametros = array_merge($updateValues, [$idArea, $config['conf_id_institucion'], $year]);
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }

    /**
     * Este metodo me elimina una area por su ID
    **/
    public static function eliminarAreasID (
        array   $config,
        string  $idArea,
        string  $yearBd = ""
    )
    {
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        $sql = "DELETE FROM ".BD_ACADEMICA.".academico_areas WHERE ar_id=? AND institucion=? AND year=?";

        $parametros = [$idArea, $config['conf_id_institucion'], $year];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }

    /**
     * Este metodo me elimina todas las areas de una institución
    **/
    public static function eliminarTodasAreas (
        int     $idInstitucion,
        string  $yearBd = ""
    )
    {
        $year= !empty($yearBd) ? $yearBd : $_SESSION["bd"];

        $sql = "DELETE FROM ".BD_ACADEMICA.".academico_areas WHERE institucion=? AND year=?";

        $parametros = [$idInstitucion, $year];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }
}