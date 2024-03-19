<?php
$acceso = explode("/", $_SERVER['HTTP_REFERER']);
if (count($acceso) > 5) {
    $ruta_session = "../../../" . $acceso[5] . "/session.php";
    include $ruta_session;
    header("Content-type: application/json; charset=utf-8");
    $input = json_decode(file_get_contents("php://input"), true);
    $response = array();
    $parametros = array();
    $filtrosDecode=array();
    if (!empty($input['valor'])) {
        $parametros = [
            'valor' => $input['valor']
        ];
    }
    require_once("../../../class/Movimientos.php");
    $filtros=$input['filtros'];
    
    foreach ($filtros as $key =>$filtro) {
        $filtrosDecode[$key]=base64_decode($filtro);
    }
    $filtroSql='';
	if (!empty($filtrosDecode['usuario'])) {
		$filtroSql .= " AND fcu_usuario='".$filtrosDecode['usuario']."'";
	}
	if (!empty($filtrosDecode['tipo'])) {
		$filtroSql .= " AND fcu_tipo='".$filtrosDecode['tipo']."'";
	}
	if (!empty($filtrosDecode['estadoFil'])) {
		$filtroSql .= " AND fcu_status='".$filtrosDecode['estadoFil']."'";
	}
	if (!empty($filtrosDecode['estadoM'])) {
		$filtroSql .= " AND mat_estado_matricula='".$filtrosDecode['estadoM']."'";
	}
	if (!empty($filtrosDecode['fecha'])) {
		$filtroSql .= " AND fcu_fecha='".$filtrosDecode['fecha']."'";
	}
	if (!empty($filtros["fFecha"]) || (!empty($filtros["desde"]) || !empty($filtros["hasta"]))) {
		$filtroSql .= " AND (fcu_fecha BETWEEN '" . $filtros["desde"] . "' AND '" . $filtros["hasta"] . "' OR fcu_fecha LIKE '%" . $filtros["hasta"] . "%')";
	}
    $parametros['filtro'] =  $filtroSql;
    
    $lista = Movimientos::listarTodos($parametros);
    $response["data"] = empty($lista) ? array() : $lista;
    $response["dataTotal"] = empty($lista) ? 0 : count($lista);;
    echo json_encode($response);
}
