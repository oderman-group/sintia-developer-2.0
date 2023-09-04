<?php
include("bd-conexion.php");
include("php-funciones.php");

require ROOT_PATH.'/librerias/phpmailer/Exception.php';
require ROOT_PATH.'/librerias/phpmailer/PHPMailer.php';
require ROOT_PATH.'/librerias/phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$year=(date('Y')+1);

//DATOS SECRETARIA(O)
$ussQuery = "SELECT * FROM usuarios WHERE uss_id = :idSecretaria";
$uss = $pdoI->prepare($ussQuery);
$uss->bindParam(':idSecretaria', $datosInfo['info_secretaria_academica'], PDO::PARAM_INT);
$uss->execute();
$datosUss = $uss->fetch();
$nombreUss=strtoupper($datosUss['uss_nombre']." ".$datosUss['uss_apellido1']);

$estQuery = "SELECT * FROM aspirantes WHERE asp_documento = :documento AND asp_institucion = :institucion AND asp_agno = :years";
$est = $pdo->prepare($estQuery);
$est->bindParam(':documento', $_POST['documento'], PDO::PARAM_INT);
$est->bindParam(':institucion', $_POST['idInst'], PDO::PARAM_INT);
$est->bindParam(':years', $year, PDO::PARAM_INT);
$est->execute();
$num = $est->rowCount();
$datos = $est->fetch();

if ($num > 0) {
    header("Location:index.php?error=2&documento=" . $_POST['documento']);
    exit();
}

if (md5($_POST['idInst']) != $_POST['iditoken']) {
    redireccionMal('index.php', 1);
}

$nombreCompleto=$_POST['nombreEstudiante'].' '.$_POST['apellido1'];
$sql = "INSERT INTO aspirantes(asp_institucion, asp_tipo_documento, asp_documento, asp_nombre, asp_email_acudiente, asp_nombre_acudiente, asp_celular_acudiente, asp_agno, asp_estado_solicitud, asp_documento_acudiente, asp_grado)VALUES(:institucion, :tipoDocumento, :documento, :nombreEstudiante, :email, :nombreAcudiente, :celular, '".(date('Y')+1)."', 8, :documentoAcudiente, :grado)";
$stmt = $pdo->prepare($sql);

$stmt->bindParam(':institucion', $_POST['idInst'], PDO::PARAM_INT);
$stmt->bindParam(':tipoDocumento', $_POST['tipoDocumento'], PDO::PARAM_INT);
$stmt->bindParam(':documento', $_POST['documento'], PDO::PARAM_STR);
$stmt->bindParam(':nombreEstudiante', $nombreCompleto, PDO::PARAM_STR);
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
    $estuQuery = "INSERT INTO usuarios(uss_usuario, uss_clave, uss_tipo, uss_nombre, uss_estado, uss_permiso1, uss_foto, uss_portada, uss_idioma, uss_tema, uss_tipo_documento, uss_apellido1)VALUES(:ussDocumento, SHA1('12345678'), 4, :ussNombres, 0, 0, 'default.png', 'default.png', 1, 'green', :ussTipoDocumento, :ussApellido1)";
    $estu = $pdoI->prepare($estuQuery);
    $estu->bindParam(':ussDocumento', $_POST['documento'], PDO::PARAM_STR);
    $estu->bindParam(':ussNombres', $_POST['nombreEstudiante'], PDO::PARAM_STR);
    $estu->bindParam(':ussTipoDocumento', $_POST['tipoDocumento'], PDO::PARAM_INT);
    $estu->bindParam(':ussApellido1', $_POST['apellido1'], PDO::PARAM_STR);
    $estu->execute();
    $estuId = $pdoI->lastInsertId();

    //Acudiente
    $acudienteQuery = "INSERT INTO usuarios(uss_usuario, uss_clave, uss_tipo, uss_nombre, uss_email, uss_celular)VALUES(:usuario, SHA1('12345678'), 3, :nombre, :email, :celular)";
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
    $matriculasQuery = "INSERT INTO academico_matriculas(mat_tipo_documento, mat_documento, mat_solicitud_inscripcion, mat_estado_matricula, mat_id_usuario, mat_primer_apellido, mat_nombres, mat_acudiente, mat_padre, mat_madre, mat_grado, mat_grupo)VALUES(:tipoDocumento, :documento, :solicitud, 5, :idUss, :apellido1, :nombres, :acudiente, :padre, :madre, :grado, 1)";
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

	$data = [
		'solicitud_id'     => $newId,
		'solicitud_nombre' => $_POST['nombreEstudiante'],
		'solicitud_documento' => $_POST['documento'],
		'usuario_email'    => $_POST['email'],
		'usuario_nombre'   => strtoupper($_POST['nombreAcudiente'])
	];
	$asunto = 'Solicitud de admisión ' . $newId;
	$bodyTemplateRoute = ROOT_PATH.'/config-general/template-email-index-inscripcion.php';
	

    $mail = new PHPMailer(true);

    try {

        ob_start();
        include($bodyTemplateRoute);
        $body = ob_get_clean();

        //Server settings
        $mail->SMTPDebug = 0;                                     // Enable verbose debug output
        $mail->isSMTP();                                            // Set mailer to use SMTP
        $mail->Host       = EMAIL_SERVER;  	                        // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = EMAIL_USER;              
        $mail->Password   = EMAIL_PASSWORD;                     
        $mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
        $mail->Port       = 465;

        //Remitente
        $mail->setFrom(EMAIL_SENDER, NAME_SENDER);

        //Destinatarios
        $mail->addAddress('soporte@plataformasintia.com', 'Soporte Plataforma SINTIA');//PLATAFORMA
        $mail->addAddress($data['usuario_email'], $data['usuario_nombre']);//ASPIRANTE
        $mail->addAddress($datosUss['uss_email'], $nombreUss);//SECRETARIA(O)

        // Content
        $mail->isHTML(true);                                   // Set email format to HTML
        $mail->Subject = $asunto;
        $mail->Body = $body;
        $mail->CharSet = 'UTF-8';

        $mail->send();

    } catch (Exception $e) {
        echo "Error: {$mail->ErrorInfo}";
        exit();
    }

    echo '<script type="text/javascript">window.location.href="consultar-estado.php?solicitud='.$newId.'&documento='.$_POST['documento'].'&idInst='.$_REQUEST['idInst'].'";</script>';
    exit();
} else {
    redireccionMal('index.php', 3);
}
