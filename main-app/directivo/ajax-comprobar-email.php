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
    $jsonData['message'] = '<div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <i class="icon-exclamation-sign"></i>Este Email Ya Se Encuentra Registrado</div>';
                            
}
//Mostrando respuesta en formato Json
header('Content-type: application/json; charset=utf-8');
echo json_encode( $jsonData );
}