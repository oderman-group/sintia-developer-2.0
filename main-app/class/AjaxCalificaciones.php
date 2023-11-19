<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Estudiantes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");

class AjaxCalificaciones {

    /**
     * Este metodo sirve para registrar las calificaciones de un estudiante
     * 
     * @param mysqli    $conexion 
     * @param array     $config 
     * @param int       $codEstudiante 
     * @param string    $nombreEst 
     * @param int       $codNota
     * @param double    $nota
     * @param double    $notaAnterior
     * 
     * @return array // se retorna mensaje de confirmación
    **/
    public static function ajaxGuardarNota($conexion, $config, $codEstudiante, $nombreEst, $codNota, $nota, $notaAnterior)
    {
        if(trim($nota)==""){
            $datosMensaje=["heading"=>"Nota vacia","estado"=>"warning","mensaje"=>"Digite una nota correcta."];
            return $datosMensaje;
        }
        if($nota>$config[4]) $nota = $config[4]; if($nota<$config[3]) $nota = $config[3];

        try{
            $consultaNum = mysqli_query($conexion, "SELECT cal_id_actividad, cal_id_estudiante FROM academico_calificaciones WHERE cal_id_actividad={$codNota} AND cal_id_estudiante={$codEstudiante}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
        $num = mysqli_num_rows($consultaNum);
    
        if($num==0){
            
            try{
                mysqli_query($conexion, "INSERT INTO academico_calificaciones(cal_id_estudiante, cal_nota, cal_id_actividad, cal_fecha_registrada, cal_cantidad_modificaciones)VALUES({$codEstudiante},{$nota},{$codNota}, now(), 0)");
            } catch (Exception $e) {
                include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
            }
            
            try{
                mysqli_query($conexion, "UPDATE academico_actividades SET act_registrada=1, act_fecha_registro=now() WHERE act_id={$codNota}");
            } catch (Exception $e) {
                include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
            }
    
        }else{
            if($notaAnterior==""){$notaAnterior = "0.0";}
            
            try{
                mysqli_query($conexion, "UPDATE academico_calificaciones SET cal_nota={$nota}, cal_fecha_modificada=now(), cal_cantidad_modificaciones=cal_cantidad_modificaciones+1, cal_nota_anterior={$notaAnterior}, cal_tipo=1 WHERE cal_id_actividad={$codNota} AND cal_id_estudiante={$codEstudiante}");
            } catch (Exception $e) {
                include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
            }
            
            try{
                mysqli_query($conexion, "UPDATE academico_actividades SET act_registrada=1 WHERE act_id={$codNota}");
            } catch (Exception $e) {
                include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
            }
    
        }

        $datosMensaje=["heading"=>"Cambios guardados","estado"=>"success","mensaje"=>"La nota se ha guardado correctamente para el estudiante <b>".strtoupper($nombreEst)."</b>"];

        return $datosMensaje;
    }

    /**
     * Este metodo sirve para registrar las observaciones de un estudiante
     * 
     * @param mysqli    $conexion 
     * @param int       $codEstudiante 
     * @param string    $nombreEst 
     * @param int       $codObservacion
     * @param string    $observacion
     * 
     * @return array // se retorna mensaje de confirmación
    **/
    public static function ajaxGuardarObservacion($conexion, $codEstudiante, $nombreEst, $codObservacion, $observacion)
    {
        if(trim($observacion)==""){
            $datosMensaje=["heading"=>"Nota vacia","estado"=>"warning","mensaje"=>"Digite una observación correcta."];
            return $datosMensaje;
        }

        try{
            $consultaNum = mysqli_query($conexion, "SELECT cal_id_actividad, cal_id_estudiante FROM academico_calificaciones WHERE cal_id_actividad={$codObservacion} AND cal_id_estudiante={$codEstudiante}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
        $num = mysqli_num_rows($consultaNum);

        if($num==0){
            
            try{
                mysqli_query($conexion, "INSERT INTO academico_calificaciones(cal_id_estudiante, cal_observaciones, cal_id_actividad)VALUES('".$codEstudiante."','".mysqli_real_escape_string($conexion,$observacion)."','".$codObservacion."')");
            } catch (Exception $e) {
                include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
            }
            
            try{
                mysqli_query($conexion, "UPDATE academico_actividades SET act_registrada=1, act_fecha_registro=now() WHERE act_id='".$codObservacion."'");
            } catch (Exception $e) {
                include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
            }
            
        }else{
            try{
                mysqli_query($conexion, "UPDATE academico_calificaciones SET cal_observaciones='".mysqli_real_escape_string($conexion,$observacion)."' WHERE cal_id_actividad='".$codObservacion."' AND cal_id_estudiante='".$codEstudiante."'");
            } catch (Exception $e) {
                include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
            }
            
            try{
                mysqli_query($conexion, "UPDATE academico_actividades SET act_registrada=1 WHERE act_id='".$codObservacion."'");
            } catch (Exception $e) {
                include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
            }
            
        }

        $datosMensaje=["heading"=>"Cambios guardados","estado"=>"success","mensaje"=>'La observación se ha guardado correctamente para el estudiante <b>'.strtoupper($nombreEst).'</b>'];

        return $datosMensaje;
    }

    /**
     * Este metodo sirve para registrar una nota masiva a los estudiantes
     * 
     * @param mysqli    $conexion 
     * @param array     $datosCargaActual
     * @param int       $codNota
     * @param string    $nota
     * 
     * @return array // se retorna mensaje de confirmación
    **/
    public static function ajaxGuardarNotasMasiva($conexion, $datosCargaActual, $codNota, $nota)
    {
        if(trim($nota)==""){
            $datosMensaje=["heading"=>"Nota vacia","estado"=>"warning","mensaje"=>"Digite una nota correcta."];
            return $datosMensaje;
        }

        $consultaE = Estudiantes::escogerConsultaParaListarEstudiantesParaDocentes($datosCargaActual);
        
        $insertBD = 0;
        $updateBD = 0;
        $datosInsert = '';
        $datosUpdate = '';
        $datosDelete = '';
        
        while($estudiantes = mysqli_fetch_array($consultaE, MYSQLI_BOTH)){
            
            try{
                $consultaNumE=mysqli_query($conexion, "SELECT cal_id_actividad, cal_id_estudiante FROM academico_calificaciones WHERE cal_id_actividad='".$codNota."' AND cal_id_estudiante='".$estudiantes['mat_id']."'");
            } catch (Exception $e) {
                include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
            }
            $numE = mysqli_num_rows($consultaNumE);
            
            if($numE==0){
                $insertBD = 1;
                $datosDelete .="cal_id_estudiante='".$estudiantes['mat_id']."' OR ";
                $datosInsert .="('".$estudiantes['mat_id']."','".$nota."','".$codNota."', now(), 0),";
            }else{
                $updateBD = 1;
                $datosUpdate .="cal_id_estudiante='".$estudiantes['mat_id']."' OR ";
            }
        }
        
        if($insertBD==1){
            $datosInsert = substr($datosInsert,0,-1);
            $datosDelete = substr($datosDelete,0,-4);  
		
            try{
                mysqli_query($conexion, "DELETE FROM academico_calificaciones WHERE cal_id_actividad='".$codNota."' AND (".$datosDelete.")"); 
            } catch (Exception $e) {
                include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
            }         
            
            try{
                mysqli_query($conexion, "INSERT INTO academico_calificaciones(cal_id_estudiante, cal_nota, cal_id_actividad, cal_fecha_registrada, cal_cantidad_modificaciones)VALUES
                ".$datosInsert."
                ");
            } catch (Exception $e) {
                include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
            }
            
        }
        
        if($updateBD==1){
            $datosUpdate = substr($datosUpdate,0,-4);
            
            try{
                mysqli_query($conexion, "UPDATE academico_calificaciones SET cal_nota='".$nota."', cal_fecha_modificada=now(), cal_cantidad_modificaciones=cal_cantidad_modificaciones+1 
                WHERE cal_id_actividad='".$codNota."' AND (".$datosUpdate.")");
            } catch (Exception $e) {
                include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
            }

        }
        
        try{
            mysqli_query($conexion, "UPDATE academico_actividades SET act_registrada=1, act_fecha_registro=now() WHERE act_id='".$codNota."'");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
    

        $datosMensaje=["heading"=>"Cambios guardados","estado"=>"success","mensaje"=>'Se ha guardado la misma nota para todos los estudiantes en esta actividad. La página se actualizará en unos segundos para que vea los cambios...'];

        return $datosMensaje;
    }

    /**
     * Este metodo sirve para registrar las notas de recuperación de un estudiante
     * 
     * @param mysqli    $conexion 
     * @param array     $config 
     * @param int       $codEstudiante 
     * @param string    $nombreEst 
     * @param int       $codNota
     * @param double    $nota
     * @param double    $notaAnterior
     * 
     * @return array // se retorna mensaje de confirmación
    **/
    public static function ajaxGuardarNotaRecuperacion($conexion, $config, $codEstudiante, $nombreEst, $codNota, $nota, $notaAnterior)
    {
        if(trim($nota)==""){
            $datosMensaje=["heading"=>"Nota vacia","estado"=>"warning","mensaje"=>"Digite una nota correcta."];
            return $datosMensaje;
        }
        if($nota>$config[4]) $nota = $config[4]; if($nota<$config[3]) $nota = $config[3];
        $codigo=Utilidades::generateCode("REC");
        
        try{
            mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_recuperaciones_notas(rec_id, rec_cod_estudiante, rec_nota, rec_id_nota, rec_fecha, rec_nota_anterior, institucion, year)VALUES('".$codigo."', '".$codEstudiante."','".$nota."','".$codNota."', now(),'".$notaAnterior."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
        
        try{
            mysqli_query($conexion, "UPDATE academico_calificaciones SET cal_nota='".$nota."', cal_fecha_modificada=now(), cal_cantidad_modificaciones=cal_cantidad_modificaciones+1, cal_nota_anterior='".$notaAnterior."', cal_tipo=2 WHERE cal_id_actividad='".$codNota."' AND cal_id_estudiante='".$codEstudiante."'");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }

        $datosMensaje=["heading"=>"Cambios guardados","estado"=>"success","mensaje"=>"La nota de recuperación se ha guardado correctamente para el estudiante <b>".strtoupper($nombreEst)."</b>"];

        return $datosMensaje;
    }

    /**
     * Este metodo sirve para registrar las notas de comportamiento de un estudiante
     * 
     * @param mysqli    $conexion 
     * @param array     $config 
     * @param int       $codEstudiante 
     * @param string    $nombreEst 
     * @param int       $carga
     * @param double    $nota
     * @param int       $periodo
     * 
     * @return array // se retorna mensaje de confirmación
    **/
    public static function ajaxGuardarNotaDisciplina($conexion, $config, $codEstudiante, $nombreEst, $carga, $nota, $periodo)
    {
        if(trim($nota)==""){
            $datosMensaje=["heading"=>"Nota vacia","estado"=>"warning","mensaje"=>"Digite una nota correcta."];
            return $datosMensaje;
        }
        if($nota>$config[4]) $nota = $config[4]; if($nota<$config[3]) $nota = $config[3];

        try{
            $consultaNumD=mysqli_query($conexion, "SELECT * FROM ".BD_DISCIPLINA.".disiplina_nota WHERE dn_cod_estudiante='".$codEstudiante."' AND dn_periodo='".$periodo."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
        $numD = mysqli_num_rows($consultaNumD);

        if($numD==0){
            try{
                mysqli_query($conexion, "INSERT INTO ".BD_DISCIPLINA.".disiplina_nota(dn_cod_estudiante, dn_id_carga, dn_nota, dn_fecha, dn_periodo, institucion, year)VALUES('".$codEstudiante."','".$carga."','".$nota."', now(),'".$periodo."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
            } catch (Exception $e) {
                include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
            }
        }else{
            try{
                mysqli_query($conexion, "UPDATE ".BD_DISCIPLINA.".disiplina_nota SET dn_nota='".$nota."', dn_fecha=now() WHERE dn_cod_estudiante='".$codEstudiante."' AND dn_periodo='".$periodo."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
            } catch (Exception $e) {
                include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
            }
        }

        $datosMensaje=["heading"=>"Cambios guardados","estado"=>"success","mensaje"=>"La nota de comportamiento se ha guardado correctamente para el estudiante <b>".strtoupper($nombreEst)."</b>"];

        return $datosMensaje;
    }

    /**
     * Este metodo sirve para registrar las observaciones de comportamiento de un estudiante
     * 
     * @param mysqli    $conexion 
     * @param array     $config 
     * @param int       $codEstudiante 
     * @param string    $nombreEst 
     * @param int       $carga
     * @param double    $observacion
     * @param int       $periodo
     * 
     * @return array // se retorna mensaje de confirmación
    **/
    public static function ajaxGuardarObservacionDisciplina($conexion, $codEstudiante, $carga, $observacion, $periodo)
    {
        global $config;
        if(trim($observacion)==""){
            $datosMensaje=["heading"=>"Nota vacia","estado"=>"warning","mensaje"=>"Digite una observación correcta."];
            return $datosMensaje;
        }

        try{
            $consultaNumD=mysqli_query($conexion, "SELECT * FROM ".BD_DISCIPLINA.".disiplina_nota WHERE dn_cod_estudiante='".$codEstudiante."' AND dn_periodo='".$periodo."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
        $numD = mysqli_num_rows($consultaNumD);

        if($numD==0){
            try{
                mysqli_query($conexion, "INSERT INTO ".BD_DISCIPLINA.".disiplina_nota(dn_cod_estudiante, dn_id_carga, dn_observacion, dn_fecha, dn_periodo, institucion, year)VALUES('".$codEstudiante."','".$carga."','".mysqli_real_escape_string($conexion,$observacion)."', now(),'".$periodo."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
            } catch (Exception $e) {
                include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
            }
        }else{
            try{
                mysqli_query($conexion, "UPDATE ".BD_DISCIPLINA.".disiplina_nota SET dn_observacion='".mysqli_real_escape_string($conexion,$observacion)."', dn_fecha=now() WHERE dn_cod_estudiante='".$codEstudiante."'  AND dn_periodo='".$periodo."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
            } catch (Exception $e) {
                include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
            }
        }
        $datosEstudiante =Estudiantes::obtenerDatosEstudiante($codEstudiante);

        $datosMensaje=["heading"=>"Cambios guardados","estado"=>"success","mensaje"=>"La observación de comportamiento se ha guardado correctamente para el estudiante <b>".Estudiantes::NombreCompletoDelEstudiante($datosEstudiante)."</b>"];

        return $datosMensaje;
    }

    /**
     * Este metodo sirve para registrar una nota de comportamiento masiva a los estudiantes
     * 
     * @param mysqli    $conexion 
     * @param array     $datosCargaActual
     * @param int       $carga
     * @param int       $periodo
     * @param string    $nota
     * 
     * @return array // se retorna mensaje de confirmación
    **/
    public static function ajaxGuardarNotasDisciplinaMasiva($conexion, $datosCargaActual, $carga, $periodo, $nota)
    {
        global $config;
        if(trim($nota)==""){
            $datosMensaje=["heading"=>"Nota vacia","estado"=>"warning","mensaje"=>"Digite una nota correcta."];
            return $datosMensaje;
        }

        $consultaE = Estudiantes::escogerConsultaParaListarEstudiantesParaDocentes($datosCargaActual);
	
        $accionBD = 0;
        $datosInsert = '';
        $datosUpdate = '';
        $datosDelete = '';
    
        while($estudiantes = mysqli_fetch_array($consultaE, MYSQLI_BOTH)){
            $consultaNumE=mysqli_query($conexion, "SELECT * FROM ".BD_DISCIPLINA.".disiplina_nota WHERE dn_cod_estudiante='".$estudiantes['mat_id']."' AND dn_periodo='".$periodo."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
            $numE = mysqli_num_rows($consultaNumE);
            
            if($numE==0){
                $accionBD = 1;
                $datosDelete .="dn_cod_estudiante='".$estudiantes['mat_id']."' OR ";
                $datosInsert .="('".$estudiantes['mat_id']."','".$carga."','".$nota."', now(),'".$periodo."', {$config['conf_id_institucion']}, {$_SESSION["bd"]}),";
            }else{
                $accionBD = 2;
                $datosUpdate .="dn_cod_estudiante='".$estudiantes['mat_id']."' OR ";
            }
        }
        
        if($accionBD==1){
            $datosInsert = substr($datosInsert,0,-1);
            $datosDelete = substr($datosDelete,0,-4);
            
            mysqli_query($conexion, "DELETE FROM ".BD_DISCIPLINA.".disiplina_nota WHERE dn_periodo='".$periodo."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]} AND (".$datosDelete.")");
            
            
            mysqli_query($conexion, "INSERT INTO ".BD_DISCIPLINA.".disiplina_nota(dn_cod_estudiante, dn_id_carga, dn_nota, dn_fecha, dn_periodo, institucion, year)VALUES
            ".$datosInsert."
            ");
                
        }
        
        if($accionBD==2){
            $datosUpdate = substr($datosUpdate,0,-4);
            $datosUpdate .= " AND (institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]})"; 
            mysqli_query($conexion, "UPDATE ".BD_DISCIPLINA.".disiplina_nota SET dn_nota='".$nota."', dn_fecha=now() WHERE dn_periodo='".$periodo."' AND (".$datosUpdate.")");
        }

        $datosMensaje=["heading"=>"Cambios guardados","estado"=>"success","mensaje"=>'Se ha guardado la misma nota de comportamiento para todos los estudiantes en esta actividad. La página se actualizará en unos segundos para que vea los cambios...'];

        return $datosMensaje;
    }

    /**
     * Este metodo sirve para registrar las observaciones que se veran reflejadas en el boletin de un estudiante
     * 
     * @param mysqli    $conexion 
     * @param array     $config 
     * @param int       $codEstudiante 
     * @param string    $nombreEst 
     * @param int       $carga
     * @param double    $observacion
     * @param int       $periodo
     * 
     * @return array // se retorna mensaje de confirmación
    **/
    public static function ajaxGuardarObservacionBoletin($conexion, $codEstudiante, $carga, $observacion, $periodo)
    {
        if(trim($observacion)==""){
            $datosMensaje=["heading"=>"Nota vacia","estado"=>"warning","mensaje"=>"Digite una observación correcta."];
            return $datosMensaje;
        }

        try{
            $consultaNumD=mysqli_query($conexion, "SELECT * FROM academico_boletin WHERE bol_carga='".$carga."' AND bol_estudiante='".$codEstudiante."' AND bol_periodo='".$periodo."'");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
        $numD = mysqli_num_rows($consultaNumD);

        if($numD==0){
            try{
                mysqli_query($conexion, "INSERT INTO academico_boletin(bol_carga, bol_estudiante, bol_periodo, bol_tipo, bol_observaciones_boletin, bol_fecha_registro, bol_actualizaciones)VALUES('".$carga."', '".$codEstudiante."', '".$periodo."', 1, '".mysqli_real_escape_string($conexion,$observacion)."', now(), 0)");
            } catch (Exception $e) {
                include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
            }
        }else{
            try{
                mysqli_query($conexion, "UPDATE academico_boletin SET bol_observaciones_boletin='".mysqli_real_escape_string($conexion,$observacion)."', bol_actualizaciones=bol_actualizaciones+1, bol_ultima_actualizacion=now() WHERE bol_carga='".$carga."' AND bol_estudiante='".$codEstudiante."' AND bol_periodo='".$periodo."'");
            } catch (Exception $e) {
                include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
            }
        }
        $datosEstudiante =Estudiantes::obtenerDatosEstudiante($codEstudiante);

        $datosMensaje=["heading"=>"Cambios guardados","estado"=>"success","mensaje"=>"La observación para el boletín de este periodo se ha guardado correctamente para el estudiante <b>".Estudiantes::NombreCompletoDelEstudiante($datosEstudiante)."</b>"];

        return $datosMensaje;
    }

    /**
     * Este metodo sirve para registrar las notas de recuperacion de indicadores de un estudiante
     * 
     * @param mysqli    $conexion 
     * @param array     $config 
     * @param int       $codEstudiante 
     * @param string    $nombreEst 
     * @param int       $carga
     * @param int       $periodo
     * @param int       $codNota
     * @param double    $nota
     * @param double    $notaAnterior
     * 
     * @return array // se retorna mensaje de confirmación
    **/
    public static function ajaxGuardarRecuperacionIndicadores($conexion, $config, $codEstudiante, $carga, $periodo, $codNota, $nota, $notaAnterior)
    {
        if(trim($nota)==""){
            $datosMensaje=["heading"=>"Nota vacia","estado"=>"warning","mensaje"=>"Digite una nota correcta."];
            return $datosMensaje;
        }
        if($nota>$config[4]) $nota = $config[4]; if($nota<$config[3]) $nota = $config[3];
        $datosEstudiante =Estudiantes::obtenerDatosEstudiante($codEstudiante);

        //Consultamos si tiene registros en el boletín
        try{
            $consultaBoletinDatos=mysqli_query($conexion, "SELECT * FROM academico_boletin WHERE bol_carga='".$carga."' AND bol_periodo='".$periodo."' AND bol_estudiante='".$codEstudiante."'");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
        $boletinDatos = mysqli_fetch_array($consultaBoletinDatos, MYSQLI_BOTH);

        $caso = 1; //Inserta la nueva definitiva del indicador normal
        if(empty($boletinDatos['bol_id'])){
            $caso = 2;
            $mensajeNot = 'El estudiante <b>'.Estudiantes::NombreCompletoDelEstudiante($datosEstudiante).'</b> no presenta registros en el boletín actualmente para este periodo, en esta asignatura.';
            $heading = 'No se generó ningún cambio';
            $tipo = 'warning';
        }

        if($caso == 1){
            try{
                $consultaIndicador=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_indicadores_carga WHERE ipc_indicador='".$codNota."' AND ipc_carga='".$carga."' AND ipc_periodo='".$periodo."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
            } catch (Exception $e) {
                include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
            }
            $indicador = mysqli_fetch_array($consultaIndicador, MYSQLI_BOTH);
            $valorIndicador = ($indicador['ipc_valor']/100);
            $rindNotaActual = ($nota * $valorIndicador);

            try{
                $consultaNum=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_indicadores_recuperacion WHERE rind_carga='".$carga."' AND rind_estudiante='".$codEstudiante."' AND rind_periodo='".$periodo."' AND rind_indicador='".$codNota."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
            } catch (Exception $e) {
                include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
            }
            $num = mysqli_num_rows($consultaNum);

            if($num==0){
                try{
                    $codigo=Utilidades::generateCode("RIN");
                    mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_indicadores_recuperacion(rind_id, rind_fecha_registro, rind_estudiante, rind_carga, rind_nota, rind_indicador, rind_periodo, rind_actualizaciones, rind_nota_actual, rind_valor_indicador_registro, institucion, year)VALUES('".$codigo."', now(), '".$codEstudiante."', '".$carga."', '".$nota."', '".$codNota."', '".$periodo."', 1, '".$rindNotaActual."', '".$indicador['ipc_valor']."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
                } catch (Exception $e) {
                    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
                }
            }else{
                if($notaAnterior==""){$notaAnterior = "0.0";}
                try{
                    mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_indicadores_recuperacion SET rind_nota='".$nota."', rind_nota_anterior='".$notaAnterior."', rind_actualizaciones=rind_actualizaciones+1, rind_ultima_actualizacion=now(), rind_nota_actual='".$rindNotaActual."', rind_tipo_ultima_actualizacion=2, rind_valor_indicador_actualizacion='".$indicador['ipc_valor']."' WHERE rind_carga='".$carga."' AND rind_estudiante='".$codEstudiante."' AND rind_periodo='".$periodo."' AND rind_indicador='".$codNota."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
                } catch (Exception $e) {
                    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
                }
            }
            
            //Actualizamos la nota actual a los que la tengan nula.
            try{
                mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_indicadores_recuperacion SET rind_nota_actual=rind_nota_original WHERE rind_carga='".$carga."' AND rind_estudiante='".$codEstudiante."' AND rind_periodo='".$periodo."' AND rind_nota_actual IS NULL AND rind_nota_original=rind_nota AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
            } catch (Exception $e) {
                include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
            }

            //Se suman los decimales de todos los indicadores para obtener la definitiva de la asignatura
            try{
                $consultaRecuperacionIndicador=mysqli_query($conexion, "SELECT SUM(rind_nota_actual) FROM ".BD_ACADEMICA.".academico_indicadores_recuperacion WHERE rind_carga='".$carga."' AND rind_estudiante='".$codEstudiante."' AND rind_periodo='".$periodo."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
            } catch (Exception $e) {
                include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
            }
            $recuperacionIndicador = mysqli_fetch_array($consultaRecuperacionIndicador, MYSQLI_BOTH);
            
            $notaDefIndicador = round($recuperacionIndicador[0],1);

            try{
                mysqli_query($conexion, "UPDATE academico_boletin SET bol_nota_anterior=bol_nota, bol_nota='".$notaDefIndicador."', bol_actualizaciones=bol_actualizaciones+1, bol_ultima_actualizacion=now(), bol_nota_indicadores='".$notaDefIndicador."', bol_tipo=3, bol_observaciones='Actualizada desde el indicador.' WHERE bol_carga='".$carga."' AND bol_periodo='".$periodo."' AND bol_estudiante='".$codEstudiante."'");
            } catch (Exception $e) {
                include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
            }
            
            $mensajeNot = 'La recuperación del indicador de este periodo se ha guardado correctamente para el estudiante <b>'.Estudiantes::NombreCompletoDelEstudiante($datosEstudiante).'</b>. La nota definitiva de la asignatura ahora es <b>'.round($recuperacionIndicador[0],1)."</b>.";
            $heading = 'Cambios guardados';
            $tipo = 'success';
        }

        $datosMensaje=["heading"=>$heading,"estado"=>$tipo,"mensaje"=>$mensajeNot];

        return $datosMensaje;
    }

    /**
     * Este metodo sirve para registrar los aspectos academicos de los estudiantes
     * 
     * @param mysqli    $conexion 
     * @param int       $codEstudiante 
     * @param int       $carga
     * @param int       $periodo
     * @param double    $aspectoAcademico
     * 
     * @return array // se retorna mensaje de confirmación
    **/
    public static function ajaxGuardarAspectosAcademicos($conexion, $codEstudiante, $carga, $periodo, $aspectoAcademico)
    {
        global $config;
        if(trim($aspectoAcademico)==""){
            $datosMensaje=["heading"=>"Nota vacia","estado"=>"warning","mensaje"=>"Digite un aspecto correcto."];
            return $datosMensaje;
        }

        $consultaNumD=mysqli_query($conexion, "SELECT * FROM ".BD_DISCIPLINA.".disiplina_nota WHERE dn_cod_estudiante='".$codEstudiante."' AND dn_periodo='".$periodo."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        $numD = mysqli_num_rows($consultaNumD);
        $datosEstudiante =Estudiantes::obtenerDatosEstudiante($codEstudiante);
	
        if($numD==0){
            mysqli_query($conexion, "INSERT INTO ".BD_DISCIPLINA.".disiplina_nota(dn_cod_estudiante, dn_id_carga, dn_aspecto_academico, dn_periodo, institucion, year)VALUES('".$codEstudiante."','".$carga."','".$aspectoAcademico."', '".$periodo."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
        }else{
            mysqli_query($conexion, "UPDATE ".BD_DISCIPLINA.".disiplina_nota SET dn_aspecto_academico='".$aspectoAcademico."', dn_fecha_aspecto=now() WHERE dn_cod_estudiante='".$codEstudiante."'  AND dn_periodo='".$periodo."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        }

        $datosMensaje=["heading"=>"Cambios guardados","estado"=>"success","mensaje"=>'El aspecto academico se ha guardado correctamente para el estudiante <b>'.Estudiantes::NombreCompletoDelEstudiante($datosEstudiante).'</b>'];

        return $datosMensaje;
    }

    /**
     * Este metodo sirve para registrar los aspectos convivencial de los estudiantes
     * 
     * @param mysqli    $conexion 
     * @param int       $codEstudiante 
     * @param int       $carga
     * @param int       $periodo
     * @param double    $aspectoConvivencial
     * 
     * @return array // se retorna mensaje de confirmación
    **/
    public static function ajaxGuardarAspectosConvivencional($conexion, $codEstudiante, $carga, $periodo, $aspectoConvivencial)
    {
        global $config;
        if(trim($aspectoConvivencial)==""){
            $datosMensaje=["heading"=>"Nota vacia","estado"=>"warning","mensaje"=>"Digite un aspecto correcto."];
            return $datosMensaje;
        }

        $consultaNumD=mysqli_query($conexion, "SELECT * FROM ".BD_DISCIPLINA.".disiplina_nota WHERE dn_cod_estudiante='".$codEstudiante."' AND dn_periodo='".$periodo."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        $numD = mysqli_num_rows($consultaNumD);
        $datosEstudiante =Estudiantes::obtenerDatosEstudiante($codEstudiante);
	
        if($numD==0){
            mysqli_query($conexion, "INSERT INTO ".BD_DISCIPLINA.".disiplina_nota(dn_cod_estudiante, dn_id_carga, dn_aspecto_convivencial, dn_periodo, institucion, year)VALUES('".$codEstudiante."','".$carga."','".$aspectoConvivencial."', '".$periodo."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
        }else{
            mysqli_query($conexion, "UPDATE ".BD_DISCIPLINA.".disiplina_nota SET dn_aspecto_convivencial='".$aspectoConvivencial."', dn_fecha_aspecto=now() WHERE dn_cod_estudiante='".$codEstudiante."'  AND dn_periodo='".$periodo."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        }

        $datosMensaje=["heading"=>"Cambios guardados","estado"=>"success","mensaje"=>'El aspecto convivencial se ha guardado correctamente para el estudiante <b>'.Estudiantes::NombreCompletoDelEstudiante($datosEstudiante).'</b>'];

        return $datosMensaje;
    }
}