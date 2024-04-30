<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
require_once("servicios/Servicios.php");

class Clases  extends Servicios{
    
      /**
   * Realiza un conteo teniendo encuenta los parametros ingresados.
   *
   * @param array|null $parametrosArray Arreglo de parámetros para filtrar la consulta (opcional).
   *
   * @return array|mysqli_result|false Arreglo de datos del resultado, objeto mysqli_result o false si hay un error.
   */
  public static function contar($parametrosArray = null,$filtro ="")
  {
    $sqlInicial = "SELECT Count(*) as cantidad FROM " . BD_ACADEMICA . ".academico_clases_preguntas";
    if ($parametrosArray && count($parametrosArray) > 0) {
        $parametrosValidos = array('cpp_id_clase', 'institucion','year','cpp_padre');
        $sqlInicial = Servicios::concatenarWhereAnd($sqlInicial, $parametrosValidos, $parametrosArray);
      };
    $sqlFinal = "";
    $sql = $sqlInicial .$filtro. $sqlFinal;
    return Servicios::SelectSql($sql)[0]["cantidad"];
  }
    /**
     * Este metodo me trae las preguntas de una clase
     * @param mysqli $conexion
     * @param array $config
     * @param string $idClase
     * @param string $filtro
     * 
     * @return array $consulta
     */
    public static function traerPreguntasClases(mysqli $conexion, array $config, string $idClase, string $filtro = ""){
     
        $sqlInicial = "SELECT * FROM ".BD_ACADEMICA.".academico_clases_preguntas cpp
        INNER JOIN ".BD_GENERAL.".usuarios uss ON uss_id=cpp.cpp_usuario AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
        WHERE cpp.cpp_id_clase='" . $idClase . "' AND cpp.institucion={$config['conf_id_institucion']}  AND cpp.year={$_SESSION["bd"]}  $filtro  ORDER BY cpp.cpp_fecha DESC";
        
        return Servicios::SelectSql($sqlInicial);
    }

