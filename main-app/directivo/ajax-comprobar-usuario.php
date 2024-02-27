<?php
include('session.php');
require_once(ROOT_PATH."/main-app/class/UsuariosPadre.php");
if($_REQUEST['usuario']!=""){
$usuario = $_REQUEST['usuario'];
$idUsuario = $_REQUEST['idUsuario'];
$jsonData = array();

$totalCliente = UsuariosPadre::validarUsuario($conexion, $usuario, $idUsuario);

if( $totalCliente <= 0 ){
    $jsonData['success'] = 0;
    $jsonData['message'] = '';
} else{
    
    $jsonData['success'] = 1;
    $jsonData['message'] = '<div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <i class="icon-exclamation-sign"></i>Este Usuario Ya Se Encuentra Registrado</div>';
}

header('Content-type: application/json; charset=utf-8');
echo json_encode( $jsonData );
}