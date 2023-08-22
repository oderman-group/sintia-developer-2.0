<?php
class SysJobs {
    public static function registrar($tipo,array $parametros = [],$buscarParametros=true)
    {
        global $config;
        if($buscarParametros){
            $parametrosBuscar = array(
                "tipo" =>$tipo,
                "responsable" => $_SESSION['id'],
                "parametros" => json_encode($parametros),
                "agno"=>$config['conf_agno'],
                "estado" =>JOBS_ESTADO_PENDIENTE
            );
        }else{
            $parametrosBuscar = array(
                "tipo" =>$tipo,
                "responsable" => $_SESSION['id'],
                "agno"=>$config['conf_agno'],
                "estado" =>JOBS_ESTADO_PENDIENTE
            );
        }
       
        $buscarJobs=self::consultar($parametrosBuscar);
        $cantidad = mysqli_num_rows($buscarJobs);
        $jobsEncontrado = mysqli_fetch_array($buscarJobs, MYSQLI_BOTH);
        if($cantidad<1){
            $idRegistro =self::crear($tipo,$parametros);
            $mensaje="Se realizó exitosamente el proceso de importación de estudiantes con el código ".$idRegistro;
        }else{
            $jobsEncontrado = mysqli_fetch_array($buscarJobs, MYSQLI_BOTH);
            $intentos = intval($jobsEncontrado["job_intentos"])+1;
            $datos = array(
                "intentos" =>$intentos,
                "id" => $jobsEncontrado['job_id']
            );
            
            self::actualizar($datos);
            $idRegistro = $jobsEncontrado["job_id"];
                $mensaje="Se actualizó exitosamente el proceso de importación de estudiantes con el código ".$idRegistro." intentos(".$intentos.")";
        }
        return $mensaje;
    }

    public static function crear($tipo,array $parametros = [])
    {
        global $conexion, $baseDatosServicios,$config;
        $idRegistro = -1;        
        try {
            $sqlUpdate="INSERT INTO ".$baseDatosServicios.".sys_jobs(
                job_estado, 
                job_tipo, 
                job_fecha_creacion, 
                job_responsable, 
                job_id_institucion, 			
                job_mensaje,
                job_parametros,
                job_year, 
                job_intentos,
                job_prioridad)
            VALUES(
                '".JOBS_ESTADO_PENDIENTE."',
                '".$tipo."',
                NOW(), 
                '".$_SESSION['id']."', 
                '".$config['conf_id_institucion']."', 
                'Generando el primer Jobs', 
                '".json_encode($parametros)."', 
                '".$config['conf_agno']."', 
                '1', 
                '".JOBS_PRIORIDAD_MEDIA."'
            )";
            mysqli_query($conexion,$sqlUpdate);
            $idRegistro = mysqli_insert_id($conexion);
       
            
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        
        return $idRegistro;
    }

    public static function actualizar(array $datos = [])
    {
        global $conexion, $baseDatosServicios;
        $resultado = [];
        
        $setIntentos=empty($datos["intentos"])?"":",job_intentos='".$datos["intentos"]."'";
        $setEstado=empty($datos["estado"])?"":",job_estado='".$datos["estado"]."'";
        $setMensaje=empty($datos["mensaje"])?"":",job_mensaje='".$datos["mensaje"]."'";
        $setPrioridad=empty($datos["prioriedad"])?"":",job_prioriedad='".$datos["prioriedad"]."'";

        $sqlUpdate="UPDATE ".$baseDatosServicios.".sys_jobs
        SET job_fecha_modificacion=NOW()"
         .$setIntentos
         .$setEstado
         .$setMensaje
         .$setPrioridad.
         " WHERE job_id='".$datos["id"]."'";

        try {
            mysqli_query($conexion,$sqlUpdate);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function consultar(array $parametrosBusqueda = [])
    {
        global $conexion, $baseDatosServicios,$config;
        $resultado = [];
        $andEstado=empty($parametrosBusqueda["estado"])?" ":"AND job_estado='".$parametrosBusqueda["estado"]."'";
        $andParametros=empty($parametrosBusqueda["parametros"])?" ":"AND job_parametros='".$parametrosBusqueda["parametros"]."'";
        try {
            $sqlExecute="SELECT * FROM ".$baseDatosServicios.".sys_jobs
            LEFT JOIN usuarios  ON uss_id = job_responsable
            LEFT JOIN ".$baseDatosServicios .".instituciones ON ins_id = job_id_institucion
            WHERE job_tipo = '".$parametrosBusqueda["tipo"]."'
            AND job_responsable ='".$parametrosBusqueda["responsable"]."'
            AND job_year='".$parametrosBusqueda["agno"]."'
            ".$andParametros
             .$andEstado."       
            ORDER BY job_fecha_creacion";
            $resultado = mysqli_query($conexion,$sqlExecute);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function listar($estado = JOBS_ESTADO_PENDIENTE)
    {
        global $conexion, $baseDatosServicios;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".sys_jobs
            LEFT JOIN ".$baseDatosServicios .".instituciones ON ins_id = job_id_institucion
            WHERE job_estado = '".$estado."'          
            ORDER BY job_fecha_creacion");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }


}