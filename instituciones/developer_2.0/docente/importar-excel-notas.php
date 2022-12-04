<?php include("session.php");?>
<?php include("verificar-carga.php");?>
<?php
if($_FILES['planilla']['name']!=""){
	$archivo = $_FILES['planilla']['name']; $destino = "../files/excel";
	move_uploaded_file($_FILES['planilla']['tmp_name'], $destino ."/".$archivo);
}
?>
<?php
//set_time_limit (0);

// Test CVS
require_once '../../../librerias/Excel/reader.php';


// ExcelFile($filename, $encoding);
$data = new Spreadsheet_Excel_Reader();

// Set output Encoding.
$data->setOutputEncoding('CP1251');

//Read File
$data->read('../files/excel/'.$archivo);

error_reporting(E_ALL ^ E_NOTICE);
/*
for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
	for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
		echo $data->sheets[0]['cells'][$i][$j].", ";	
	}
	echo "<br>";
}
*/

$accionBD = 0;
$datosInsert = '';
$datosUpdate = '';
$datosDelete = '';
for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
	if(trim($data->sheets[0]['cells'][$i][2])!="" and trim($data->sheets[0]['cells'][$i][4])!=""){
		$numE = mysql_num_rows(mysql_query("SELECT academico_calificaciones.cal_id_actividad, academico_calificaciones.cal_id_estudiante FROM academico_calificaciones 
		WHERE academico_calificaciones.cal_id_actividad='".$_POST["idR"]."' AND academico_calificaciones.cal_id_estudiante='".$data->sheets[0]['cells'][$i][2]."'",$conexion));
		$lineaError = __LINE__;
		include("../compartido/reporte-errores.php");
		if($numE==0){
			$accionBD = 1;
			$datosDelete .="cal_id_estudiante='".$data->sheets[0]['cells'][$i][2]."' OR ";
			$datosInsert .="('".$data->sheets[0]['cells'][$i][2]."','".$data->sheets[0]['cells'][$i][4]."','".$_POST["idR"]."', now(), 0, '".$data->sheets[0]['cells'][$i][5]."'),";
		}else{
			mysql_query("UPDATE academico_calificaciones SET cal_nota='".$data->sheets[0]['cells'][$i][4]."', cal_fecha_modificada=now(), cal_cantidad_modificaciones=cal_cantidad_modificaciones+1 WHERE cal_id_actividad='".$_POST["idR"]."' AND cal_id_estudiante='".$data->sheets[0]['cells'][$i][2]."'",$conexion);
			$lineaError = __LINE__;
			include("../compartido/reporte-errores.php");
		}
	}
}

if($accionBD==1){
	$datosInsert = substr($datosInsert,0,-1);
	$datosDelete = substr($datosDelete,0,-4);
		
	mysql_query("DELETE FROM academico_calificaciones WHERE cal_id_actividad='".$_POST["idR"]."' AND (".$datosDelete.")",$conexion);
	$lineaError = __LINE__;
	include("../compartido/reporte-errores.php");
		
	mysql_query("INSERT INTO academico_calificaciones(cal_id_estudiante, cal_nota, cal_id_actividad, cal_fecha_registrada, cal_cantidad_modificaciones, cal_observaciones)VALUES
	".$datosInsert."
	",$conexion);
	$lineaError = __LINE__;
	include("../compartido/reporte-errores.php");	
}

mysql_query("UPDATE academico_actividades SET act_registrada=1 WHERE act_id='".$_POST["idR"]."'",$conexion);
$lineaError = __LINE__;
include("../compartido/reporte-errores.php");

echo '<script type="text/javascript">window.location.href="calificaciones.php";</script>';
exit();


//print_r($data);
//print_r($data->formatRecords);
?>
