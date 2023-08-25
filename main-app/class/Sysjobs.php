<?php
class SysJobs {
     /**
     * Esta función  crea ó actualiza si ya existe un registro, llamando las funcions de crear o  atualizar 
     * 
     * @param string $tipo
     * @param array $parametros
     * @param string $msj
     * 
     * @return String // se retorna el mensaje de la operacion registrada sys_jobs
     */
    public static function registrar($tipo,array $parametros = [],$msj ='')
    {
        global $config;
        $parametrosBuscar = array(
            "tipo" =>$tipo,
            "responsable" => $_SESSION['id'],
            "parametros" => json_encode($parametros),
            "agno"=>$config['conf_agno']
        );
       
        $buscarJobs=self::consultar($parametrosBuscar);
        $cantidad = mysqli_num_rows($buscarJobs);
        if($cantidad<1){
            $idRegistro =self::crear($tipo,$parametros,$msj);
            $mensaje="Se realizó exitosamente el proceso de ".$tipo." con el código ".$idRegistro;
        }else{
            $jobsEncontrado = mysqli_fetch_array($buscarJobs, MYSQLI_BOTH);
            $idRegistro = $jobsEncontrado["job_id"];           
            if($jobsEncontrado["job_intentos"]==JOBS_ESTADO_PENDIENTE){
                $intentos = intval($jobsEncontrado["job_intentos"])+1;            
                $datos = array(
                    "intentos" =>$intentos,
                    "id" => $jobsEncontrado['job_id']
                );
                self::actualizar($datos);
                $mensaje="Se actualizó exitosamente el proceso de ".$tipo." con el código ".$idRegistro." intentos(".$intentos.")";
            }else{
                $mensaje="Proceso ".$tipo." con el código ".$idRegistro." ya está marcado como finalizado";
            }
            
            
            
           
        }
        return $mensaje;
    }
    /**
     * Esta función  crea  un registro de en la tabla sys_jobs
     * 
     * @param string $tipo
     * @param array $parametros
     * @param string $msj
     * 
     * @return String // se retorna el id del registro
     */
    public static function crear($tipo,array $parametros = [],$mensaje ='')
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
                '".$mensaje."', 
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
    /**
     * Esta función  actualiza  un registro de en la tabla sys_jobs
     * 
     * @param array $datos // son los parametros que se va actualizar en la tabla
     * 
     * @return void 
     */
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
     /**
     * Esta función  consulta el registro en la tabla sys_jobs 
     * hay que tener en ceunta que siempre debe venir en el array $parametros
     *  los parametros["tipo"], parametros["responsable"] , parametros["agno"] 
     * @param array $parametrosBusqueda 
     * 
     * // se retorna el registro consultado
     */
    public static function consultar(array $parametrosBusqueda = [])
    {
        global $conexion, $baseDatosServicios;
        $resultado = [];
        $andEstado=empty($parametrosBusqueda["estado"])?" ":"AND job_estado='".$parametrosBusqueda["estado"]."'";
        $andParametros=empty($parametrosBusqueda["parametros"])?" ":"AND job_parametros='".$parametrosBusqueda["parametros"]."'";
       
        $sqlExecute="SELECT * FROM ".$baseDatosServicios.".sys_jobs
        LEFT JOIN usuarios  ON uss_id = job_responsable
        LEFT JOIN ".$baseDatosServicios .".instituciones ON ins_id = job_id_institucion
        WHERE job_tipo = '".$parametrosBusqueda["tipo"]."'
        AND job_responsable ='".$parametrosBusqueda["responsable"]."'
        AND job_year='".$parametrosBusqueda["agno"]."'
        ".$andParametros
         .$andEstado."       
        ORDER BY job_fecha_creacion";
        try {
            $resultado = mysqli_query($conexion,$sqlExecute);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }
     /**
     * Esta función  lista los registros en la tabla sys_jobs 
     * hay que tener en ceunta que siempre debe venir en el array $parametros
     * los parametros["estado"]
     * @param array $parametros 
     * 
     * @return array // se retorna el registro para consultar
     */
    public static function listar(array $parametrosBusqueda = [])
    {
        global $conexion, $baseDatosServicios;
        $resultado = [];
        $andEstado=empty($parametrosBusqueda["estado"])?JOBS_ESTADO_PENDIENTE:$parametrosBusqueda["estado"];
        $andTipo=empty($parametrosBusqueda["tipo"])?" ":"AND job_tipo='".$parametrosBusqueda["tipo"]."' ";
        $andResponsable=empty($parametrosBusqueda["responsable"])?" ":"AND job_responsable='".$parametrosBusqueda["responsable"]."' ";
        $andAgno=empty($parametrosBusqueda["agno"])?" ":"AND job_year='".$parametrosBusqueda["agno"]."' ";
        
        $sqlExecute="SELECT * FROM ".$baseDatosServicios.".sys_jobs
        LEFT JOIN ".$baseDatosServicios .".instituciones ON ins_id = job_id_institucion
        WHERE job_estado = '".$andEstado."'
        ".$andTipo
        . $andResponsable
        .$andAgno."            
        ORDER BY job_fecha_creacion";
        try {
            $resultado = mysqli_query($conexion,$sqlExecute);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }
    /**
     * Esta función  actauliza los intentos de cada crob jobs ejecutado
     * 
     * @param array $id
     * @param array $intento 
     * @param array $mensaje 
     * 
     * 
     * @return void 
     */
    public static function actualizarMensaje($id,$intento,$mensaje){
        $intento=intval($intento)+1;
        $datos = array(
            "id" => $id,
            "mensaje" => "Advertencia: ".$mensaje."!",
            "intentos" =>$intento,
        );
        self::actualizar($datos);
    }
    /**
     * Esta función  envia mensajes al usuario responsable del crobjobs notificando el estado
     * 
     * @param String $destinatario
     * @param array $contenido 
     * @param array $idJob 
     * @param array $tipo
     * 
     * @return void 
     */
    public static  function enviarMensaje($destinatario,$contenido,$idJob,$tipo){
        global $conexion,$baseDatosServicios,$config;       
        
        $para=$destinatario;
        try{
            $asunto="Ejecuci&oacute;n Finalizada Crob jobs (".$idJob.") de tipo ".$tipo;
			$remitente = mysqli_fetch_array(mysqli_query($conexion, "SELECT * FROM usuarios WHERE uss_permiso1='" .CODE_DEV_MODULE_PERMISSION. "' limit 1"), MYSQLI_BOTH); 
			$destinatario = mysqli_fetch_array(mysqli_query($conexion, "SELECT * FROM usuarios WHERE uss_id='" . $destinatario . "'"), MYSQLI_BOTH);
            $contenido="<br>Hola Sr(a) ".$destinatario["uss_nombre"]."<br> <p>".$contenido."</p>";
			mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".social_emails(ema_de, ema_para, ema_asunto, ema_contenido, ema_fecha, ema_visto, ema_eliminado_de, ema_eliminado_para, ema_institucion, ema_year)
				VALUES('" . $remitente["uss_id"] . "', '" . $para . "', '" . mysqli_real_escape_string($conexion,$asunto) . "', '" . mysqli_real_escape_string($conexion,$contenido) . "', now(), 0, 0, 0,'" . $config['conf_id_institucion'] . "','" . $config["conf_agno"] . "')");
						
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
         }
    }
}