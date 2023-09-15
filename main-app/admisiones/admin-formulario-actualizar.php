<?php
session_start();
if ($_SESSION["id"] == "") {
    header("Location:index.php?sesion=0");
    exit();
}

include("bd-conexion.php");
include("php-funciones.php");
require_once("../class/EnviarEmail.php");

//DATOS SECRETARIA(O)
$ussQuery = "SELECT * FROM usuarios WHERE uss_id = :idSecretaria";
$uss = $pdoI->prepare($ussQuery);
$uss->bindParam(':idSecretaria', $datosInfo['info_secretaria_academica'], PDO::PARAM_INT);
$uss->execute();
$datosUss = $uss->fetch();
$nombreUss=strtoupper($datosUss['uss_nombre']." ".$datosUss['uss_apellido1']);

if (!empty($_FILES['archivo1']['name'])) {
	$destino = "files/adjuntos";
    $explode = explode(".", $_FILES['archivo1']['name']);
	$extension = end($explode);
	$archivo1 = uniqid('a1_') . "." . $extension;
	@unlink($destino . "/" . $archivo1);
    move_uploaded_file($_FILES['archivo1']['tmp_name'], $destino . "/" . $archivo1);
    $adjunto1 = '<p><a href="https://plataformasintia.com/admisiones/files/adjuntos/'.$archivo1.'">Descargar archivo 1</a></p>';
} else {
    $archivo1 = $_POST['archivo1A'];
    $adjunto1 = '';
}

if (!empty($_FILES['archivo2']['name'])) {
	$destino = "files/adjuntos";
    $explode = explode(".", $_FILES['archivo2']['name']);
	$extension = end($explode);
	$archivo2 = uniqid('a2_') . "." . $extension;
	@unlink($destino . "/" . $archivo2);
    move_uploaded_file($_FILES['archivo2']['tmp_name'], $destino . "/" . $archivo2);
    $adjunto2 = '<p><a href="https://plataformasintia.com/admisiones/files/adjuntos/'.$archivo2.'">Descargar archivo 2</a></p>';
} else {
    $archivo2 = $_POST['archivo2A'];
    $adjunto2 = '';
}

//Actualiza datos en aspirantes
$aspQuery = 'UPDATE aspirantes SET asp_estado_solicitud = :estado, asp_observacion = :observacion, asp_fecha_observacion = now(), asp_usuario_observacion = :sesion, asp_observacion_enviada = :envioCorreo, asp_archivo1 = :archivo1, asp_archivo2 = :archivo2 WHERE asp_id = :id';
$asp = $pdo->prepare($aspQuery);
$asp->bindParam(':id', $_POST['solicitud'], PDO::PARAM_INT);
$asp->bindParam(':estado', $_POST['estadoSolicitud'], PDO::PARAM_INT);
$asp->bindParam(':observacion', $_POST['observacion'], PDO::PARAM_STR);
$asp->bindParam(':envioCorreo', $_POST['enviarCorreo'] , PDO::PARAM_INT);
$asp->bindParam(':sesion', $_SESSION["id"] , PDO::PARAM_INT);
$asp->bindParam(':archivo1', $archivo1, PDO::PARAM_STR);
$asp->bindParam(':archivo2', $archivo2, PDO::PARAM_STR);
$asp->execute();

if($_POST['enviarCorreo'] == 1){

    $archivos = array();
    if(!empty($archivo1) and file_exists('files/adjuntos/'.$archivo1)){
        $archivos[1] = 'files/adjuntos/'.$archivo1;
    }

    if(!empty($archivo2) and file_exists('files/adjuntos/'.$archivo2)){
        $archivos[2] = 'files/adjuntos/'.$archivo2;
    }
    $data = [
        'usuario_email'     => $_POST['emailAcudiente'],
        'usuario_nombre'    => $_POST['nombreAcudiente'],
        'usuario2_email'    => $datosUss['uss_email'],
        'usuario2_nombre'   => $nombreUss,
        'solicitud_id'      => $_POST["solicitud"],
        'observaciones'     => $_POST['observacion'],
        'institucion_id'    => $datosInfo['info_institucion']
    ];
    $asunto = 'Actualización de solicitud de admisión '.$_POST["solicitud"];
	$bodyTemplateRoute = ROOT_PATH.'/config-general/template-email-formulario-inscripcion.php';

    EnviarEmail::enviar($data,$asunto,$bodyTemplateRoute,null,$archivos);

    echo '<script type="text/javascript">window.location.href="admin-formulario-editar.php?msg=3&token='.md5($_POST["solicitud"]).'&id='.$_POST["solicitud"].'&idInst='.$_REQUEST['idInst'].'";</script>';
}else{
    echo '<script type="text/javascript">window.location.href="admin-formulario-editar.php?msg=3&token='.md5($_POST["solicitud"]).'&id='.$_POST["solicitud"].'&idInst='.$_REQUEST['idInst'].'";</script>';
}
