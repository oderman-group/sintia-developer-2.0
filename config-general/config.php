<?php
$tiempo_inicial = microtime(true);
include("../modelo/conexion.php");
include("../class/Plataforma.php");
include("../../librerias/Utilidades/util.php");

$Plataforma = new Plataforma; // Variable que manejará los datos de configuracion de la visualizacionde la plataforma icono y colores
//$configConsulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".configuracion WHERE conf_base_datos='".$_SESSION["inst"]."' AND conf_agno='".$_SESSION["bd"]."'");
$config = $_SESSION["configuracion"];

//$informacionInstConsulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".general_informacion WHERE info_institucion='" . $config['conf_id_institucion'] . "' AND info_year='" . $_SESSION["bd"] . "'");
$informacion_inst = $_SESSION["informacionInstConsulta"];

//$datosUnicosInstitucionConsulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".instituciones WHERE ins_id='".$config['conf_id_institucion']."'");
$datosUnicosInstitucion = $_SESSION["datosUnicosInstitucion"];
$yearArray = explode(",", $datosUnicosInstitucion['ins_years']);
$yearStart = $yearArray[0];
$yearEnd = $yearArray[1];

//CONFIGURACIÓN GENERAL
$opcionSINO = array ("NO","SI");
$mesesAgno = array("01"=>"Enero","02"=>"Febrero","03"=>"Marzo","04"=>"Abril","05"=>"Mayo","06"=>"Junio","07"=>"Julio","08"=>"Agosto","09"=>"Septiembre","10"=>"Octubre","11"=>"Noviembre","12"=>"Diciembre");
$opcionEstado = array("INACTIVO", "ACTIVO");
$estadosMatriculasEstudiantes = array("","Matriculado","Asistente","Cancelado","No Matriculado");
$clavePorDefectoUsuarios = '12345678';
$estadosEtiquetasMatriculas = array("","text-success","text-warning","text-danger","text-warning");