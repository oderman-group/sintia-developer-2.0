<?php
include('session.php');
require_once("../class/servicios/GradoServicios.php");
if (isset($_GET['term'])) {
	try {
		$parametros = [
			'nombre' => $_GET['term'],
			'gra_tipo' => GRADO_INDIVIDUAL,
			'gra_estado' => 1,
			'institucion' => $config['conf_id_institucion'],
			'year' => $_SESSION["bd"]
		];
		$lista = GradoServicios::listarCursosILike($parametros);
		$valores = json_encode($lista);
		foreach ($lista as $clave => $dato) {
			$response[] = ["value" => $dato["gra_id"], "label" => $dato["gra_nombre"], "title" => $dato["gra_nombre"] ];
		}
		echo json_encode($response);
	} catch (Exception $e) {
		echo "Excepción catpurada: " . $e->getMessage();
		exit();
	}
}
