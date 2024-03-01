<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
class Foros{
    /**
     * Este metodo me consulta los datos de un foro
     * @param mysqli $conexion
     * @param array $config
     * @param string $idForo
     * 
     * @return array $resultado
     */
    public static function consultarDatosForos(mysqli $conexion, array $config, string $idForo){
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_foro WHERE foro_id='".$idForo."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;
    }
    
    /**
     * Este metodo me consulta los datos de un foro
     * @param mysqli $conexion
     * @param array $config
     * @param string $idForo
     * @param string $carga
     * @param int $periodo
     * 
     * @return mysqli_result $consulta
     */
    public static function traerForosDisintos(mysqli $conexion, array $config, string $idForo, string $carga, int $periodo){
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_foro 
            WHERE foro_id_carga='".$carga."' AND foro_periodo='".$periodo."' AND foro_estado=1 AND foro_id!='".$idForo."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}
            ORDER BY foro_id DESC");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $consulta;
    }
    
    /**
     * Este metodo me guarda un foro
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
     * @param string $carga
     * @param int $periodo
     */
    public static function guardarForos(mysqli $conexion, array $config, array $POST, string $carga, int $periodo){
        $codigo=Utilidades::generateCode("FORO");
        try{
            mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_actividad_foro(foro_id, foro_nombre, foro_descripcion, foro_id_carga, foro_periodo, foro_estado, institucion, year)VALUES('".$codigo."', '".mysqli_real_escape_string($conexion,$POST["titulo"])."', '".mysqli_real_escape_string($conexion,$POST["contenido"])."', '".$carga."', '".$periodo."', 1, {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return mysqli_insert_id($conexion);
    }
    
    /**
     * Este metodo me consulta los datos de un foro
     * @param mysqli $conexion
     * @param array $config
     * @param string $carga
     * @param int $periodo
     * 
     * @return mysqli_result $consulta
     */
    public static function traerForos(mysqli $conexion, array $config, string $carga, int $periodo){
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_foro 
            WHERE foro_id_carga='".$carga."' AND foro_periodo='".$periodo."' AND foro_estado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}
            ORDER BY foro_id DESC");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $consulta;
    }
    
    /**
     * Este metodo me actualiza un foro
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
     */
    public static function actualizarForos(mysqli $conexion, array $config, array $POST){
        try{
            mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_actividad_foro SET foro_nombre='".mysqli_real_escape_string($conexion,$POST["titulo"])."', foro_descripcion='".mysqli_real_escape_string($conexion,$POST["contenido"])."' WHERE foro_id='".$POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }
    
    /**
     * Este metodo me elimina un foro
     * @param mysqli $conexion
     * @param array $config
     * @param string $idForo
     */
    public static function eliminarForos(mysqli $conexion, array $config, string $idForo){
        try{
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_foro WHERE foro_id='".$idForo."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }
    
    /**
     * Este metodo me consulta los comentarios de un foro
     * @param mysqli $conexion
     * @param array $config
     * @param string $idForo
     * @param string $filtro
     * 
     * @return mysqli_result $consulta
     */
    public static function traerComentariosForos(mysqli $conexion, array $config, string $idForo, string $filtro = ''){
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_foro_comentarios com
            INNER JOIN ".BD_GENERAL.".usuarios uss ON uss_id=com_id_estudiante AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
            WHERE com_id_foro='".$idForo."' AND com.institucion={$config['conf_id_institucion']} AND com.year={$_SESSION["bd"]}
            $filtro
            ORDER BY com_id DESC");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $consulta;
    }
    
    /**
     * Este metodo me elimina un comentario
     * @param mysqli $conexion
     * @param array $config
     * @param string $idComentario
     */
    public static function eliminarComentario(mysqli $conexion, array $config, string $idComentario){
        try{
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_foro_comentarios WHERE com_id='" . $idComentario . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }
    
    /**
     * Este metodo me elimina un comentario
     * @param mysqli $conexion
     * @param array $config
     */
    public static function eliminarTodosComentario(mysqli $conexion, array $config){
        try{
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_foro_comentarios WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }
    
    /**
     * Este metodo me elimina un comentario
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEstudiante
     */
    public static function eliminarComentarioEstudiante(mysqli $conexion, array $config, string $idEstudiante){
        try{
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_foro_comentarios WHERE com_id_estudiante='" . $idEstudiante . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }
    
    /**
     * Este metodo me elimina un comentario
     * @param mysqli $conexion
     * @param array $config
     * @param string $idForo
     */
    public static function eliminarComentarioForo(mysqli $conexion, array $config, string $idForo){
        try{
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_foro_comentarios WHERE com_id_foro='" . $idForo . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }
    
    /**
     * Este metodo me guarda un comentario
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
     */
    public static function guardarComentario(mysqli $conexion, array $config, array $POST){
        $codigo=Utilidades::generateCode("COM");
        try{
            mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_actividad_foro_comentarios(com_id, com_id_foro, com_descripcion, com_id_estudiante, com_fecha, institucion, year)VALUES('".$codigo."', '" . mysqli_real_escape_string($conexion,$POST["foro"]) . "', '" . mysqli_real_escape_string($conexion,$POST["contenido"]) . "', '" . $_SESSION["id"] . "', now(), {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return mysqli_insert_id($conexion);
    }
    
    /**
     * Este metodo me trae las respuesta de un comentario
     * @param mysqli $conexion
     * @param array $config
     * @param string $idComentario
     * 
     * @return mysqli_result $consulta
     */
    public static function consultarRespuestas(mysqli $conexion, array $config, string $idComentario){
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_foro_respuestas fore
            INNER JOIN ".BD_GENERAL.".usuarios uss ON uss_id=fore.fore_id_estudiante AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
            WHERE fore.fore_id_comentario='".$idComentario."' AND fore.institucion={$config['conf_id_institucion']} AND fore.year={$_SESSION["bd"]}
            ORDER BY fore.fore_id ASC
            ");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        return $consulta;
    }
    
    /**
     * Este metodo me elimina las respuesta de un comentario
     * @param mysqli $conexion
     * @param array $config
     * @param string $idComentario
     */
    public static function eliminarRespuestaComentario(mysqli $conexion, array $config, string $idComentario){
        try{
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_foro_respuestas WHERE fore_id_comentario='" . $idComentario . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }
    
    /**
     * Este metodo me elimina una respuesta
     * @param mysqli $conexion
     * @param array $config
     * @param string $idRespuesta
     */
    public static function eliminarRespuesta(mysqli $conexion, array $config, string $idRespuesta){
        try{
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_foro_respuestas WHERE fore_id='" . $idRespuesta . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }
    
    /**
     * Este metodo me elimina todas las respuestas
     * @param mysqli $conexion
     * @param array $config
     */
    public static function eliminarTodasRespuestas(mysqli $conexion, array $config){
        try{
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_foro_respuestas WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }
    
    /**
     * Este metodo me elimina las respuestas de un estudiante
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEstudiante
     */
    public static function eliminarRespuestaEstudiante(mysqli $conexion, array $config, string $idEstudiante){
        try{
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_foro_respuestas WHERE fore_id_estudiante='" . $idEstudiante . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }
    
    /**
     * Este metodo me guarda una respuesta
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
     */
    public static function guardarRespuesta(mysqli $conexion, array $config, array $POST){
        $codigo=Utilidades::generateCode("FOR");
        try{
            mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_actividad_foro_respuestas(fore_id, fore_id_estudiante, fore_id_comentario, fore_fecha, fore_respuesta, institucion, year)VALUES('".$codigo."', '" . $_SESSION["id"] . "', '" . $POST["comentario"] . "', now(), '" . mysqli_real_escape_string($conexion,$POST["contenido"]) . "', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return mysqli_insert_id($conexion);
    }
}