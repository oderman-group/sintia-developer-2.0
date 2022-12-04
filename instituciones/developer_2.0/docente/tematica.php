<?php include("session.php");?>
<?php $idPaginaInterna = 15;?>
<?php include("../compartido/historial-acciones-guardar.php");?>
<?php include("verificar-carga.php");?>
<?php //include("verificar-periodos-diferentes.php");?>
<?php include("../compartido/head.php");?>
<?php
$tematica = mysql_fetch_array(mysql_query("SELECT * FROM academico_indicadores 
WHERE ind_carga='".$cargaConsultaActual."' AND ind_periodo='".$periodoConsultaActual."' AND ind_tematica=1",$conexion));
?>

	<!--bootstrap -->
    <link href="../../../config-general/assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    <link href="../../../config-general/assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" media="screen">
	<!-- Theme Styles -->
    <link href="../../../config-general/assets/css/pages/formlayout.css" rel="stylesheet" type="text/css" />
	<!-- dropzone -->
    <link href="../../../config-general/assets/plugins/dropzone/dropzone.css" rel="stylesheet" media="screen">
    <!--tagsinput-->
    <link href="../../../config-general/assets/plugins/jquery-tags-input/jquery-tags-input.css" rel="stylesheet">
    <!--select2-->
    <link href="../../../config-general/assets/plugins/select2/css/select2.css" rel="stylesheet" type="text/css" />
    <link href="../../../config-general/assets/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
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
                                <div class="page-title"><?=$frases[251][$datosUsuarioActual[8]];?></div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
							<ol class="breadcrumb page-breadcrumb pull-right">
                                <li><a class="parent-item" href="indicadores.php"><?=$frases[63][$datosUsuarioActual[8]];?></a>&nbsp;<i class="fa fa-angle-right"></i></li>
                                <li class="active"><?=$frases[56][$datosUsuarioActual[8]];?> <?=$frases[251][$datosUsuarioActual[8]];?></li>
                            </ol>
                        </div>
                    </div>
                    <div class="row">
						
						<div class="col-sm-3">

						<?php include("info-carga-actual.php");?>
							
						<?php include("filtros-cargas.php");?>	

                        </div>
						
                        <div class="col-sm-9">


								<div class="panel">
									<header class="panel-heading panel-heading-purple"><?=$frases[119][$datosUsuarioActual[8]];?> </header>
                                	<div class="panel-body">

                                   
									<form name="formularioGuardar" action="guardar.php?carga=<?=$cargaConsultaActual;?>&periodo=<?=$periodoConsultaActual;?>" method="post">
										<input type="hidden" value="39" name="id">

										<div class="form-group row">
											<label class="col-sm-2 control-label">Descripción</label>
											<div class="col-sm-10">
												<textarea name="contenido" class="form-control" rows="5" style="margin-top: 0px; margin-bottom: 0px; height: 100px; resize: none;" required><?=$tematica['ind_nombre'];?></textarea>
											</div>
										</div>
										
										<div class="form-group row">
											<label class="col-sm-2 control-label">Fecha creación</label>
											<div class="col-sm-4">
												<input type="text" name="fecha" class="form-control" value="<?=$tematica['ind_fecha_creacion'];?>" readonly>
											</div>
										</div>
										
										<div class="form-group row">
											<label class="col-sm-2 control-label">Última edición</label>
											<div class="col-sm-4">
												<input type="text" name="fecha" class="form-control" value="<?=$tematica['ind_fecha_modificacion'];?>" readonly>
											</div>
										</div>

										<input type="submit" class="btn btn-primary" value="Guardar cambios">&nbsp;
                                    </form>
                                </div>
                            </div>
                        </div>
						
                    </div>

                </div>
                <!-- end page content -->
             <?php include("../compartido/panel-configuracion.php");?>
        </div>
        <!-- end page container -->
        <?php include("../compartido/footer.php");?>
    </div>
    <!-- start js include path -->
    <script src="../../../config-general/assets/plugins/jquery/jquery.min.js" ></script>
    <script src="../../../config-general/assets/plugins/popper/popper.js" ></script>
    <script src="../../../config-general/assets/plugins/jquery-blockui/jquery.blockui.min.js" ></script>
	<script src="../../../config-general/assets/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
    <!-- bootstrap -->
    <script src="../../../config-general/assets/plugins/bootstrap/js/bootstrap.min.js" ></script>
    <script src="../../../config-general/assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js" ></script>
    <script src="../../../config-general/assets/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js" ></script>
    <script src="../../../config-general/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"  charset="UTF-8"></script>
    <script src="../../../config-general/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker-init.js"  charset="UTF-8"></script>
    <script src="../../../config-general/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"  charset="UTF-8"></script>
    <script src="../../../config-general/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker-init.js"  charset="UTF-8"></script>
    <!-- Common js-->
	<script src="../../../config-general/assets/js/app.js" ></script>
    <script src="../../../config-general/assets/js/layout.js" ></script>
	<script src="../../../config-general/assets/js/theme-color.js" ></script>
	<!-- notifications -->
	<script src="../../../config-general/assets/plugins/jquery-toast/dist/jquery.toast.min.js" ></script>
	<script src="../../../config-general/assets/plugins/jquery-toast/dist/toast.js" ></script>	
	<!-- Material -->
	<script src="../../../config-general/assets/plugins/material/material.min.js"></script>
	<!-- dropzone -->
    <script src="../../../config-general/assets/plugins/dropzone/dropzone.js" ></script>
    <!--tags input-->
    <script src="../../../config-general/assets/plugins/jquery-tags-input/jquery-tags-input.js" ></script>
    <script src="../../../config-general/assets/plugins/jquery-tags-input/jquery-tags-input-init.js" ></script>
    <!--select2-->
    <script src="../../../config-general/assets/plugins/select2/js/select2.js" ></script>
    <script src="../../../config-general/assets/js/pages/select2/select2-init.js" ></script>
    <!-- end js include path -->
</body>

<!-- Mirrored from radixtouch.in/templates/admin/smart/source/light/advance_form.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 18 May 2018 17:32:54 GMT -->
</html>