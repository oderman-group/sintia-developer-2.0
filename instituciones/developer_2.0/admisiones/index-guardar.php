<?php
include("bd-conexion.php");
include("php-funciones.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

$estQuery = "SELECT * FROM aspirantes WHERE asp_documento = :documento AND asp_institucion = :institucion";
$est = $pdo->prepare($estQuery);
$est->bindParam(':documento', $_POST['documento'], PDO::PARAM_INT);
$est->bindParam(':institucion', $_POST['institucion'], PDO::PARAM_INT);
$est->execute();
$num = $est->rowCount();
$datos = $est->fetch();

if ($num > 0) {
    header("Location:index.php?error=2&documento=" . $_POST['documento']);
    exit();
}

if (md5($_POST['institucion']) != $_POST['iditoken']) {
    redireccionMal('index.php', 1);
}

$sql = "INSERT INTO aspirantes(asp_institucion, asp_tipo_documento, asp_documento, asp_nombre, asp_email_acudiente, asp_nombre_acudiente, asp_celular_acudiente, asp_agno, asp_estado_solicitud, asp_documento_acudiente, asp_grado)VALUES(:institucion, :tipoDocumento, :documento, :nombreEstudiante, :email, :nombreAcudiente, :celular, '".(date('Y')+1)."', 8, :documentoAcudiente, :grado)";
$stmt = $pdo->prepare($sql);

$stmt->bindParam(':institucion', $_POST['institucion'], PDO::PARAM_INT);
$stmt->bindParam(':tipoDocumento', $_POST['tipoDocumento'], PDO::PARAM_INT);
$stmt->bindParam(':documento', $_POST['documento'], PDO::PARAM_STR);
$stmt->bindParam(':nombreEstudiante', $_POST['nombreEstudiante'], PDO::PARAM_STR);
$stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
$stmt->bindParam(':nombreAcudiente', $_POST['nombreAcudiente'], PDO::PARAM_STR);
$stmt->bindParam(':celular', $_POST['celular'], PDO::PARAM_STR);
$stmt->bindParam(':documentoAcudiente', $_POST['documentoAcudiente'], PDO::PARAM_STR);
$stmt->bindParam(':grado', $_POST['grado'], PDO::PARAM_INT);

$stmt->execute();

$newId = $pdo->lastInsertId();
$valor = '55000';

if ($newId > 0) {

    //Guardar información en SINTIA COLEGIOS

    //Estudiante
    $estuQuery = "INSERT INTO usuarios(uss_usuario, uss_clave, uss_tipo, uss_nombre, uss_estado, uss_permiso1, uss_foto, uss_portada, uss_idioma, uss_tema, uss_tipo_documento, uss_apellido1)VALUES(:ussDocumento, '1234', 4, :ussNombres, 0, 0, 'default.png', 'default.png', 1, 'green', :ussTipoDocumento, :ussApellido1)";
    $estu = $pdoI->prepare($estuQuery);
    $estu->bindParam(':ussDocumento', $_POST['documento'], PDO::PARAM_STR);
    $estu->bindParam(':ussNombres', $_POST['nombreEstudiante'], PDO::PARAM_STR);
    $estu->bindParam(':ussTipoDocumento', $_POST['tipoDocumento'], PDO::PARAM_INT);
    $estu->bindParam(':ussApellido1', $_POST['apellido1'], PDO::PARAM_STR);
    $estu->execute();
    $estuId = $pdoI->lastInsertId();

    //Acudiente
    $acudienteQuery = "INSERT INTO usuarios(uss_usuario, uss_tipo, uss_nombre, uss_email, uss_celular)VALUES(:usuario, 3, :nombre, :email, :celular)";
    $acudiente = $pdoI->prepare($acudienteQuery);
    $acudiente->bindParam(':usuario', $_POST['documentoAcudiente'], PDO::PARAM_STR);
    $acudiente->bindParam(':nombre', $_POST['nombreAcudiente'], PDO::PARAM_STR);
    $acudiente->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
    $acudiente->bindParam(':celular', $_POST['celular'], PDO::PARAM_STR);
    $acudiente->execute();
    $acuId = $pdoI->lastInsertId();

    //Padre
    $padreQuery = "INSERT INTO usuarios(uss_tipo)VALUES(3)";
    $padre = $pdoI->prepare($padreQuery);
    $padre->execute();
    $padreId = $pdoI->lastInsertId();

    //Madre
    $madreQuery = "INSERT INTO usuarios(uss_tipo)VALUES(3)";
    $madre = $pdoI->prepare($madreQuery);
    $madre->execute();
    $madreId = $pdoI->lastInsertId();

    //Matriculas
    $matriculasQuery = "INSERT INTO academico_matriculas(mat_tipo_documento, mat_documento, mat_solicitud_inscripcion, mat_estado_matricula, mat_id_usuario, mat_primer_apellido, mat_nombres, mat_acudiente, mat_padre, mat_madre, mat_grado)VALUES(:tipoDocumento, :documento, :solicitud, 5, :idUss, :apellido1, :nombres, :acudiente, :padre, :madre, :grado)";
    $matriculas = $pdoI->prepare($matriculasQuery);
    $matriculas->bindParam(':tipoDocumento', $_POST['tipoDocumento'], PDO::PARAM_INT);
    $matriculas->bindParam(':documento', $_POST['documento'], PDO::PARAM_STR);
    $matriculas->bindParam(':solicitud', $newId, PDO::PARAM_INT);
    $matriculas->bindParam(':idUss', $estuId, PDO::PARAM_INT);
    $matriculas->bindParam(':apellido1', $_POST['apellido1'], PDO::PARAM_STR);
    $matriculas->bindParam(':nombres', $_POST['nombreEstudiante'], PDO::PARAM_STR);
    $matriculas->bindParam(':acudiente', $acuId, PDO::PARAM_INT);
    $matriculas->bindParam(':padre', $padreId, PDO::PARAM_INT);
    $matriculas->bindParam(':madre', $madreId, PDO::PARAM_INT);
    $matriculas->bindParam(':grado', $_POST['grado'], PDO::PARAM_INT);
    $matriculas->execute();
    $matId = $pdoI->lastInsertId();

    //Documentos
    $documentosQuery = "INSERT INTO academico_matriculas_documentos(matd_matricula)VALUES(:matricula)";
    $documentos = $pdoI->prepare($documentosQuery);
    $documentos->bindParam(':matricula', $matId, PDO::PARAM_INT);
    $documentos->execute();


    //Mensaje para correo
    $fin =  '<html><body style="background-color:#CCC;">';
    $fin .= '
                    <center>
            
                        <div style="font-family:arial; background:#FFF; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px; margin-top:20px;">
                        
                            <div style="width:800px; text-align:center; padding:15px;">

                                <img src="http://plataformasintia.com/admisiones/files/logoicolven.jpeg" width="150">

                            </div>

							<p style="color:#000;">
                                Cordial saludo ' . strtoupper($_POST['nombreAcudiente']) . ', su solicitud de admisión para el aspirante <b>'.$_POST['nombreEstudiante'].'</b>, en el <b>INSTITUTO COLOMBO VENEZOLANO</b> fue realizada correctamente.<br>
                                A continuación le informaremos algunos datos importantes que debe tener presente al momento de continuar al siguiente paso, o cuando desee consultar el estado de su solicitud.
							</p>

                            <p style="color:#000;">
                                <span style="color:navy; font-weight:bold;">
                                    Guarde estos datos en un lugar seguro porque los necesitará durante todo el proceso de admisión:
                                </span><br>   

                                Número de solicitud: <b>' . $newId . '</b><br>
                                Número de documento del aspirante: <b>' . $_POST['documento'] . '</b><br>
                            </p>

                            <p>
                                Puede consultar el estado de su solicitud en el siguiente enlace:<br>
                                <a href="https://plataformasintia.com/admisiones/consultar-estado.php">CONSULTAR ESTADO DE SOLICITUD</a>
                            </p>

							<p>
                                Cualquier duda o inquietud no dude en contactarnos.<br>
                                <b>WhatsApp:</b> +57 317 572 1061<br>
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

    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);
    echo '<div style="display:none;">';
    try {
        //Server settings
        $mail->SMTPDebug = 2;                                       // Enable verbose debug output
        $mail->isSMTP();                                            // Set mailer to use SMTP
        $mail->Host       = 'mail.plataformasintia.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'info@plataformasintia.com';                     // SMTP username
        $mail->Password   = 'B=XKY?y{VWiH';                              // SMTP password
        $mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
        $mail->Port       = 465;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom('info@plataformasintia.com', 'Plataforma SINTIA');

        $mail->addAddress($_POST['email'], $_POST['nombreAcudiente']);     // Add a recipient
        $mail->addAddress('sec.academica@icolven.edu.co', 'Sec. Académica');     // Add a recipient

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = "Solicitud de admisión ICOLVEN #" . $newId;
        $mail->Body = $fin;
        $mail->CharSet = 'UTF-8';

        $mail->send();
        echo 'Enviado OK.';
    } catch (Exception $e) {
        echo "Error: {$mail->ErrorInfo}";
        exit();
    }
    echo '</div>';

    //pagarOnline($newId, $_POST['email'], $valor, $_POST['documentoAcudiente'], $_POST['nombreAcudiente'], $_POST['celular']);
    //header("Location:consultar-estado.php?solicitud=".$newId."&documento=".$_POST['documento']);
    echo '<script type="text/javascript">window.location.href="consultar-estado.php?solicitud='.$newId.'&documento='.$_POST['documento'].'";</script>';
    exit();
} else {
    redireccionMal('index.php', 3);
}
