<?php
class Servicios
{
    public static function getSql($sql)// funcion para obtener un dato especifico de una consulta
    {
        global $conexion;
        try {
            $resulsConsulta = mysqli_query($conexion, $sql." limit 1");
            
        } catch (Exception $e) {
            echo "Excepción catpurada: " . $e->getMessage();
            exit();
        }
        return mysqli_fetch_array($resulsConsulta, MYSQLI_BOTH);
    }

    public static function selectSql($sql,$limite ='LIMIT 20',$esArreglo=true) // funcion para obtener datos en un array de una consulta
    {
        global $conexion;
        try {
            $resulsConsulta = mysqli_query($conexion, $sql.' '.$limite);
            if($resulsConsulta->num_rows>0){
                $index=0;
                if(is_null($esArreglo) || $esArreglo){
                    while($fila=$resulsConsulta->fetch_assoc()){
                        $arraysDatos[$index]=$fila;
                        $index++;
                    } 
                    return $arraysDatos;
                }else{
                    return $resulsConsulta;
                }               
             }
            
        } catch (Exception $e) {
            echo "Excepción catpurada: " . $e->getMessage();
            exit();
        }
    }

    public static function insertSql($sql) { // funcion para insertar en una tabla 
        global $conexion;
        try {
            mysqli_query($conexion, $sql);
            return mysqli_insert_id($conexion);
        } catch (Exception $e) {
            echo "Excepción catpurada: " . $e->getMessage();
            exit();
        }        
    }

    public static function updateSql($sql) // funcion para actualizar Insert en una tabla 
    {
        global $conexion;
        try {
            mysqli_query($conexion, $sql);
        } catch (Exception $e) {
            echo "Excepción catpurada: " . $e->getMessage();
            exit();
        }
    }

    public static function buildSelectSql($parametrosArray=null)// funcion para construir y enviar un Sql teniendo en cuenta los parametros
    {
      $sqlInicial=$parametrosArray["sqlInicial"];
      $parametrosValidos=$parametrosArray["parametrosValidos"];
      if($parametrosArray && count($parametrosArray)>0){
        $sqlInicial=Servicios::concatenarWhereAnd($sqlInicial,$parametrosValidos,$parametrosArray);
        $andPersonalizado=$parametrosArray['and'];
      };
      $sqlFinal =$parametrosArray["sqlFinal"];;     
      $sql=$sqlInicial." ".$andPersonalizado." ".$sqlFinal;
      $limite=$parametrosArray["limite"];
      $esArreglo=$parametrosArray["arreglo"];
      return Servicios::selectSql($sql,$limite,$esArreglo);
    }

    public static function concatenarWhereAnd($sqlInicial,$parametrosValidos,$parametrosArray)// funcion para concatenar los parametros WHERE o AND en una Consulta
    {
        $contador=0;//contará cuantos parametros validos existen
        foreach($parametrosValidos as $clave => $parametro){
            $valor=$parametrosArray[$parametro];
            if(!is_null($valor)){
               $contador++;
               if(is_numeric($valor)) 
                    $condicion=$parametro." = ".$valor;
                else
                    $condicion=$parametro." = '".$valor."'";

                if ($contador == 1) {
                    $sqlInicial = $sqlInicial . " WHERE ";
                } else {
                    $sqlInicial = $sqlInicial . " AND ";
                }
                $sqlInicial = $sqlInicial . $condicion;
            }
            
        }
        return $sqlInicial." ";
        }
}
