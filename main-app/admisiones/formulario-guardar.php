<?php
include("bd-conexion.php");
include("php-funciones.php");
require_once(ROOT_PATH."/main-app/class/Inscripciones.php");


if (!empty($_FILES['foto']['name'])) {
	$destino = "files/fotos";
    $explode = explode(".", $_FILES['foto']['name']);
	$extension = end($explode);
	$foto = uniqid('foto_') . "." . $extension;
	@unlink($destino . "/" . $foto);
	move_uploaded_file($_FILES['foto']['tmp_name'], $destino . "/" . $foto);
} else {
	$foto = $_POST['fotoA'];
}

$sql = "UPDATE ".BD_ACADEMICA.".academico_matriculas SET 
mat_primer_apellido = :apellido1, 
mat_segundo_apellido = :apellido2, 
mat_nombres = :nombre, 
mat_grado = :grado, 
mat_genero = :genero, 
mat_fecha_nacimiento = :fechaNacimiento, 
mat_lugar_nacimiento = :lugarNacimiento, 
mat_tipo_documento = :tipoDocumento, 
mat_documento = :documento, 
mat_direccion = :direccion, 
mat_barrio = :barrio, 
mat_ciudad_actual = :ciudadActual,
mat_razon_ingreso_plantel = :razonIngreso,
mat_institucion_procedencia = :institucionProcedencia,
mat_lugar_colegio_procedencia = :iLugarProcedencia,
mat_lugar_expedicion = :lugarExp,
mat_motivo_retiro_anterior = :motivoRetiro,
mat_foto = :foto
WHERE mat_id = :idMatricula AND institucion= :idInstitucion AND year= :year";
$stmt = $pdoI->prepare($sql);

$stmt->bindParam(':idMatricula', $_POST['idMatricula'], PDO::PARAM_STR);
$stmt->bindParam(':apellido1', $_POST['primerApellidos'], PDO::PARAM_STR);
$stmt->bindParam(':apellido2', $_POST['segundoApellidos'], PDO::PARAM_STR);
$stmt->bindParam(':nombre', $_POST['nombre'], PDO::PARAM_STR);
$stmt->bindParam(':grado', $_POST['curso'], PDO::PARAM_INT);
$stmt->bindParam(':genero', $_POST['genero'], PDO::PARAM_INT);
$stmt->bindParam(':fechaNacimiento', $_POST['fechaNacimiento'], PDO::PARAM_STR);
$stmt->bindParam(':lugarNacimiento', $_POST['LugarNacimiento'], PDO::PARAM_STR);
$stmt->bindParam(':tipoDocumento', $_POST['tipoDoc'], PDO::PARAM_INT);
$stmt->bindParam(':documento', $_POST['numeroDoc'], PDO::PARAM_STR);
$stmt->bindParam(':direccion', $_POST['direccion'], PDO::PARAM_STR);
$stmt->bindParam(':barrio', $_POST['barrio'], PDO::PARAM_STR);
$stmt->bindParam(':ciudadActual', $_POST['municipio'], PDO::PARAM_STR);
$stmt->bindParam(':razonIngreso', $_POST['razonPlantel'], PDO::PARAM_STR);
$stmt->bindParam(':institucionProcedencia', $_POST['coleAnoAnterior'], PDO::PARAM_STR);
$stmt->bindParam(':iLugarProcedencia', $_POST['lugar'], PDO::PARAM_STR);
$stmt->bindParam(':lugarExp', $_POST['LugarExp'], PDO::PARAM_STR);
$stmt->bindParam(':motivoRetiro', $_POST['motivo'], PDO::PARAM_STR);
$stmt->bindParam(':foto', $foto, PDO::PARAM_STR);
$stmt->bindParam(':idInstitucion', $datosConfig['conf_id_institucion'], PDO::PARAM_INT);
$stmt->bindParam(':year', $datosConfig['conf_agno'], PDO::PARAM_STR);

$stmt->execute();
$filasAfectadas = $stmt->rowCount();


//Actualiza estado en aspirantes

$nombreCompleto=$_POST['nombre'].' '.$_POST['primerApellidos'].' '.$_POST['segundoApellidos'];
$aspQuery = 'UPDATE aspirantes SET asp_estado_solicitud = 4, asp_nombre = :nombre WHERE asp_id = :id';
$asp = $pdo->prepare($aspQuery);
$asp->bindParam(':id', $_POST['solicitud'], PDO::PARAM_INT);
$asp->bindParam(':nombre', $nombreCompleto, PDO::PARAM_STR);
$asp->execute();


//Documentos
Inscripciones::actualizarDocumentos($pdoI, $datosConfig, $_FILES, $_POST);

$documentos->execute();
$filasAfectadasDoc = $documentos->rowCount();


//Acudiente
$acudienteQuery = "UPDATE ".BD_GENERAL.".usuarios SET
uss_usuario = :usuarioAcudiente, 
uss_clave = SHA1('12345678'), 
uss_nombre = :nombreAcudiente, 
uss_telefono = :telefonoAcudiente, 
uss_celular = :celularAcudiente, 
uss_direccion = :dirAcudiente, 
uss_email = :emailAcudiente,
uss_religion = :religionAcudiente,
uss_parentezco = :parentescoAcudiente
WHERE uss_id = :acudiente AND institucion= :idInstitucion AND year= :year";
$acudiente = $pdoI->prepare($acudienteQuery);

