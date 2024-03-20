<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
require_once(ROOT_PATH."/main-app/compartido/sintia-funciones.php");

class Actividades {

    /**
     * Este metodo guarda una actividad
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
     * @param array $FILES
     * @param string $idCarga
     * @param string $periodo
     * 
     * @return string $codigo
     */
    public static function guardarActividad(mysqli $conexion, array $config, array $POST, array $FILES, $storage, string $idCarga, int $periodo){
        $codigo=Utilidades::generateCode("TAR");
        
        $archivoSubido = new Archivos;
        
        $archivo = '';
        $pesoMB = '';
        if(!empty($FILES['file']['name'])){
            $nombreInputFile = 'file';
            $archivoSubido->validarArchivo($FILES['file']['size'], $FILES['file']['name']);
            $explode=explode(".", $FILES['file']['name']);
            $extension = end($explode);
            $archivo = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_file_').".".$extension;
            $destino = ROOT_PATH."/main-app/files/tareas";
            @unlink($destino."/".$archivo);
            $archivoSubido->subirArchivoStorage(FILE_TAREAS, $archivo, $nombreInputFile,$storage); 
            $pesoMB = round($FILES['file']['size']/1048576,2);
        }
        
        if(empty($POST["retrasos"]) || (!empty($POST["retrasos"]) && $POST["retrasos"]!=1)) $POST["retrasos"]='0';
        
        try{
            mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_actividad_tareas(tar_id, tar_titulo, tar_descripcion, tar_id_carga, tar_periodo, tar_estado, tar_fecha_disponible, tar_fecha_entrega, tar_impedir_retrasos, tar_archivo, tar_peso1, institucion, year)
            VALUES('".$codigo."', '".mysqli_real_escape_string($conexion,$POST["titulo"])."', '".mysqli_real_escape_string($conexion,$POST["contenido"])."', '".$idCarga."', '".$periodo."', 1, '".$POST["desde"]."', '".$POST["hasta"]."', '".$POST["retrasos"]."', '".$archivo."', '".$pesoMB."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }

        return $codigo;
    }

    /**
     * Este metodo actualiza una actividad
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
     * @param array $FILES
     */
    public static function actualizarActividad(mysqli $conexion, array $config, array $POST, array $FILES, $storage){

        $archivoSubido = new Archivos;
        
        if(!empty($FILES['file']['name'])){
            $nombreInputFile = 'file';
            $archivoSubido->validarArchivo($FILES['file']['size'], $FILES['file']['name']);
            $explode=explode(".", $FILES['file']['name']);
            $extension = end($explode);
            $archivo = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_file_').".".$extension;
            $destino = ROOT_PATH."/main-app/files/tareas";
            @unlink($destino."/".$archivo);
            $archivoSubido->subirArchivoStorage(FILE_TAREAS, $archivo, $nombreInputFile,$storage); 
            try{
                mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_actividad_tareas SET tar_archivo='".$archivo."' WHERE tar_id='".$POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
            } catch (Exception $e) {
                include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
            }
        }
        
        if(empty($POST["retrasos"]) || $POST["retrasos"]!=1) $POST["retrasos"]='0';
        
        try{
            mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_actividad_tareas SET tar_titulo='".mysqli_real_escape_string($conexion,$POST["titulo"])."', tar_descripcion='".mysqli_real_escape_string($conexion,$POST["contenido"])."', tar_fecha_disponible='".$POST["desde"]."', tar_fecha_entrega='".$POST["hasta"]."', tar_impedir_retrasos='".$POST["retrasos"]."' WHERE tar_id='".$POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
    }

    /**
     * Este metodo me trae las actividades de una carga
     * @param mysqli $conexion
     * @param array $config
     * @param string $idCarga
     * 
     * @return mysqli_result $consulta
     */
    public static function actividadesCargas(mysqli $conexion, array $config, string $idCarga){
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_tareas 
            WHERE tar_id_carga='".$idCarga."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}
            ");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $consulta;
    }

    /**
     * Este metodo me trae las actividades de una carga en un periodo, diferente a la actividad actual
     * @param mysqli $conexion
     * @param array $config
     * @param string $idCarga
     * @param int $periodo
     * @param string $idActividad
     * 
     * @return mysqli_result $consulta
     */
    public static function actividadesCargasPeriodosDiferente(mysqli $conexion, array $config, string $idCarga, int $periodo, string $idActividad){
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_tareas
            WHERE tar_id_carga='".$idCarga."' AND tar_periodo='".$periodo."' AND tar_id!='".$idActividad."' 
            AND tar_estado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}
            ORDER BY tar_id DESC");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $consulta;
    }

    /**
     * Este metodo me trae las actividades de una carga en un periodo
     * @param mysqli $conexion
     * @param array $config
     * @param string $idCarga
     * @param int $periodo
     * 
     * @return mysqli_result $consulta
     */
    public static function actividadesCargasPeriodos(mysqli $conexion, array $config, string $idCarga, int $periodo){
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_tareas
            WHERE tar_id_carga='".$idCarga."' AND tar_periodo='".$periodo."' AND tar_estado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}
            ORDER BY tar_id DESC");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $consulta;
    }

    /**
     * Este metodo me trae las datos de una actividades
     * @param mysqli $conexion
     * @param array $config
     * @param string $idActividad
     * 
     * @return array $resultado
     */
    public static function traerDatosActividades(mysqli $conexion, array $config, string $idActividad){
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_tareas WHERE tar_id='".$idActividad."' AND tar_estado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo me trae las fechas de una actividad
     * @param mysqli $conexion
     * @param array $config
     * @param string $idActividad
     * 
     * @return array $resultado
     */
    public static function traerFechaActividad(mysqli $conexion, array $config, string $idActividad){
        try{
            $consulta = mysqli_query($conexion, "SELECT DATEDIFF(tar_fecha_disponible, now()), DATEDIFF(tar_fecha_entrega, now()) FROM ".BD_ACADEMICA.".academico_actividad_tareas WHERE tar_id='".$idActividad."' AND tar_estado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo me trae las fechas de una actividad para los estudiantes
     * @param mysqli $conexion
     * @param array $config
     * @param string $idActividad
     * 
     * @return array $resultado
     */
    public static function traerFechaActividadEstudiante(mysqli $conexion, array $config, string $idActividad){
        try{
            $consulta = mysqli_query($conexion, "SELECT TIMESTAMPDIFF(MINUTE, tar_fecha_disponible, now()), TIMESTAMPDIFF(MINUTE, tar_fecha_entrega, now()) FROM ".BD_ACADEMICA.".academico_actividad_tareas WHERE tar_id='".$idActividad."' AND tar_estado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo me trae las fechas de una actividad
     * @param mysqli $conexion
     * @param array $config
     * @param string $idActividad
     * 
     * @return array $resultado
     */
    public static function fechaEntregaActividad(mysqli $conexion, array $config, string $idActividad){
        try{
            $consulta = mysqli_query($conexion, "SELECT DATEDIFF(tar_fecha_disponible, now()), DATEDIFF(tar_fecha_entrega, now()), tar_fecha_entrega, tar_impedir_retrasos FROM ".BD_ACADEMICA.".academico_actividad_tareas WHERE tar_id='".$idActividad."' AND tar_estado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo elimina una actividad
     * @param mysqli $conexion
     * @param array $config
     * @param string $idActividad
     */
    public static function eliminarActividad(mysqli $conexion, array $config, string $idActividad, $storage){
        try{
            $consultaRegistro=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_tareas WHERE tar_id='".$idActividad."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
        $registro = mysqli_fetch_array($consultaRegistro, MYSQLI_BOTH);
        
        $url1= $storage->getBucket()->object(FILE_TAREAS.$registro["tar_archivo"])->signedUrl(new DateTime('tomorrow'));
        $existe1=$storage->getBucket()->object(FILE_TAREAS.$registro["tar_archivo"])->exists();
        if($existe1){
            unlink($url1);	
        }
        $url2= $storage->getBucket()->object(FILE_TAREAS.$registro["tar_archivo2"])->signedUrl(new DateTime('tomorrow'));
        $existe2=$storage->getBucket()->object(FILE_TAREAS.$registro["tar_archivo2"])->exists();
        if($existe2){
            unlink($url2);	
        }
        $url3= $storage->getBucket()->object(FILE_TAREAS.$registro["ar_archivo3"])->signedUrl(new DateTime('tomorrow'));
        $existe3=$storage->getBucket()->object(FILE_TAREAS.$registro["ar_archivo3"])->exists();
        if($existe3){
            unlink($url3);	
        }
        
        try{
            mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_actividad_tareas SET tar_estado=0 WHERE tar_id='".$idActividad."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
    }

    /**
     * Este metodo es para impedir o no el retrso en una actividad
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
     */
    public static function impedirRetrasoActividad(mysqli $conexion, array $config, array $POST){
        
        try{
            mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_actividad_tareas SET tar_impedir_retrasos='".$POST["valor"]."' WHERE tar_id='".$POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
    }

    /**
     * Este metodo me elimina todas las entregas de los estudiantes
     * @param mysqli $conexion
     * @param array $config
     */
    public static function eliminarActividadesEntregasTodas(mysqli $conexion, array $config){
        
        try{
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_tareas_entregas WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
    }

    /**
     * Este metodo me elimina todas las entregas de un estudiante
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEstudiante
     */
    public static function eliminarActividadesEntregasEstudiante(mysqli $conexion, array $config, string $idEstudiante){
        
        try{
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_tareas_entregas WHERE ent_id_estudiante='" . $idEstudiante . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
    }

    /**
     * Este metodo elimina las entregas de una actividad
     * @param mysqli $conexion
     * @param array $config
     * @param string $idActividad
     */
    public static function eliminarActividadEntregas(mysqli $conexion, array $config, string $idActividad, $storage){

        try{
            $rEntregas = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_tareas_entregas WHERE ent_id_actividad='".$idActividad."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
        
        while($registroEntregas = mysqli_fetch_array($rEntregas, MYSQLI_BOTH)){
            $url1= $storage->getBucket()->object(FILE_TAREAS_ENTREGADAS.$registroEntregas["ent_archivo"])->signedUrl(new DateTime('tomorrow'));
            $existe1=$storage->getBucket()->object(FILE_TAREAS_ENTREGADAS.$registroEntregas["ent_archivo"])->exists();
            if($existe1){
                unlink($url1);	
            }
            $url2= $storage->getBucket()->object(FILE_TAREAS_ENTREGADAS.$registroEntregas["ent_archivo2"])->signedUrl(new DateTime('tomorrow'));
            $existe2=$storage->getBucket()->object(FILE_TAREAS_ENTREGADAS.$registroEntregas["ent_archivo2"])->exists();
            if($existe2){
                unlink($url2);	
            }
            $url3= $storage->getBucket()->object(FILE_TAREAS_ENTREGADAS.$registroEntregas["ent_archivo3"])->signedUrl(new DateTime('tomorrow'));
            $existe3=$storage->getBucket()->object(FILE_TAREAS_ENTREGADAS.$registroEntregas["ent_archivo3"])->exists();
            if($existe3){
                unlink($url3);	
            }
        }
        
        try{
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_tareas_entregas WHERE ent_id_actividad='".$idActividad."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
    }

    /**
     * Este metodo me trae cuenta las entrgas de un estudiante
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEstudiante
     * @param string $idActivida
     * 
     * @return array $num
     */
    public static function contarEntregas(mysqli $conexion, array $config, string $idEstudiante, string $idActivida){
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_tareas_entregas WHERE ent_id_actividad='".$idActivida."' AND ent_id_estudiante='".$idEstudiante."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
            $num = mysqli_num_rows($consulta);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $num;
    }

    /**
     * Este metodo me trae las entrgas de un estudiante
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEstudiante
     * @param string $idActivida
     * 
     * @return array $resultado
     */
    public static function consultarEntregas(mysqli $conexion, array $config, string $idEstudiante, string $idActivida){
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_tareas_entregas WHERE ent_id_actividad='".$idActivida."' AND ent_id_estudiante='".$idEstudiante."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
            $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    /**
     * Este metodo me trae las entrgas en una activida de un estudiante
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEstudiante
     * @param string $idActivida
     * 
     * @return mysqli_result $consulta
     */
    public static function actividadesEntregasEstudiante(mysqli $conexion, array $config, string $idEstudiante, string $idActivida){
        try{
            $consulta = mysqli_query($conexion, "SELECT ent_fecha, MOD(TIMESTAMPDIFF(MINUTE, ent_fecha, now()),60), MOD(TIMESTAMPDIFF(SECOND, ent_fecha, now()),60), ent_archivo, ent_comentario, ent_archivo2, ent_archivo3 FROM ".BD_ACADEMICA.".academico_actividad_tareas_entregas 
            WHERE ent_id_estudiante='".$idEstudiante."' AND ent_id_actividad='".$idActivida."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $consulta;
    }

    /**
     * Este metodo guarda una entrega
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
     * @param array $FILES
     * @param string $idEstudiante
     */
    public static function guardarEntrega(mysqli $conexion, array $config, array $POST, array $FILES, $storage, string $idEstudiante){
        $archivoSubido = new Archivos;
        $codigo=Utilidades::generateCode("ENT");
        $destino = ROOT_PATH."/main-app/files/tareas-entregadas";
    
        $archivo = "";
        $pesoMB1 = "";
        if(!empty($FILES['file']['name'])){
            $nombreInputFile = 'file';
            $archivoSubido->validarArchivo($FILES['file']['size'], $FILES['file']['name']);
            $explode=explode(".", $FILES['file']['name']);
            $extension = end($explode);
            $archivo = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_file1_').".".$extension;
            @unlink($destino."/".$archivo);
            $archivoSubido->subirArchivoStorage(FILE_TAREAS_ENTREGADAS, $archivo, $nombreInputFile,$storage);
            $pesoMB1 = round($FILES['file']['size']/1048576,2);
        }
    
        $archivo2 = "";
        $pesoMB2 = "";
        if(!empty($FILES['file2']['name'])){
            $nombreInputFile = 'file2';
            $archivoSubido->validarArchivo($FILES['file2']['size'], $FILES['file2']['name']);
            $explode2=explode(".", $FILES['file2']['name']);
            $extension2 = end($explode2);
            $archivo2 = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_file2_').".".$extension2;
            @unlink($destino."/".$archivo2);
            $archivoSubido->subirArchivoStorage(FILE_TAREAS_ENTREGADAS, $archivo2, $nombreInputFile,$storage);
            $pesoMB2 = round($FILES['file2']['size']/1048576,2);
        }
    
        $archivo3 = "";
        $pesoMB3 = "";
        if(!empty($FILES['file3']['name'])){
            $nombreInputFile = 'file3';
            $archivoSubido->validarArchivo($FILES['file3']['size'], $FILES['file3']['name']);
            $explode3=explode(".", $FILES['file3']['name']);
            $extension3 = end($explode3);
            $archivo3 = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_file3_').".".$extension3;
            @unlink($destino."/".$archivo3);
            $archivoSubido->subirArchivoStorage(FILE_TAREAS_ENTREGADAS, $archivo3, $nombreInputFile,$storage); 
            $pesoMB3 = round($FILES['file3']['size']/1048576,2);
        }
    
        try{
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_tareas_entregas WHERE ent_id_estudiante='".$idEstudiante."' AND ent_id_actividad='".$POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
    
        try{
            mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_actividad_tareas_entregas (ent_id, ent_id_estudiante, ent_id_actividad, ent_archivo, ent_fecha, ent_comentario, ent_archivo2, ent_archivo3, ent_peso1, ent_peso2, ent_peso3, institucion, year) VALUES('".$codigo."', '".$idEstudiante."', '".$POST["idR"]."', '".$archivo."', now(), '".mysqli_real_escape_string($conexion,$POST["comentario"])."', '".$archivo2."', '".$archivo3."', '".$pesoMB1."', '".$pesoMB2."', '".$pesoMB3."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
    }

    /**
     * Este metodo actualiza una entrega
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
     * @param array $FILES
     * @param string $idEstudiante
     */
    public static function actualizarEntrega(mysqli $conexion, array $config, array $POST, array $FILES, $storage, string $idEstudiante){
        $archivoSubido = new Archivos;
        $destino = ROOT_PATH."/main-app/files/tareas-entregadas";

        if(!empty($FILES['file']['name'])){
            $nombreInputFile = 'file';
            $archivoSubido->validarArchivo($FILES['file']['size'], $FILES['file']['name']);
            $explode=explode(".", $FILES['file']['name']);
            $extension = end($explode);
            $archivo = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_file1_').".".$extension;
            @unlink($destino."/".$archivo);
            $archivoSubido->subirArchivoStorage(FILE_TAREAS_ENTREGADAS, $archivo, $nombreInputFile,$storage);
    
            try{
                mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_actividad_tareas_entregas SET ent_archivo='".$archivo."' WHERE ent_id_estudiante='".$idEstudiante."' AND ent_id_actividad='".$POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
            } catch (Exception $e) {
                include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
            }
        }
    
        if(!empty($FILES['file2']['name'])){
            $nombreInputFile = 'file2';
            $archivoSubido->validarArchivo($FILES['file2']['size'], $FILES['file2']['name']);
            $explode2=explode(".", $FILES['file2']['name']);
            $extension2 = end($explode2);
            $archivo2 = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_file2_').".".$extension2;
            @unlink($destino."/".$archivo2);
            $archivoSubido->subirArchivoStorage(FILE_TAREAS_ENTREGADAS, $archivo2, $nombreInputFile,$storage);
            try{
                mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_actividad_tareas_entregas SET ent_archivo2='".$archivo2."' WHERE ent_id_estudiante='".$idEstudiante."' AND ent_id_actividad='".$POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
            } catch (Exception $e) {
                include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
            }
        }
    
        if(!empty($FILES['file3']['name'])){
            $nombreInputFile = 'file3';
            $archivoSubido->validarArchivo($FILES['file3']['size'], $FILES['file3']['name']);
            $explode3=explode(".", $FILES['file3']['name']);
            $extension3 = end($explode3);
            $archivo3 = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_file3_').".".$extension3;
            @unlink($destino."/".$archivo3);
            $archivoSubido->subirArchivoStorage(FILE_TAREAS_ENTREGADAS, $archivo3, $nombreInputFile,$storage);
            try{
                mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_actividad_tareas_entregas SET ent_archivo3='".$archivo3."' WHERE ent_id_estudiante='".$idEstudiante."' AND ent_id_actividad='".$POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
            } catch (Exception $e) {
                include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
            }
        }
    
        try{
            mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_actividad_tareas_entregas SET ent_comentario='".mysqli_real_escape_string($conexion,$POST["comentario"])."' WHERE ent_id_estudiante='".$idEstudiante."' AND ent_id_actividad='".$POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
    }
}