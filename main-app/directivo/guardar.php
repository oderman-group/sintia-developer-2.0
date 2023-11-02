<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0130';
include("../compartido/historial-acciones-guardar.php");

include("../compartido/sintia-funciones.php");
require_once(ROOT_PATH."/main-app/class/Estudiantes.php");

if (!empty($_POST["id"])) {
//GUARDAR MOVIMIENTO
//GUARDAR REPORTE
if ($_POST["id"] == 9) {// esta en compartido
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
if ($_POST["id"] == 13) {// esta en compartido
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
if ($_POST["id"] == 27) {// esta en compartido
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
//EDITAR OPCION GENERALS


//GENERAR COBRO MASIVO 
if ($_POST["id"] == 50) {// No se esta llamando de ningun lado
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
if ($_POST["id"] == 52) {// No se esta llamando de ningun lado
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
if ($_POST["id"] == 53) {// No se esta llamando de ningun lado
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
//AGREGAR INDICADORES
//ACTUALIZAR CATEGORÍAS FALTAS
//ACTUALIZAR FALTAS
//AGREGAR FALTAS
//AGREGAR categoria
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