$acudiente->bindParam(':acudiente', $_POST['idAcudiente'], PDO::PARAM_STR);
$acudiente->bindParam(':usuarioAcudiente', $_POST['documentoAcudiente'], PDO::PARAM_STR);
$acudiente->bindParam(':nombreAcudiente', $_POST['nombreAcudiente'], PDO::PARAM_STR);
$acudiente->bindParam(':telefonoAcudiente', $_POST['telfonoAcudiente'], PDO::PARAM_STR);
$acudiente->bindParam(':celularAcudiente', $_POST['celularAcudiente'], PDO::PARAM_STR);
$acudiente->bindParam(':dirAcudiente', $_POST['direccionAcudiente'], PDO::PARAM_STR);
$acudiente->bindParam(':emailAcudiente', $_POST['emailAcudiente'], PDO::PARAM_STR);
$acudiente->bindParam(':religionAcudiente', $_POST['religionAcudiente'], PDO::PARAM_STR);
$acudiente->bindParam(':parentescoAcudiente', $_POST['parentesco'], PDO::PARAM_STR);
$acudiente->bindParam(':idInstitucion', $datosConfig['conf_id_institucion'], PDO::PARAM_INT);
$acudiente->bindParam(':year', $datosConfig['conf_agno'], PDO::PARAM_STR);

$acudiente->execute();
$filasAfectadasAcu = $acudiente->rowCount();

//Padre
$padreQuery = "UPDATE ".BD_GENERAL.".usuarios SET
uss_usuario = :usuariopadre, 
uss_clave = SHA1('12345678'), 
uss_nombre = :nombrepadre, 
uss_telefono = :telefonopadre, 
uss_celular = :celularpadre, 
uss_direccion = :dirpadre, 
uss_email = :emailpadre,
uss_ocupacion = :ocupacionpadre,
uss_religion = :religionpadre,
uss_tipo_documento = :tipodocpadre
WHERE uss_id = :padre AND institucion= :idInstitucion AND year= :year";
$padre = $pdoI->prepare($padreQuery);

$padre->bindParam(':padre', $_POST['idPadre'], PDO::PARAM_STR);
$padre->bindParam(':usuariopadre', $_POST['documentoPadre'], PDO::PARAM_STR);
$padre->bindParam(':nombrepadre', $_POST['nombrePadre'], PDO::PARAM_STR);
$padre->bindParam(':telefonopadre', $_POST['telfonoPadre'], PDO::PARAM_STR);
$padre->bindParam(':celularpadre', $_POST['celularPadre'], PDO::PARAM_STR);
$padre->bindParam(':dirpadre', $_POST['direccionPadre'], PDO::PARAM_STR);
$padre->bindParam(':emailpadre', $_POST['emailPadre'], PDO::PARAM_STR);
$padre->bindParam(':ocupacionpadre', $_POST['ocupacionPadre'], PDO::PARAM_STR);
$padre->bindParam(':religionpadre', $_POST['religionPadre'], PDO::PARAM_STR);
$padre->bindParam(':tipodocpadre', $_POST['tipoDocumentoPadre'], PDO::PARAM_STR);
$padre->bindParam(':idInstitucion', $datosConfig['conf_id_institucion'], PDO::PARAM_INT);
$padre->bindParam(':year', $datosConfig['conf_agno'], PDO::PARAM_STR);

$padre->execute();
$filasAfectadasPad = $padre->rowCount();


//Madre
$madreQuery = "UPDATE ".BD_GENERAL.".usuarios SET
uss_usuario = :usuariomadre, 
uss_clave = SHA1('12345678'), 
uss_nombre = :nombremadre, 
uss_telefono = :telefonomadre, 
uss_celular = :celularmadre, 
uss_direccion = :dirmadre, 
uss_email = :emailmadre,
uss_ocupacion = :ocupacionmadre,
uss_religion = :religionmadre,
uss_tipo_documento = :tipodocmadre
WHERE uss_id = :madre AND institucion= :idInstitucion AND year= :year";
$madre = $pdoI->prepare($madreQuery);

$madre->bindParam(':madre', $_POST['idMadre'], PDO::PARAM_STR);
$madre->bindParam(':usuariomadre', $_POST['documentoMadre'], PDO::PARAM_STR);
$madre->bindParam(':nombremadre', $_POST['nombreMadre'], PDO::PARAM_STR);
$madre->bindParam(':telefonomadre', $_POST['telfonoMadre'], PDO::PARAM_STR);
$madre->bindParam(':celularmadre', $_POST['celularMadre'], PDO::PARAM_STR);
$madre->bindParam(':dirmadre', $_POST['direccionMadre'], PDO::PARAM_STR);
$madre->bindParam(':emailmadre', $_POST['emailMadre'], PDO::PARAM_STR);
$madre->bindParam(':ocupacionmadre', $_POST['ocupacionMadre'], PDO::PARAM_STR);
$madre->bindParam(':religionmadre', $_POST['religionMadre'], PDO::PARAM_STR);
$madre->bindParam(':tipodocmadre', $_POST['tipoDocumentoMadre'], PDO::PARAM_STR);
$madre->bindParam(':idInstitucion', $datosConfig['conf_id_institucion'], PDO::PARAM_INT);
$madre->bindParam(':year', $datosConfig['conf_agno'], PDO::PARAM_STR);

$madre->execute();
$filasAfectadasMad = $madre->rowCount();




header('Location:formulario.php?msg='.base64_encode(3).'&token=' . md5($_POST['solicitud']) . '&idInst=' . $_REQUEST['idInst'] . '&id=' . base64_encode($_POST['solicitud']) . '&fa=' . base64_encode($filasAfectadas) . '&faa=' . base64_encode($filasAfectadasAcu));
exit();
