<?php
session_start();
if ($_SESSION["id"] == "") {
    header("Location:index.php?sesion=0");
    exit();
}
?>
<?php
include("bd-conexion.php");
include("php-funciones.php");
require_once("../class/EnviarEmail.php");

if ($_FILES['archivo1']['name'] != "") {
	$destino = "files/adjuntos";
	$extension = end(explode(".", $_FILES['archivo1']['name']));
	$archivo1 = uniqid('a1_') . "." . $extension;
	@unlink($destino . "/" . $archivo1);
    move_uploaded_file($_FILES['archivo1']['tmp_name'], $destino . "/" . $archivo1);
    $adjunto1 = '<p><a href="https://plataformasintia.com/admisiones/files/adjuntos/'.$archivo1.'">Descargar archivo 1</a></p>';
} else {
    $archivo1 = $_POST['archivo1A'];
    $adjunto1 = '';
}

if ($_FILES['archivo2']['name'] != "") {
	$destino = "files/adjuntos";
	$extension = end(explode(".", $_FILES['archivo2']['name']));
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

	//Mensaje para correo
    $fin =  '<html><body style="background-color:#CCC;">';
    $fin .= '
                    <center>
            
                        <div style="font-family:arial; background:#FFF; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px; margin-top:20px;">
                        
                            <div style="width:800px; text-align:center; padding:15px;">

                                <img src="http://plataformasintia.com/admisiones/files/logoicolven.jpeg" width="150">

                            </div>

							<p style="color:#000;">
								Cordial saludo, a su solicitud <b>#'.$_POST['solicitud'].'</b> se la ha añadido la siguiente observación:<br><br>
								<b>'.$_POST['observacion'].'</b>
                            </p>
                            

                            <p>
                                Puede consultar el estado de su solicitud o hacer correciones en el formulario en el siguiente enlace:<br>
                                <a href="https://plataformasintia.com/admisiones/consultar-estado.php?idInst='.$_REQUEST['idInst'].'">CONSULTAR ESTADO DE SOLICITUD</a>
                            </p>

							<p>
                                Cualquier duda o inquietud no dude en contactarnos.<br>
                                <b>WhatsApp:</b> +57 317 5721061<br>
                                <b>Correo:</b> sec.academica@icolven.edu.co
							</p>

							<p align="center" style="color:#000;">
								Gracias por preferirnos, que tenga un feliz día.
							</p>

						</div>
					</center>
					<p>&nbsp;</p>
				';
    $fin .= '';
    $fin .=  '<html><body>';
    if($archivo1 != "" and file_exists('files/adjuntos/'.$archivo1)){
        $archivos[1] = 'files/adjuntos/'.$archivo1;
    }

    if($archivo2 != "" and file_exists('files/adjuntos/'.$archivo2)){
        $archivos[2] = 'files/adjuntos/'.$archivo2;
    }
    $data = [
        'institucion_id'   => $datosInfo['info_institucion'],
        'usuario_email'    => $_POST['emailAcudiente'],
        'usuario_nombre'   => 'Sec. Académica',
    ];
    $asunto = 'Tus credenciales han llegado';
    EnviarEmail::enviar($data, $asunto,null,$fin,$archivos);

    echo '</div>';
    echo '<script type="text/javascript">window.location.href="admin-formulario-editar.php?msg=3&token='.md5($_POST["solicitud"]).'&id='.$_POST["solicitud"].'&idInst='.$_REQUEST['idInst'].'";</script>';
}else{
    echo '<script type="text/javascript">window.location.href="admin-formulario-editar.php?msg=3&token='.md5($_POST["solicitud"]).'&id='.$_POST["solicitud"].'&idInst='.$_REQUEST['idInst'].'";</script>';
}
