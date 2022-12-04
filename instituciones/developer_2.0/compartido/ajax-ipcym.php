<?php include("../../../config-general/config.php");?>
<?php
include("../modelo/conexion.php");
$indicadorObg = mysql_fetch_array(mysql_query("SELECT * FROM academico_indicadores WHERE ind_id='".$_POST["indicador"]."'",$conexion));

$cargaEjemplo = mysql_fetch_array(mysql_query("SELECT * FROM academico_cargas WHERE car_id='".$_POST["carga"]."'",$conexion));

$cargas = mysql_query("SELECT * FROM academico_cargas WHERE car_curso='".$cargaEjemplo['car_curso']."' AND car_materia='".$cargaEjemplo['car_materia']."'",$conexion);

while($cgs = mysql_fetch_array($cargas)){
	$ipc = mysql_fetch_array(mysql_query("SELECT * FROM academico_indicadores_carga WHERE ipc_carga='".$cgs[0]."' AND ipc_indicador='".$_POST["indicador"]."' AND ipc_creado=0",$conexion));
	if($ipc[0]==""){
		$p=1;
		while($p<=$config['conf_periodos_maximos']){
			mysql_query("INSERT INTO academico_indicadores_carga(ipc_carga, ipc_indicador, ipc_valor, ipc_periodo, ipc_creado)VALUES('".$cgs[0]."','".$_POST["indicador"]."','".$indicadorObg['ind_valor']."','".$p."',0)",$conexion);
			if(mysql_errno()!=0){echo mysql_error(); exit();}
			$p++;
		}
	}else{
		mysql_query("DELETE FROM academico_indicadores_carga WHERE ipc_carga='".$cgs[0]."' AND ipc_indicador='".$_POST["indicador"]."' AND ipc_creado=0",$conexion);
		if(mysql_errno()!=0){echo mysql_error(); exit();}
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