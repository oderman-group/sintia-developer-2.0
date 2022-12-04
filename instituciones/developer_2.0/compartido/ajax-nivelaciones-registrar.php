<?php //include("session.php");
/*
echo "Carga:". $_POST["carga"]."<br>";
echo "codEst:". $_POST["codEst"]."<br>";
echo "Opcion:". $_POST["op"]."<br>";
echo "Nota:". $_POST["nota"]."<br>";
exit();
*/
?>
<?php include("../../../config-general/config.php");?>
<?php
$datosCargaActual = mysql_fetch_array(mysql_query("SELECT * FROM academico_cargas WHERE car_id='".$_POST["carga"]."' AND car_activa=1",$conexion));
?>
<?php
if(trim($_POST["nota"])==""){
    echo "<span style='color:red; font-size:16px;'>Digite una nota correcta</span>";
	exit();
}
if($_POST["op"]==1){
	if($_POST["nota"]>$config[4]) $_POST["nota"] = $config[4]; if($_POST["nota"]<1) $_POST["nota"] = 1;
}
include("../modelo/conexion.php");
$consulta = mysql_query("SELECT * FROM academico_nivelaciones WHERE niv_cod_estudiante='".$_POST["codEst"]."' AND niv_id_asg='".$_POST["carga"]."'",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}
$num = mysql_num_rows($consulta);
$rB = mysql_fetch_array($consulta);
if($num==0 and $_POST["op"]==1){
	mysql_query("DELETE FROM academico_nivelaciones WHERE niv_id='".$rB[0]."'",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}
	mysql_query("INSERT INTO academico_nivelaciones(niv_id_asg, niv_cod_estudiante, niv_definitiva, niv_fecha)VALUES('".$_POST["carga"]."','".$_POST["codEst"]."','".$_POST["nota"]."',now())",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}
}else{
	switch($_POST["op"]){
		case 1:
			mysql_query("UPDATE academico_nivelaciones SET niv_definitiva='".$_POST["nota"]."' WHERE niv_id='".$rB[0]."'",$conexion);
			if(mysql_errno()!=0){echo mysql_error(); exit();}
		break;
		
		case 2:
			mysql_query("UPDATE academico_nivelaciones SET niv_acta='".$_POST["nota"]."' WHERE niv_id='".$rB[0]."'",$conexion);
			if(mysql_errno()!=0){echo mysql_error(); exit();}
		break;
		
		case 3:
			mysql_query("UPDATE academico_nivelaciones SET niv_fecha_nivelacion='".$_POST["nota"]."' WHERE niv_id='".$rB[0]."'",$conexion);
			if(mysql_errno()!=0){echo mysql_error(); exit();}
		break;
	}
}	
?>
	<script type="text/javascript">
		function notifica(){
			var unique_id = $.gritter.add({
				// (string | mandatory) the heading of the notification
				title: 'Correcto',
				// (string | mandatory) the text inside the notification
				text: 'Los cambios se ha guardado correctamente!',
				// (string | optional) the image to display on the left
				image: 'files/iconos/Accept-Male-User.png',
				// (bool | optional) if you want it to fade out on its own or just sit there
				sticky: false,
				// (int | optional) the time you want it to be alive for before fading out
				time: '3000',
				// (string | optional) the class name you want to apply to that specific message
				class_name: 'my-sticky-class'
			});
		}
		
		setTimeout ("notifica()", 100);	
	</script>
    <div class="alert alert-success">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<i class="icon-exclamation-sign"></i><strong>INFORMACI&Oacute;N:</strong> Los cambios se ha guardado correctamente!.
	</div>
<?php	
	exit();
?>