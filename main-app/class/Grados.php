<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/servicios/GradoServicios.php");
class Grados {

    public static function listarGrados($estado = 1,$tipo =null){
        
        global $conexion, $arregloModulos, $config;
        
        $resultado = [];
        $filtro="";
        if(!is_null($tipo)){
            $filtro="AND gra_tipo ='".$tipo."'";
        }

        if( !array_key_exists(10,$arregloModulos) ) { 
            $filtro="AND gra_tipo ='".GRADO_GRUPAL."'";
        }
         
        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_grados
            WHERE gra_estado IN (1, '".$estado."') AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]} 
            ".$filtro."
            ORDER BY gra_vocal
            ");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function obtenerDatosGrados($grado = 0){
        
        global $conexion, $config;
        
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_grados WHERE gra_id={$grado} AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        return $resultado;
    }

    public static function obtenerGrado($grado = 0){        
            return mysqli_fetch_array(Grados::obtenerDatosGrados($grado));
    }

}