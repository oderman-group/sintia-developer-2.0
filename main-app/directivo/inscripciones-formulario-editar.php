<?php
include("session.php");
$idPaginaInterna = 'DT0028';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}

include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
include(ROOT_PATH."/main-app/compartido/head.php");

include(ROOT_PATH."/main-app/admisiones/php-funciones.php");
include(ROOT_PATH."/config-general/config-admisiones.php");

$id = "";
if (!empty($_GET["id"])) {
    $id = base64_decode($_GET["id"]);
}

if (md5($id) != $_GET['token']) {
    redireccionMal('respuestas-usuario.php', 4);
}

$estQuery = "SELECT * FROM academico_matriculas
LEFT JOIN usuarios ON uss_id=mat_acudiente
WHERE mat_solicitud_inscripcion = :id";
$est = $conexionPDO->prepare($estQuery);
$est->bindParam(':id', $id, PDO::PARAM_INT);
$est->execute();
$num = $est->rowCount();
$datos = $est->fetch();

//Documentos
$documentosQuery = "SELECT * FROM ".BD_ACADEMICA.".academico_matriculas_documentos WHERE matd_matricula = :id AND institucion= :idInstitucion AND year= :year";
$documentos = $conexionPDO->prepare($documentosQuery);
$documentos->bindParam(':id', $datos['mat_id'], PDO::PARAM_INT);
$documentos->bindParam(':idInstitucion', $config['conf_id_institucion'], PDO::PARAM_INT);
$documentos->bindParam(':year', $_SESSION["bd"], PDO::PARAM_STR);
$documentos->execute();
$datosDocumentos = $documentos->fetch();

//Padre
$padreQuery = "SELECT * FROM usuarios WHERE uss_id = :id";
$padre = $conexionPDO->prepare($padreQuery);
$padre->bindParam(':id', $datos['mat_padre'], PDO::PARAM_INT);
$padre->execute();
$datosPadre = $padre->fetch();

//Madre
$madreQuery = "SELECT * FROM usuarios WHERE uss_id = :id";
$madre = $conexionPDO->prepare($madreQuery);
$madre->bindParam(':id', $datos['mat_madre'], PDO::PARAM_INT);
$madre->execute();
$datosMadre = $madre->fetch();

//Aspirantes
$aspQuery = "SELECT * FROM ".$baseDatosAdmisiones.".aspirantes WHERE asp_id = :id";
$asp = $conexionPDO->prepare($aspQuery);
$asp->bindParam(':id', $id, PDO::PARAM_INT);
$asp->execute();
$datosAsp = $asp->fetch();
?>
    <!-- steps -->
    <link rel="stylesheet" href="../../config-general/assets/plugins/steps/steps.css"> 

    <!--select2-->
    <link href="../../config-general/assets/plugins/select2/css/select2.css" rel="stylesheet" type="text/css" />
    <link href="../../config-general/assets/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />

    <!--bootstrap -->
    <link href="../../config-general/assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    <link href="../../config-general/assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" media="screen">
    <script>
        tinymce.init({
            selector: 'textarea#editor',
            menubar: false
        });
    </script>
