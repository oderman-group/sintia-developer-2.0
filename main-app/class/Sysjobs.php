<?php
class SysJobs {


    public static function actualizar(array $datos = []
    )
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

    public static function consultar(array $parametrosBusqeda = []
    )
    {
        global $conexion, $baseDatosServicios;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".sys_jobs
            LEFT JOIN usuarios  ON uss_id = job_responsable
            LEFT JOIN ".$baseDatosServicios .".instituciones ON ins_id = job_id_institucion
            WHERE job_tipo = '".$parametrosBusqeda["tipo"]."'
            AND job_responsable ='".$parametrosBusqeda["responsable"]."'
            AND job_year='".$parametrosBusqeda["agno"]."'
            AND job_parametros='".$parametrosBusqeda["parametros"]."'           
            ORDER BY job_fecha_creacion");
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