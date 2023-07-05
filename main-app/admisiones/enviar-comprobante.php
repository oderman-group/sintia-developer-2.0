<?php
include("bd-conexion.php");
include("php-funciones.php");

if($_FILES['comprobante']['name']!=""){
	$destino = "files/comprobantes";
	$extension = end(explode(".", $_FILES['comprobante']['name']));
	$archivo = uniqid('comp_').".".$extension;
	@unlink($destino."/".$archivo);
	move_uploaded_file($_FILES['comprobante']['tmp_name'], $destino ."/".$archivo);
}

$sql = "UPDATE aspirantes SET asp_comprobante = :comprobante, asp_estado_solicitud = 1 WHERE asp_id = :idR";                                 
$stmt = $pdo->prepare($sql);
                                              
$stmt->bindParam(':idR', $_POST['solicitud'], PDO::PARAM_INT);
$stmt->bindParam(':comprobante', $archivo, PDO::PARAM_STR);                              

$stmt->execute();

redireccionBien('respuestas-usuario.php?idInst='.$_REQUEST['idInst'].'', 2);



