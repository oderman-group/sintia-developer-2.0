<?php include("session.php");?>
<?php $idPaginaInterna = 'DT0099';?>
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
                                <div class="page-title">Informes</div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
							<h4>INFORMES ACADEMICOS</h4>
                            <div class="row">
								
								<div class="col-md-6">
									<div class="panel">
										<header class="panel-heading panel-heading-blue">MATRICULAS</header>
										<div class="panel-body">
											<p><a href="informes-boletines.php">1. Boletines</a></p>
											<p><a href="estudiantes-certificados.php">2. Certificados</a></p>
											<p><a href="consolidado-perdidos.php">3. Consolidado de asignaturas perdidas</a></p>
											<p><a href="informe-libro-cursos.php">4. Libro final por curso</a></p>
											<p><a href="informe-estudiantes.php">5. Listado de estudiantes</a></p>
											<p><a href="informe-parcial-grupo.php">6. Informe parcial por grupo</a></p>
											<p><a href="../compartido/reporte-pasos.php" target="_blank">7. Informe pasos matrícula</a></p>
											<p><a href="consolidado-final-filtro.php">8. Informe de consolidado final</a></p>
											<p><a href="estudiantes-planilla.php">9. Planilla de estudiantes</a></p>
											<p><a href="reportes-academicos-consultas.php">10. Reporte general de estudiantes</a></p>
											<p><a href="../compartido/reporte-informe-parcial.php" target="_blank">11. Reporte informe parcial</a></p>
											<p><a href="asistencia-entrega-informes-filtros.php">12. Reporte de asistencia a entrega de informes</a></p>
                                            <p><a href="../compartido/informe-matriculas-repetidas.php" target="_blank">13. Informe Matriculas repetidas</a></p>
										</div>
                                	</div>
								</div>

								<div class="col-md-6">
									<div class="panel">
										<header class="panel-heading panel-heading-blue">CARGAS ACADÉMICAS</header>
										<div class="panel-body">
											<p><a href="../compartido/informes-generales-docentes-cargas.php" target="_blank">1. Docentes y cargas académicas</a></p>
											<p><a href="informe-reporte-sabana.php">2. Informe de sábanas</a></p>
											<p><a href="../compartido/informe-cargas-duplicadas.php" target="_blank">3. Informe de cargas duplicadas</a></p>
											<p><a href="asistencia-planilla.php">4. Planilla de asistencia</a></p>
											<p><a href="planilla-docentes-filtros.php">5. Planilla docentes con notas</a></p>
											<p><a href="notas-registradas-informes-filtros.php" >4. Notas declaradas y registradas</a></p>
										</div>
                                	</div>
								</div>
							</div>

							<h4>OTROS INFORMES</h4>
							<div class="row">
                                <div class="col-md-4">
									<div class="panel">
										<header class="panel-heading panel-heading-green">INFORMES FINANCIEROS</header>
										<div class="panel-body">
											<p><a href="../compartido/reporte-movimientos.php" target="_blank">1. Informe de movimientos financieros</a></p>
										</div>
                                	</div>
								</div>

                                <div class="col-md-4">
									<div class="panel">
										<header class="panel-heading panel-heading-red">INFORMES DISCPLINARIOS</header>
										<div class="panel-body">
											<p><a href="reportes-sacar-filtro.php">1. Sacar reportes</a></p>
											<p><a href="../compartido/reporte-ver-observador.php" target="_blank">2. Reporte vista observador</a></p>
										</div>
                                	</div>
								</div>
								
								<div class="col-md-4">
									<div class="panel">
										<header class="panel-heading panel-heading-yellow">EXPORTAR A EXCEL</header>
										<div class="panel-body">
                                            <p><a href="../compartido/excel-inscripciones.php" target="_blank">1. Exportar inscripciones</a></p>
                                            <p><a href="../compartido/excel-estudiantes.php" target="_blank">2. Exportar matrículas</a></p>
										</div>
                                	</div>
								</div>
								
								<div class="col-md-4">
									<div class="panel">
										<header class="panel-heading panel-heading-green">INFORMES ADMINISTRATIVOS</header>
										<div class="panel-body">
                                            <p><a href="../compartido/informe-usuarios-repetidos.php" target="_blank">1. Informe usuarios repetidos</a></p>
                                            <p><a href="../compartido/informe-estudiantes-sin-usuarios.php" target="_blank">2. Informe estudiantes sin usuario</a></p>
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