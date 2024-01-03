<?php
include("session.php");
$idPaginaInterna = 'DT0270';
require_once(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
require_once(ROOT_PATH."/main-app/class/Movimientos.php");

$filtro='';
if (!empty($_REQUEST["term"])){
    $busqueda = $_REQUEST["term"];
    $filtro = " AND (
        fcu_id LIKE '%".$busqueda."%'
        OR uss_id LIKE '%".$busqueda."%' 
        OR uss_nombre LIKE '%".$busqueda."%' 
        OR uss_nombre2 LIKE '%".$busqueda."%' 
        OR uss_apellido1 LIKE '%".$busqueda."%' 
        OR uss_apellido2 LIKE '%".$busqueda."%' 
        OR uss_usuario LIKE '%".$busqueda."%' 
        OR uss_email LIKE '%".$busqueda."%'
        OR uss_documento LIKE '%".$busqueda."%'
        OR CONCAT(TRIM(uss_nombre), ' ',TRIM(uss_apellido1), ' ', TRIM(uss_apellido2)) LIKE '%".$busqueda."%'
        OR CONCAT(TRIM(uss_nombre), TRIM(uss_apellido1), TRIM(uss_apellido2)) LIKE '%".$busqueda."%'
        OR CONCAT(TRIM(uss_nombre), ' ', TRIM(uss_apellido1)) LIKE '%".$busqueda."%'
        OR CONCAT(TRIM(uss_nombre), TRIM(uss_apellido1)) LIKE '%".$busqueda."%'
        )";
}

$consulta = Movimientos::listarInvoicedSelect($conexion, $config, $filtro);

while ($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {

    $vlrAdicional = !empty($resultado['fcu_valor']) ? $resultado['fcu_valor'] : 0;

    $totalNeto = Movimientos::calcularTotalNeto($conexion, $config, $resultado['fcu_id'], $vlrAdicional);

    $resultados[] = [
        'value' => $resultado['fcu_id'],
        'label' => $resultado['fcu_id']."- ".UsuariosPadre::nombreCompletoDelUsuario($resultado)." (total: ".$totalNeto.")"
    ];

}

require_once(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
// Devolver los resultados como JSON
echo json_encode($resultados);
exit();