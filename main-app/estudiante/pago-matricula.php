<?php include("session.php"); ?>
<?php include("verificar-usuario.php"); ?>
<?php include("verificar-sanciones.php"); ?>
<?php $idPaginaInterna = 'ES0038'; ?>
<?php include("../compartido/historial-acciones-guardar.php"); ?>
<?php include("../compartido/head.php"); ?>

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
                            <div class="page-title"><?=$frases[332][$datosUsuarioActual[8]];?></div>
                            <?php include("../compartido/texto-manual-ayuda.php"); ?>
                        </div>
                    </div>
                </div>
                <div class="row">



                    <div class="col-sm-9">
                        <div class="card card-box">
                            <div class="card-head">
                                <header><?=$frases[332][$datosUsuarioActual[8]];?></header>
                            </div>
                            <div class="card-body " id="bar-parent6">


                                <p><?=$frases[340][$datosUsuarioActual[8]];?> <b>$<?= number_format($saldoEC, 0, ",", "."); ?></b></p>
                                <p><?=$frases[341][$datosUsuarioActual[8]];?>:</p>
                                <ol>
                                    <li><?=$frases[342][$datosUsuarioActual[8]];?>: <a href="https://www.pagosvirtualesavvillas.com.co/personal/pagos/22" style="text-decoration: underline; font-weight: bold;" target="_Blank"><?=$frases[349][$datosUsuarioActual[8]];?></a></li>
                                    <li><?=$frases[343][$datosUsuarioActual[8]];?> # 00411865393</li>
                                </ol>
                                <p>&nbsp;</p>
                                <p style="font-weight: bold;"><?=$frases[344][$datosUsuarioActual[8]];?></p>




                                <form action="../compartido/guardar-pago-matricula.php" method="post" enctype="multipart/form-data">

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label"><?=$frases[345][$datosUsuarioActual[8]];?></label>
                                        <div class="col-sm-4">
                                            <input type="file" name="archivo" class="form-control">
                                        </div>
                                        <div class="col-sm-4">
                                            <a href="../files/comprobantes/<?= $datosEstudianteActual["mat_soporte_pago"]; ?>" target="_blank"><?= $datosEstudianteActual["mat_soporte_pago"]; ?></a>
                                        </div>
                                    </div>



                                    <?php if ($datosEstudianteActual["mat_actualizar_datos"] == 1 and $datosEstudianteActual["mat_pago_matricula"] == '0') { ?>
                                        <input type="submit" class="btn btn-primary" value="<?=$frases[350][$datosUsuarioActual[8]];?>">&nbsp;
                                    <?php } ?>

                                </form>




                                <p><br /><br />&nbsp;</p>
                                <p><?=$frases[346][$datosUsuarioActual[8]];?></p>
                                <p><?=$frases[347][$datosUsuarioActual[8]];?></p>
                                <hr>

                                <h3 align="center"><?=$frases[348][$datosUsuarioActual[8]];?></h3>
                                <p align="center">
                                    <img src="../files/imagencostosicolven2022.jpeg">
                                </p>

                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3">

                        <?php include("../compartido/matricula-pasos.php"); ?>

                        <?php include("../compartido/modulo-frases-lateral.php"); ?>

                        <?php //include("../compartido/publicidad-lateral.php"); 
                        ?>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page content -->
        <?php // include("../compartido/panel-configuracion.php"); ?>
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
</body>

<!-- Mirrored from radixtouch.in/templates/admin/smart/source/light/advance_form.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 18 May 2018 17:32:54 GMT -->

</html>