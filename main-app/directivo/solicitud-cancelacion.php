<?php include("session.php"); ?>
<?php $idPaginaInterna = 'DT0202'; ?>
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
                            <div class="page-title"><?= $frases[367][$datosUsuarioActual[8]]; ?></div>
                        </div>
                    </div>
                </div>


                <form action="solicitud-cancelacion-guardar.php" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-6">
                            <?php include("../../config-general/mensajes-informativos.php"); ?>
                            
                            <div class="card card-box">
                                <div class="card-head">
                                    <header><?= $frases[368][$datosUsuarioActual[8]]?></header>
                                </div>
                                <div class="card-body " id="bar-parent6">


                                    <div class="form-group row">
                                        <textarea class="form-control" name="motivoCancelacion" rows="3" required></textarea>
                                    </div>

                                    <input  onclick="enviarSolicitud()" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal" value="Enviar solicitud">&nbsp;


                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3"></div>

                        <!-- Modal -->
                        <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content shadow">
                                    <div class="modal-body p-4 text-center">
                                        <h5 class="mb-0">Esta seguro de cancelar este servicio?</h5>
                                        <h5 >Receurde que una vez enviada la solicitud sera evalauda y se le dará respuesta prontamente</h5>
                                    </div>
                                    <div class="modal-footer flex-nowrap p-0">
                                        <button type="submit" type="button" style="color: #dc3545;" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 py-3 m-0 rounded-0 border-end"><strong>SI</strong></button>
                                        <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 py-3 m-0 rounded-0" data-bs-dismiss="modal">No</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Fin del Modal -->

                    </div>
                </form>

            </div>
        </div>

        <!-- Confirmacion de cancelacion modal -->

    </div>

    <script>
        function enviarSolicitud() {
          
            $('#myModal').on('shown.bs.modal', function() {
                $('#myInput').trigger('focus')
            })
        }
    </script>



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
<script src="../../config-general/assets/plugins/bootstrap-bundle/js/bootstrap.bundle.min.js"></script>
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