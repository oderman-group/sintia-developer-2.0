<?php
include("session.php");
$idPaginaInterna = 'DT0319';
include("../compartido/head.php");

if (!Modulos::validarSubRol([$idPaginaInterna])) {
    echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
    exit();
}

$disabledPermiso = "";
if (!Modulos::validarPermisoEdicion()) {
    $disabledPermiso = "disabled";
}

$idE = '';
if (!empty($_GET['idE'])) {
    $idE = base64_decode($_GET['idE']);;
}
?>
    <!--bootstrap -->
    <link href="../../config-general/assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    <link href="../../config-general/assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" media="screen">
    <!-- Theme Styles -->
    <link href="../../config-general/assets/css/pages/formlayout.css" rel="stylesheet" type="text/css" />
    <!-- dropzone -->
    <link href="../../config-general/assets/plugins/dropzone/dropzone.css" rel="stylesheet" media="screen">
    <!--tagsinput-->
    <link href="../../config-general/assets/plugins/jquery-tags-input/jquery-tags-input.css" rel="stylesheet">
    <!--select2-->
    <link href="../../config-general/assets/plugins/select2/css/select2.css" rel="stylesheet" type="text/css" />
    <link href="../../config-general/assets/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
</head>
<!-- END HEAD -->
<?php include("../compartido/body.php"); ?>
<div class="page-wrapper">
    <?php include("../compartido/encabezado.php"); ?>

    <?php include("../compartido/panel-color.php"); ?>
    <!-- start page container -->
    <div class="page-container">
        <?php include("../compartido/menu.php"); ?>
        <!-- start page content -->
        <div class="page-content-wrapper">
            <div class="page-content">
                <div class="page-bar">
                    <div class="page-title-breadcrumb">
                        <div class=" pull-left">
                            <div class="page-title"><?= $frases[56][$datosUsuarioActual['uss_idioma']]; ?> Asignación</div>
                            <?php include("../compartido/texto-manual-ayuda.php"); ?>
                        </div>
                        <ol class="breadcrumb page-breadcrumb pull-right">
                            <li><a class="parent-item" href="javascript:void(0);" name="asignaciones.php?idE=<?= base64_encode($idE); ?>" onClick="deseaRegresar(this)">Asignaciones</a>&nbsp;<i class="fa fa-angle-right"></i></li>
                            <li class="active"><?= $frases[56][$datosUsuarioActual['uss_idioma']]; ?> Asignación</li>
                        </ol>
                    </div>
                </div>
                <div class="row">

                    <div class="col-sm-12">
                        <?php
                        include("../../config-general/mensajes-informativos.php");
                        ?>
                        <div class="panel">
                            <header class="panel-heading panel-heading-purple">Asignación </header>
                            <div class="panel-body">

                                <form name="formularioGuardar" action="asignaciones-guardar.php" method="post">
                                    <input type="hidden" value="<?= $idE; ?>" name="idE">
                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Tipo de Encuesta
                                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="A que o quien se va a enfocar esta asignación."><i class="fa fa-question"></i></button>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control  select2" name="tipoEncuesta" data-id-evaluado="0" onchange="selectEvaluado(this)" <?= $disabledPermiso; ?>>
                                                <option value="">Escoja una opción</option>
                                                <option value="<?=DIRECTIVO?>"><?=DIRECTIVO?></option>
                                                <option value="<?=DOCENTE?>"><?=DOCENTE?></option>
                                                <option value="<?=AREA?>"><?=AREA?></option>
                                                <option value="<?=MATERIA?>"><?=MATERIA?></option>
                                                <option value="<?=CURSO?>"><?=CURSO?></option>
                                            </select>
                                        </div>

                                        <label class="col-sm-2 control-label">Evaluado
                                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Escoja los usuarios, curso, materia o areas que seran evaluadas."><i class="fa fa-question"></i></button>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control  select2-multiple" multiple id="evaluado" name="evaluado[]" <?= $disabledPermiso; ?>>
                                                <option value="">Escoja una opción</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Evaluador
                                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Escoja el tipo de usuarios o cursos que realizaran esta encuesta."><i class="fa fa-question"></i></button>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control  select2" name="evaluador" onchange="mostrarSelectCurso(this)" <?= $disabledPermiso; ?>>
                                                <option value="">Escoja una opción</option>
                                                <option value="<?=ACUDIENTE?>"><?=ACUDIENTE?></option>
                                                <option value="<?=ESTUDIANTE?>"><?=ESTUDIANTE?></option>
                                                <option value="<?=DOCENTE?>"><?=DOCENTE?></option>
                                                <option value="<?=DIRECTIVO?>"><?=DIRECTIVO?></option>
                                                <option value="<?=CURSO?>"><?=CURSO?></option>
                                            </select>
                                        </div>

                                        <div id="elementSelectCurso" style="display: none;">
                                            <label class="col-sm-2 control-label">Escoje los cursos
                                                <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Si el evaluador sera curso, especifique que cursos realizaran la encuesta."><i class="fa fa-question"></i></button>
                                            </label>
                                            <div class="col-sm-4">
                                                <select class="form-control  select2-multiple" style="width: 100%;" multiple name="evaluadorCursos[]" <?= $disabledPermiso; ?>>
                                                    <option value="">Escoja una opción</option>
                                                    <?php
                                                        $consultaCursos = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_grados WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
                                                        while ($datosCursos = mysqli_fetch_array($consultaCursos, MYSQLI_BOTH)) {
                                                    ?>
                                                    <option value="<?=$datosCursos['gra_id']?>"><?=$datosCursos['gra_nombre']?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <a href="javascript:void(0);" name="asignaciones.php?idE=<?= base64_encode($idE); ?>" class="btn btn-secondary" onClick="deseaRegresar(this)"><i class="fa fa-long-arrow-left"></i><?= $frases[184][$datosUsuarioActual['uss_idioma']]; ?></a>
                                    <?php if (Modulos::validarPermisoEdicion()) { ?>
                                        <button type="submit" class="btn  btn-info">
                                            <i class="fa fa-save" aria-hidden="true"></i> <?= $frases[419][$datosUsuarioActual['uss_idioma']]; ?>
                                        </button>
                                    <?php } ?>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <!-- end page content -->
            <?php // include("../compartido/panel-configuracion.php");
            ?>
        </div>
        <!-- end page container -->
        <?php include("../compartido/footer.php"); ?>
    </div>
    <!-- start js include path -->
    <script src="../js/Asignaciones.js" ></script>
    <script src="../../config-general/assets/plugins/jquery/jquery.min.js" ></script>
    <script src="../../config-general/assets/plugins/popper/popper.js" ></script>
    <script src="../../config-general/assets/plugins/jquery-blockui/jquery.blockui.min.js" ></script>
	<script src="../../config-general/assets/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
    <!-- bootstrap -->
    <script src="../../config-general/assets/plugins/bootstrap/js/bootstrap.min.js" ></script>
    <script src="../../config-general/assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js" ></script>
    <script src="../../config-general/assets/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js" ></script>
    <script src="../../config-general/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"  charset="UTF-8"></script>
    <script src="../../config-general/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker-init.js"  charset="UTF-8"></script>
    <script src="../../config-general/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"  charset="UTF-8"></script>
    <script src="../../config-general/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker-init.js"  charset="UTF-8"></script>
    <!-- Common js-->
	<script src="../../config-general/assets/js/app.js" ></script>
    <script src="../../config-general/assets/js/layout.js" ></script>
	<script src="../../config-general/assets/js/theme-color.js" ></script>
	<!-- notifications -->
	<script src="../../config-general/assets/plugins/jquery-toast/dist/jquery.toast.min.js" ></script>
	<script src="../../config-general/assets/plugins/jquery-toast/dist/toast.js" ></script>	
	<!-- Material -->
	<script src="../../config-general/assets/plugins/material/material.min.js"></script>
	<!-- dropzone -->
    <script src="../../config-general/assets/plugins/dropzone/dropzone.js" ></script>
    <!--tags input-->
    <script src="../../config-general/assets/plugins/jquery-tags-input/jquery-tags-input.js" ></script>
    <script src="../../config-general/assets/plugins/jquery-tags-input/jquery-tags-input-init.js" ></script>
    <!--select2-->
    <script src="../../config-general/assets/plugins/select2/js/select2.js" ></script>
    <script src="../../config-general/assets/js/pages/select2/select2-init.js" ></script>
    <!-- end js include path -->
    <script src="../ckeditor/ckeditor.js"></script>
    </body>


    </html>