<?php
include($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
//require_once("conexion-datos.php");
require_once("main-app/class/UsuariosPadre.php");
require_once(ROOT_PATH."/main-app/class/EnviarEmail.php");

//DATOS DE FECHA ACTUAL
date_default_timezone_set("America/New_York");
$fechaActual=date("Y/m/d");  
$valoresSegunda = explode ("/", $fechaActual); 
$diaSegunda   = $valoresSegunda[2];  
$mesSegunda = $valoresSegunda[1];  
$anyoSegunda  = $valoresSegunda[0];
$diasSegundaJuliano = gregoriantojd($mesSegunda, $diaSegunda, $anyoSegunda); 

//CONSULTA INSTITUCIONES ACTIVAS
$conexionBaseDatosServicios = mysqli_connect($servidorConexion, $usuarioConexion, $claveConexion, $baseDatosServicios);
$institucionConsulta = mysqli_query($conexionBaseDatosServicios, "SELECT * FROM instituciones 
WHERE ins_estado=1 AND ins_enviroment='".ENVIROMENT."'");

//CICLO PARA EJECUTAR NOTIFICACION
while($datosInstituciones=mysqli_fetch_array($institucionConsulta, MYSQLI_BOTH)){

  //DATOS FECHA DE RENOVACION
  $fechaR=$datosInstituciones['ins_fecha_renovacion'];
  $fechaRenovacion=date("Y/m/d", strtotime($fechaR));
  $valoresPrimera = explode ("/", $fechaRenovacion);
  $diaPrimera    = $valoresPrimera[2];  
  $mesPrimera  = $valoresPrimera[1];  
  $anyoPrimera   = ($valoresPrimera[0]+1); 
  $diasPrimeraJuliano = gregoriantojd($mesPrimera, $diaPrimera, $anyoPrimera);

  //VALIDA FECHAS
  if(!checkdate($mesPrimera, $diaPrimera, $anyoPrimera) || !checkdate($mesSegunda, $diaSegunda, $anyoSegunda)){
    echo 'Fecha invalida.';
    exit();
  }

  //OPERACION PARA SABER DIAS FALTANTES
  $dfDias=$diasPrimeraJuliano - $diasSegundaJuliano;

  //VALIDAMOS DIAS PARA NOTIFICAR POR CORREO
  if($dfDias==90 || $dfDias==30 || $dfDias==5 || $dfDias==1){

    //CANTIDAD EN MESES
    $falta="";
    if($dfDias==90){$falta="3 meses";}
    if($dfDias==30){$falta="1 mes";}
    if($dfDias==5){$falta="5 dias";}
    if($dfDias==1){$falta="1 dia";}

    //CONSULTAMOS DIRECTIVOS ACTIVOS DE LA INSTITUCION
    $conexionUsuarios = mysqli_connect($servidorConexion, $usuarioConexion, $claveConexion, $datosInstituciones['ins_bd']."_".date("Y"));
    $directivosConsulta = mysqli_query($conexionUsuarios, "SELECT * FROM usuarios WHERE uss_tipo=5 AND uss_estado=1 AND uss_permiso1=".CODE_PRIMARY_MANAGER);
    
    //CICLO PARA ENVIAR CORREO A DIRECTIVOS
    while($datosDirectivos=mysqli_fetch_array($directivosConsulta, MYSQLI_BOTH)){

      $data = [
        'falta'   => $falta,
        'institucion_nombre' => strtoupper($datosInstituciones['ins_nombre']),
        'usuario_email'    => $datosDirectivos['uss_email'],
        'usuario_nombre'   => UsuariosPadre::nombreCompletoDelUsuario($datosDirectivos)
      ];
      $asunto = 'NOTIFICACIÓN DE VENCIMIENTO DE LICENCIA';
      $bodyTemplateRoute = ROOT_PATH.'/config-general/plantilla-email-1.php';
      
      EnviarEmail::enviar($data, $asunto, $bodyTemplateRoute);

    }

  }
}