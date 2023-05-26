<?php
$tiempo_inicial = microtime(true);
require_once("../modelo/conexion.php");
require_once("../class/Plataforma.php");
require_once("../class/Utilidades.php");
require_once("../class/Modulos.php");

$Utilidades = new Utilidades; 
$Plataforma = new Plataforma;
$config = $_SESSION["configuracion"];

$informacion_inst = $_SESSION["informacionInstConsulta"];

$datosUnicosInstitucion = $_SESSION["datosUnicosInstitucion"];
$yearArray = explode(",", $datosUnicosInstitucion['ins_years']);
$yearStart = $yearArray[0];
$yearEnd = $yearArray[1];

//CONFIGURACIÓN GENERAL
$opcionSINO = array ("NO","SI");
$mesesAgno = array("01"=>"Enero","02"=>"Febrero","03"=>"Marzo","04"=>"Abril","05"=>"Mayo","06"=>"Junio","07"=>"Julio","08"=>"Agosto","09"=>"Septiembre","10"=>"Octubre","11"=>"Noviembre","12"=>"Diciembre");
$opcionEstado = array("INACTIVO", "ACTIVO");
$estadosMatriculasEstudiantes = array("","Matriculado","Asistente","Cancelado","No Matriculado");
$clavePorDefectoUsuarios = SHA1('12345678');
$estadosEtiquetasMatriculas = array("","text-success","text-warning","text-danger","text-warning");