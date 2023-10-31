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
	if($_POST["id"]==9){
		//SABER SI EL ESTUDIANTE YA HIZO LA EVALUACION
		try{
			$consultaEvaluacion=mysqli_query($conexion, "SELECT * FROM academico_actividad_evaluaciones_resultados 
			WHERE res_id_evaluacion='".$_POST["idE"]."' AND res_id_estudiante='".$datosEstudianteActual[0]."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$nume = mysqli_num_rows($consultaEvaluacion);

		if($nume>0 and $_POST["envioauto"]=='0'){

			include("../compartido/guardar-historial-acciones.php");
			echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=200";</script>';
			exit();
		}

		//BORRAR LAS RESPUESTAS ANTES DE VOLVER A GUARDAR
		try{
			mysqli_query($conexion, "DELETE FROM academico_actividad_evaluaciones_resultados
			WHERE res_id_estudiante='".$datosEstudianteActual[0]."' AND res_id_evaluacion='".$_POST["idE"]."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		//Cantidad de preguntas de la evaluación
		try{
			$preguntasConsulta = mysqli_query($conexion, "SELECT * FROM academico_actividad_evaluacion_preguntas
			INNER JOIN academico_actividad_preguntas ON preg_id=evp_id_pregunta
			WHERE evp_id_evaluacion='".$_POST["idE"]."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$cantPreguntas = mysqli_num_rows($preguntasConsulta);

		$contPreguntas = 1;
		while($preguntas = mysqli_fetch_array($preguntasConsulta, MYSQLI_BOTH)){
			try{
				$respuestasConsulta = mysqli_query($conexion, "SELECT * FROM academico_actividad_respuestas
				WHERE resp_id_pregunta='".$preguntas['preg_id']."'");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}		
			$cantRespuestas = mysqli_num_rows($respuestasConsulta);
			if($cantRespuestas==0) {
				continue;
			}

			//GUARDAR RESPUESTAS
			$archivo = '';
			if($preguntas['preg_tipo_pregunta']==3){
				$idPregunta = $preguntas['preg_id'];
				$destino = "../files/evaluaciones";
				if($_FILES['file'.$idPregunta]['name']!=""){
					$nombreInputFile = 'file'.$idPregunta;
					$archivoSubido->validarArchivo($_FILES['file'.$idPregunta]['size'], $_FILES['file'.$idPregunta]['name']);
					$_FILES['file'.$idPregunta]['name'];
					$extension = end(explode(".", $_FILES['file'.$idPregunta]['name']));
					$archivo = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_eva_res_').".".$extension;
					@unlink($destino."/".$archivo);
					$archivoSubido->subirArchivo($destino, $archivo, $nombreInputFile);
				}
			}
			if($_POST["R$contPreguntas"]=="") $_POST["R$contPreguntas"] = 0;
			try{
				mysqli_query($conexion, "INSERT INTO academico_actividad_evaluaciones_resultados(res_id_pregunta, res_id_respuesta, res_id_estudiante, res_id_evaluacion, res_archivo)
				VALUES('".$_POST["P$contPreguntas"]."', '".$_POST["R$contPreguntas"]."', '".$datosEstudianteActual[0]."', '".$_POST["idE"]."', '".$archivo."')");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}
			$contPreguntas ++;
		}

		//ACTUALIZAR QUE EL ESTUDIANTE TERMINÓ
		try{
			mysqli_query($conexion, "UPDATE academico_actividad_evaluaciones_estudiantes SET epe_fin=now() 
			WHERE epe_id_estudiante='".$datosEstudianteActual[0]."' AND epe_id_evaluacion='".$_POST["idE"]."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=103&idE='.base64_encode($_POST["idE"]).'";</script>';
		exit();
	}

	//ENVIAR ACTIVIDAD

	//FIRMAR ASPECTOS
	if($_POST["id"]==11){
		try{
			mysqli_query($conexion, "UPDATE disiplina_nota SET dn_aprobado=1, dn_fecha_aprobado=now()
			WHERE dn_cod_estudiante=" . $_POST["estudiante"] . " AND dn_periodo='" . $_POST["periodo"] . "'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="aspectos.php";</script>';
		exit();
	}
}

//GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET

if(!empty($_GET["get"])){
	//FIRMA DIGITAL DE LOS REPORTES
	if(base64_decode($_GET["get"])==1){
		$id=base64_decode($_GET["id"]);
		try{
			mysqli_query($conexion, "UPDATE disciplina_reportes SET dr_aprobacion_estudiante=1, dr_aprobacion_estudiante_fecha=now() WHERE dr_id='".$id."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="reportes-disciplinarios.php";</script>';
		exit();
	}
}

//EN CASO DE QUE NO ENTRE POR NINGUNA DE LAS ANTERIORES
$_GET["get"] == 0;
include("../compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="https://plataformasintia.com?error=1";</script>';
exit();
?>