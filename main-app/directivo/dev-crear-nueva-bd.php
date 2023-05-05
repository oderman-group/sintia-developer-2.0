<?php
include("session.php");

$idPaginaInterna = 'DV0001';

include("../compartido/historial-acciones-guardar.php");
include("../compartido/head.php");

$institucionesConsulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".instituciones WHERE ins_estado = 1");
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
    <script type="application/javascript">
        function institucion(enviada){
            var insti = enviada.value;

            if(insti==1){
                document.getElementById('nueva').style.display='block';
                document.getElementById('antigua').style.display='none';
            }
            if(insti==0){
                document.getElementById('nueva').style.display='none';
                document.getElementById('antigua').style.display='block';
            }
        }
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
                                <div class="page-title">Crear BD Nueva</div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
							<ol class="breadcrumb page-breadcrumb pull-right">
                                <li class="active">Crear BD Nueva</li>
                            </ol>
                        </div>
                    </div>
                    <div class="row">
						
                        <div class="col-sm-12">
                            <?php include("../../config-general/mensajes-informativos.php"); ?>
                            <div class="panel">
                                <header class="panel-heading panel-heading-purple">Crear BD</header>
                                <div class="panel-body">

									<form name="formularioGuardar" action="crear-bd.php" method="post">

										<div class="form-group row">
											<label class="col-sm-2 control-label">Tipo Institución</label>
											<div class="col-sm-3">
                                                <select class="form-control  select2" name="tipoInsti" onchange="institucion(this)">
                                                    <option value="">Seleccione una opción</option>
                                                    <option value="1">Nueva</option>
                                                    <option value="0">Antigua</option>
                                                </select>
											</div>
										</div>
                                        
                                        <div id="nueva" style="display: none;">

                                            <div class="form-group row">
                                                <label class="col-sm-2 control-label">Nombre de la institución</label>
                                                <div class="col-sm-3">
                                                    <input type="text" name="nombreInsti" class="form-control" autocomplete="off" value="">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-2 control-label">Siglas de la institución</label>
                                                <div class="col-sm-3">
                                                    <input type="text" name="siglasInst" class="form-control" autocomplete="off" value="">
                                                    <span style="color:#6017dc;">Nombre corto de la institución.</span>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-2 control-label">Nombre de la Base de datos (Siglas)</label>
                                                <div class="col-sm-6">
                                                    <input type="text" name="siglasBD" class="form-control col-sm-6" autocomplete="off" value="">
                                                    <span style="color:#6017dc;">Aquí colocamos las siglas que van al intermedio del nombre de la BD ejemplo: dominio_{[$siglasBD]}_{[$year]}</span>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-2 control-label">Año a crear</label>
                                                <div class="col-sm-3">
                                                    <input type="number" name="yearN" class="form-control" autocomplete="off" value="">
                                                </div>
                                            </div>

										</div>

                                        <div id="antigua" style="display: none;">

                                            <div class="form-group row">
                                                <label class="col-sm-2 control-label">Institución</label>
                                                <div class="col-sm-3">
                                                    <select class="form-control" name="idInsti">
                                                        <option value="">Seleccione una opción</option>
                                                        <?php
                                                            while($instituciones = mysqli_fetch_array($institucionesConsulta, MYSQLI_BOTH)){
                                                        ?>
                                                            <option value="<?=$instituciones['ins_id'];?>"><?=$instituciones['ins_siglas'];?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-2 control-label">Año a crear</label>
                                                <div class="col-sm-3">
                                                    <input type="number" name="yearA" class="form-control" autocomplete="off" value="">
                                                </div>
                                            </div>

                                        </div>

										<input type="submit" class="btn btn-primary" value="Guardar cambios">&nbsp;
                                    </form>
                                </div>
                            </div>
                        </div>
						
                    </div>

                </div>
        </div>
        <!-- end page container -->
        <?php include("../compartido/footer.php");?>
    </div>
    <!-- start js include path -->
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

<!-- Mirrored from radixtouch.in/templates/admin/smart/source/light/advance_form.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 18 May 2018 17:32:54 GMT -->
</html>