<?php include("session.php");?>
<?php $idPaginaInterna = 'DT0078';?>
<?php include("../compartido/historial-acciones-guardar.php");?>
<?php include("../compartido/head.php");?>
<?php
require_once("../class/Estudiantes.php");
?>
<?php
$datosEstudianteActual = Estudiantes::obtenerDatosEstudiante($_GET["id"]);
?>
    <!-- Material Design Lite CSS -->
	<link rel="stylesheet" href="../../config-general/assets/plugins/material/material.min.css">
	<link rel="stylesheet" href="../../config-general/assets/css/material_style.css">
	<!-- steps -->
	<link rel="stylesheet" href="../../config-general/assets/plugins/steps/steps.css"> 
	<!-- Theme Styles -->
    <link href="../../config-general/assets/css/theme/light/theme_style.css" rel="stylesheet" id="rt_style_components" type="text/css" />
    <link href="../../config-general/assets/css/theme/light/style.css" rel="stylesheet" type="text/css" />
    <link href="../../config-general/assets/css/plugins.min.css" rel="stylesheet" type="text/css" />
    <link href="../../config-general/assets/css/responsive.css" rel="stylesheet" type="text/css" />
	<link href="../../config-general/assets/css/theme/light/theme-color.css" rel="stylesheet" type="text/css" />
	<!-- favicon -->
    <link rel="shortcut icon" href="http://radixtouch.in/templates/admin/smart/source/assets/img/favicon.ico" />

	<!--select2-->
    <link href="../../config-general/assets/plugins/select2/css/select2.css" rel="stylesheet" type="text/css" />
    <link href="../../config-general/assets/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />

	<!--bootstrap -->
    <link href="../../config-general/assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    <link href="../../config-general/assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" media="screen">

    <script type="application/javascript">
		function validarEstudiante(enviada){
			var nDoct = enviada.value;

			if(nDoct!=""){
				$('#nDocu').empty().hide().html("Validando documento...").show(1);

				datos = "nDoct="+(nDoct);
					$.ajax({
					type: "POST",
					url: "ajax-estudiantes-editar.php",
					data: datos,
					success: function(data){
						$('#nDocu').empty().hide().html(data).show(1);
					}

				});

			}
		}
	</script>

</head>
<!-- END HEAD -->
<?php include("../compartido/body.php");?>
    <div class="page-wrapper">
        <!-- start header -->
		<?php include("../compartido/encabezado.php");?>
		
        <?php include("../compartido/panel-color.php");?>
        <!-- start page container -->
        <div class="page-container">
 			<?php include("../compartido/menu.php");?>
            <div class="page-content-wrapper">
                <div class="page-content">
                    <div class="page-bar">
                        <div class="page-title-breadcrumb">
                            <div class=" pull-left">
                                <div class="page-title">Editar matrículas</div>
                            </div>
                            <ol class="breadcrumb page-breadcrumb pull-right">
                                <li><a class="parent-item" href="#" name="estudiantes.php?cantidad=10" onClick="deseaRegresar(this)">Matrículas</a>&nbsp;<i class="fa fa-angle-right"></i></li>
                                <li class="active">Editar matrículas</li>
                            </ol>
                        </div>
                    </div>

                    <span style="color: blue; font-size: 15px;" id="nDocu"></span>
                    <!-- wizard with validation-->
                    <div class="row">
                    	<div class="col-sm-12">
							<?php include("../../config-general/mensajes-informativos.php"); ?>
							<?php
							if($config['conf_id_institucion']==1){
								if(isset($_GET['msgsion']) AND $_GET['msgsion']!=''){
									$aler='alert-success';
									$mensajeSion=$_GET['msgsion'];
									if($_GET['stadsion']!=true){
										$aler='alert-danger';
									}
								?>
									<div class="alert alert-block <?=$aler;?>">
										<button type="button" class="close" data-dismiss="alert">×</button>
										<h4 class="alert-heading">SION!</h4>
										<p><?=$mensajeSion;?></p>
									</div>
								<?php 
								}
							}
							if(isset($_GET['msgsintia'])){
								$aler='alert-success';
								if($_GET['stadsintia']!=true){
								$aler='alert-danger';
								}
							?>
							<div class="alert alert-block <?=$aler;?>">
								<button type="button" class="close" data-dismiss="alert">×</button>
								<h4 class="alert-heading">SINTIA!</h4>
								<p><?=$_GET['msgsintia'];?></p>
							</div>
							<?php }?>
                             <div class="card-box">
                                 <div class="card-head">
                                     <header>Matrículas</header>
                                 </div>
                                 <div class="card-body">
                                 	<form name="example_advanced_form" id="example-advanced-form" action="estudiantes-actualizar.php" method="post" enctype="multipart/form-data">
									<input type="hidden" name="id" value="<?=$_GET["id"];?>">
									<input type="hidden" name="idU" value="<?=$datosEstudianteActual["mat_id_usuario"];?>">
									  
										<h3>Información personal</h3>
									    <?php include("includes/info-personal.php");?>
										
										<h3>Información académica</h3>
										<?php include("includes/info-academica.php");?>
											
										<h3>Información del Acudiente</h3>
										<fieldset>
											<?php include("includes/acudiente-1.php");?>

											<?php include("includes/acudiente-2.php");?>
											
										</fieldset>
										
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
        <!-- start footer -->
        <?php include("../compartido/footer.php");?>
        <!-- end footer -->
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

</body>

</html>