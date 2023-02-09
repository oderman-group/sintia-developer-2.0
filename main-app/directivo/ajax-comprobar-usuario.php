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
    $jsonData['message'] = '<div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <i class="icon-exclamation-sign"></i>Este Usuario Ya Se Encuentra Registrado</div>';
}

header('Content-type: application/json; charset=utf-8');
echo json_encode( $jsonData );
}
?>