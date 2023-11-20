<?php
session_start();
include("../../config-general/config.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
$consultaIndicadorObg=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_indicadores WHERE ind_id='".$_POST["indicador"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
$indicadorObg = mysqli_fetch_array($consultaIndicadorObg, MYSQLI_BOTH);

$consultaCargasEjemplo=mysqli_query($conexion, "SELECT * FROM academico_cargas WHERE car_id='".$_POST["carga"]."'");
$cargaEjemplo = mysqli_fetch_array($consultaCargasEjemplo, MYSQLI_BOTH);

$cargas = mysqli_query($conexion, "SELECT * FROM academico_cargas WHERE car_curso='".$cargaEjemplo['car_curso']."' AND car_materia='".$cargaEjemplo['car_materia']."'");

while($cgs = mysqli_fetch_array($cargas, MYSQLI_BOTH)){
	$consultaIpc=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_indicadores_carga WHERE ipc_carga='".$cgs[0]."' AND ipc_indicador='".$_POST["indicador"]."' AND ipc_creado=0 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
	$ipc = mysqli_fetch_array($consultaIpc, MYSQLI_BOTH);
	if($ipc[0]==""){
		$p=1;
		while($p<=$config['conf_periodos_maximos']){
			$codigo=Utilidades::generateCode("IPC");
			mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_indicadores_carga(ipc_id, ipc_carga, ipc_indicador, ipc_valor, ipc_periodo, ipc_creado, institucion, year)VALUES('".$codigo."', '".$cgs[0]."','".$_POST["indicador"]."','".$indicadorObg['ind_valor']."','".$p."',0, {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
			
			$p++;
		}
	}else{
		mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_indicadores_carga WHERE ipc_carga='".$cgs[0]."' AND ipc_indicador='".$_POST["indicador"]."' AND ipc_creado=0 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
		
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