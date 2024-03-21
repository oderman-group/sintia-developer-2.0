<?php
$input = json_decode(file_get_contents("php://input"), true);
$content = explode("/", !empty($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : "")[1];
$url = !empty($input['url']) ? $input['url'] : "";
$filtros = !empty($input['filtros']) ? $input['filtros'] : "";
$acceso = explode("/", $_SERVER['HTTP_REFERER']);

if (count($acceso) > 5) {
    $ruta_session = "../../" . $acceso[5] . "/session.php";
    include $ruta_session;
    require_once($_SERVER['DOCUMENT_ROOT'] . "/app-sintia/config-general/constantes.php");
}
if ($content == 'json') {    
    $valor =  !empty($input['valor']) ? $input['valor'] : "";
    $response = array();
    $parametros = array();
    $filtrosDecode = array();
    if (!empty($input['valor'])) {
        $parametros = [
            'valor' => $input['valor']
        ];
    }
    $filtros = $input['filtros'];
    foreach ($filtros as $key => $filtro) {
        $filtrosDecode[$key] = base64_decode($filtro);
    }
    $filtro = '';
    include($url);
    $response["data"] = empty($lista) ? array() : $lista;
    $response["dataTotal"] = empty($lista) ? 0 : count($lista);;
    echo json_encode($response);
} else {
    $data = !empty($input['data']) ? $input['data'] : "";
    include($url);
}
