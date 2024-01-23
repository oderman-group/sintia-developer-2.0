<?php
include("session.php");

$idPaginaInterna = 'DV0075';

include("../compartido/historial-acciones-guardar.php");

Modulos::verificarPermisoDev();

include("../compartido/head.php");
require_once(ROOT_PATH."/main-app/class/Scripts.php");
$id = '';
if (!empty($_GET['id'])) {
    $id = base64_decode($_GET['id']);
}
$resultado = Scripts::datosScripts($conexion, $config, $id);
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
                            <div class="page-title">Script SQL Detalles</div>
                            <?php include("../compartido/texto-manual-ayuda.php"); ?>
                        </div>
                        <ol class="breadcrumb page-breadcrumb pull-right">
                            <li><a class="parent-item" href="javascript:void(0);" name="dev-scripts.php" onClick="deseaRegresar(this)">Script SQL</a>&nbsp;<i class="fa fa-angle-right"></i></li>
                            <li class="active">Script SQL Detalles</li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel">
                            <header class="panel-heading panel-heading-purple">Detalles</header>
                            <div class="panel-body">
                                <form name="formularioGuardar">

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label"><?=$frases[51][$datosUsuarioActual['uss_idioma']];?></label>
                                        <div class="col-sm-4">
                                            <input type="datetime" class="form-control" value="<?= $resultado['spt_fecha_registro']; ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label"><?=$frases[383][$datosUsuarioActual['uss_idioma']];?></label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" value="<?= UsuariosPadre::nombreCompletoDelUsuario($resultado); ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Script</label>
                                        <div class="col-sm-4">
                                            <textarea cols="80" id="editor2" rows="4" readonly><?=$resultado['spt_script'];?></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label"><?=$frases[384][$datosUsuarioActual['uss_idioma']];?></label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" value="<?= $resultado['spt_ambiente']; ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">BDs Modificadas</label>
                                        <div class="col-sm-6">
                                            <textarea cols="80" id="editor1" rows="10" readonly>
                                                <pre><?php print_r(json_decode($resultado['spt_bds'])); ?></pre>
                                            </textarea>
                                        </div>
                                    </div>

                                    <a href="javascript:void(0);" name="dev-scripts.php" class="btn btn-secondary" onClick="deseaRegresar(this)"><i class="fa fa-long-arrow-left"></i>Regresar</a>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page container -->
        <?php include("../compartido/footer.php"); ?>
    </div>
    <!-- start js include path -->
    <script src="../../config-general/assets/plugins/jquery/jquery.min.js"></script>
    <script src="../../config-general/assets/plugins/popper/popper.js"></script>
    <script src="../../config-general/assets/plugins/jquery-blockui/jquery.blockui.min.js"></script>
    <script src="../../config-general/assets/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
    <!-- bootstrap -->
    <script src="../../config-general/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="../../config-general/assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
    <script src="../../config-general/assets/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>
    <script src="../../config-general/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js" charset="UTF-8"></script>
    <script src="../../config-general/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker-init.js" charset="UTF-8"></script>
    <script src="../../config-general/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
    <script src="../../config-general/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker-init.js" charset="UTF-8"></script>
    <!-- Common js-->
    <script src="../../config-general/assets/js/app.js"></script>
    <script src="../../config-general/assets/js/layout.js"></script>
    <script src="../../config-general/assets/js/theme-color.js"></script>
    <!-- notifications -->
    <script src="../../config-general/assets/plugins/jquery-toast/dist/jquery.toast.min.js"></script>
    <script src="../../config-general/assets/plugins/jquery-toast/dist/toast.js"></script>
    <!-- Material -->
    <script src="../../config-general/assets/plugins/material/material.min.js"></script>
    <!-- dropzone -->
    <script src="../../config-general/assets/plugins/dropzone/dropzone.js"></script>
    <!--tags input-->
    <script src="../../config-general/assets/plugins/jquery-tags-input/jquery-tags-input.js"></script>
    <script src="../../config-general/assets/plugins/jquery-tags-input/jquery-tags-input-init.js"></script>
    <!--select2-->
    <script src="../../config-general/assets/plugins/select2/js/select2.js"></script>
    <script src="../../config-general/assets/js/pages/select2/select2-init.js"></script>
    <!-- end js include path -->
    <script src="../ckeditor/ckeditor.js"></script>

    <script>
        // Replace the <textarea id="editor1"> with a CKEditor 4
        // instance, using default configuration.
        CKEDITOR.replace('editor1');
    </script>
    </body>

    <!-- Mirrored from radixtouch.in/templates/admin/smart/source/light/advance_form.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 18 May 2018 17:32:54 GMT -->

    </html>