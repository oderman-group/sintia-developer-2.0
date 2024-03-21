<?php
    require_once("../../class/Movimientos.php");  
	if (!empty($filtrosDecode['usuario'])) {
		$filtro .= " AND fcu_usuario='".$filtrosDecode['usuario']."'";
	}
	if (!empty($filtrosDecode['tipo'])) {
		$filtro .= " AND fcu_tipo='".$filtrosDecode['tipo']."'";
	}
	if (!empty($filtrosDecode['estadoFil'])) {
		$filtro .= " AND fcu_status='".$filtrosDecode['estadoFil']."'";
	}
	if (!empty($filtrosDecode['estadoM'])) {
		$filtro .= " AND mat_estado_matricula='".$filtrosDecode['estadoM']."'";
	}
	if (!empty($filtrosDecode['fecha'])) {
		$filtro .= " AND fcu_fecha='".$filtrosDecode['fecha']."'";
	}
	if (!empty($filtros["fFecha"]) || (!empty($filtros["desde"]) || !empty($filtros["hasta"]))) {
		$filtro .= " AND (fcu_fecha BETWEEN '" . $filtros["desde"] . "' AND '" . $filtros["hasta"] . "' OR fcu_fecha LIKE '%" . $filtros["hasta"] . "%')";
	}
    $parametros['filtro'] =  $filtro;    
    $lista = Movimientos::listarTodos($parametros);
