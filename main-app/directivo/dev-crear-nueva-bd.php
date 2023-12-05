<?php
include("session.php");

$idPaginaInterna = 'DV0001';

include("../compartido/historial-acciones-guardar.php");

Modulos::verificarPermisoDev();

include("../compartido/head.php");
$datosNuevaBD = [
	'tipoInsti'     => '',
	'idInsti'      => '',
	'ins_bd'       => '',
	'yearA'    => '',
	'siglasBD'      => '',
	'nombreInsti' => '',
	'siglasInst'      => '',
	'yearN'       => ''
];
$displayNueva= 'none';
$displayAntigua= 'none';
if(isset($_GET['tipoInsti'])){

    $displayNueva= 'block';
    $displayAntigua= 'none';
    if(base64_decode($_GET['tipoInsti'])==0){
        $displayNueva= 'none';
        $displayAntigua= 'block';
    }

	$datosNuevaBD['tipoInsti'] = base64_decode($_GET['tipoInsti']);
}
if(isset($_GET['idInsti'])){
	$datosNuevaBD['idInsti'] = base64_decode($_GET['idInsti']);
}
if(isset($_GET['ins_bd'])){
	$datosNuevaBD['ins_bd'] = base64_decode($_GET['ins_bd']);
}
if(isset($_GET['yearA'])){
	$datosNuevaBD['yearA'] = base64_decode($_GET['yearA']);
}
if(isset($_GET['siglasBD'])){
	$datosNuevaBD['siglasBD'] = base64_decode($_GET['siglasBD']);
}
if(isset($_GET['nombreInsti'])){
	$datosNuevaBD['nombreInsti'] = base64_decode($_GET['nombreInsti']);
}
if(isset($_GET['siglasInst'])){
	$datosNuevaBD['siglasInst'] = base64_decode($_GET['siglasInst']);
}
if(isset($_GET['yearN'])){
	$datosNuevaBD['yearN'] = base64_decode($_GET['yearN']);
}

try{
    $institucionesConsulta = mysqli_query($conexion, "SELECT * FROM ".BD_ADMIN.".instituciones 
    WHERE ins_estado = 1 AND ins_enviroment='".ENVIROMENT."'");
} catch (Exception $e) {
    include("../compartido/error-catch-to-report.php");
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
    <script type="application/javascript">
        function institucion(enviada){
            var insti = enviada.value;

            var divNueva = document.getElementById('nueva');
            var inputElementsNueva = divNueva.querySelectorAll('input');

            var divAntigua = document.getElementById('antigua');
            var inputElementsAntigua = divAntigua.querySelectorAll('input, select');

            if(insti==1){
                document.getElementById('nueva').style.display='block';
                document.getElementById('antigua').style.display='none';
                
                inputElementsNueva.forEach((input) => {
                    input.required = true;
                });

                inputElementsAntigua.forEach((input) => {
                    input.required = false;
                });
            }
            if(insti==0){
                document.getElementById('nueva').style.display='none';
                document.getElementById('antigua').style.display='block';
                
                inputElementsNueva.forEach((input) => {
                    input.required = false;
                });

                inputElementsAntigua.forEach((input) => {
                    input.required = true;
                });
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

                        <div class="col-sm-3">
                            <div class="panel animate__animated animate__pulse animate__delay-1s animate__repeat-2">
                                <header class="panel-heading panel-heading-purple">Información importante</header>
                                <div class="panel-body">
                                    <b>Paso 1:</b> Ir al <a href="https://sintia.co:2083/" target="_blank">cPanel</a> y crear la BD nueva que usará en esta implementación.<br>
                                    <b>Paso 2:</b> Asignar la BD al usuario para este ambiente. (Recuerda asignar todos los privilegios).<br>
                                </div>
                            </div>
                        </div>
						
                        <div class="col-sm-9">
                            <?php include("../../config-general/mensajes-informativos.php"); ?>
                            <div class="panel">
                                <header class="panel-heading panel-heading-purple">Crear BD</header>
                                <div class="panel-body">

									<form name="formularioGuardar" action="crear-bd.php" method="post">

										<div class="form-group row">
											<label class="col-sm-2 control-label">Tipo Institución</label>
											<div class="col-sm-3">
                                                <select class="form-control  select2" name="tipoInsti" required onchange="institucion(this)">
                                                    <option value="">Seleccione una opción</option>
                                                    <option value="1"<?php if($datosNuevaBD['tipoInsti']==1){echo "selected";}?>>Nueva</option>
                                                    <option value="0"<?php if($datosNuevaBD['tipoInsti']==0){echo "selected";}?>>Antigua</option>
                                                </select>
											</div>
										</div>
                                        
                                        <div id="nueva" style="display: <?=$displayNueva?>;">

                                            <div class="form-group row">
                                                <label class="col-sm-2 control-label">Nombre de la institución</label>
                                                <div class="col-sm-3">
                                                    <input type="text" name="nombreInsti" class="form-control" autocomplete="off" value="<?=$datosNuevaBD['nombreInsti'];?>">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-2 control-label">Siglas de la institución</label>
                                                <div class="col-sm-3">
                                                    <input type="text" name="siglasInst" class="form-control" autocomplete="off" value="<?=$datosNuevaBD['siglasInst'];?>">
                                                    <span style="color:#6017dc;">Nombre corto de la institución.</span>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-2 control-label">Nombre de la Base de datos <b>(SiglasBD)</b></label>
                                                <div class="col-sm-6">
                                                    <input type="text" name="siglasBD" class="form-control col-sm-6" autocomplete="off" value="<?=$datosNuevaBD['siglasBD'];?>">
                                                    <span style="color:#6017dc;">Aquí colocamos las siglas que van al intermedio del nombre de la BD ejemplo: dominio_<b>SiglasBD</b>_year</span>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-2 control-label">Año a crear</label>
                                                <div class="col-sm-3">
                                                    <input type="number" name="yearN" class="form-control" autocomplete="off" value="<?=$datosNuevaBD['yearN'];?>">
                                                </div>
                                            </div>

										</div>

                                        <div id="antigua" style="display: <?=$displayAntigua?>;">

                                            <div class="form-group row">
                                                <label class="col-sm-2 control-label">Institución</label>
                                                <div class="col-sm-3">
                                                    <select class="form-control" name="idInsti">
                                                        <option value="">Seleccione una opción</option>
                                                        <?php
                                                            while($instituciones = mysqli_fetch_array($institucionesConsulta, MYSQLI_BOTH)){
                                                        ?>
                                                            <option value="<?=$instituciones['ins_id'];?>" <?php if($datosNuevaBD['idInsti']==$instituciones['ins_id']){echo "selected";}?>><?=$instituciones['ins_siglas'];?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-2 control-label">Año a crear</label>
                                                <div class="col-sm-3">
                                                    <input type="number" name="yearA" class="form-control" autocomplete="off" value="<?=$datosNuevaBD['yearA'];?>">
                                                </div>
                                            </div>

                                        </div>

                                        <button type="submit" class="btn  deepPink-bgcolor">Continuar 
                                            <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                                        </button>
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