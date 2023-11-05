<?php include("session.php");?>
<?php $idPaginaInterna = 25;?>
<?php include("../compartido/historial-acciones-guardar.php");?>
<?php include("verificar-carga.php");?>
<?php include("verificar-periodos-diferentes.php");?>
<?php include("../compartido/head.php");?>
<?php
$consultaDato=mysqli_query($conexion, "SELECT * FROM academico_clases WHERE cls_id='".$_GET["idR"]."' AND cls_estado=1");
$datosConsulta = mysqli_fetch_array($consultaDato, MYSQLI_BOTH);
?>

	<!--bootstrap -->
    <link href="../assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    <link href="../assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" media="screen">
	<!-- Theme Styles -->
    <link href="../assets/css/pages/formlayout.css" rel="stylesheet" type="text/css" />
	<!-- dropzone -->
    <link href="../assets/plugins/dropzone/dropzone.css" rel="stylesheet" media="screen">
    <!--tagsinput-->
    <link href="../assets/plugins/jquery-tags-input/jquery-tags-input.css" rel="stylesheet">
    <!--select2-->
    <link href="../assets/plugins/select2/css/select2.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
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
                                <div class="page-title"><?=$frases[56][$datosUsuarioActual[8]];?> <?=$frases[7][$datosUsuarioActual[8]];?></div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
							<ol class="breadcrumb page-breadcrumb pull-right">
                                <li><a class="parent-item" href="#" name="clases.php" onClick="deseaRegresar(this)"><?=$frases[7][$datosUsuarioActual[8]];?></a>&nbsp;<i class="fa fa-angle-right"></i></li>
                                <li class="active"><?=$frases[56][$datosUsuarioActual[8]];?> <?=$frases[7][$datosUsuarioActual[8]];?></li>
                            </ol>
                        </div>
                    </div>
                    <div class="row">
						
						<div class="col-sm-3">


						<?php include("info-carga-actual.php");?>

							
                            <div class="panel">
								<header class="panel-heading panel-heading-purple"><?=$frases[119][$datosUsuarioActual[8]];?> </header>
                                <div class="panel-body">
									<p><b>Banco de datos:</b> Tienes la opción de usar información que ya existe y así no tengas que escribir todo de nuevo. <mark>Sólo debes usar una de las 2 alternativas:</mark> o llenas la información desde cero o escoges la existente. Si usas las 2, <mark>el banco de datos tendrá prioridad</mark> y esta será lo que el sistema use.<br>
									<mark> - MIO :</mark> Significa que la información fue creada por ti.
									</p>
									<p><b>Compartir:</b> Compartir la información <mark>es una manera de colaborar con tus colegas.</mark> La información irá al banco de datos y podrá ser usada por ti o por otros colegas tuyos más adelante. En caso de que no desees compartirla puedes dar click sobre el botón para que se desactive y la información sólo puedas verla tú.</p>
								</div>
							</div>
                        </div>
						
                        <div class="col-sm-9">


								<div class="panel">
									<header class="panel-heading panel-heading-purple"><?=$frases[119][$datosUsuarioActual[8]];?> </header>
                                	<div class="panel-body">

                                   
									<form name="formularioGuardar" action="guardar.php?carga=<?=$cargaConsultaActual;?>&periodo=<?=$periodoConsultaActual;?>" method="post">
										<input type="hidden" value="13" name="id">
										<input type="hidden" value="<?=$_GET["idR"];?>" name="idR">


											<div class="form-group row">
												<label class="col-sm-2 control-label">Tema</label>
												<div class="col-sm-10">
													<input type="text" name="contenido" class="form-control" autocomplete="off" value="<?=$datosConsulta['cls_tema'];?>" required>
												</div>
											</div>
											
											<div class="form-group row">
													<label class="col-sm-2 control-label">Fecha</label>
													<div class="col-sm-4">
														<input type="date" name="fecha" class="form-control" autocomplete="off" value="<?=$datosConsulta['cls_fecha'];?>"  required>
													</div>
											</div>
										
										<p class="text-warning">Opcional.</p>
											<div class="form-group row">
												<label class="col-sm-2 control-label">Video de youtube</label>
												<div class="col-sm-10">
													<input type="text" name="video" class="form-control" autocomplete="off" value="<?=$datosConsulta['cls_video_url'];?>">
												</div>
											</div>



                                            <a href="#" name="clases.php" class="btn btn-secondary" onClick="deseaRegresar(this)"><i class="fa fa-long-arrow-left"></i>Regresar</a>

                                            <button type="submit" class="btn  btn-info">
                                                <i class="fa fa-save" aria-hidden="true"></i> Guardar cambios 
                                            </button>
                                    </form>
                                </div>
                            </div>
							
							<?php if($datosConsulta['cls_video']!=""){?>
							<div class="panel">
								<header class="panel-heading panel-heading-red">VIDEO YOUTUBE</header>
                                <div class="panel-body">
									<iframe width="560" height="415" src="https://www.youtube.com/embed/<?=$datosConsulta['cls_video'];?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
								</div>
							</div>
							<?php }?>
							
                        </div>
						
						
                    </div>

                </div>
                <!-- end page content -->
             <?php // include("../compartido/panel-configuracion.php");?>
        </div>
        <!-- end page container -->
        <?php include("../compartido/footer.php");?>
    </div>
    <!-- start js include path -->
    <script src="../assets/plugins/jquery/jquery.min.js" ></script>
    <script src="../assets/plugins/popper/popper.js" ></script>
    <script src="../assets/plugins/jquery-blockui/jquery.blockui.min.js" ></script>
	<script src="../assets/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
    <!-- bootstrap -->
    <script src="../assets/plugins/bootstrap/js/bootstrap.min.js" ></script>
    <script src="../assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js" ></script>
    <script src="../assets/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js" ></script>
    <script src="../assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"  charset="UTF-8"></script>
    <script src="../assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker-init.js"  charset="UTF-8"></script>
    <script src="../assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"  charset="UTF-8"></script>
    <script src="../assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker-init.js"  charset="UTF-8"></script>
    <!-- Common js-->
	<script src="../assets/js/app.js" ></script>
    <script src="../assets/js/layout.js" ></script>
	<script src="../assets/js/theme-color.js" ></script>
	<!-- notifications -->
	<script src="../assets/plugins/jquery-toast/dist/jquery.toast.min.js" ></script>
	<script src="../assets/plugins/jquery-toast/dist/toast.js" ></script>	
	<!-- Material -->
	<script src="../assets/plugins/material/material.min.js"></script>
	<!-- dropzone -->
    <script src="../assets/plugins/dropzone/dropzone.js" ></script>
    <!--tags input-->
    <script src="../assets/plugins/jquery-tags-input/jquery-tags-input.js" ></script>
    <script src="../assets/plugins/jquery-tags-input/jquery-tags-input-init.js" ></script>
    <!--select2-->
    <script src="../assets/plugins/select2/js/select2.js" ></script>
    <script src="../assets/js/pages/select2/select2-init.js" ></script>
    <!-- end js include path -->
</body>

<!-- Mirrored from radixtouch.in/templates/admin/smart/source/light/advance_form.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 18 May 2018 17:32:54 GMT -->
</html>