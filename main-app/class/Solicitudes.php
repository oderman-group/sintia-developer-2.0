<?php
class Solicitudes {
   
    // Propiedades estáticas para manejar información sobre la cantidad de registros y la paginación
    public static $cantidadRegistros;
    public static $inicio;
    public static $maxRegistro;
   
    /**
     * Listar solicitudes de cancelación.
     *
     * @param array|null $parametros Parámetros adicionales para filtrar la consulta.
     * @param bool $totalizar Indica si se debe realizar una consulta de totalización.
     *
     * @return array Devuelve un conjunto de resultados de la consulta.
     */
    public static function listar($parametros = null,$totalizar =false){
        
        global $conexion;
        global $baseDatosServicios;
        global $config;
        $resultado = [];
        $filtrar="";
        if(!empty($estado)){
            $filtrar='WHERE solcan_estado ='.$estado;
        }        
       
        self::$maxRegistro= $config['conf_num_registros'];
        $pagina=null;
        $limite="";
        if(!empty($_REQUEST["nume"])){
            $pagina=base64_decode($_REQUEST["nume"]);
        }        
        if (is_numeric($pagina)){
            self:: $inicio= (($pagina-1)* self::$maxRegistro);           
        }			     
        else{
            self:: $inicio=0;
        }
        if (!$totalizar){
            $limite="LIMIT ". self::$inicio.",". self::$maxRegistro;
        }       
        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM " . $baseDatosServicios . ".solicitud_cancelacion
            LEFT JOIN ".BD_GENERAL.".usuarios uss ON uss_id = solcan_usuario AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
            LEFT JOIN ".$baseDatosServicios .".instituciones ON ins_id = solcan_institucion
            ".$filtrar."            
             ORDER BY solcan_fecha_creacion
            ".$limite." 
            ");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        self::$cantidadRegistros=mysqli_num_rows($resultado);
       
       
        return $resultado;
    }

    /**
     * Consultar detalles de una solicitud de cancelación por ID.
     *
     * @param int $id Identificador único de la solicitud.
     *
     * @return array Devuelve un conjunto de resultados de la consulta.
     */
    public static function consultar($id){
        
        global $conexion;
        global $baseDatosServicios;
        global $config;
        $resultado = [];
                   
        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM " . $baseDatosServicios . ".solicitud_cancelacion
            LEFT JOIN ".BD_GENERAL.".usuarios uss ON uss_id = solcan_usuario AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
            LEFT JOIN ".$baseDatosServicios .".instituciones ON ins_id = solcan_institucion
            WHERE solcan_id  ='".$id."'         
            ");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
      
        $resultado = mysqli_fetch_array($resultado, MYSQLI_BOTH);
        return $resultado;
    }
}