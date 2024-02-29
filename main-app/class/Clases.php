<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");

class Clases {
    
    /**
     * Este metodo me trae las preguntas de una clase
     * @param mysqli $conexion
     * @param array $config
     * @param string $idClase
     * @param string $filtro
     * 
     * @return mysqli_result $consulta
     */
    public static function traerPreguntasClases(mysqli $conexion, array $config, string $idClase, string $filtro = ""){
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_clases_preguntas cpp
            INNER JOIN ".BD_GENERAL.".usuarios uss ON uss_id=cpp.cpp_usuario AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
            WHERE cpp.cpp_id_clase='" . $idClase . "' AND cpp.institucion={$config['conf_id_institucion']} AND cpp.year={$_SESSION["bd"]} $filtro ORDER BY cpp.cpp_fecha DESC");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $consulta;
    }
    
    /**
     * Este metodo me elimina las preguntas de una clase
     * @param mysqli $conexion
     * @param array $config
     * @param string $idPregunta
     */
    public static function eliminarPreguntasClases(mysqli $conexion, array $config, string $idPregunta){
        try{
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_clases_preguntas WHERE cpp_id='" . $idPregunta . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }
    
    /**
     * Este metodo me guarda las preguntas de una clase
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
     */
    public static function guardarPreguntasClases(mysqli $conexion, array $config, array $POST){
        $codigo=Utilidades::generateCode("CPP");
        try{
            mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_clases_preguntas(cpp_id, cpp_usuario, cpp_fecha, cpp_id_clase, cpp_contenido, institucion, year)VALUES('".$codigo."', '" . $_SESSION["id"] . "', now(), '" . $POST["idClase"] . "', '" . mysqli_real_escape_string($conexion,$POST["contenido"]) . "', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }

}