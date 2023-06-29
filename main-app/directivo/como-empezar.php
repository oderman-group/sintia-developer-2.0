<?php include("session.php");?>
<?php $idPaginaInterna = 'DT0139';?>
<?php include("../compartido/historial-acciones-guardar.php");?>
<?php include("../compartido/head.php");?>
	<!-- data tables -->
    <link href="../../config-general/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
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
                                <div class="page-title">Como Empezar</div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">

                        <div class="col-md-12">

                            <div class="row">
								
								<div class="col-md-8">
									<div class="panel">
										<header class="panel-heading panel-heading-blue">PRIMEROS PASOS</header>
										<div class="panel-body">
                                            <p>
                                                <b>1.</b> Primeramente organiceremos la configuración del sistema para tu institución.<br>
                                                <mark>Menú principal -> Configuración -> del sistema</mark>
                                            </p>

											<p>
                                                <b>2.</b> Ahora te sugerimos que crees los <b>usuarios</b> de tipo <b>Directivo</b> y los usuarios de tipo <b>Docente</b>.<br>
                                                <mark>Menú principal -> G. Administrativa -> Usuarios -> Agregar nuevo</mark>
                                            </p>

                                            <p>
                                                <b>3.</b> Buen trabajo, en este punto vamos a colocar la <b>Información de la Institución</b>. Esta información es importante porque es la que aparecerá en varios informes que la plataforma te permite generar y aquí podrás definir los cargos o roles de algunos usuarios directivos dentro de la institución.<br>
                                                <mark>Menú principal -> Configuración -> de la Institución</mark>
                                            </p>

                                            <p>
                                                <b>4.</b> Lo siguiente que te recomendamos es ir a la opción de <b>Cursos</b>, revisar los que la plataforma generó automáticamente y comprobar si esos son todos los que tu institución necesita. Si hace falta alguno lo puedes crear allí mismo.<br>
                                                <mark>Menú principal -> G. Académica -> Cursos -> Agregar nuevo</mark>
                                            </p>

                                            <p>
                                                <b>5.</b> Este paso es parecido al anterior. Te recomendamos ir a la opción de <b>Áreas</b>, revisar las que la plataforma generó automáticamente y comprobar si esas son todas las que tu institución necesita. Si hace falta alguna la puedes crear allí mismo.<br>
                                                <mark>Menú principal -> G. Académica -> Áreas -> Agregar nuevo</mark>
                                            </p>

                                            <p>
                                                <b>6.</b> Este paso es casi igual al anterior. Te recomendamos ir a la opción de <b>Asignaturas</b>, revisar las que la plataforma generó automáticamente y comprobar si esas son todas las que tu institución necesita. Si hace falta alguna la puedes crear allí mismo.<br>
                                                <mark>Menú principal -> G. Académica -> Asignaturas -> Agregar nuevo</mark>
                                            </p>

                                            <p>
                                                <b>7.</b> Felicidades por haber llegado a este punto. Ya casi hemos terminado. En este punto ya deberías tener la información básica para usar la plataforma.<br>
                                            Lo siguiente es crear las <b>Cargas Académicas</b> que los docentes trabajarán para este año escolar. Allí debes relacionar al docente con un curso, grupo y asignatura.<br>
                                            <mark>Menú principal -> G. Académica -> Cargas académicas -> Agregar nuevo</mark>
                                            </p>
                                            <p>
                                                <b>8.</b> Excelente trabajo hasta ahora. Podemos pasar a las <b>Matrículas</b>. Es momento de crear a los estudiantes uno por uno o también puedes importarlos desde un archivo de excel para que te sea más fácil y rápido (La plataforma te da la plantilla adecuada para hacer este llenado de datos y la posterior carga del archivo).<br>
                                            Recuerda que si has creado cursos nuevos diferentes a los que la plataforma había generado automáticamente entonces después de importar el listado de estudiantes debes relacionarlos con su curso (Esto solo lo debes hacer para los cursos nuevos que hayas creado).<br>
                                            <mark>Menú principal -> G. Académica -> Matrículas -> Agregar nuevo | Importar matrículas excel</mark>
                                            </p>
                                            
										</div>
                                	</div>
								</div>

                                <div class="col-md-4">
									<div class="panel">
										<header class="panel-heading panel-heading-purple">Video de Guia</header>
										<div class="panel-body">
                                            <div style="position: relative; padding-bottom: 56.25%; height: 0;"><iframe src="https://www.loom.com/embed/8eac333b167c48d98ca3b459e78faeac" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></iframe></div>
										</div>
                                	</div>
								</div>

									
								</div>
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
	<!-- data tables -->
    <script src="../../config-general/assets/plugins/datatables/jquery.dataTables.min.js" ></script>
 	<script src="../../config-general/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap4.min.js" ></script>
    <script src="../../config-general/assets/js/pages/table/table_data.js" ></script>
    <!-- Common js-->
	<script src="../../config-general/assets/js/app.js" ></script>
    <script src="../../config-general/assets/js/layout.js" ></script>
	<script src="../../config-general/assets/js/theme-color.js" ></script>
	<!-- notifications -->
	<script src="../../config-general/assets/plugins/jquery-toast/dist/jquery.toast.min.js" ></script>
	<script src="../../config-general/assets/plugins/jquery-toast/dist/toast.js" ></script>
	<!-- Material -->
	<script src="../../config-general/assets/plugins/material/material.min.js"></script>
    <!-- end js include path -->
</body>

</html>