</head>
<!-- END HEAD -->
<?php include("../compartido/body.php");?>
    <div class="page-wrapper">
        <?php include("../compartido/encabezado.php");?>
		
        <?php include("../compartido/panel-color.php");?>
        <!-- start page container -->
        <div class="page-container">
 			<?php include("../compartido/menu.php");?>
			<!-- start page content -->
            <div class="page-content-wrapper">
                <div class="page-content">
                    <div class="page-bar">
                        <div class="page-title-breadcrumb">
                            <div class=" pull-left">
                                <div class="page-title">Gestionar Inscripción</div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
                            <ol class="breadcrumb page-breadcrumb pull-right">
                                <li><a class="parent-item" href="javascript:void(0);" name="inscripciones.php" onClick="deseaRegresar(this)">Inscripciones</a>&nbsp;<i class="fa fa-angle-right"></i></li>
                                <li class="active">Gestionar Inscripción</li>
                            </ol>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <?php include("../admisiones/alertas.php"); ?>
                            <?php include("../../config-general/mensajes-informativos.php"); ?>
                            <div class="card-box">
                                <div class="card-head">
                                    <header>Gestionar Inscripción</header>
                                </div>
                                <div class="card-body">
                                    <form name="example_advanced_form" id="example-advanced-form" action="inscripciones-formulario-actualizar.php" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="idMatricula" value="<?= $datos['mat_id']; ?>">
                                        <input type="hidden" name="solicitud" value="<?= $id; ?>">
                                        <input type="hidden" name="emailAcudiente" value="<?= $datos['uss_email']; ?>">
                                        <input type="hidden" name="nombreAcudiente" value="<?= $datosAsp['asp_nombre_acudiente']; ?>">
                                        <input type="hidden" name="documentoAspirante" value="<?= $datosAsp['asp_documento']; ?>">
                                        <input type="hidden" name="idPadre" value="<?= $datos['mat_padre']; ?>">
                                        <input type="hidden" name="idMadre" value="<?= $datos['mat_madre']; ?>">
                                        <input type="hidden" name="idInst" value="<?= $_REQUEST['idInst']; ?>">
                                        <input type="hidden" name="fotoA" value="<?= $datos['mat_foto']; ?>">
                                        <div class="form-group row">
                                            <div class="form-group col-md-4">
                                                <label>Nombres </label>
                                                <input type="text" class="form-control" name="nombre" value="<?= $datos['mat_nombres']; ?>" disabled>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>Primer Apellido </label>
                                                <input type="text" class="form-control" name="primerApellidos" value="<?= $datos['mat_primer_apellido']; ?>" disabled>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>Segundo Apellido</label>
                                                <input type="text" class="form-control" name="segundoApellidos" value="<?= $datos['mat_segundo_apellido']; ?>" disabled>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Género </label>
                                                <select class="form-control select2" name="genero" disabled>
                                                    <option value="">Escoger</option>
                                                    <option value="127" <?php if ($datos['mat_genero'] == 127) echo "selected"; ?>>Femenino</option>
                                                    <option value="126" <?php if ($datos['mat_genero'] == 126) echo "selected"; ?>>Masculino</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="form-group col-md-4">
                                                <label>Tipo de documento</label>
                                                <select class="form-control select2" name="tipoDoc" disabled>
                                                    <option value="">Escoger</option>
                                                    <option value="105" <?php if ($datos['mat_tipo_documento'] == 105) echo "selected"; ?>>Cédula de ciudadanía</option>
                                                    <option value="106" <?php if ($datos['mat_tipo_documento'] == 106) echo "selected"; ?>>NUIP</option>
                                                    <option value="107" <?php if ($datos['mat_tipo_documento'] == 107) echo "selected"; ?>>Tarjeta de identidad</option>
                                                    <option value="108" <?php if ($datos['mat_tipo_documento'] == 108) echo "selected"; ?>>Registro civil o NUIP</option>
                                                    <option value="109" <?php if ($datos['mat_tipo_documento'] == 109) echo "selected"; ?>>Cédula de Extranjería</option>
                                                    <option value="110" <?php if ($datos['mat_tipo_documento'] == 110) echo "selected"; ?>>Pasaporte</option>
                                                    <option value="139" <?php if ($datos['mat_tipo_documento'] == 139) echo "selected"; ?>>PEP</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Numero de documento </label>
                                                <input type="text" class="form-control" name="numeroDoc" value="<?= $datos['mat_documento']; ?>" disabled>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Lugar de expedición </label>
                                                <input type="text" class="form-control" name="LugarExp" value="<?= $datos['mat_lugar_expedicion']; ?>" disabled required>
                                            </div>
                                        </div>
                                        <hr class="my-4">
                                        <div class="form-group row">
                                            <div class="form-group col-md-6">
                                                <label>Estado de solicitud <span style="color:red;">(*)</span></label>
                                                <select class="form-control select2" name="estadoSolicitud" required>
                                                    <option value="">Escoger</option>
                                                    <?php foreach ($estadosSolicitud as $key => $value) { ?>
                                                        <option value="<?= $key; ?>" <?php if ($datosAsp['asp_estado_solicitud'] == $key) echo "selected"; ?>><?= $value; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Enviar correo al guardar los cambios </label>
                                                <select class="form-control select2" name="enviarCorreo" required>
                                                    <option value="">Escoger</option>
                                                    <option value="1">SI</option>
                                                    <option value="2" selected>NO</option>
                                                </select>
                                                <p class="text-info">Si escoge que sí, se enviará un correo al acudiente con la observación y el estado de la solicitud al guardar los cambios.</p>
                                                <p class="text-info">El mensaje se enviará al correo <b><?= $datos['uss_email']; ?></b>.</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Observación</label>
                                            <textarea class="form-control" name="observacion" rows="10" id="editor1"><?= $datosAsp['asp_observacion']; ?></textarea>
                                        </div>
                                        <h3 class="mb-4" style="text-align: center;">ARCHIVOS ADJUNTOS</h3>
                                        <div class="p-3 mb-2 bg-secondary text-white">Debe cargar solo un archivo por cada campo. Si necesita cargar más de un archivo en un solo campo por favor comprimalos(.ZIP, .RAR) y los carga.</div>
                                        <div class="form-group row">
                                            <div class="form-group col-md-6">
                                                <label>Archivo 1 </label>
                                                <input type="file" class="form-control" name="archivo1">
                                                <input type="hidden" name="archivo1A" value="<?= $datosAsp['asp_archivo1']; ?>">
                                                <?php if ($datosAsp['asp_archivo1'] != "" and file_exists(ROOT_PATH.'/main-app/admisiones/files/adjuntos/' . $datosAsp['asp_archivo1'])) { ?>
                                                    <p><a href="<?=REDIRECT_ROUTE?>/admisiones/files/adjuntos/<?= $datosAsp['asp_archivo1']; ?>" target="_blank" class="link"><?= $datosAsp['asp_archivo1']; ?></a></p>
                                                    <p><a href="<?=REDIRECT_ROUTE?>/admisiones/admin-adjuntos-eliminar.php?solicitud=<?= $_GET["id"]; ?>&adj=<?= base64_encode(1) ?>&file=<?= base64_encode($datosAsp['asp_archivo1']); ?>&idInst=<?= $_REQUEST['idInst'] ?>" onclick="if(!confirm('Desea eliminar este adjunto?')) {return false;}" style="text-decoration: underline; color:red;">Eliminar adjunto</a></p>
                                                <?php } ?>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Archivo 2</label>
                                                <input type="file" class="form-control" name="archivo2">
                                                <input type="hidden" name="archivo2A" value="<?= $datosAsp['asp_archivo2']; ?>">
                                                <?php if ($datosAsp['asp_archivo2'] != "" and file_exists(ROOT_PATH.'/main-app/admisiones/files/adjuntos/' . $datosAsp['asp_archivo2'])) { ?>
                                                    <p><a href="<?=REDIRECT_ROUTE?>/admisiones/files/adjuntos/<?= $datosAsp['asp_archivo2']; ?>" target="_blank" class="link"><?= $datosAsp['asp_archivo2']; ?></a></p>
                                                    <p><a href="<?=REDIRECT_ROUTE?>/admisiones/admin-adjuntos-eliminar.php?solicitud=<?= $_GET["id"]; ?>&adj=<?= base64_encode(2) ?>&file=<?= base64_encode($datosAsp['asp_archivo2']); ?>&idInst=<?= $_REQUEST['idInst'] ?>" onclick="if(!confirm('Desea eliminar este adjunto?')) {return false;}" style="text-decoration: underline; color:red;">Eliminar adjunto</a></p>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn  btn-info">
										<i class="fa fa-save" aria-hidden="true"></i> Guardar cambios 
									</button>
										<a href="javascript:void(0);" name="inscripciones.php" class="btn btn-secondary" onClick="deseaRegresar(this)"><i class="fa fa-long-arrow-left"></i>Regresar</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
					<div id="wizard" style="display: none;"></div>
                </div>
            </div>
            <!-- end page content -->
             <?php // include("../compartido/panel-configuracion.php");?>
        </div>
        <!-- end page container -->
        <?php include("../compartido/footer.php");?>
    </div>
    <!-- start js include path -->
    <script src="../../config-general/assets/plugins/jquery/jquery.min.js" ></script>
    <script src="../../config-general/assets/plugins/popper/popper.js" ></script>
    <script src="../../config-general/assets/plugins/jquery-blockui/jquery.blockui.min.js" ></script>
	<script src="../../config-general/assets/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
	<script src="../../config-general/assets/plugins/jquery-validation/js/jquery.validate.min.js"></script>
    <!-- bootstrap -->
    <script src="../../config-general/assets/plugins/bootstrap/js/bootstrap.min.js" ></script>
    <!-- steps -->
    <script src="../../config-general/assets/plugins/steps/jquery.steps.js" ></script>
    <script src="../../config-general/assets/js/pages/steps/steps-data.js" ></script>
    <!-- Common js-->
	<script src="../../config-general/assets/js/app.js" ></script>
    <script src="../../config-general/assets/js/layout.js" ></script>
	<script src="../../config-general/assets/js/theme-color.js" ></script>
	<!-- Material -->
	<script src="../../config-general/assets/plugins/material/material.min.js"></script>
	<!--select2-->
    <script src="../../config-general/assets/plugins/select2/js/select2.js" ></script>
    <script src="../../config-general/assets/js/pages/select2/select2-init.js" ></script>

	<script src="../../config-general/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"  charset="UTF-8"></script>
    <script src="../../config-general/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker-init.js"  charset="UTF-8"></script>
    <!-- end js include path -->
    <script src="../ckeditor/ckeditor.js"></script>

    <script>
        // Replace the <textarea id="editor1"> with a CKEditor 4
        // instance, using default configuration.
        CKEDITOR.replace( 'editor1' );
    </script>
</body>

</html>