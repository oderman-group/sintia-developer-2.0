<?php include("session.php");?>
<?php
$datosCargaActual = mysql_fetch_array(mysql_query("SELECT * FROM academico_cargas WHERE car_id='".$_POST["carga"]."' AND car_activa=1",$conexion));
?>
<?php
if(trim($_POST["nota"])==""){
    echo "<span style='color:red; font-size:16px;'>Digite una nota correcta</span>";
	exit();
}
if($_POST["nota"]>$config[4]) $_POST["nota"] = $config[4]; if($_POST["nota"]<1) $_POST["nota"] = 1;
include("../modelo/conexion.php");
$consulta = mysql_query("SELECT * FROM academico_boletin WHERE bol_estudiante='".$_POST["codEst"]."' AND bol_carga='".$_COOKIE["carga"]."' AND bol_periodo='".$_POST["per"]"'",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}
$num = mysql_num_rows($consulta);
$rB = mysql_fetch_array($consulta);
if($num==0){
	mysql_query("DELETE FROM academico_boletin WHERE bol_id='".$rB[0]."'",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}
	mysql_query("INSERT INTO academico_boletin(bol_carga, bol_estudiante, bol_periodo, bol_nota, bol_tipo, bol_observaciones)VALUES('".$_COOKIE["carga"]."','".$_POST["codEst"]."','".$_POST["per"]."','".$_POST["nota"]."',2, '')",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}
	mysql_query("INSERT INTO seguridad_historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha)VALUES('".$idSession."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', 'Inserción de notas en el periodo', now())",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}
}else{
	mysql_query("UPDATE academico_boletin SET bol_nota='".$_POST["nota"]."', bol_observaciones='', bol_tipo=2 WHERE bol_id=".$rB[0],$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}
	mysql_query("INSERT INTO seguridad_historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha)VALUES('".$idSession."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', 'Actualización de notas en el periodo', now())",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}
}	

?>
<script type="text/javascript">
function notifica(){
	$.toast({
		heading: 'Cambios guardados',  
		text: 'Los cambios se ha guardado correctamente!.',
		position: 'botom-left',
		loaderBg:'#ff6849',
		icon: 'success',
		hideAfter: 3000, 
		stack: 6
	});
}
setTimeout ("notifica()", 100);
</script>

    <div class="alert alert-success">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<i class="icon-exclamation-sign"></i><strong>INFORMACI&Oacute;N:</strong> Los cambios se ha guardado correctamente!.
	</div>