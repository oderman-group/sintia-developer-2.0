<?php include("session.php");?>
<?php $idPaginaInterna = 'DT0002';?>
<?php include("verificar-permiso-pagina.php");?>
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
                                <div class="page-title"><?=$frases[247][$datosUsuarioActual['uss_idioma']];?></div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
								
								<div class="col-md-3">
									<div class="panel">
										<header class="panel-heading panel-heading-purple"><?=$frases[5][$datosUsuarioActual['uss_idioma']];?> </header>
										<div class="panel-body">
											<?php
											$cursos = mysql_query("SELECT * FROM academico_grados
											WHERE gra_estado=1
											ORDER BY gra_vocal
											",$conexion);
											while($curso = mysql_fetch_array($cursos)){
												if($curso['gra_id']==$_GET["curso"]) $estiloResaltado = 'style="color: orange;"'; else $estiloResaltado = '';
											?>
												<p><a href="<?=$_SERVER['PHP_SELF'];?>?curso=<?=$curso['gra_id'];?>&grupo=<?=$_GET["grupo"];?>&periodo=<?=$_GET["periodo"];?>&cantidad=<?=$_GET["cantidad"];?>&orden=<?=$_GET["orden"];?>" <?=$estiloResaltado;?>><?=strtoupper($curso['gra_nombre']);?></a></p>
											<?php }?>
											<p align="center"><a href="<?=$_SERVER['PHP_SELF'];?>?grupo=<?=$_GET["grupo"];?>&periodo=<?=$_GET["periodo"];?>&cantidad=<?=$_GET["cantidad"];?>&orden=<?=$_GET["orden"];?>">VER TODOS</a></p>
										</div>
                                    </div>
									
									<div class="panel">
										<header class="panel-heading panel-heading-purple">Grupos </header>
										<div class="panel-body">
											<?php
											$grupos = mysql_query("SELECT * FROM academico_grupos
											",$conexion);
											while($grupo = mysql_fetch_array($grupos)){
												if($grupo['gru_id']==$_GET["grupo"]) $estiloResaltado = 'style="color: orange;"'; else $estiloResaltado = '';
											?>
												<p><a href="<?=$_SERVER['PHP_SELF'];?>?grupo=<?=$grupo['gru_id'];?>&curso=<?=$_GET["curso"];?>&periodo=<?=$_GET["periodo"];?>&cantidad=<?=$_GET["cantidad"];?>&orden=<?=$_GET["orden"];?>" <?=$estiloResaltado;?>><?=strtoupper($grupo['gru_nombre']);?></a></p>
											<?php }?>
											<p align="center"><a href="<?=$_SERVER['PHP_SELF'];?>?curso=<?=$_GET['curso'];?>&periodo=<?=$_GET["periodo"];?>&cantidad=<?=$_GET["cantidad"];?>&orden=<?=$_GET["orden"];?>">VER TODOS</a></p>
										</div>
                                    </div>
									
									<div class="panel">
										<header class="panel-heading panel-heading-purple">Periodos </header>
										<div class="panel-body">
											<?php
											for($i=1; $i<=4; $i++){
												if($i==$_GET["periodo"]) $estiloResaltado = 'style="color: orange;"'; else $estiloResaltado = '';
											?>
												<p><a href="<?=$_SERVER['PHP_SELF'];?>?grupo=<?=$_GET['grupo'];?>&curso=<?=$_GET["curso"];?>&periodo=<?=$i;?>&cantidad=<?=$_GET["cantidad"];?>&orden=<?=$_GET["orden"];?>&carga=<?=$_GET["carga"];?>" <?=$estiloResaltado;?>><?=$i." periodo";?></a></p>
											<?php }?>
											<p align="center"><a href="<?=$_SERVER['PHP_SELF'];?>?curso=<?=$_GET['curso'];?>&grupo=<?=$_GET["grupo"];?>&cantidad=<?=$_GET["cantidad"];?>&orden=<?=$_GET["orden"];?>&carga=<?=$carga['car_id'];?>">VER TODOS</a></p>
										</div>
                                    </div>
									
									
									
									
									
									<?php include("../compartido/publicidad-lateral.php");?>
								</div>
								
								<div class="col-md-6">
                                    <div class="panel">
										<header class="panel-heading panel-heading-blue">PROMEDIOS GENERALES </header>
										<div class="panel-body">
											<?php
											$filtro = '';
											if(is_numeric($_GET["curso"])){$filtro .= " AND mat_grado='".$_GET["curso"]."'";}
											if(is_numeric($_GET["grupo"])){$filtro .= " AND mat_grupo='".$_GET["grupo"]."'";}
											
											$filtroBoletin = '';
											if(is_numeric($_GET["periodo"])){$filtroBoletin .= " AND bol_periodo='".$_GET["periodo"]."'";}
											if(is_numeric($_GET["carga"])){$filtroBoletin .= " AND bol_carga='".$_GET["carga"]."'";}
											
											$filtroLimite = '';
											if(is_numeric($_GET["cantidad"])){$filtroLimite = "LIMIT 0,".$_GET["cantidad"];}
											
											$filtroOrden ='DESC';
											if($_GET["orden"]!=""){$filtroOrden = $_GET["orden"];}
											
											$destacados = mysql_query("SELECT ROUND(AVG(bol_nota),".$config['conf_decimales_notas'].") AS promedio, bol_estudiante, mat_nombres, mat_primer_apellido, mat_segundo_apellido, mat_grado FROM academico_boletin
											INNER JOIN academico_matriculas ON mat_id=bol_estudiante $filtro AND mat_eliminado=0
											WHERE bol_id=bol_id $filtroBoletin
											GROUP BY bol_estudiante ORDER BY promedio $filtroOrden
											$filtroLimite
											",$conexion);
											$contP = 1;
											while($dest = mysql_fetch_array($destacados)){
												$porcentaje = ($dest['promedio']/$config['conf_nota_hasta'])*100;
												if($dest['promedio'] < $config['conf_nota_minima_aprobar']) $colorGrafico = 'danger'; else $colorGrafico = 'info';
											?>
														<div class="work-monitor work-progress">
															<div class="states">
																<div class="info">
																	<div class="desc pull-left"><?="<b>".$contP.".</b> ".strtoupper($dest['mat_nombres']." ".$dest['mat_primer_apellido']);?>: <b><?=$dest['promedio'];?></b></div>
																	<div class="percent pull-right"><?=$porcentaje;?>%</div>
																</div>

																<div class="progress progress-xs">
																	<div class="progress-bar progress-bar-<?=$colorGrafico;?> progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?=$porcentaje;?>%">
																		<span class="sr-only">90% </span>
																	</div>
																</div>
															</div>
														</div>	
											<?php $contP++;}?>
										</div>
                                    </div>
                                </div>
								
								<div class="col-md-3">
									
									<div class="panel">
										<header class="panel-heading panel-heading-purple">Cantidades </header>
										<div class="panel-body">
											<?php
											for($i=10; $i<=50; $i=$i+10){
												if($i==$_GET["cantidad"]) $estiloResaltado = 'style="color: orange;"'; else $estiloResaltado = '';
											?>
												<p><a href="<?=$_SERVER['PHP_SELF'];?>?grupo=<?=$_GET['grupo'];?>&curso=<?=$_GET["curso"];?>&periodo=<?=$_GET["periodo"];?>&cantidad=<?=$i;?>&orden=<?=$_GET["orden"];?>&carga=<?=$_GET["carga"];?>" <?=$estiloResaltado;?>><?=$i." estudiantes";?></a></p>
											<?php }?>
											<p align="center"><a href="<?=$_SERVER['PHP_SELF'];?>?curso=<?=$_GET['curso'];?>&grupo=<?=$_GET["grupo"];?>&periodo=<?=$_GET["periodo"];?>&orden=<?=$_GET["orden"];?>&carga=<?=$carga['car_id'];?>">VER TODOS</a></p>
										</div>
                                    </div>
									
									<div class="panel">
										<header class="panel-heading panel-heading-purple">Orden </header>
										<div class="panel-body">
											<p><a href="<?=$_SERVER['PHP_SELF'];?>?grupo=<?=$_GET['grupo'];?>&curso=<?=$_GET["curso"];?>&periodo=<?=$_GET["periodo"];?>&cantidad=<?=$_GET["cantidad"];?>&orden=DESC&carga=<?=$_GET["carga"];?>" <?php if($filtroOrden==='DESC') echo 'style="color: orange;"';?> >De mayor a menor</a></p>
											<p><a href="<?=$_SERVER['PHP_SELF'];?>?grupo=<?=$_GET['grupo'];?>&curso=<?=$_GET["curso"];?>&periodo=<?=$_GET["periodo"];?>&cantidad=<?=$_GET["cantidad"];?>&orden=ASC&carga=<?=$_GET["carga"];?>" <?php if($filtroOrden==='ASC') echo 'style="color: orange;"';?>>De menor a mayor</a></p>
											<p align="center"><a href="<?=$_SERVER['PHP_SELF'];?>?curso=<?=$_GET['curso'];?>&grupo=<?=$_GET["grupo"];?>&periodo=<?=$_GET["periodo"];?>&cantidad=<?=$_GET["cantidad"];?>&carga=<?=$carga['car_id'];?>">VER TODOS</a></p>
										</div>
                                    </div>
									
									<?php if(is_numeric($_GET["curso"]) and is_numeric($_GET["grupo"])){?>
										<div class="panel">
											<header class="panel-heading panel-heading-purple"><?=$frases[73][$datosUsuarioActual['uss_idioma']];?> </header>
											<div class="panel-body">
												<?php
												$cargas = mysql_query("SELECT * FROM academico_cargas 
												INNER JOIN academico_materias ON mat_id=car_materia
												WHERE car_curso='".$_GET["curso"]."' AND car_grupo='".$_GET["grupo"]."'
												ORDER BY mat_nombre
												",$conexion);
												while($carga = mysql_fetch_array($cargas)){
													if($carga['car_id']==$_GET["carga"]) $estiloResaltado = 'style="color: orange;"'; else $estiloResaltado = '';
												?>
													<p><a href="<?=$_SERVER['PHP_SELF'];?>?curso=<?=$_GET['curso'];?>&grupo=<?=$_GET["grupo"];?>&periodo=<?=$_GET["periodo"];?>&cantidad=<?=$_GET["cantidad"];?>&orden=<?=$_GET["orden"];?>&carga=<?=$carga['car_id'];?>" <?=$estiloResaltado;?>><?=strtoupper($carga['mat_nombre']);?></a></p>
												<?php }?>
												<p align="center"><a href="<?=$_SERVER['PHP_SELF'];?>?curso=<?=$_GET['curso'];?>&grupo=<?=$_GET["grupo"];?>&periodo=<?=$_GET["periodo"];?>&cantidad=<?=$_GET["cantidad"];?>&orden=<?=$_GET["orden"];?>">VER TODOS</a></p>
											</div>
										</div>
									<?php }?>
									
									
									<?php include("../compartido/publicidad-lateral.php");?>
								</div>
								
								
								
							
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