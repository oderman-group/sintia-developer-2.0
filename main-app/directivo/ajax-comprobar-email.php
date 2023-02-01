<?php
include('session.php');
if($_REQUEST['email']!=""){
$email = $_REQUEST['email'];
$jsonData = array();
$selectQuery   = ("SELECT * FROM usuarios WHERE uss_email='".$email."' ");
$query         = mysqli_query($conexion, $selectQuery);
$totalCliente  = mysqli_num_rows($query);

if( $totalCliente <= 0 ){
    $jsonData['success'] = 0;
    $jsonData['message'] = '';
} else{
    $jsonData['success'] = 1;
    $jsonData['message'] = '<div style="color:red; text-align:right">Ya existe este Correo</div>';
}
//Mostrando respuesta en formato Json
header('Content-type: application/json; charset=utf-8');
echo json_encode( $jsonData );
}