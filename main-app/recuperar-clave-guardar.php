<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/app-sintia/config-general/constantes.php");
//include(ROOT_PATH."/conexion-datos.php");

require_once(ROOT_PATH . "/main-app/class/Usuarios.php");
require_once(ROOT_PATH . "/main-app/class/EnviarEmail.php");

$conexion = mysqli_connect($servidorConexion, $usuarioConexion, $claveConexion, $baseDatosServicios);

if(!empty($_POST["Usuario"])){
	$datosUsuario = Usuarios::datosUsuarioRecuperarClave($_POST["Usuario"]);
} else if(!empty($_POST["usuarioId"])){
	$datosUsuario = Usuarios::datosUsuarioRecuperarClaveId($_POST["usuarioId"]);
}

$contar = count($datosUsuario);

if ($contar == 1) {
	$datosUsuario = $datosUsuario[0];
	if (!empty($datosUsuario)) {

		$data = [
			'institucion_id'   => $datosUsuario['institucion'],
			'institucion_agno' => $_SESSION["bd"],
			'usuario_id'       => $datosUsuario['uss_id'],
			'usuario_email'    => $datosUsuario['uss_email'],
			'usuario_nombre'   => $datosUsuario['uss_nombre'],
			'usuario_usuario'  => $datosUsuario['uss_usuario'],
			'usuario_clave'    => $datosUsuario['uss_clave'],
			'nueva_clave'      => Usuarios::generatePassword(8)
		];
		$asunto = 'Tus credenciales han llegado';
		$bodyTemplateRoute = ROOT_PATH . '/config-general/template-email-recuperar-clave.php';

		EnviarEmail::enviar($data, $asunto, $bodyTemplateRoute, null, null);
		Usuarios::guardarRegistroRestauracion($data);

		echo '<script type="text/javascript">window.location.href="index.php?success=SC_DT_5&email=' . $datosUsuario['uss_email'] . '";</script>';
		exit();
	} else {
		echo '<script type="text/javascript">window.location.href="recuperar-clave.php?error=1&inst=' . $institucion['ins_id'] . '";</script>';
		exit();
	}
}
if ($contar > 1) {
	// Serializar el array para pasarlo como un campo oculto
	$arraySerialized = serialize($datosUsuario);
	// Crear el formulario de redirección automática
	echo '<form id="form" method="post" action="recuperar-clave.php?valor=' . base64_encode($_POST["Usuario"]) . '">';
	echo '<input type="hidden" name="usuariosEncontrados" value="' . htmlspecialchars($arraySerialized) . '">';
	echo '</form>';
	echo '<script>document.getElementById("form").submit();</script>';
	exit();
} else {
	echo '<script type="text/javascript">window.location.href="recuperar-clave.php?error=1";</script>';
	exit();
}
