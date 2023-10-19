<?php
session_start();
include("../../config-general/config.php");
include("../../config-general/consulta-usuario-actual.php");
include("../compartido/sintia-funciones.php");
//Instancia de Clases generales

$chat_remite_usuario = $_GET["chat_remite_usuario"];
$chat_destino_usuario = $_GET["chat_destino_usuario"];

$consultaUsuarios = mysqli_query($conexion,"SELECT * FROM $baseDatosSocial.chat WHERE (chat_remite_usuario = '" . $chat_remite_usuario . "' AND  chat_destino_usuario = '" . $chat_destino_usuario . "') OR (chat_remite_usuario = '" . $chat_destino_usuario . "' AND  chat_destino_usuario = '" . $chat_remite_usuario . "') ORDER BY chat_fecha_registro ASC");


$resultados = array();
while ($datosUsuarios = mysqli_fetch_array($consultaUsuarios, MYSQLI_BOTH)) {
    array_push($resultados,$datosUsuarios);
   
}

$response[] = array(
    'data' => $resultados,
    'count'        => count($resultados),
    'status'    => "OK"
);

header('Content-Type: application/json');
echo json_encode($response);
exit();