        /**
     * Este metodo me trae una pregunta especifica de la clase
     * @param string $idClase
     * 
     * @return array $resultado
     */
    public static function traerPregunta(string $idPregunta){
        try{
            global $conexion,$config;
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_clases_preguntas cpp
            INNER JOIN ".BD_GENERAL.".usuarios uss ON uss_id=cpp.cpp_usuario AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
            WHERE cpp.cpp_id='" . $idPregunta . "' AND cpp.institucion={$config['conf_id_institucion']} AND cpp.year={$_SESSION["bd"]} ");
            $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
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
            $sql="INSERT INTO ".BD_ACADEMICA.".academico_clases_preguntas(cpp_id, cpp_usuario, cpp_fecha, cpp_id_clase, cpp_contenido, institucion, year,cpp_padre)
            VALUES('".$codigo."',
             '" . $_SESSION["id"] . "',
              now(), 
              '" . $POST["idClase"] . "',
              '" . mysqli_real_escape_string($conexion,$POST["contenido"]) . "',
               {$config['conf_id_institucion']},
               {$_SESSION["bd"]},
               '" .$POST["idPadre"]. "')
               ";
            mysqli_query($conexion, $sql);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        return $codigo;
    }
    
    /**
     * Este metodo me trae las clases de una carga en un periodo
     * @param mysqli $conexion
     * @param array $config
     * @param string $idClase
     * 
     * @return array $resultado
     */
    public static function traerDatosClases(mysqli $conexion, array $config, string $idClase){
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_clases 
            WHERE cls_id='".$idClase."' AND cls_estado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
            $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }
    
    /**
     * Este metodo me trae las clases de una carga en un periodo
     * @param mysqli $conexion
     * @param array $config
     * @param string $idCarga
     * @param int $periodo
     * 
     * @return mysqli_result $consulta
     */
    public static function traerClasesCargaPeriodo(mysqli $conexion, array $config, string $idCarga, int $periodo){
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_clases 
            WHERE cls_id_carga='".$idCarga."' AND cls_periodo='".$periodo."' AND cls_registrada=1 AND cls_estado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $consulta;
    }
    
    /**
     * Este metodo me trae las ausencias en una clase
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEstudiante
     * @param string $idCarga
     * @param int $periodo
     * 
     * @return array $resultado
     */
    public static function traerDatosAusencias(mysqli $conexion, array $config, string $idEstudiante, string $idCarga, int $periodo, string $year){
        try{
            $consulta = mysqli_query($conexion, "SELECT sum(aus_ausencias) FROM ".BD_ACADEMICA.".academico_clases cls 
            INNER JOIN ".BD_ACADEMICA.".academico_ausencias aus ON aus.aus_id_clase=cls.cls_id AND aus.aus_id_estudiante='".$idEstudiante."' AND aus.institucion={$config['conf_id_institucion']} AND aus.year={$year}
            WHERE cls.cls_id_carga='".$idCarga."' AND cls.cls_periodo='".$periodo."' AND cls.institucion={$config['conf_id_institucion']} AND cls.year={$year}");
            $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }
    
    /**
     * Este metodo me registra la ausencia de una clase
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
     */
    public static function registrarAusenciaClase(mysqli $conexion, array $config, array $POST){
        try{
            mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_clases SET cls_registrada=1, cls_fecha_registro=now() WHERE cls_id='".$POST["codNota"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }
    
    /**
     * Este metodo pone una clase disponible o no
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
     */
    public static function cambiarEstadoClase(mysqli $conexion, array $config, array $POST){
        try{
            mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_clases SET cls_disponible='".$POST["valor"]."' WHERE cls_id='".$POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }
    
    /**
     * Este metodo me actualiza una clase
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
     * @param array $FILES
     */
    public static function actualizarClase(mysqli $conexion, array $config, array $POST, array $FILES){

        $archivoSubido = new Archivos;
        global $storage;
        //Video
        if(!empty($FILES['videoClase']['name'])){
            $nombreInputFile = 'videoClase';
            $archivoSubido->validarArchivo($FILES['videoClase']['size'], $FILES['videoClase']['name']);
            $explode=explode(".", $FILES['videoClase']['name']);
            $extension = end($explode);
            $archivo = $_SESSION["inst"].'_'.$_SESSION["id"].'_clase_video_'.$POST["idR"].".".$extension;
            $archivoSubido->subirArchivoStorage(FILE_VIDEO_CLASES, $archivo, $nombreInputFile,$storage); 
            try{
                mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_clases SET cls_video_clase='".$archivo."' WHERE cls_id='".$POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
            } catch (Exception $e) {
                include("../compartido/error-catch-to-report.php");
            }
        }else if(!empty($POST["videoClase"])){
            try{
                $storage->getBucket()->object(FILE_VIDEO_CLASES . $POST["videoClase"])->delete();
                mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_clases SET cls_video_clase='' WHERE cls_id='".$POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
            } catch (Exception $e) {
                include("../compartido/error-catch-to-report.php");
            }

        }
        //Archivos
        $destino = "../files/clases";
        if(!empty($FILES['file']['name'])){
            $archivoSubido->validarArchivo($FILES['file']['size'], $FILES['file']['name']);
            $explode=explode(".", $FILES['file']['name']);
            $extension = end($explode);
            $archivo = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_file1_').".".$extension;
            @unlink($destino."/".$archivo);
            move_uploaded_file($FILES['file']['tmp_name'], $destino ."/".$archivo);
            try{
                mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_clases SET cls_archivo='".$archivo."' WHERE cls_id='".$POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
            } catch (Exception $e) {
                include("../compartido/error-catch-to-report.php");
            }
        }
        
        if(!empty($FILES['file2']['name'])){
            $archivoSubido->validarArchivo($FILES['file2']['size'], $FILES['file2']['name']);
            $explode=explode(".", $FILES['file2']['name']);
            $extension = end($explode);
            $archivo2 = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_file2_').".".$extension2;
            @unlink($destino."/".$archivo2);
            move_uploaded_file($FILES['file2']['tmp_name'], $destino ."/".$archivo2);
            try{
                mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_clases SET cls_archivo2='".$archivo2."' WHERE cls_id='".$POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
            } catch (Exception $e) {
                include("../compartido/error-catch-to-report.php");
            }
        }
        
        if(!empty($FILES['file3']['name'])){
            $archivoSubido->validarArchivo($FILES['file3']['size'], $FILES['file3']['name']);
            $explode=explode(".", $FILES['file3']['name']);
            $extension = end($explode);
            $archivo3 = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_file3_').".".$extension3;
            @unlink($destino."/".$archivo3);
            move_uploaded_file($FILES['file3']['tmp_name'], $destino ."/".$archivo3);
            try{
                mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_clases SET cls_archivo3='".$archivo3."' WHERE cls_id='".$POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
            } catch (Exception $e) {
                include("../compartido/error-catch-to-report.php");
            }
        }
        
        $findme   = '?v=';
        $pos = strpos($POST["video"], $findme) + 3;
        $video = substr($POST["video"],$pos,11);
        
        $disponible=0;
        if($POST["disponible"]==1) $disponible=1;
        
        $date = date('Y-m-d', strtotime(str_replace('-', '/', $POST["fecha"])));
        
        try{
            mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_clases SET cls_tema='".mysqli_real_escape_string($conexion,$POST["contenido"])."', cls_fecha='".$date."', cls_video='".$video."', cls_video_url='".$POST["video"]."', cls_descripcion='".mysqli_real_escape_string($conexion,$POST["descripcion"])."', cls_nombre_archivo1='".$POST["archivo1"]."', cls_nombre_archivo2='".$POST["archivo2"]."', cls_nombre_archivo3='".$POST["archivo3"]."', cls_disponible='".$disponible."', cls_hipervinculo='".$POST["vinculo"]."', cls_unidad='".$POST["unidad"]."'
            WHERE cls_id='".$POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
    }
    
    /**
     * Este metodo me lista las clases para el Banco de Datos
     * @param mysqli $conexion
     * @param array $config
     * @param string $idCarga
     * @param int $idPeriodo
     * 
     * @return mysqli_result $consulta
     */
    public static function listarClasesBD(mysqli $conexion, array $config, string $idCarga, int $idPeriodo){
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_clases 
            WHERE cls_estado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]} AND ((cls_compartir=1 AND cls_id_carga!='".$idCarga."') OR (cls_id_carga='".$idCarga."' AND cls_periodo!='".$idPeriodo."'))");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $consulta;
    }
    
    /**
     * Este metodo me lelimina una clase
     * @param mysqli $conexion
     * @param array $config
     * @param string $idClase
     */
    public static function eliminarClases(mysqli $conexion, array $config, string $idClase){
        try{
            $consultaRegistro=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_clases WHERE cls_id='".$idClase."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
        $registro = mysqli_fetch_array($consultaRegistro, MYSQLI_BOTH);
        
        $ruta = ROOT_PATH."/main-app/files/clases";
        if(!empty($registro['cls_archivo']) && file_exists($ruta."/".$registro['cls_archivo'])){
            unlink($ruta."/".$registro['cls_archivo']);	
        }
        
        if(!empty($registro['cls_archivo2']) && file_exists($ruta."/".$registro['cls_archivo2'])){
            unlink($ruta."/".$registro['cls_archivo2']);	
        }
        
        if(!empty($registro['cls_archivo3']) && file_exists($ruta."/".$registro['cls_archivo3'])){
            unlink($ruta."/".$registro['cls_archivo3']);	
        }
        
        try{
            mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_clases SET cls_estado=0 WHERE cls_id='".$idClase."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
    }
    
    /**
     * Este metodo me guarda una clase
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
     * @param array $FILES
     */
    public static function guardarClases(mysqli $conexion, array $config, array $POST, array $FILES, string $idCarga, int $periodo){
        $codigo=Utilidades::generateCode("CLS");
        
        $archivoSubido = new Archivos;
        global $storage;
        //Video
        $claseVideo='';
        if(!empty($FILES['videoClase']['name'])){
            $nombreInputFile = 'videoClase';
            // $archivoSubido->validarArchivo($FILES['videoClase']['size'], $FILES['videoClase']['name']);
            $explode=explode(".", $FILES['videoClase']['name']);
            $extension = end($explode);
            $claseVideo = $_SESSION["inst"].'_'.$_SESSION["id"].'_clase_video_'.$codigo.".".$extension;
            $archivoSubido->subirArchivoStorage(FILE_VIDEO_CLASES, $claseVideo, $nombreInputFile,$storage); 
        }
        //Archivos
        $archivo = '';
        $destino = ROOT_PATH."/main-app/files/clases";
        if(!empty($FILES['file']['name'])){
            $archivoSubido->validarArchivo($FILES['file']['size'], $FILES['file']['name']);
            $explode=explode(".", $FILES['file']['name']);
            $extension = end($explode);
            $archivo = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_file1_').".".$extension;
            @unlink($destino."/".$archivo);
            move_uploaded_file($FILES['file']['tmp_name'], $destino ."/".$archivo);
        }
        
        $archivo2 = '';
        if(!empty($FILES['file2']['name'])){
            $archivoSubido->validarArchivo($FILES['file2']['size'], $FILES['file2']['name']);
            $explode=explode(".", $FILES['file2']['name']);
            $extension2 = end($explode);
            $archivo2 = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_file2_').".".$extension2;
            @unlink($destino."/".$archivo2);
            move_uploaded_file($FILES['file2']['tmp_name'], $destino ."/".$archivo2);
        }
        
        $archivo3 = '';
        if(!empty($FILES['file3']['name'])){
            $archivoSubido->validarArchivo($FILES['file3']['size'], $FILES['file3']['name']);
            $explode=explode(".", $FILES['file3']['name']);
            $extension3 = end($explode);
            $archivo3 = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_file3_').".".$extension3;
            @unlink($destino."/".$archivo3);
            move_uploaded_file($FILES['file3']['tmp_name'], $destino ."/".$archivo3);
        }
        
        $findme   = '?v=';
        $pos = strpos($POST["video"], $findme) + 3;
        $video = substr($POST["video"],$pos,11);
        
        if(empty($POST["bancoDatos"]) || $POST["bancoDatos"]==0){
            $date = date('Y-m-d', strtotime(str_replace('-', '/', $POST["fecha"])));
            $disponible=0;
            if($POST["disponible"]==1) $disponible=1;
        
            try{
                mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_clases(cls_id, cls_tema, cls_fecha, cls_id_carga, cls_estado, cls_periodo, cls_video, cls_video_url, cls_archivo, cls_archivo2, cls_archivo3, cls_nombre_archivo1, cls_nombre_archivo2, cls_nombre_archivo3, cls_descripcion, cls_disponible, cls_meeting, cls_hipervinculo,cls_unidad, institucion, year,cls_video_clase)"." VALUES('".$codigo."', '".mysqli_real_escape_string($conexion,$POST["contenido"])."', '".$date."', '".$idCarga."', 1, '".$periodo."', '".$video."', '".$POST["video"]."', '".$archivo."', '".$archivo2."', '".$archivo3."', '".$POST["archivo1"]."', '".$POST["archivo2"]."', '".$POST["archivo3"]."', '".mysqli_real_escape_string($conexion,$POST["descripcion"])."', '".$disponible."', '".$POST["idMeeting"]."', '".$POST["vinculo"]."', '".$POST["unidad"]."', {$config['conf_id_institucion']}, {$_SESSION["bd"]},'".$claseVideo."')");
            } catch (Exception $e) {
                include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
            }
        }

        return $codigo;
    }
    
    /**
     * Este metodo me lista las clases diferente a la actual
     * @param mysqli $conexion
     * @param array $config
     * @param string $idClases
     * @param string $idCarga
     * @param int $idPeriodo
     * 
     * @return mysqli_result $consulta
     */
    public static function listarClasesDiferentes(mysqli $conexion, array $config, string $idClases, string $idCarga, int $idPeriodo){
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_clases 
            WHERE cls_id_carga='".$idCarga."' AND cls_periodo='".$idPeriodo           ."' AND cls_estado=1 AND cls_id!='".$idClases."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}
            ORDER BY cls_id DESC");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $consulta;
    }
    
    /**
     * Este metodo eliminar clases de una carga
     * @param mysqli $conexion
     * @param array $config
     * @param string $idCarga
     * @param int $idPeriodo
     */
    public static function eliminarClasesCargas(mysqli $conexion, array $config, string $idCarga, int $idPeriodo){
        try{
            mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_clases SET cls_estado=0
            WHERE cls_id_carga='".$idCarga."' AND cls_periodo='".$idPeriodo."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }

    /**
     * Me trae el plan de clases de una carga en un periodo
     *
     * @param mysqli $conexion
     * @param array $config
     * @param string $carga
     * @param int $periodo
     * 
     * @return array $resultado
     *
     */
    public static function traerPlanClase(
        mysqli $conexion,
        array $config,
        string $carga,
        int $periodo
    ){
        
        $resultado = [];

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_pclase 
            WHERE pc_id_carga='".$carga."' AND pc_periodo='".$periodo."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
            $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        return $resultado;
    }

    /**
     * Me elimina el plan de clases de una carga en un periodo
     *
     * @param mysqli $conexion
     * @param array $config
     * @param string $carga
     * @param int $periodo
     *
     */
    public static function eliminarPlanClases(
        mysqli $conexion,
        array $config,
        string $carga,
        int $periodo
    ){

        try {
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_pclase WHERE pc_id_carga='".$carga."' AND pc_periodo='".$periodo."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }

    /**
     * Me guarda el plan de clases de una carga en un periodo
     *
     * @param mysqli $conexion
     * @param PDO $conexionPDO
     * @param array $config
     * @param string $carga
     * @param int $periodo
     * @param array $FILES
     *
     */
    public static function guardarPlanClases(
        mysqli $conexion,
        PDO $conexionPDO,
        array $config,
        string $carga,
        int $periodo,
        array $FILES
    ){
        $codigo=Utilidades::getNextIdSequence($conexionPDO, BD_ACADEMICA, 'academico_pclase');

        $archivoSubido = new Archivos;

        $archivoSubido->validarArchivo($FILES['file']['size'], $FILES['file']['name']);
        $explode=explode(".", $FILES['file']['name']);
        $extension = end($explode);
        $archivo = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_file_').".".$extension;
        $destino = ROOT_PATH."/main-app/files/pclase";
        @unlink($destino."/".$archivo);
        move_uploaded_file($FILES['file']['tmp_name'], $destino ."/".$archivo);

        try {
            mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_pclase(pc_id, pc_plan, pc_id_carga, pc_periodo, pc_fecha_subido, institucion, year)VALUES('".$codigo."', '".$archivo."', '".$carga."', '".$periodo."', now(), {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }

}