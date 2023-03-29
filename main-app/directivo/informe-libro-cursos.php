<?php include("session.php");?>
<?php $idPaginaInterna = 'DT0135';?>
<?php include("../compartido/historial-acciones-guardar.php");?>
<?php include("../compartido/head.php");?>

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
                                <div class="page-title">Libro final</div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
							<ol class="breadcrumb page-breadcrumb pull-right">
                                <li><a class="parent-item" href="#" name="informes-todos.php" onClick="deseaRegresar(this)">Informes Todos</a>&nbsp;<i class="fa fa-angle-right"></i></li>
                                <li class="active">Libro final</li>
                            </ol>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel">
                                <header class="panel-heading panel-heading-purple">POR CURSO </header>
                                <div class="panel-body">
                                <form name="formularioGuardar" action="../compartido/matricula-libro-curso.php" method="post" target="_blank">
                                    
                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Curso</label>
                                        <div class="col-sm-8">
                                            <?php
                                            $opcionesConsulta = mysqli_query($conexion, "SELECT * FROM academico_grados
                                            ORDER BY gra_vocal");
                                            ?>
                                            <select class="form-control  select2" name="curso" required>
                                                <option value="">Seleccione una opción</option>
                                                <?php
                                                while($opcionesDatos = mysqli_fetch_array($opcionesConsulta, MYSQLI_BOTH)){
                                                    $disabled = '';
                                                    if($opcionesDatos['gra_estado']=='0') $disabled = 'disabled';
                                                ?>
                                                    <option value="<?=$opcionesDatos[0];?>" <?=$disabled;?>><?=$opcionesDatos['gra_id'].". ".strtoupper($opcionesDatos['gra_nombre']);?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Grupo</label>
                                        <div class="col-sm-4">
                                            <?php
                                            $opcionesConsulta = mysqli_query($conexion, "SELECT * FROM academico_grupos");
                                            ?>
                                            <select class="form-control  select2" name="grupo">
                                                <option value="">Seleccione una opción</option>
                                                <?php
                                                while($opcionesDatos = mysqli_fetch_array($opcionesConsulta, MYSQLI_BOTH)){
                                                ?>
                                                    <option value="<?=$opcionesDatos[0];?>"><?=$opcionesDatos['gru_id'].". ".strtoupper($opcionesDatos['gru_nombre']);?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                    </div>
                                        
                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Año</label>
                                        <div class="col-sm-4">
                                            <select class="form-control  select2" name="year" required>
                                                <option value="">Seleccione una opción</option>
                                                <?php
                                                while($yearStart <= $yearEnd){	
                                                if($_SESSION["bd"]==$yearStart)
                                                    echo "<option value='".$yearStart."' selected style='color:blue;'>".$yearStart."</option>";
                                                else
                                                    echo "<option value='".$yearStart."'>".$yearStart."</option>";
                                                    $yearStart++;
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <input type="submit" class="btn btn-primary" value="Generar informe">&nbsp;
                                    
                                    <a href="#" name="informes-todos.php" class="btn btn-secondary" onClick="deseaRegresar(this)"><i class="fa fa-long-arrow-left"></i>Regresar</a>
                                </form>
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
</body>

<!-- Mirrored from radixtouch.in/templates/admin/smart/source/light/advance_form.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 18 May 2018 17:32:54 GMT -->
</html>