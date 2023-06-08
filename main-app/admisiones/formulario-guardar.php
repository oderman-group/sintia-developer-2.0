<?php
include("bd-conexion.php");
include("php-funciones.php");


if ($_FILES['foto']['name'] != "") {
	$destino = "files/fotos";
	$extension = end(explode(".", $_FILES['foto']['name']));
	$foto = uniqid('foto_') . "." . $extension;
	@unlink($destino . "/" . $foto);
	move_uploaded_file($_FILES['foto']['tmp_name'], $destino . "/" . $foto);
} else {
	$foto = $_POST['fotoA'];
}

$sql = "UPDATE academico_matriculas SET 
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
WHERE mat_id = :idMatricula";
$stmt = $pdoI->prepare($sql);

$stmt->bindParam(':idMatricula', $_POST['idMatricula'], PDO::PARAM_INT);
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

$stmt->execute();
$filasAfectadas = $stmt->rowCount();


//Actualiza estado en aspirantes
$aspQuery = 'UPDATE aspirantes SET asp_estado_solicitud = 4 WHERE asp_id = :id';
$asp = $pdo->prepare($aspQuery);
$asp->bindParam(':id', $_POST['solicitud'], PDO::PARAM_INT);
$asp->execute();


//Documentos
if ($_FILES['pazysalvo']['name'] != "") {
	$destino = "files/otros";
	$extension = end(explode(".", $_FILES['pazysalvo']['name']));
	$pazysalvo = uniqid('pyz_') . "." . $extension;
	@unlink($destino . "/" . $pazysalvo);
	move_uploaded_file($_FILES['pazysalvo']['tmp_name'], $destino . "/" . $pazysalvo);
} else {
	$pazysalvo = $_POST['pazysalvoA'];
}

if ($_FILES['observador']['name'] != "") {
	$destino = "files/otros";
	$extension = end(explode(".", $_FILES['observador']['name']));
	$observador = uniqid('obs_') . "." . $extension;
	@unlink($destino . "/" . $observador);
	move_uploaded_file($_FILES['observador']['tmp_name'], $destino . "/" . $observador);
} else {
	$observador = $_POST['observadorA'];
}

if ($_FILES['eps']['name'] != "") {
	$destino = "files/otros";
	$extension = end(explode(".", $_FILES['eps']['name']));
	$eps = uniqid('eps_') . "." . $extension;
	@unlink($destino . "/" . $eps);
	move_uploaded_file($_FILES['eps']['tmp_name'], $destino . "/" . $eps);
} else {
	$eps = $_POST['epsA'];
}

if ($_FILES['recomendacion']['name'] != "") {
	$destino = "files/otros";
	$extension = end(explode(".", $_FILES['recomendacion']['name']));
	$recomendacion = uniqid('rec_') . "." . $extension;
	@unlink($destino . "/" . $recomendacion);
	move_uploaded_file($_FILES['recomendacion']['tmp_name'], $destino . "/" . $recomendacion);
} else {
	$recomendacion = $_POST['recomendacionA'];
}

if ($_FILES['vacunas']['name'] != "") {
	$destino = "files/otros";
	$extension = end(explode(".", $_FILES['vacunas']['name']));
	$vacunas = uniqid('vac_') . "." . $extension;
	@unlink($destino . "/" . $vacunas);
	move_uploaded_file($_FILES['vacunas']['tmp_name'], $destino . "/" . $vacunas);
} else {
	$vacunas = $_POST['vacunasA'];
}

if ($_FILES['boletines']['name'] != "") {
	$destino = "files/otros";
	$extension = end(explode(".", $_FILES['boletines']['name']));
	$boletines = uniqid('bol_') . "." . $extension;
	@unlink($destino . "/" . $boletines);
	move_uploaded_file($_FILES['boletines']['tmp_name'], $destino . "/" . $boletines);
} else {
	$boletines = $_POST['boletinesA'];
}

if ($_FILES['documentoIde']['name'] != "") {
	$destino = "files/otros";
	$extension = end(explode(".", $_FILES['documentoIde']['name']));
	$documentoIde = uniqid('doc_') . "." . $extension;
	@unlink($destino . "/" . $documentoIde);
	move_uploaded_file($_FILES['documentoIde']['tmp_name'], $destino . "/" . $documentoIde);
} else {
	$documentoIde = $_POST['documentoIdeA'];
}

if ($_FILES['certificado']['name'] != "") {
	$destino = "files/otros";
	$extension = end(explode(".", $_FILES['certificado']['name']));
	$certificado = uniqid('cert_') . "." . $extension;
	@unlink($destino . "/" . $certificado);
	move_uploaded_file($_FILES['certificado']['tmp_name'], $destino . "/" . $certificado);
} else {
	$certificado = $_POST['certificadoA'];
}

$documentosQuery = "UPDATE academico_matriculas_documentos SET
matd_pazysalvo = :pazysalvo, 
matd_observador = :observador, 
matd_eps = :eps, 
matd_recomendacion = :recomendacion, 
matd_vacunas = :vacunas, 
matd_boletines_actuales = :boletines,
matd_documento_identidad = :documentoIde,
matd_certificados = :certificado
WHERE matd_matricula = :idMatricula";
$documentos = $pdoI->prepare($documentosQuery);

$documentos->bindParam(':idMatricula', $_POST['idMatricula'], PDO::PARAM_INT);
$documentos->bindParam(':pazysalvo', $pazysalvo, PDO::PARAM_STR);
$documentos->bindParam(':observador', $observador, PDO::PARAM_STR);
$documentos->bindParam(':eps', $eps, PDO::PARAM_STR);
$documentos->bindParam(':vacunas', $vacunas, PDO::PARAM_STR);
$documentos->bindParam(':boletines', $boletines, PDO::PARAM_STR);
$documentos->bindParam(':documentoIde', $documentoIde, PDO::PARAM_STR);
$documentos->bindParam(':recomendacion', $recomendacion, PDO::PARAM_STR);
$documentos->bindParam(':certificado', $certificado, PDO::PARAM_STR);

