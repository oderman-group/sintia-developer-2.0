<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0089';
include("../compartido/historial-acciones-guardar.php");

include("../compartido/sintia-funciones.php");
require_once("../class/CargaAcademica.php");

$archivoSubido = new Archivos;
$operacionBD = new BaseDatos;

//SELECCIONAR UNA CARGA - DEBE ESTAR ARRIBA POR LAS COOKIES QUE CREA.
if(!empty($_GET["get"]) && base64_decode($_GET["get"])==100){
	$carga = base64_decode($_GET["carga"]);
	$periodo = base64_decode($_GET["periodo"]);
	if(is_numeric($carga)){
		setcookie("carga",$carga);
		setcookie("periodo",$periodo);
		
		$infoCargaActual = CargaAcademica::cargasDatosEnSesion($carga, $_SESSION["id"]);
		$_SESSION["infoCargaActual"] = $infoCargaActual;

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="pagina-opciones.php?carga='.$_GET["carga"].'&periodo='.$_GET["periodo"].'";</script>';
		exit();
	}
}

if(!empty($_POST["id"])){
	//AGREGAR RESPUESTAS
	if($_POST["id"]==6){//No se llama de ningun lado
		try{
			mysqli_query($conexion, "INSERT INTO academico_actividad_respuestas(resp_descripcion, resp_correcta, resp_id_pregunta)VALUES('".mysqli_real_escape_string($conexion,$_POST["respuesta"])."',0,'".$_POST["idPregunta"]."')");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="academico-evaluaciones-ver.php?idEvaluacion='.$_POST["idEvaluacion"].'#P'.$_POST["idPregunta"].'";</script>';
		exit();
	}

	//GUARDAR COMENTARIO
	if($_POST["id"]==17){//No se llama de ningun lado
		try{
			mysqli_query($conexion, "INSERT INTO academico_actividad_foro_comentarios(com_id_foro, com_descripcion, com_id_estudiante, com_fecha)VALUES('".$_POST["idForo"]."', '".mysqli_real_escape_string($conexion,$_POST["com"])."', '".$idSession."', now())");
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
				window.location.href='academico-foros-ver.php?idForo=<?=$_POST["idForo"];?>';
			}
			setTimeout ("redirige()", 2000);	
		</script>
	<?php
		exit();
	}

	//GUARDAR RESPUESTA
	if($_POST["id"]==18){//No se llama de ningun lado

		try{
			mysqli_query($conexion, "INSERT INTO academico_actividad_foro_respuestas(fore_id_comentario, fore_respuesta, fore_id_estudiante, fore_fecha)VALUES('".$_POST["idCom"]."', '".mysqli_real_escape_string($conexion,$_POST["respu"])."', '".$idSession."', now())");
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
				window.location.href='academico-foros-ver.php?idForo=<?=$_POST["idForo"];?>';
			}
			setTimeout ("redirige()", 2000);	
		</script>
	<?php
		exit();
	}

	//GUARDAR PREGUNTA/OPINIÓN
	if($_POST["id"]==26){//No se llama de ningun lado
		try{
			mysqli_query($conexion, "INSERT INTO academico_clases_preguntas(cpp_usuario, cpp_fecha, cpp_id_clase, cpp_contenido)VALUES('".$idSession."', now(), '".$_POST["idClase"]."', '".mysqli_real_escape_string($conexion,$_POST["contenido"])."')");
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
				window.location.href='clases-ver.php?idClase=<?=$_POST["idClase"];?>';
			}
			setTimeout ("redirige()", 2000);	
		</script>
	<?php
		exit();
	}

	//CONFIGURAR REPARTO DE PORCENTAJES
	if($_POST["id"]==29){//No se llama de ningun lado	
		try{
			mysqli_query($conexion, "UPDATE academico_cargas SET car_configuracion='".$_POST["config"]."' WHERE car_id='".$datosCargaActual[0]."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="academico-config-porcentajes.php";</script>';
		exit();
	}

	//AGREGAR INDICADORES PRIMER PERIODO
	if($_POST["id"]==30){//No se llama de ningun lado
		try{
			$consultaI = mysqli_query($conexion, "SELECT * FROM academico_indicadores_carga WHERE ipc_carga='".$_COOKIE["carga"]."' AND ipc_periodo=1 AND ipc_creado=1");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$numI = mysqli_num_rows($consultaI);

		if($numI>=$config[20]){
			include("../compartido/guardar-historial-acciones.php");
			echo '<script type="text/javascript">window.location.href="indicadores1.php?error=1";</script>';
			exit();
		}else{
			$numI++;
			$valor = ($config[21]/$numI);
		}

		try{
			mysqli_query($conexion, "INSERT INTO academico_indicadores(ind_nombre, ind_obligatorio) VALUES('".mysqli_real_escape_string($conexion,$_POST["contenido"])."', 0)");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$Id = mysqli_insert_id($conexion);

		try{
			mysqli_query($conexion, "INSERT INTO academico_indicadores_carga(ipc_carga, ipc_indicador, ipc_valor, ipc_periodo, ipc_creado) VALUES('".$_COOKIE["carga"]."', '".$Id."', '".$valor."',1,1)");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		try{
			mysqli_query($conexion, "UPDATE academico_indicadores_carga SET ipc_valor='".$valor."' WHERE ipc_carga='".$_COOKIE["carga"]."' AND ipc_periodo=1 AND ipc_creado=1");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="indicadores1.php";</script>';
		exit();
	}

	//EDITAR INDICADORES PRIMER PERIODO
	if($_POST["id"]==31){//No se llama de ningun lado
		try{
			mysqli_query($conexion, "UPDATE academico_indicadores SET ind_nombre='".mysqli_real_escape_string($conexion,$_POST["contenido"])."' WHERE ind_id='".$_POST["idInd"]."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="indicadores1.php";</script>';
		exit();
	}

	//EDITAR CALIFICACIONES
	if($_POST["id"]==34){//No se llama de ningun lado	

		$date = date('Y-m-d', strtotime(str_replace('-', '/', $_POST["fecha"])));
		$consultaRegistroActual=mysqli_query($conexion, "SELECT * FROM academico_actividades WHERE act_id='".$_POST["idActividad"]."'");
		$registroActual = mysqli_fetch_array($consultaRegistroActual, MYSQLI_BOTH);

		if($_POST["indicador"]==$registroActual[4]){
			try{
				mysqli_query($conexion, "UPDATE academico_actividades SET act_descripcion='".$_POST["contenido"]."', act_fecha='".$date."' WHERE act_id='".$_POST["idActividad"]."'");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}
		}else{

			//INDICADOR Y PORCENTAJES ANTERIORES
			try{
				$consultaIndicadorAntes=mysqli_query($conexion, "SELECT * FROM academico_indicadores_carga WHERE ipc_carga='".$_COOKIE["carga"]."' AND ipc_indicador='".$registroActual[4]."' AND ipc_periodo=1");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}
			$indicadorAntes = mysqli_fetch_array($consultaIndicadorAntes, MYSQLI_BOTH);
			
			try{
				$consultaNumActividadesAntes=mysqli_query($conexion, "SELECT * FROM academico_actividades WHERE act_id_carga='".$_COOKIE["carga"]."' AND act_id_tipo='".$registroActual[4]."' AND act_periodo=1  AND act_estado=1");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}
			$numActividadesAntes = mysqli_num_rows($consultaNumActividadesAntes);
			$numActividadesAntes = $numActividadesAntes - 1;

			if($numActividadesAntes>0){
				$valorActividadAntes = ($indicadorAntes[3]/$numActividadesAntes);
				try{
					mysqli_query($conexion, "UPDATE academico_actividades SET act_valor='".$valorActividadAntes."' WHERE act_id_carga='".$_COOKIE["carga"]."' AND act_id_tipo='".$registroActual[4]."' AND act_periodo=1  AND act_estado=1");
				} catch (Exception $e) {
					include("../compartido/error-catch-to-report.php");
				}
			}

			//INDICADOR Y PORCENTAJES NUEVOS
			try{
				$consultaIndicadorNuevo=mysqli_query($conexion, "SELECT * FROM academico_indicadores_carga WHERE ipc_carga='".$_COOKIE["carga"]."' AND ipc_indicador='".$_POST["indicador"]."' AND ipc_periodo=1");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}
			$indicadorNuevo = mysqli_fetch_array($consultaIndicadorNuevo, MYSQLI_BOTH);
			
			try{
				$consultaNumActividadesNuevo=mysqli_query($conexion, "SELECT * FROM academico_actividades WHERE act_id_carga='".$_COOKIE["carga"]."' AND act_id_tipo='".$_POST["indicador"]."' AND act_periodo=1 AND act_estado=1");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}
			$numActividadesNuevo = mysqli_num_rows($consultaNumActividadesNuevo);
			$numActividadesNuevo = $numActividadesNuevo + 1;

			if($numActividadesNuevo>0){
				$valorActividadNuevo = ($indicadorNuevo[3]/$numActividadesNuevo);
				try{
					mysqli_query($conexion, "UPDATE academico_actividades SET act_valor='".$valorActividadNuevo."' WHERE act_id_carga='".$_COOKIE["carga"]."' AND act_id_tipo='".$_POST["indicador"]."' AND act_periodo=1  AND act_estado=1");
				} catch (Exception $e) {
					include("../compartido/error-catch-to-report.php");
				}
			}

			try{
				mysqli_query($conexion, "UPDATE academico_actividades SET act_descripcion='".$_POST["contenido"]."', act_fecha='".$date."', act_valor='".$valorActividadNuevo."', act_id_tipo='".$_POST["indicador"]."' WHERE act_id='".$_POST["idActividad"]."'  AND act_estado=1");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="calificaciones1.php";</script>';
		exit();
	}

	//EDITAR CALIFICACIONES CON VALOR MANUAL
	if($_POST["id"]==35){//No se llama de ningun lado	
		try{
			$tipos = mysqli_query($conexion, "SELECT * FROM academico_indicadores_carga WHERE ipc_carga='".$_COOKIE["carga"]."' AND ipc_indicador='".$_POST["indicador"]."' AND ipc_periodo=1");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$tipo = mysqli_fetch_array($tipos, MYSQLI_BOTH);

		//============================================= VERIFICAMOS CUANTAS NOTAS EXISTEN DEL MISMO TIPO ================================================
		try{
			$consultaSumaN=mysqli_query($conexion, "SELECT sum(act_valor) FROM academico_actividades WHERE act_id_carga='".$_COOKIE["carga"]."' AND act_id_tipo='".$_POST["indicador"]."' AND act_periodo=1 AND act_estado=1 AND act_id!='".$_POST["idActividad"]."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$sumaN = mysqli_fetch_array($consultaSumaN, MYSQLI_BOTH);
		$sumaTotal = ($sumaN[0]+$_POST["valor"]);

		if($sumaTotal>$tipo[3]){
			echo "<span style='font-family:Arial; color:red;'>La suma de estos valores sobrepasa el valor del indicador. La suma actual con esta actividad es de <b>".$sumaTotal."</b>. Y el indicador relacionado tiene un valor de <b>".$tipo[3]."</b>. Por favor verifique.<br>
			<a href='javascript:history.go(-1);'>[Regresar]</a></span>";

			include("../compartido/guardar-historial-acciones.php");
			exit();
		}

		$date = date('Y-m-d', strtotime(str_replace('-', '/', $_POST["fecha"])));

		try{
			mysqli_query($conexion, "UPDATE academico_actividades SET act_descripcion='".$_POST["contenido"]."', act_fecha='".$date."', act_valor='".$_POST["valor"]."', act_id_tipo='".$_POST["indicador"]."' WHERE act_id='".$_POST["idActividad"]."'  AND act_estado=1");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="calificaciones1.php";</script>';
		exit();
	}

	//AGREGAR INDICADORES CON VALOR MANUAL

	if($_POST["id"]==36){//No se llama de ningun lado
		try{
			$consultaSumaIndObg=mysqli_query($conexion, "SELECT sum(ipc_valor) FROM academico_indicadores_carga WHERE ipc_carga='".$datosCargaActual[0]."' AND ipc_periodo='".$datosCargaActual[5]."' AND ipc_creado=0");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$sumaIndObg = mysqli_fetch_array($consultaSumaIndObg, MYSQLI_BOTH);

		$porcentajeRestante = 100 - $sumaIndObg[0];

		try{
			$consultaI = mysqli_query($conexion, "SELECT * FROM academico_indicadores_carga WHERE ipc_carga='".$_COOKIE["carga"]."' AND ipc_periodo='".$datosCargaActual[5]."' AND ipc_creado=1");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$numI = mysqli_num_rows($consultaI);

		if($numI>=$config[20] and $datosCargaActual[2]<40){
			include("../compartido/guardar-historial-acciones.php");
			echo '<script type="text/javascript">window.location.href="indicadores.php?error=1";</script>';
			exit();
		}else{
			$numI++;
			$valor = $_POST["valor"];
		}

		try{
			mysqli_query($conexion, "INSERT INTO academico_indicadores(ind_nombre, ind_obligatorio) VALUES('".$_POST["contenido"]."', 0)");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$Id = mysqli_insert_id($conexion);

		try{
			mysqli_query($conexion, "INSERT INTO academico_indicadores_carga(ipc_carga, ipc_indicador, ipc_valor, ipc_periodo, ipc_creado) VALUES('".$_COOKIE["carga"]."', '".$Id."', '".$valor."', '".$datosCargaActual[5]."',1)");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="indicadores.php";</script>';
		exit();
	}

	//EDITAR INDICADORES CON VALOR MANUAL
	if($_POST["id"]==37){//No se llama de ningun lado

		try{
			mysqli_query($conexion, "UPDATE academico_indicadores SET ind_nombre='".$_POST["contenido"]."' WHERE ind_id='".$_POST["idInd"]."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		try{
			mysqli_query($conexion, "UPDATE academico_indicadores_carga SET ipc_valor='".$_POST["valor"]."' WHERE ipc_indicador='".$_POST["idInd"]."' AND ipc_carga='".$_COOKIE["carga"]."' AND ipc_periodo='".$datosCargaActual[5]."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="indicadores.php";</script>';
		exit();
	}

	//CONFIGURAR REPARTO DE PORCENTAJES EN LOS INDICADORES
	if($_POST["id"]==38){//No se llama de ningun lado	

		try{
			mysqli_query($conexion, "UPDATE academico_cargas SET car_valor_indicador='".$_POST["config"]."' WHERE car_id='".$datosCargaActual[0]."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="academico-config-porcentajes-ind.php";</script>';
		exit();
	}

	//AGREGAR PREGUNTAS A LAS CATEGORIAS DE FORMATOS MONITOREO
	if($_POST["id"]==41){//No se se estan usando
		include("verificar-carga.php");
		include("verificar-periodos-diferentes.php");

		if($_POST["critica"]!=1) $_POST["critica"]='0';
		try{
			mysqli_query($conexion, "INSERT INTO academico_actividad_preguntas(preg_descripcion, preg_critica)VALUES('".$_POST["contenido"]."','".$_POST["critica"]."')");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$idPregunta = mysqli_insert_id($conexion);

		try{
			mysqli_query($conexion, "INSERT INTO academico_actividad_evaluacion_preguntas(evp_id_evaluacion, evp_id_pregunta)VALUES('".$_POST["idE"]."','".$idPregunta."')");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="formatos-categorias-preguntas.php?idE='.$_POST["idE"].'&idF='.$_POST["idF"].'#pregunta'.$idPregunta.'";</script>';
		exit();
	}

	//AGREGAR  MONITOREO
	if($_POST["id"]==42){//No se se estan usando
		include("verificar-carga.php");
		include("verificar-periodos-diferentes.php");
		try{
			mysqli_query($conexion, "INSERT INTO academico_monitoreo(moni_fecha, moni_evaluador, moni_evaluado, moni_id_formato)
			VALUES(now(), '".$_SESSION["id"]."','".$_POST["evaluado"]."','".$_POST["idF"]."')");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$idRegistro = mysqli_insert_id($conexion);

		try{
			$consultaCat = mysqli_query($conexion, "SELECT * FROM academico_actividad_evaluaciones 
			WHERE eva_formato='".$_POST["idF"]."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		$contReg = 1;
		while($resultadoCat = mysqli_fetch_array($consultaCat, MYSQLI_BOTH)){
			try{
				$preguntasConsulta = mysqli_query($conexion, "SELECT * FROM academico_actividad_evaluacion_preguntas
				INNER JOIN academico_actividad_preguntas ON preg_id=evp_id_pregunta
				WHERE evp_id_evaluacion='".$resultadoCat['eva_id']."'");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}

			while($preguntas = mysqli_fetch_array($preguntasConsulta, MYSQLI_BOTH)){
				//GUARDAR RESPUESTAS
				if(empty($_POST["P$contReg"])) $_POST["P$contReg"] = 0;
				try{
					mysqli_query($conexion, "INSERT INTO academico_actividad_evaluaciones_resultados(res_id_pregunta, res_id_respuesta, res_id_estudiante, res_id_evaluacion, res_id_monitoreo)
					VALUES('".$preguntas['preg_id']."', '".$_POST["P$contReg"]."', '".$_POST["evaluado"]."', '".$resultadoCat['eva_id']."', '".$idRegistro."')");
				} catch (Exception $e) {
					include("../compartido/error-catch-to-report.php");
				}
				$contReg++;
			}
		}

		try{
			mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".general_alertas (alr_nombre, alr_descripcion, alr_tipo, alr_usuario, alr_fecha_envio, alr_categoria, alr_importancia, alr_url_acceso, alr_vista, alr_institucion, alr_year)
			VALUES('Nuevo monitoreo', 'Acaban de hacerte un nuevo monitoreo', 2, '".$_POST["evaluado"]."', now(), 3, 2, '', 0,'" . $config['conf_id_institucion'] . "','" . $_SESSION["bd"] . "')");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="formatos.php";</script>';
		exit();
	}

	//AGREGAR CATEGORIAS A LOS FORMATOS
	if($_POST["id"]==43){//No se se estan usando
		include("verificar-carga.php");
		include("verificar-periodos-diferentes.php");

		try{
			mysqli_query($conexion, "INSERT INTO academico_actividad_evaluaciones(eva_nombre, eva_estado, eva_formato)VALUES('".$_POST["titulo"]."', 1, '".$_POST["idF"]."')");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="formatos-categorias.php?idF='.$_POST['idF'].'";</script>';
		exit();
	}

	//EDITAR CATEGORIAS A LOS FORMATOS
	if($_POST["id"]==44){//No se se estan usando
		try{
			mysqli_query($conexion, "UPDATE academico_actividad_evaluaciones SET eva_nombre='".$_POST["titulo"]."' WHERE eva_id='".$_POST["idR"]."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="formatos-categorias.php?idF='.$_POST['idF'].'";</script>';
		exit();
	}

	//EDITAR PREGUNTAS A LAS CATEGORIAS DE FORMATOS MONITOREO
	if($_POST["id"]==45){//No se se estan usando
		include("verificar-carga.php");
		include("verificar-periodos-diferentes.php");

		if($_POST["critica"]!=1) $_POST["critica"]='0';

		try{
			mysqli_query($conexion, "UPDATE academico_actividad_preguntas SET preg_descripcion='".$_POST["contenido"]."', preg_critica='".$_POST["critica"]."' WHERE preg_id='".$_POST["idR"]."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="formatos-categorias-preguntas.php?idE='.$_POST["idE"].'&idF='.$_POST["idF"].'#pregunta'.$_POST["idR"].'";</script>';
		exit();
	}

	//AGREGAR FORMATOS
	if($_POST["id"]==46){//No se se estan usando
		include("verificar-carga.php");
		include("verificar-periodos-diferentes.php");

		try{
			mysqli_query($conexion, "INSERT INTO academico_formatos(form_nombre, form_carga)VALUES('".$_POST["titulo"]."', '".$cargaConsultaActual."')");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="formatos.php";</script>';
		exit();
	}

	//EDITAR FORMATOS
	if($_POST["id"]==47){//No se se estan usando

		try{
			mysqli_query($conexion, "UPDATE academico_formatos SET form_nombre='".$_POST["titulo"]."' WHERE form_id='".$_POST["idR"]."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="formatos.php";</script>';
		exit();
	}
}

//========================================== GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET  GET GET GET GET GET GET GET GET GET GET GET GET GET ======================================================

if(!empty($_GET["get"])){
	


	//CAMBIAR DE ESTADO LAS RESPUESTAS DE LOS EXAMENES
	if(base64_decode($_GET["get"])==8){
		if(base64_decode($_GET["estado"])==0) $estado=1; else $estado=0;
		try{
			mysqli_query($conexion, "UPDATE academico_actividad_respuestas SET resp_correcta='".$estado."' WHERE resp_id='".base64_decode($_GET["idR"])."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'#pregunta'.base64_encode($_GET["preg"]).'";</script>';
		exit();
	}

	//ELIMINAR RESPUESTAS
	if(base64_decode($_GET["get"])==9){
		try{
			mysqli_query($conexion, "DELETE FROM academico_actividad_respuestas WHERE resp_id='".base64_decode($_GET["idR"])."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
		exit();
	}

	//ELIMINAR INDICADORES
	if(base64_decode($_GET["get"])==10){
		include("verificar-carga.php");
		include("verificar-periodos-diferentes.php");
		try{
			$actividadesRelacionadasConsulta = mysqli_query($conexion, "SELECT * FROM academico_actividades 
			WHERE act_id_tipo='".base64_decode($_GET["idIndicador"])."' AND act_id_carga='".$cargaConsultaActual."' AND act_periodo='".$periodoConsultaActual."' AND act_estado=1");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		while($actividadesRelacionadasDatos = mysqli_fetch_array($actividadesRelacionadasConsulta, MYSQLI_BOTH)){
			try{
				mysqli_query($conexion, "UPDATE academico_actividades SET act_estado=0, act_fecha_eliminacion=now(), act_motivo_eliminacion='Eliminar indicadores de carga: ".$cargaConsultaActual.", del P: ".$periodoConsultaActual."' WHERE act_id='".$actividadesRelacionadasDatos['act_id']."'");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}
		}

		try{
			mysqli_query($conexion, "DELETE FROM academico_indicadores_carga WHERE ipc_id='".base64_decode($_GET["idR"])."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		try{
			$consultaSumaIndicadores=mysqli_query($conexion, "SELECT
			(SELECT sum(ipc_valor) FROM academico_indicadores_carga 
			WHERE ipc_carga='".$cargaConsultaActual."' AND ipc_periodo='".$periodoConsultaActual."' AND ipc_creado=0),
			(SELECT sum(ipc_valor) FROM academico_indicadores_carga 
			WHERE ipc_carga='".$cargaConsultaActual."' AND ipc_periodo='".$periodoConsultaActual."' AND ipc_creado=1),
			(SELECT count(*) FROM academico_indicadores_carga 
			WHERE ipc_carga='".$cargaConsultaActual."' AND ipc_periodo='".$periodoConsultaActual."' AND ipc_creado=1)
			");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		$sumaIndicadores = mysqli_fetch_array($consultaSumaIndicadores, MYSQLI_BOTH);
		$porcentajePermitido = 100 - $sumaIndicadores[0];
		$porcentajeRestante = ($porcentajePermitido - $sumaIndicadores[1]);

		//Si decide poner los valores porcentuales de los indicadores de forma manual
		if($datosCargaActual['car_valor_indicador']==1){}else{
		//El sistema reparte los porcentajes automáticamente y equitativamente.
			$valorIgualIndicador = ($porcentajePermitido/($sumaIndicadores[2]));
			//Actualiza todos valores de la misma carga y periodo.
			try{
				mysqli_query($conexion, "UPDATE academico_indicadores_carga SET ipc_valor='".$valorIgualIndicador."' 
				WHERE ipc_carga='".$cargaConsultaActual."' AND ipc_periodo='".$periodoConsultaActual."' AND ipc_creado=1");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}

			//Si decide que los valores de las calificaciones son de forma automática.
			if($datosCargaActual['car_configuracion']==0){
				//Repetimos la consulta de los indicadores porque los valores fueron actualizados
				try{
					$indicadoresConsultaActualizado = mysqli_query($conexion, "SELECT * FROM academico_indicadores_carga 
					WHERE ipc_carga='".$cargaConsultaActual."' AND ipc_periodo='".$periodoConsultaActual."' AND ipc_creado=1");
				} catch (Exception $e) {
					include("../compartido/error-catch-to-report.php");
				}

				//Actualizamos todas las actividades por cada indicador
				while($indicadoresDatos = mysqli_fetch_array($indicadoresConsultaActualizado, MYSQLI_BOTH)){
					try{
						$consultaActividadesNum=mysqli_query($conexion, "SELECT * FROM academico_actividades 
						WHERE act_id_tipo='".$indicadoresDatos['ipc_indicador']."' AND act_periodo='".$periodoConsultaActual."' AND act_id_carga='".$cargaConsultaActual."' AND act_estado=1");
					} catch (Exception $e) {
						include("../compartido/error-catch-to-report.php");
					}
					$actividadesNum = mysqli_num_rows($consultaActividadesNum);
					//Si hay actividades relacionadas al indicador, actualizamos su valor.
					if($actividadesNum>0){
						$valorIgualActividad = ($indicadoresDatos['ipc_valor']/$actividadesNum);
						try{
							mysqli_query($conexion, "UPDATE academico_actividades SET act_valor='".$valorIgualActividad."' 
							WHERE act_id_tipo='".$indicadoresDatos['ipc_indicador']."' AND act_periodo='".$periodoConsultaActual."' AND act_id_carga='".$cargaConsultaActual."' AND act_estado=1");
						} catch (Exception $e) {
							include("../compartido/error-catch-to-report.php");
						}
					}
				}
			}
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="indicadores.php";</script>';
		exit();
	}

	//ELIMINAR CLASES
	if(base64_decode($_GET["get"])==11){
		include("verificar-carga.php");
		include("verificar-periodos-diferentes.php");
		try{
		$consultaRegistro=mysqli_query($conexion, "SELECT * FROM academico_clases WHERE cls_id='".base64_decode($_GET["idR"])."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$registro = mysqli_fetch_array($consultaRegistro, MYSQLI_BOTH);

		$ruta = '../files/clases';
		if(!empty($registro['cls_archivo']) && file_exists($ruta."/".$registro['cls_archivo'])){
			unlink($ruta."/".$registro['cls_archivo']);	
		}

		if(!empty($registro['cls_archivo2']) && file_exists($ruta."/".$registro['cls_archivo2'])){
			unlink($ruta."/".$registro['cls_archivo2']);	
		}

		if(!empty($registro['cls_archivo3']) && file_exists($ruta."/".$registro['cls_archivo3'])){
			unlink($ruta."/".$registro['cls_archivo3']);	
		}

		try{
			mysqli_query($conexion, "UPDATE academico_clases SET cls_estado=0 WHERE cls_id=".base64_decode($_GET["idR"]));
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		try{
			mysqli_query($conexion, "DELETE FROM academico_ausencias WHERE aus_id_clase=".base64_decode($_GET["idR"]));
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="clases.php?carga='.base64_encode($cargaConsultaActual).'&periodo='.base64_encode($periodoConsultaActual).'";</script>';
		exit();
	}

	//ELIMINAR CALIFICACIONES
	if(base64_decode($_GET["get"])==12){
		include("verificar-carga.php");
		include("verificar-periodos-diferentes.php");

		try{
			$consultaIndicadoresDatos=mysqli_query($conexion, "SELECT * FROM academico_indicadores_carga 
			WHERE ipc_indicador='".base64_decode($_GET["idIndicador"])."' AND ipc_carga='".$cargaConsultaActual."' AND ipc_periodo='".$periodoConsultaActual."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$indicadoresDatos = mysqli_fetch_array($consultaIndicadoresDatos, MYSQLI_BOTH);
		
		//"Borramos" la actividad
		try{
			mysqli_query($conexion, "UPDATE academico_actividades SET act_estado=0, act_fecha_eliminacion=now(), act_motivo_eliminacion='Eliminar la actividad de carga: ".$cargaConsultaActual.", del P: ".$periodoConsultaActual."' WHERE act_id=".base64_decode($_GET["idR"]));
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		//Si los valores de las calificaciones son de forma automática.

		if($datosCargaActual['car_configuracion']==0){
			//Actualizamos el valor de todas las actividades del indicador
			try{
				$consultaActividadesNum=mysqli_query($conexion, "SELECT * FROM academico_actividades WHERE act_id_tipo='".$indicadoresDatos['ipc_indicador']."' AND act_id_carga='".$cargaConsultaActual."' AND act_periodo='".$periodoConsultaActual."' AND act_estado=1");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}
			$actividadesNum = mysqli_num_rows($consultaActividadesNum);

			//Si hay actividades relacionadas al indicador, actualizamos su valor.
			if($actividadesNum>0){
				$valorIgualActividad = ($indicadoresDatos['ipc_valor']/($actividadesNum));
				try{
					mysqli_query($conexion, "UPDATE academico_actividades SET act_valor='".$valorIgualActividad."' WHERE act_id_tipo='".$indicadoresDatos['ipc_indicador']."' AND act_id_carga='".$cargaConsultaActual."' AND act_periodo='".$periodoConsultaActual."' AND act_estado=1");
				} catch (Exception $e) {
					include("../compartido/error-catch-to-report.php");
				}
			}	
		}
		
		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="calificaciones.php?tab=2";</script>';
		exit();
	}

	//ELIMINAR CRONOGRAMA
	if(base64_decode($_GET["get"])==13){
		try{
			mysqli_query($conexion, "DELETE FROM academico_cronograma WHERE cro_id=".base64_decode($_GET["idR"]));
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
		exit();
	}

	//ELIMINAR COMENTARIOS DE FOROS
	if($_GET["get"]==14){
		try{
			mysqli_query($conexion, "DELETE FROM academico_actividad_foro_respuestas WHERE fore_id_comentario=".$_GET["idCom"]);
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		try{
			mysqli_query($conexion, "DELETE FROM academico_actividad_foro_comentarios WHERE com_id=".$_GET["idCom"]);
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="academico-foros-ver.php?idForo='.$_GET["idForo"].'";</script>';
		exit();
	}

	//ELIMINAR RESPUESTAS A COMENTARIOS
	if($_GET["get"]==15){
		try{
			mysqli_query($conexion, "DELETE FROM academico_actividad_foro_respuestas WHERE fore_id=".$_GET["idRes"]);
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="academico-foros-ver.php?idForo='.$_GET["idForo"].'";</script>';
		exit();

	}

	//ELIMINAR FOROS
	if(base64_decode($_GET["get"])==16){
		try{
			$foroC = mysqli_query($conexion, "SELECT * FROM academico_actividad_foro_comentarios WHERE com_id_foro='".base64_decode($_GET["idR"])."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		while($foro=mysqli_fetch_array($foroC, MYSQLI_BOTH)){
			try{
				mysqli_query($conexion, "DELETE FROM academico_actividad_foro_respuestas WHERE fore_id_comentario='".$foro[0]."'");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}
		}

		try{
			mysqli_query($conexion, "DELETE FROM academico_actividad_foro_comentarios WHERE com_id_foro='".base64_decode($_GET["idR"])."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		try{
			mysqli_query($conexion, "DELETE FROM academico_actividad_foro WHERE foro_id='".base64_decode($_GET["idR"])."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="foros.php";</script>';
		exit();
	}

	//ELIMINAR TAREAS
	if(base64_decode($_GET["get"])==17){
		try{
			$rEntregas = mysqli_query($conexion, "SELECT * FROM academico_actividad_tareas_entregas WHERE ent_id_actividad='".base64_decode($_GET["idR"])."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		$rutaEntregas = '../files/tareas-entregadas';
		while($registroEntregas = mysqli_fetch_array($rEntregas, MYSQLI_BOTH)){
			if(file_exists($rutaEntregas."/".$registroEntregas['ent_archivo'])){
				unlink($rutaEntregas."/".$registroEntregas['ent_archivo']);	
			}

			if(file_exists($rutaEntregas."/".$registroEntregas['ent_archivo2'])){
				unlink($rutaEntregas."/".$registroEntregas['ent_archivo2']);	
			}

			if(file_exists($rutaEntregas."/".$registroEntregas['ent_archivo3'])){
				unlink($rutaEntregas."/".$registroEntregas['ent_archivo3']);	
			}
		}

		try{
			mysqli_query($conexion, "DELETE FROM academico_actividad_tareas_entregas WHERE ent_id_actividad='".base64_decode($_GET["idR"])."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		try{
			$consultaRegistro=mysqli_query($conexion, "SELECT * FROM academico_actividad_tareas WHERE tar_id='".base64_decode($_GET["idR"])."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$registro = mysqli_fetch_array($consultaRegistro, MYSQLI_BOTH);

		$ruta = '../files/tareas';
		if(file_exists($ruta."/".$registro['tar_archivo'])){
			unlink($ruta."/".$registro['tar_archivo']);	
		}

		if(file_exists($ruta."/".$registro['tar_archivo2'])){
			unlink($ruta."/".$registro['tar_archivo2']);	
		}

		if(file_exists($ruta."/".$registro['tar_archivo3'])){
			unlink($ruta."/".$registro['tar_archivo3']);	
		}

		try{
			mysqli_query($conexion, "UPDATE academico_actividad_tareas SET tar_estado=0 WHERE tar_id='".base64_decode($_GET["idR"])."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
		exit();
	}

	//ELIMINAR EVALUACIONES
	if(base64_decode($_GET["get"])==18){
		$idR="";
		if(!empty($_GET["idR"])){ $idR=base64_decode($_GET["idR"]);}

		try{
			mysqli_query($conexion, "DELETE FROM academico_actividad_evaluacion_preguntas WHERE evp_id_evaluacion='".$idR."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		//Eliminamos los archivos de respuestas de las preguntas de esta evaluacion.
		try{
			$rEntregas = mysqli_query($conexion, "SELECT * FROM academico_actividad_evaluaciones_resultados WHERE res_id_evaluacion='".$idR."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		$rutaEntregas = '../files/evaluaciones';
		while($registroEntregas = mysqli_fetch_array($rEntregas, MYSQLI_BOTH)){
			if(file_exists($ruta."/".$registro['res_archivo'])){
				unlink($ruta."/".$registro['res_archivo']);	
			}
		}

		try{
			mysqli_query($conexion, "DELETE FROM academico_actividad_evaluaciones_resultados WHERE res_id_evaluacion='".$idR."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		try{
			mysqli_query($conexion, "DELETE FROM academico_actividad_evaluaciones_estudiantes WHERE epe_id_evaluacion=".$idR);
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		try{
			mysqli_query($conexion, "DELETE FROM academico_actividad_evaluaciones WHERE eva_id=".$idR);
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
		exit();
	}

	//RECALCULAR VALOR INDICADORES
	if($_GET["get"]==19){
		try{
			$consultaI = mysqli_query($conexion, "SELECT * FROM academico_indicadores_carga WHERE ipc_carga='".$_COOKIE["carga"]."' AND ipc_periodo='".$datosCargaActual[5]."' AND ipc_creado=1");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$numI = mysqli_num_rows($consultaI);

		$valor = ($config[21]/$numI);
		try{
			mysqli_query($conexion, "UPDATE academico_indicadores_carga SET ipc_valor='".$valor."' WHERE ipc_carga='".$_COOKIE["carga"]."' AND ipc_periodo='".$datosCargaActual[5]."' AND ipc_creado=1");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="indicadores.php";</script>';
		exit();
	}

	//RECALCULAR VALOR CALIFICACIONES POR INDICADOR
	if($_GET["get"]==20){
		try{
			$tipos = mysqli_query($conexion, "SELECT * FROM academico_indicadores_carga WHERE ipc_carga='".$_COOKIE["carga"]."' AND ipc_indicador='".$_GET["ind"]."' AND ipc_periodo='".$datosCargaActual[5]."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$tipo = mysqli_fetch_array($tipos, MYSQLI_BOTH);

		//============================================= VERIFICAMOS CUANTAS NOTAS EXISTEN DEL MISMO TIPO ================================================
		try{
			$registros = mysqli_query($conexion, "SELECT * FROM academico_actividades WHERE act_id_carga='".$_COOKIE["carga"]."' AND act_id_tipo='".$_GET["ind"]."' AND act_periodo='".$datosCargaActual[5]."' AND act_estado=1");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$num_reg = mysqli_num_rows($registros);

		//================== VALOR INDIVIDUAL DE CADA NOTA QUE PERTENECE AL MISMO TIPO ======================================

		@$valnota=($tipo[3]/$num_reg);
		if($valnota==0 or !is_numeric($valnota) or empty($valnota)) $valnota = 0;
		try{
			mysqli_query($conexion, "UPDATE academico_actividades SET act_valor='".$valnota."' WHERE act_id_carga='".$_COOKIE["carga"]."' AND act_id_tipo='".$_GET["ind"]."' AND act_periodo='".$datosCargaActual[5]."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="calificaciones.php";</script>';
		exit();
	}

	//ELIMINAR NOTA ACADEMICA DE UN ESTUDIANTE

	if(base64_decode($_GET["get"])==21){

		mysqli_query($conexion, "DELETE FROM academico_calificaciones WHERE cal_id='".base64_decode($_GET["id"])."'");

		echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';

		exit();

	}

	//ELIMINAR NOTA DISCIPLINARIA DE UN ESTUDIANTE
	if($_GET["get"]==22){
		try{
			mysqli_query($conexion, "DELETE FROM disiplina_nota WHERE dn_id=".$_GET["id"]);
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
		exit();
	}

	//RECALCULAR VALOR CALIFICACIONES POR INDICADOR1
	if($_GET["get"]==25){
		try{
			$tipos = mysqli_query($conexion, "SELECT * FROM academico_indicadores_carga WHERE ipc_carga='".$_COOKIE["carga"]."' AND ipc_indicador='".$_GET["ind"]."' AND ipc_periodo=1");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$tipo = mysqli_fetch_array($tipos, MYSQLI_BOTH);

		//============================================= VERIFICAMOS CUANTAS NOTAS EXISTEN DEL MISMO TIPO ================================================
		try{
			$registros = mysqli_query($conexion, "SELECT * FROM academico_actividades WHERE act_id_carga='".$_COOKIE["carga"]."' AND act_id_tipo='".$_GET["ind"]."' AND act_periodo=1 AND act_estado=1");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$num_reg = mysqli_num_rows($registros);

		//================== VALOR INDIVIDUAL DE CADA NOTA QUE PERTENECE AL MISMO TIPO ======================================
		@$valnota=($tipo[3]/$num_reg);
		if($valnota==0 or !is_numeric($valnota) or empty($valnota)) $valnota = 0;

		try{
			mysqli_query($conexion, "UPDATE academico_actividades SET act_valor='".$valnota."' WHERE act_id_carga='".$_COOKIE["carga"]."' AND act_id_tipo='".$_GET["ind"]."' AND act_periodo=1");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="calificaciones1.php";</script>';
		exit();
	}

	if($_GET["get"]==26){
	//REPLICAR INDICADOR CON CODIGOS DIFERENTES

		$cont=1;
		try{
			$asignacionT = mysqli_query($conexion, "SELECT * FROM academico_indicadores_carga WHERE ipc_carga='".$datosCargaActual[0]."' AND ipc_periodo='".$datosCargaActual[5]."' AND ipc_creado=1");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		while($asgT = mysqli_fetch_array($asignacionT, MYSQLI_BOTH)){
			try{
				$consultaNInd=mysqli_query($conexion, "SELECT * FROM academico_indicadores WHERE ind_id='".$asgT[2]."'");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}
			$nInd = mysqli_fetch_array($consultaNInd, MYSQLI_BOTH);
			try{
				mysqli_query($conexion, "INSERT INTO academico_indicadores(ind_nombre, ind_obligatorio)VALUES('".$nInd[1]."',0)");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}
			$idInd = mysqli_insert_id($conexion);

			try{
				mysqli_query($conexion, "INSERT INTO academico_indicadores_carga(ipc_carga, ipc_indicador, ipc_valor, ipc_periodo, ipc_creado)VALUES('".$_COOKIE["carga"]."','".$idInd."','".$asgT[3]."','".$datosCargaActual[5]."','".$asgT[5]."')");
				mysqli_query($conexion, "UPDATE academico_actividades SET act_id_tipo='".$idInd."' WHERE act_id_tipo='".$asgT[2]."' AND act_id_carga='".$_COOKIE["carga"]."' AND act_periodo='".$datosCargaActual[5]."'");
				mysqli_query($conexion, "DELETE FROM academico_indicadores_carga WHERE ipc_carga=".$_COOKIE["carga"]." AND ipc_periodo='".$datosCargaActual[5]."' AND ipc_indicador='".$asgT[2]."'");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}
			$cont++;
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="indicadores.php";</script>';
		exit();
	}

	//ELIMINAR PREGUNTAS DE LAS EVALUACIONES
	if(base64_decode($_GET["get"])==27){
		try{
			mysqli_query($conexion, "DELETE FROM academico_actividad_evaluacion_preguntas 
			WHERE evp_id_evaluacion='".base64_decode($_GET["idE"])."' AND evp_id_pregunta='".base64_decode($_GET["idP"])."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
		exit();
	}

	//ELIMINAR INTENTO DE LAS EVALUACIONES
	if(base64_decode($_GET["get"])==28){
		try{
			mysqli_query($conexion, "DELETE FROM academico_actividad_evaluaciones_estudiantes 
			WHERE epe_id_evaluacion='".base64_decode($_GET["idE"])."' AND epe_id_estudiante='".base64_decode($_GET["idEstudiante"])."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		try{
			mysqli_query($conexion, "DELETE FROM academico_actividad_evaluaciones_resultados 
			WHERE res_id_evaluacion='".base64_decode($_GET["idE"])."' AND res_id_estudiante='".base64_decode($_GET["idEstudiante"])."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
		exit();
	}

	//ELIMINAR AUSENCIA DE UN ESTUDIANTE
	if(base64_decode($_GET["get"])==29){
		try{
			mysqli_query($conexion, "DELETE FROM academico_ausencias WHERE aus_id='".base64_decode($_GET["id"])."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
		exit();
	}

	//ELIMINAR FORMATO

	if($_GET["get"]==30){
		try{
			mysqli_query($conexion, "DELETE FROM academico_actividad_evaluaciones WHERE eva_formato=".$_GET["idR"]);
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		try{
			mysqli_query($conexion, "DELETE FROM academico_formatos WHERE form_id=".$_GET["idR"]);
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
		exit();
	}

	//ELIMINAR NOTA DISCIPLINARIA DE UN ESTUDIANTE
	if(base64_decode($_GET["get"])==31){
		try{
			mysqli_query($conexion, "UPDATE disiplina_nota SET dn_nota=NULL WHERE dn_id=".base64_decode($_GET["id"]));
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
		exit();
	}
}

//EN CASO DE QUE NO ENTRE POR NINGUNA DE LAS ANTERIORES
$_GET["get"] == 0;
include("../compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="https://plataformasintia.com?error=1";</script>';
exit();
?>