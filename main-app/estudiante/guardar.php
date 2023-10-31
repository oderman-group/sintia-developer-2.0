<?php
include("session.php");
include("verificar-usuario.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'ES0053';
include("../compartido/historial-acciones-guardar.php");

include("../compartido/sintia-funciones.php");

$archivoSubido = new Archivos;

if(!empty($_POST["id"])){
	//ACTUALIZAR MATRICULA
	if($_POST["id"]==1000){
		try{
			mysqli_query($conexion, "UPDATE academico_matriculas SET mat_tipo_documento='".$_POST["tipoD"]."', mat_documento='".$_POST["nDoc"]."', mat_religion='".$_POST["religion"]."', mat_email='".$_POST["email"]."', mat_direccion='".$_POST["direccion"]."', mat_barrio='".$_POST["barrio"]."', mat_telefono='".$_POST["telefono"]."', mat_celular='".$_POST["celular"]."', mat_estrato='".$_POST["estrato"]."', mat_genero='".$_POST["genero"]."', mat_fecha_nacimiento='".$_POST["fNac"]."', mat_primer_apellido='".$_POST["apellido1"]."', mat_segundo_apellido='".$_POST["apellido2"]."', mat_nombres='".$_POST["nombres"]."', mat_grado='".$_POST["grado"]."', mat_tipo='".$_POST["tipoEst"]."' WHERE mat_id_usuario='".$_SESION["id"]."'");

			mysqli_query($conexion, "UPDATE usuarios SET uss_usuario='".$_POST["nDoc"]."', uss_email='".$_POST["email"]."' WHERE uss_id='".$_SESION["id"]."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="matricula.php";</script>';
		exit();
	}

	//GUARDAR COMENTARIO
	if($_POST["id"]==7){
		try{
			mysqli_query($conexion, "INSERT INTO academico_actividad_foro_comentarios(com_id_foro, com_descripcion, com_id_estudiante, com_fecha)VALUES('".$_POST["idForo"]."', '".$_POST["com"]."', '".$_SESION["id"]."', now())");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
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
		<script type="text/javascript">
			function redirige(){
				window.location.href='foros-ver.php?idForo=<?=$_POST["idForo"];?>';
			}
			setTimeout ("redirige()", 2000);	
		</script>
	<?php
		exit();
	}

	//GUARDAR RESPUESTA
	if($_POST["id"]==8){
		try{
			mysqli_query($conexion, "INSERT INTO academico_actividad_foro_respuestas(fore_id_comentario, fore_respuesta, fore_id_estudiante, fore_fecha)VALUES('".$_POST["idCom"]."', '".$_POST["respu"]."', '".$_SESION["id"]."', now())");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
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
		<script type="text/javascript">
			function redirige(){
				window.location.href='foros-ver.php?idForo=<?=$_POST["idForo"];?>';
			}
			setTimeout ("redirige()", 2000);	
		</script>
	<?php
		exit();
	}

	//GUARDAR RESPUESTAS EVALUACIONES

	//ENVIAR ACTIVIDAD

	//FIRMAR ASPECTOS
}

//GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET

if(!empty($_GET["get"])){
	//FIRMA DIGITAL DE LOS REPORTES
}

//EN CASO DE QUE NO ENTRE POR NINGUNA DE LAS ANTERIORES
$_GET["get"] == 0;
include("../compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="https://plataformasintia.com?error=1";</script>';
exit();
?>