$documentos->execute();
$filasAfectadasDoc = $documentos->rowCount();


//Acudiente
$acudienteQuery = "UPDATE usuarios SET
uss_usuario = :usuarioAcudiente, 
uss_clave = 1234, 
uss_nombre = :nombreAcudiente, 
uss_telefono = :telefonoAcudiente, 
uss_celular = :celularAcudiente, 
uss_direccion = :dirAcudiente, 
uss_email = :emailAcudiente,
uss_religion = :religionAcudiente,
uss_parentezco = :parentescoAcudiente
WHERE uss_id = :acudiente";
$acudiente = $pdoI->prepare($acudienteQuery);

$acudiente->bindParam(':acudiente', $_POST['idAcudiente'], PDO::PARAM_INT);
$acudiente->bindParam(':usuarioAcudiente', $_POST['documentoAcudiente'], PDO::PARAM_STR);
$acudiente->bindParam(':nombreAcudiente', $_POST['nombreAcudiente'], PDO::PARAM_STR);
$acudiente->bindParam(':telefonoAcudiente', $_POST['telfonoAcudiente'], PDO::PARAM_STR);
$acudiente->bindParam(':celularAcudiente', $_POST['celularAcudiente'], PDO::PARAM_STR);
$acudiente->bindParam(':dirAcudiente', $_POST['direccionAcudiente'], PDO::PARAM_STR);
$acudiente->bindParam(':emailAcudiente', $_POST['emailAcudiente'], PDO::PARAM_STR);
$acudiente->bindParam(':religionAcudiente', $_POST['religionAcudiente'], PDO::PARAM_STR);
$acudiente->bindParam(':parentescoAcudiente', $_POST['parentesco'], PDO::PARAM_STR);

$acudiente->execute();
$filasAfectadasAcu = $acudiente->rowCount();

//Padre
$padreQuery = "UPDATE usuarios SET
uss_usuario = :usuariopadre, 
uss_clave = 1234, 
uss_nombre = :nombrepadre, 
uss_telefono = :telefonopadre, 
uss_celular = :celularpadre, 
uss_direccion = :dirpadre, 
uss_email = :emailpadre,
uss_ocupacion = :ocupacionpadre,
uss_religion = :religionpadre,
uss_tipo_documento = :tipodocpadre
WHERE uss_id = :padre";
$padre = $pdoI->prepare($padreQuery);

$padre->bindParam(':padre', $_POST['idPadre'], PDO::PARAM_INT);
$padre->bindParam(':usuariopadre', $_POST['documentoPadre'], PDO::PARAM_STR);
$padre->bindParam(':nombrepadre', $_POST['nombrePadre'], PDO::PARAM_STR);
$padre->bindParam(':telefonopadre', $_POST['telfonoPadre'], PDO::PARAM_STR);
$padre->bindParam(':celularpadre', $_POST['celularPadre'], PDO::PARAM_STR);
$padre->bindParam(':dirpadre', $_POST['direccionPadre'], PDO::PARAM_STR);
$padre->bindParam(':emailpadre', $_POST['emailPadre'], PDO::PARAM_STR);
$padre->bindParam(':ocupacionpadre', $_POST['ocupacionPadre'], PDO::PARAM_STR);
$padre->bindParam(':religionpadre', $_POST['religionPadre'], PDO::PARAM_STR);
$padre->bindParam(':tipodocpadre', $_POST['tipoDocumentoPadre'], PDO::PARAM_STR);

$padre->execute();
$filasAfectadasPad = $padre->rowCount();


//Madre
$madreQuery = "UPDATE usuarios SET
uss_usuario = :usuariomadre, 
uss_clave = 1234, 
uss_nombre = :nombremadre, 
uss_telefono = :telefonomadre, 
uss_celular = :celularmadre, 
uss_direccion = :dirmadre, 
uss_email = :emailmadre,
uss_ocupacion = :ocupacionmadre,
uss_religion = :religionmadre,
uss_tipo_documento = :tipodocmadre
WHERE uss_id = :madre";
$madre = $pdoI->prepare($madreQuery);

$madre->bindParam(':madre', $_POST['idMadre'], PDO::PARAM_INT);
$madre->bindParam(':usuariomadre', $_POST['documentoMadre'], PDO::PARAM_STR);
$madre->bindParam(':nombremadre', $_POST['nombreMadre'], PDO::PARAM_STR);
$madre->bindParam(':telefonomadre', $_POST['telfonoMadre'], PDO::PARAM_STR);
$madre->bindParam(':celularmadre', $_POST['celularMadre'], PDO::PARAM_STR);
$madre->bindParam(':dirmadre', $_POST['direccionMadre'], PDO::PARAM_STR);
$madre->bindParam(':emailmadre', $_POST['emailMadre'], PDO::PARAM_STR);
$madre->bindParam(':ocupacionmadre', $_POST['ocupacionMadre'], PDO::PARAM_STR);
$madre->bindParam(':religionmadre', $_POST['religionMadre'], PDO::PARAM_STR);
$madre->bindParam(':tipodocmadre', $_POST['tipoDocumentoMadre'], PDO::PARAM_STR);

$madre->execute();
$filasAfectadasMad = $madre->rowCount();




header('Location:formulario.php?msg=3&token=' . md5($_POST['solicitud']) . '&idInst=' . $_POST['idInst'] . '&id=' . $_POST['solicitud'] . '&fa=' . $filasAfectadas . '&faa=' . $filasAfectadasAcu);
exit();
