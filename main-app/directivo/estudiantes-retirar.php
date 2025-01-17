<?php
include("session.php");
$idPaginaInterna = 'DT0074';
include("../compartido/historial-acciones-guardar.php");
include("../compartido/head.php");

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
require_once("../class/Estudiantes.php");
require_once("../class/UsuariosPadre.php");

$id="";
if(!empty($_GET["id"])){ $id=base64_decode($_GET["id"]);}

$e = Estudiantes::traerDatosEstudiantesretirados($conexion, $config, $id);

$nombreBoton='Restaurar Matrícula';
$colorBoton='success';
$readonly="readonly";
$tituloFormulario='Restaurar Estudiante';

if ($e['mat_estado_matricula']==1){
    $nombreBoton='Retirar y cancelar matrícula';
    $colorBoton='danger';
    $readonly="";
    $tituloFormulario='Retirar Estudiante';
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
                                <div class="page-title"><?=$tituloFormulario;?></div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
							<ol class="breadcrumb page-breadcrumb pull-right">
                                <li class="active"><?=$tituloFormulario;?></li>
                            </ol>
                        </div>
                    </div>
                   
                          
                                
								<div class="panel">
									<header class="panel-heading panel-heading-purple"><?=$tituloFormulario?></header>
                                	<div class="panel-body">

                                    <form action="estudiantes-retirar-actualizar.php" method="post" class="form-horizontal" enctype="multipart/form-data">
                                        <input type="hidden" value="<?=$e['mat_id'];?>" name="estudiante">
                                        <input type="hidden" value="<?=$e['mat_estado_matricula'];?>" name="estadoMatricula">
										
											
                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label">Estudiante</label>
                                            
                                            <div class="col-sm-4">
                                                <input type="text" name="nombre" class="form-control" autocomplete="off" value="<?=$e['mat_documento']." - ".Estudiantes::NombreCompletoDelEstudiante($e);?>" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label">Estado Actual</label>
                                            
                                            <div class="col-sm-4">
                                            <input type="text" name="estadoNombre" class="form-control" autocomplete="off" value="<?=$estadosMatriculasEstudiantes[$e['mat_estado_matricula']];?>" readonly>
                                            </div>
                                        </div>

										<?php if(!empty($e['matret_fecha'])) {?>
                                            <div class="form-group row">
                                                <label class="col-sm-2 control-label">Última actualización</label>
                                                
                                                <div class="col-sm-4">
                                                    <input type="text" name="ultimaActualizacion" class="form-control" autocomplete="off" value="<?=$e['matret_fecha'];?>" readonly>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-2 control-label">Último responsable</label>
                                                
                                                <div class="col-sm-4">
                                                    <input type="text" name="responsable" class="form-control" autocomplete="off" value="<?=$e['uss_usuario']." - ".UsuariosPadre::nombreCompletoDelUsuario($e);?>" readonly>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-2 control-label">Motivo de retiro</label>
                                                <div class="col-sm-10">
                                                    <textarea cols="80" id="editor1" name="motivo" rows="10" <?php echo $readonly; ?> ><?=$e['matret_motivo'];?></textarea>
                                                </div>
                                            </div>
                                        <?php } else {?>
                                            <div class="alert alert-block alert-warning">
                                                <p>Este estudiante no tiene historial de retiros.</p>
                                            </div>
                                        <?php }?>

                                        <input type="submit" class="btn btn-<?=$colorBoton;?>" value="<?=$nombreBoton;?>" name="consultas">
                                        
                                    </form>
                                </div>
                            </div>
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

    <script>
        // Replace the <textarea id="editor1"> with a CKEditor 4
        // instance, using default configuration.
        CKEDITOR.replace( 'editor1' );
    </script>
</body>

<!-- Mirrored from radixtouch.in/templates/admin/smart/source/light/advance_form.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 18 May 2018 17:32:54 GMT -->
</html>