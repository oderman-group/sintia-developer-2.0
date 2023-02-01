<?php
include('session.php');
if($_REQUEST['usuario']!=""){
$usuario = $_REQUEST['usuario'];
$jsonData = array();
$selectQuery   = ("SELECT * FROM usuarios WHERE uss_usuario='".$usuario."' ");
$query         = mysqli_query($conexion, $selectQuery);
$totalCliente  = mysqli_num_rows($query);

if( $totalCliente <= 0 ){
    $jsonData['success'] = 0;
    $jsonData['message'] = '';
} else{
    
    $jsonData['success'] = 1;
    $jsonData['message'] = '<div style="color:red; text-align:right">Ya existe el Usuario </div>';
}

header('Content-type: application/json; charset=utf-8');
echo json_encode( $jsonData );
}
?>