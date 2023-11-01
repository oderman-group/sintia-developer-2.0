<?php
$tiempo_inicial = microtime(true);
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/modelo/conexion.php");
require_once(ROOT_PATH."/main-app/class/Plataforma.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
require_once(ROOT_PATH."/main-app/class/Modulos.php");

$Utilidades = new Utilidades; 
$Plataforma = new Plataforma;
$config = $_SESSION["configuracion"];

$informacion_inst = $_SESSION["informacionInstConsulta"];

$datosUnicosInstitucion = $_SESSION["datosUnicosInstitucion"];
$yearArray = explode(",", $datosUnicosInstitucion['ins_years']);
$yearStart = $yearArray[0];
$yearEnd = $yearArray[1];

//CONFIGURACIÓN GENERAL
$opcionSINO =  [
    0 => "NO",
    1 => "SI",
    null => "NO"
];
$mesesAgno = array("01"=>"Enero","02"=>"Febrero","03"=>"Marzo","04"=>"Abril","05"=>"Mayo","06"=>"Junio","07"=>"Julio","08"=>"Agosto","09"=>"Septiembre","10"=>"Octubre","11"=>"Noviembre","12"=>"Diciembre");
$opcionEstado = array("INACTIVO", "ACTIVO");
$estadosMatriculasEstudiantes = [
    1 => "Matriculado",
    2 => "Asistente",
    3 => "Cancelado",
    4 => "No Matriculado",
    5 => "En inscripción"
];
$clavePorDefectoUsuarios = SHA1('12345678');
$estadosEtiquetasMatriculas = array("","text-success","text-warning","text-danger","text-warning");
$opcionesGenerales = array("","T. Documento","Religion","Estratos","Generos","Nuevo/Antiguo","Dias","Nivel Educativo","Estado Civil","Estado Laboral","T. de Empresa","Si/No","T. de Vivienda","T. de Trasporte","T. de Sangre","Boletines");