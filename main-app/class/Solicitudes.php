<?php
class Solicitudes {
   
    public static $cantidadRegistros;
    public static $inicio;
    public static $maxRegistro;
   

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