<?php
include("bd-conexion.php");
include("php-funciones.php");
require_once("../class/EnviarEmail.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
require_once(ROOT_PATH."/main-app/class/Inscripciones.php");
require_once(ROOT_PATH."/main-app/class/Tables/BDT_aspirante.php");
require_once(ROOT_PATH."/main-app/class/Estudiantes.php");

$idInst = "";

if (!empty($_REQUEST["idInst"])) { 
    $idInst = base64_decode($_REQUEST["idInst"]);
}

Inscripciones::iniciarTransacion();

$year = $config["cfgi_year_inscripcion"];

//DATOS SECRETARIA(O)
$ussQuery = "SELECT * FROM ".BD_GENERAL.".usuarios WHERE uss_id = :idSecretaria AND institucion= :idInstitucion AND year= :year";
$uss      = $pdoI->prepare($ussQuery);

$uss->bindParam(':idSecretaria', $datosInfo['info_secretaria_academica'], PDO::PARAM_STR);
$uss->bindParam(':idInstitucion', $config['conf_id_institucion'], PDO::PARAM_INT);
$uss->bindParam(':year', $config['conf_agno'], PDO::PARAM_STR);
$uss->execute();

$datosUss  = $uss->fetch();
$nombreUss = strtoupper($datosUss['uss_nombre']." ".$datosUss['uss_apellido1']);

$estQuery = "SELECT * FROM aspirantes 
WHERE asp_documento = :documento 
AND asp_institucion = :institucion 
AND asp_agno = :years
AND asp_oculto = ".BDT_Aspirante::ESTADO_OCULTO_FALSO."
";

$est = $pdo->prepare($estQuery);

$est->bindParam(':documento', $_POST['documento'], PDO::PARAM_INT);
$est->bindParam(':institucion', $idInst, PDO::PARAM_INT);
$est->bindParam(':years', $year, PDO::PARAM_INT);
$est->execute();

$num   = $est->rowCount();
$datos = $est->fetch();

if ($num > 0) {
    header("Location:index.php?error=".base64_encode(2)."&documento=" . $_POST['documento']);
    exit();
}

if (md5($_POST['idInst']) != $_POST['iditoken']) {
    redireccionMal('index.php', 1);
}

$nombreCompleto          = $_POST['nombreEstudiante'].' '.$_POST['nombreEstudiante2'].' '.$_POST['apellido1'].' '.$_POST['apellido2'];
$nombreCompletoAcudiente = $_POST['nombre1Acudiente'].' '.$_POST['nombre2Acudiente'].' '.$_POST['apellido1Acudiente'].' '.$_POST['apellido2Acudiente'];

$sql = "INSERT INTO aspirantes(asp_institucion, asp_tipo_documento, asp_documento, asp_nombre, asp_email_acudiente, asp_nombre_acudiente, asp_celular_acudiente, asp_agno, asp_estado_solicitud, asp_documento_acudiente, asp_grado, asp_hizo_proceso_antes)VALUES(:institucion, :tipoDocumento, :documento, :nombreEstudiante, :email, :nombreAcudiente, :celular, '".$year."', 8, :documentoAcudiente, :grado, :hizoProceso)";
$stmt = $pdo->prepare($sql);

$stmt->bindParam(':institucion', $idInst, PDO::PARAM_INT);
$stmt->bindParam(':tipoDocumento', $_POST['tipoDocumento'], PDO::PARAM_INT);
$stmt->bindParam(':documento', $_POST['documento'], PDO::PARAM_STR);
$stmt->bindParam(':nombreEstudiante', $nombreCompleto, PDO::PARAM_STR);
$stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
$stmt->bindParam(':nombreAcudiente', $nombreCompletoAcudiente, PDO::PARAM_STR);
$stmt->bindParam(':celular', $_POST['celular'], PDO::PARAM_STR);
$stmt->bindParam(':documentoAcudiente', $_POST['documentoAcudiente'], PDO::PARAM_STR);
$stmt->bindParam(':grado', $_POST['grado'], PDO::PARAM_INT);
$stmt->bindParam(':hizoProceso', $_POST['procesoAdmisionAntes'], PDO::PARAM_INT);

$stmt->execute();

$newId = $pdo->lastInsertId();

if ($newId > 0) {

    //Guardar información en SINTIA COLEGIOS

    //Estudiante
    $estuQuery = "INSERT INTO ".BD_GENERAL.".usuarios(uss_id, uss_usuario, uss_clave, uss_tipo, uss_nombre, uss_estado, uss_permiso1, uss_foto, uss_portada, uss_idioma, uss_tema, uss_tipo_documento, uss_apellido1, institucion, year, uss_documento, uss_nombre2, uss_apellido2)VALUES(:codigo, :ussDocumento, SHA1('12345678'), 4, :ussNombres, 0, 0, 'default.png', 'default.png', 1, 'green', :ussTipoDocumento, :ussApellido1, :idInstitucion, :year, :ussDNI, :nombre2, :apellido2)";

    $estuId = Utilidades::generateCode("USS");
    $estu   = $pdoI->prepare($estuQuery);

    $estu->bindParam(':codigo', $estuId, PDO::PARAM_STR);
    $estu->bindParam(':ussDocumento', $_POST['documento'], PDO::PARAM_STR);
    $estu->bindParam(':ussNombres', $_POST['nombreEstudiante'], PDO::PARAM_STR);
    $estu->bindParam(':ussTipoDocumento', $_POST['tipoDocumento'], PDO::PARAM_INT);
    $estu->bindParam(':ussApellido1', $_POST['apellido1'], PDO::PARAM_STR);
    $estu->bindParam(':idInstitucion', $config['conf_id_institucion'], PDO::PARAM_INT);
    $estu->bindParam(':year', $config['conf_agno'], PDO::PARAM_STR);
    $estu->bindParam(':ussDNI', $_POST['documento'], PDO::PARAM_STR);
    $estu->bindParam(':nombre2', $_POST['nombreEstudiante2'], PDO::PARAM_STR);
    $estu->bindParam(':apellido2', $_POST['apellido2'], PDO::PARAM_STR);
    $estu->execute();

    //Acudiente
    $acudienteQuery = "INSERT INTO ".BD_GENERAL.".usuarios(uss_id, uss_usuario, uss_clave, uss_tipo, uss_nombre, uss_email, uss_celular, institucion, year, uss_documento, uss_nombre2, uss_apellido1, uss_apellido2, uss_tipo_documento)VALUES(:codigo, :usuario, SHA1('12345678'), 3, :nombre, :email, :celular, :idInstitucion, :year, :ussDNI, :nombre2, :apellido1, :apellido2, :ussTipoDocumento)";

    $acuId     = Utilidades::generateCode("USS");
    $acudiente = $pdoI->prepare($acudienteQuery);

    $acudiente->bindParam(':codigo', $acuId, PDO::PARAM_STR);
    $acudiente->bindParam(':usuario', $_POST['documentoAcudiente'], PDO::PARAM_STR);
    $acudiente->bindParam(':nombre', $_POST['nombre1Acudiente'], PDO::PARAM_STR);
    $acudiente->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
    $acudiente->bindParam(':celular', $_POST['celular'], PDO::PARAM_STR);
    $acudiente->bindParam(':idInstitucion', $config['conf_id_institucion'], PDO::PARAM_INT);
    $acudiente->bindParam(':year', $config['conf_agno'], PDO::PARAM_STR);
    $acudiente->bindParam(':ussDNI', $_POST['documentoAcudiente'], PDO::PARAM_STR);
    $acudiente->bindParam(':nombre2', $_POST['nombre2Acudiente'], PDO::PARAM_STR);
    $acudiente->bindParam(':apellido1', $_POST['apellido1Acudiente'], PDO::PARAM_STR);
    $acudiente->bindParam(':apellido2', $_POST['apellido2Acudiente'], PDO::PARAM_STR);
    $acudiente->bindParam(':ussTipoDocumento', $_POST['tipoDocumentoAcudiente'], PDO::PARAM_INT);
    $acudiente->execute();

    //Padre
    $padreQuery = "INSERT INTO ".BD_GENERAL.".usuarios(uss_id, uss_tipo, institucion, year)VALUES(:codigo, 3, :idInstitucion, :year)";
    $padreId    = Utilidades::generateCode("USS");
    $padre      = $pdoI->prepare($padreQuery);

    $padre->bindParam(':codigo', $padreId, PDO::PARAM_STR);
    $padre->bindParam(':idInstitucion', $config['conf_id_institucion'], PDO::PARAM_INT);
    $padre->bindParam(':year', $config['conf_agno'], PDO::PARAM_STR);
    $padre->execute();

    //Madre
    $madreQuery = "INSERT INTO ".BD_GENERAL.".usuarios(uss_id, uss_tipo, institucion, year)VALUES(:codigo, 3, :idInstitucion, :year)";
    $madreId    = Utilidades::generateCode("USS");
    $madre      = $pdoI->prepare($madreQuery);

    $madre->bindParam(':codigo', $madreId, PDO::PARAM_STR);
    $madre->bindParam(':idInstitucion', $config['conf_id_institucion'], PDO::PARAM_INT);
    $madre->bindParam(':year', $config['conf_agno'], PDO::PARAM_STR);
    $madre->execute();

    //Matriculas
    $matriculasQuery = "INSERT INTO ".BD_ACADEMICA.".academico_matriculas(mat_id, mat_tipo_documento, mat_documento, mat_solicitud_inscripcion, mat_estado_matricula, mat_id_usuario, mat_primer_apellido, mat_nombres, mat_acudiente, mat_padre, mat_madre, mat_grado, mat_grupo, institucion, year, mat_nombre2, mat_segundo_apellido, mat_forma_creacion)VALUES(:codigo, :tipoDocumento, :documento, :solicitud, 5, :idUss, :apellido1, :nombres, :acudiente, :padre, :madre, :grado, 1, :idInstitucion, :year, :nombre2, :apellido2, :formaCreacion)";

    $formaCreacion = Estudiantes::AUTO_INSCRIPCION;

    $codigoMAT  = Utilidades::getNextIdSequence($pdoI, BD_ACADEMICA, 'academico_matriculas');
    $matriculas = $pdoI->prepare($matriculasQuery);

    $matriculas->bindParam(':codigo', $codigoMAT, PDO::PARAM_STR);
    $matriculas->bindParam(':tipoDocumento', $_POST['tipoDocumento'], PDO::PARAM_INT);
    $matriculas->bindParam(':documento', $_POST['documento'], PDO::PARAM_STR);
    $matriculas->bindParam(':solicitud', $newId, PDO::PARAM_INT);
    $matriculas->bindParam(':idUss', $estuId, PDO::PARAM_STR);
    $matriculas->bindParam(':apellido1', $_POST['apellido1'], PDO::PARAM_STR);
    $matriculas->bindParam(':nombres', $_POST['nombreEstudiante'], PDO::PARAM_STR);
    $matriculas->bindParam(':acudiente', $acuId, PDO::PARAM_STR);
    $matriculas->bindParam(':padre', $padreId, PDO::PARAM_STR);
    $matriculas->bindParam(':madre', $madreId, PDO::PARAM_STR);
    $matriculas->bindParam(':grado', $_POST['grado'], PDO::PARAM_STR);
    $matriculas->bindParam(':idInstitucion', $config['conf_id_institucion'], PDO::PARAM_INT);
    $matriculas->bindParam(':year', $config['conf_agno'], PDO::PARAM_STR);
    $matriculas->bindParam(':nombre2', $_POST['nombreEstudiante2'], PDO::PARAM_STR);
    $matriculas->bindParam(':apellido2', $_POST['apellido2'], PDO::PARAM_STR);
    $matriculas->bindParam(':formaCreacion', $formaCreacion, PDO::PARAM_STR);
    $matriculas->execute();

    //Documentos
    Inscripciones::guardarDocumentos($pdoI, $config, $codigoMAT);
    Inscripciones::finalizarTransacion();

    //Mensaje para correo

	$data = [
		'solicitud_id'        => $newId,
		'solicitud_nombre'    => $nombreCompleto,
		'solicitud_documento' => $_POST['documento'],
		'usuario_email'       => $_POST['email'],
		'usuario_nombre'      => strtoupper($nombreCompletoAcudiente),
        'usuario2_email'      => $datosUss['uss_email'],
        'usuario2_nombre'     => $nombreUss,
        'institucion_id'      => $datosInfo['info_institucion'],
        'usuario_id'          => $datosUss['uss_id']
        
	];

	$asunto            = 'Solicitud de admisión ' . $newId;
	$bodyTemplateRoute = ROOT_PATH.'/config-general/template-email-index-inscripcion.php';

	EnviarEmail::enviar($data, $asunto, $bodyTemplateRoute, null, null);

    echo '<script type="text/javascript">window.location.href="consultar-estado.php?solicitud='.base64_encode($newId).'&documento='.base64_encode($_POST['documento']).'&idInst='.$_REQUEST['idInst'].'";</script>';
    exit();
} else {
    redireccionMal('index.php', 3);
}
