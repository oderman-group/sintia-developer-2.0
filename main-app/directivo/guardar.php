<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0130';
include("../compartido/historial-acciones-guardar.php");

include("../compartido/sintia-funciones.php");
require_once(ROOT_PATH."/main-app/class/Estudiantes.php");

if (!empty($_POST["id"])) {
//GUARDAR MOVIMIENTO
if ($_POST["id"] == 8) {
	if (trim($_POST["fecha"]) == "" or trim($_POST["detalle"]) == "" or trim($_POST["valor"]) == "" or trim($_POST["tipo"]) == "" or trim($_POST["forma"]) == "") {
		include("../compartido/guardar-historial-acciones.php");
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}
	if ($_POST["tipo"] == 1) {

		try{
			$consultaConsecutivoActual=mysqli_query($conexion, "SELECT * FROM finanzas_cuentas WHERE fcu_tipo=1 ORDER BY fcu_id DESC");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$consecutivoActual = mysqli_fetch_array($consultaConsecutivoActual, MYSQLI_BOTH);
		if ($consecutivoActual['fcu_consecutivo'] == "") {
			$consecutivo = $config['conf_inicio_recibos_ingreso'];
		} else {
			$consecutivo = $consecutivoActual['fcu_consecutivo'] + 1;
		}
	}
	if ($_POST["tipo"] == 2) {

		try{
			$consultaConsecutivoActual=mysqli_query($conexion, "SELECT * FROM finanzas_cuentas WHERE fcu_tipo=2 ORDER BY fcu_id DESC");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$consecutivoActual = mysqli_fetch_array($consultaConsecutivoActual, MYSQLI_BOTH);
		if ($consecutivoActual['fcu_consecutivo'] == "") {
			$consecutivo = $config['conf_inicio_recibos_egreso'];
		} else {
			$consecutivo = $consecutivoActual['fcu_consecutivo'] + 1;
		}
	}

	try{
		mysqli_query($conexion, "INSERT INTO finanzas_cuentas(fcu_fecha, fcu_detalle, fcu_valor, fcu_tipo, fcu_observaciones, fcu_usuario, fcu_anulado, fcu_forma_pago, fcu_cerrado, fcu_consecutivo)VALUES('" . $_POST["fecha"] . "','" . $_POST["detalle"] . "','" . $_POST["valor"] . "','" . $_POST["tipo"] . "','" . $_POST["obs"] . "','" . $_POST["usuario"] . "',0,'" . $_POST["forma"] . "',0,'" . $consecutivo . "')");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="movimientos.php";</script>';
	exit();
}
//GUARDAR REPORTE
if ($_POST["id"] == 9) {
	if (trim($_POST["fecha"]) == "" or trim($_POST["codigo"]) == "" or trim($_POST["falta"]) == "" or trim($_POST["tipo"]) == "") {
		include("../compartido/guardar-historial-acciones.php");
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}

	try{
		$consultaUsuarioResponsable=mysqli_query($conexion, "SELECT * FROM usuarios_por_estudiantes WHERE upe_id_estudiante='" . $_POST["codigo"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	$usuarioResponsable = mysqli_fetch_array($consultaUsuarioResponsable, MYSQLI_BOTH);

	try{
		mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".general_alertas(alr_nombre, alr_descripcion, alr_tipo, alr_usuario, alr_fecha_envio, alr_vista, alr_categoria, alr_importancia, alr_institucion, alr_year)VALUES('Reporte disciplinario','El estudiante " . $_POST["codigo"] . " le han hecho un reporte disciplinario',2,'" . $usuarioResponsable[1] . "',now(),0,2,2,'" . $config['conf_id_institucion'] . "','" . $_SESSION["bd"] . "')");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	try{
		mysqli_query($conexion, "INSERT INTO disciplina_reportes(dr_fecha, dr_estudiante, dr_falta, dr_tipo, dr_usuario)VALUES('" . $_POST["fecha"] . "','" . $_POST["codigo"] . "','" . $_POST["falta"] . "','" . $_POST["tipo"] . "','" . $_SESSION["id"] . "')");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="disciplina-listado-reportes.php";</script>';
	exit();
}
//ACTUALIZAR CONFIGURACION REPORTES
if ($_POST["id"] == 13) {
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["color_borde"]) == "" or trim($_POST["color_encabezado"]) == "" or trim($_POST["tborde"]) == "") {
		include("../compartido/guardar-historial-acciones.php");
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}

	try{
		mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".configuracion SET conf_color_borde='" . $_POST["color_borde"] . "', conf_color_encabezado='" . $_POST["color_encabezado"] . "',conf_tam_borde=" . $_POST["tborde"] . " WHERE conf_id='".$config['conf_id']."';");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="config-reporte.php";</script>';
	exit();
}



//MODIFICAR REPORTE
if ($_POST["id"] == 27) {
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["fecha"]) == "" or trim($_POST["falta"]) == "" or trim($_POST["tipo"]) == "") {
		include("../compartido/guardar-historial-acciones.php");
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}

	try{
		mysqli_query($conexion, "UPDATE disciplina_reportes SET dr_fecha='" . $_POST["fecha"] . "', dr_falta='" . $_POST["falta"] . "', dr_tipo=" . $_POST["tipo"] . " WHERE dr_id=" . $_POST["idR"] . "");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}







//CREAR OPCION GENERALS
if ($_POST["id"] == 46) {
	try{
		mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".opciones_generales (ogen_nombre, ogen_grupo)VALUES('" . $_POST["nombre"] . "','" . $_POST["grupo"] . "')");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	$idIte = mysqli_insert_id($conexion);

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="configuracion-opciones-generales.php"</script>';
	exit();
}
//EDITAR OPCION GENERALS
if ($_POST["id"] == 47) {
	try{
		mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".opciones_generales SET ogen_nombre='" . $_POST["nombre"] . "', ogen_grupo='" . $_POST["grupo"] . "' WHERE ogen_id='" . $_POST["idogen"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="configuracion-opciones-generales.php"</script>';
	exit();
}


//GENERAR COBRO MASIVO 
if ($_POST["id"] == 50) {
	if (trim($_POST["grado"]) == "" or trim($_POST["fecha"]) == "" or trim($_POST["detalle"]) == "") {
		include("../compartido/guardar-historial-acciones.php");
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.<br>
			<a href='javascript:history.go(-1)'>[Volver al formulario]</a></samp>";
		exit();
	}
	try{
		$consultaValor=mysqli_query($conexion, "SELECT * FROM finanzas_cobros_masivos WHERE mas_id='" . $_POST["detalle"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	$cValor = mysqli_fetch_array($consultaValor, MYSQLI_BOTH);
	$valor = $cValor[2];
	$detalle = $cValor[1];

	$consulta = Estudiantes::obtenerListadoDeEstudiantes(" AND mat_grado='" . $_POST["grado"] . "'");
	while ($datosE = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
		try{
			mysqli_query($conexion, "INSERT INTO finanzas_cuentas(fcu_fecha, fcu_detalle, fcu_valor, fcu_tipo, fcu_observaciones, fcu_usuario, fcu_anulado, fcu_forma_pago, fcu_cerrado)VALUES('" . $_POST["fecha"] . "','" . $detalle . "','" . $valor . "','" . $_POST["tipo"] . "','" . $_POST["obs"] . "','" . $datosE["mat_id_usuario"] . "',0,5,0)");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}		
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//CREAR COBROS MASIVOS
if ($_POST["id"] == 52) {
	try{
		mysqli_query($conexion, "INSERT INTO finanzas_cobros_masivos (mas_nombre, mas_valor)VALUES('" . $_POST["nombre"] . "','" . $_POST["costo"] . "')");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '"</script>';
	exit();
}
//EDITAR COBROS MASIVOS
if ($_POST["id"] == 53) {
	try{
		mysqli_query($conexion, "UPDATE finanzas_cobros_masivos SET mas_nombre='" . $_POST["nombre"] . "', mas_valor='" . $_POST["costo"] . "' WHERE mas_id='" . $_POST["idMas"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '"</script>';
	exit();
}
//EDITAR INDICADORES DE LOS DOCENTES
if ($_POST["id"] == 56) {
	include("verificar-carga.php");
	//include("verificar-periodos-diferentes.php");
	try{
		$consultaSumaIndicadores=mysqli_query($conexion, "SELECT
		(SELECT sum(ipc_valor) FROM academico_indicadores_carga 
		WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=0),
		(SELECT sum(ipc_valor) FROM academico_indicadores_carga 
		WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=1),
		(SELECT count(*) FROM academico_indicadores_carga 
		WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=1)");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	$sumaIndicadores = mysqli_fetch_array($consultaSumaIndicadores, MYSQLI_BOTH);
	$porcentajePermitido = 100 - $sumaIndicadores[0];
	$porcentajeRestante = ($porcentajePermitido - $sumaIndicadores[1]);
	$porcentajeRestante = ($porcentajeRestante + $_POST["valorIndicador"]);

	try{
		mysqli_query($conexion, "UPDATE academico_indicadores SET ind_nombre='" . $_POST["contenido"] . "' WHERE ind_id='" . $_POST["idInd"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	//Si vamos a relacionar los indicadores con los SABERES
	if ($datosCargaActual['car_saberes_indicador'] == 1) {
		try{
			mysqli_query($conexion, "UPDATE academico_indicadores_carga SET ipc_evaluacion='" . $_POST["saberes"] . "' WHERE ipc_id='" . $_POST["idR"] . "'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
	}

	//Para los DIRECTIVOS los valores de los indicadores son de forma manual
	if (!is_numeric($_POST["valor"])) {
		$_POST["valor"] = 1;
	}
	//Si el valor es mayor al adecuado lo ajustamos al porcentaje restante; Siempre que este último sea mayor a 0.
	if ($_POST["valor"] > $porcentajeRestante and $porcentajeRestante > 0) {
		$_POST["valor"] = $porcentajeRestante;
	}

	try{
		mysqli_query($conexion, "UPDATE academico_indicadores_carga SET ipc_valor='" . $_POST["valor"] . "', ipc_creado='" . $_POST["creado"] . "' WHERE ipc_id='" . $_POST["idR"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="cargas-indicadores.php?carga=' . $_GET["carga"] . '&docente=' . $_GET["docente"] . '";</script>';
	exit();
}
//AGREGAR INDICADORES
if ($_POST["id"] == 57) {
	include("verificar-carga.php");
	//include("verificar-periodos-diferentes.php");
	try{
		$consultaSumaIndicadores=mysqli_query($conexion, "SELECT
		(SELECT sum(ipc_valor) FROM academico_indicadores_carga 
		WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=0),
		(SELECT sum(ipc_valor) FROM academico_indicadores_carga 
		WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=1),
		(SELECT count(*) FROM academico_indicadores_carga 
		WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=1)");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	$sumaIndicadores = mysqli_fetch_array($consultaSumaIndicadores, MYSQLI_BOTH);
	
	$porcentajePermitido = 100 - $sumaIndicadores[0];
	$porcentajeRestante = ($porcentajePermitido - $sumaIndicadores[1]);

	if ($sumaIndicadores[2] >= $datosCargaActual['car_maximos_indicadores']) {
		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=209";</script>';
		exit();
	}


	try{
		mysqli_query($conexion, "INSERT INTO academico_indicadores(ind_nombre, ind_obligatorio, ind_publico) VALUES('" . $_POST["contenido"] . "', '" . $_POST["creado"] . "', 0)");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	$idRegistro = mysqli_insert_id($conexion);
	//Si decide poner los valores porcentuales de los indicadores de forma manual
	if ($datosCargaActual['car_valor_indicador'] == 1) {
		if ($porcentajeRestante <= 0) {
			include("../compartido/guardar-historial-acciones.php");
			echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=210&restante=' . $porcentajeRestante . '";</script>';
			exit();
		}
		if (!is_numeric($_POST["valor"])) {
			$_POST["valor"] = 1;
		}
		//Si el valor es mayor al adecuado lo ajustamos al porcentaje restante; Siempre que este último sea mayor a 0.
		if ($_POST["valor"] > $porcentajeRestante and $porcentajeRestante > 0) {
			$_POST["valor"] = $porcentajeRestante;
		}
		try{
			mysqli_query($conexion, "INSERT INTO academico_indicadores_carga(ipc_carga, ipc_indicador, ipc_valor, ipc_periodo, ipc_creado, ipc_evaluacion)
			VALUES('" . $cargaConsultaActual . "', '" . $idRegistro . "', '" . $_POST["valor"] . "', '" . $periodoConsultaActual . "', 1, '" . $_POST["saberes"] . "')");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
	}
	//El sistema reparte los porcentajes automáticamente y equitativamente.
	else {
		$valorIgualIndicador = ($porcentajePermitido / ($sumaIndicadores[2] + 1));
		try{
			mysqli_query($conexion, "INSERT INTO academico_indicadores_carga(ipc_carga, ipc_indicador, ipc_periodo, ipc_creado, ipc_evaluacion)
			VALUES('" . $cargaConsultaActual . "', '" . $idRegistro . "', '" . $periodoConsultaActual . "', 1, '" . $_POST["saberes"] . "')");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		//Actualiza todos valores de la misma carga y periodo; incluyendo el que acaba de crear.
		try{
			mysqli_query($conexion, "UPDATE academico_indicadores_carga SET ipc_valor='" . $valorIgualIndicador . "' WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=1");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
	}




	//Si las calificaciones son de forma automática.
	if ($datosCargaActual['car_configuracion'] == 0) {
		//Repetimos la consulta de los indicadores porque los valores fueron actualizados
		try{
			$indicadoresConsultaActualizado = mysqli_query($conexion, "SELECT * FROM academico_indicadores_carga 
			WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=1");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		//Actualizamos todas las actividades por cada indicador
		while ($indicadoresDatos = mysqli_fetch_array($indicadoresConsultaActualizado, MYSQLI_BOTH)) {
			try{
				$consultaNumActividades=mysqli_query($conexion, "SELECT * FROM academico_actividades 
				WHERE act_id_tipo='" . $indicadoresDatos['ipc_indicador'] . "' AND act_periodo='" . $periodoConsultaActual . "' AND act_id_carga='" . $cargaConsultaActual . "' AND act_estado=1");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}
			$actividadesNum = mysqli_num_rows($consultaNumActividades);
			//Si hay actividades relacionadas al indicador, actualizamos su valor.
			if ($actividadesNum > 0) {
				$valorIgualActividad = ($indicadoresDatos['ipc_valor'] / $actividadesNum);
				try{
					mysqli_query($conexion, "UPDATE academico_actividades SET act_valor='" . $valorIgualActividad . "' 
					WHERE act_id_tipo='" . $indicadoresDatos['ipc_indicador'] . "' AND act_periodo='" . $periodoConsultaActual . "' AND act_id_carga='" . $cargaConsultaActual . "' AND act_estado=1");
				} catch (Exception $e) {
					include("../compartido/error-catch-to-report.php");
				}
			}
		}
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="cargas-indicadores.php?carga=' . base64_encode($cargaConsultaActual) . '&periodo=' . base64_encode($periodoConsultaActual) . '&docente=' . $_GET["docente"] . '";</script>';
	exit();
}
//ACTUALIZAR CATEGORÍAS FALTAS
if ($_POST["id"] == 58) {
	try{
		mysqli_query($conexion, "UPDATE disciplina_categorias SET dcat_nombre='" . $_POST["categoria"] . "' WHERE dcat_id=" . $_POST["idR"] . ";");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="disciplina-categorias-editar.php?success=SC_DT_2&idR='.base64_encode($_POST["idR"]).'&id='.base64_encode($_POST["idR"]).'";</script>';
	exit();
}
//ACTUALIZAR FALTAS
if ($_POST["id"] == 59) {
	try{
		mysqli_query($conexion, "UPDATE disciplina_faltas SET dfal_codigo='" . $_POST["codigo"] . "', dfal_nombre='" . $_POST["nombre"] . "', dfal_id_categoria='" . $_POST["categoria"] . "' 
		WHERE dfal_id='" . $_POST["idR"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="disciplina-faltas-editar.php?success=SC_DT_2&idR='.base64_encode($_POST["idR"]).'&id='.base64_encode($_POST["idR"]).'";</script>';
	exit();
}
//AGREGAR FALTAS
if ($_POST["id"] == 60) {
	try{
		mysqli_query($conexion, "INSERT INTO disciplina_faltas(dfal_nombre, dfal_id_categoria, dfal_codigo)
		VALUES('" . $_POST["nombre"] . "', '" . $_POST["categoria"] . "', '" . $_POST["codigo"] . "')");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	$idRegistro=mysqli_insert_id($conexion);

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="disciplina-faltas.php?success=SC_DT_1&id='.base64_encode($idRegistro).'";</script>';
	exit();
}
//AGREGAR categoria
if ($_POST["id"] == 61) {
	try{
		mysqli_query($conexion, "INSERT INTO disciplina_categorias(dcat_nombre)
		VALUES('" . $_POST["categoria"] . "')");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	$idRegistro=mysqli_insert_id($conexion);

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="disciplina-categorias.php?success=SC_DT_1&id='.base64_encode($idRegistro).'";</script>';
	exit();
}
}
//========================================== GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET  GET GET GET GET GET GET GET GET GET GET GET GET GET ======================

if (!empty($_GET["get"])) {
//ELIMINAR REPORTE
if ($_GET["get"] == 12) {//No se llama de ningun lado
	try{
		mysqli_query($conexion, "DELETE FROM disciplina_reportes WHERE dr_id='" . $_GET["idR"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//BLOQUEAR O DESBLOQUEAR UN USUARIO
if (base64_decode($_GET["get"]) == 17) {//No se llama de ningun lado
	if (base64_decode($_GET["lock"]) == 1) $estado = 0;
	else $estado = 1;
	try{
		mysqli_query($conexion, "UPDATE usuarios SET uss_bloqueado='" . $estado . "' WHERE uss_id='" . base64_decode($_GET["idR"]) . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo $estado;
	exit();
}
}

//EN CASO DE QUE NO ENTRE POR NINGUNA DE LAS ANTERIORES
$_GET["get"] == 0;
include("../compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="https://plataformasintia.com?error=1";</script>';
exit();
?>