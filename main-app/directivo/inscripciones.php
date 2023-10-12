<?php
include("session.php");
$idPaginaInterna = 'DT0102';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");
include("../compartido/head.php");

$db = $_SESSION["inst"]."_".$_SESSION["bd"];
$urlInscripcion=REDIRECT_ROUTE.'/admisiones/';
?>
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
                                <div class="page-title">Inscripciones</div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
								
								<div class="col-md-12">

                                <?php
                                    $filtro="";
                                    include("../../config-general/config-admisiones.php");
                                    include("includes/barra-superior-inscripciones.php");
                                ?>

                                    <?php if (isset($_GET["msg"]) and base64_decode($_GET["msg"]) == 1) { ?>
                                    <div class="alert alert-block alert-success">
                                        <h4 class="alert-heading">Documentación eliminada!</h4>
                                        <p>La documentación del aspirante se ha borrado correctamente.</p>
                                    </div>
                                    <?php } ?>

                                    <?php if (isset($_GET["msg"]) and base64_decode($_GET["msg"]) == 2) { ?>
                                    <div class="alert alert-block alert-success">
                                        <h4 class="alert-heading">Apisrante eliminado!</h4>
                                        <p>El aspirante se ha borrado correctamente.</p>
                                    </div>
                                    <?php } ?>
                                    <div class="card card-topline-purple">
                                        <div class="card-head">
                                            <header>Inscripciones</header>
                                            <div class="tools">
                                                <a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>
			                                    <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
			                                    <a class="t-close btn-color fa fa-times" href="javascript:;"></a>
                                            </div>
                                        </div>
                                        <div class="card-body">
											
                                        <div class="alert alert-block alert-warning">
                                            <h4 class="alert-heading">Libera espacio para no llenar el disco!</h4>
                                            <p>Recomendamos descargar la documentación y comprobante de pago de cada aspirante y luego borrar esa documentación del sistema para evitar que el disco se llene más rápido. <br>
                                                <b>En cada aspirante en estado Aprobado: Ve al botón Acciones y luego Borrar documentación.</b></p>
                                        </div>
											
                                        <div class="alert alert-block alert-success">
                                            <h4 class="alert-heading">Enlace para inscripción:</h4>
                                            <p>Para ir al formulario de inscripción <a href="<?=$urlInscripcion?>" target="_blank"><b>CLICK AQUÍ</b></a> o copie el siguiente enlace para enviar al usuario</p>
                                            <input type="text" name="enlace" class="form-control col-md-6" value="<?=$urlInscripcion?>" disabled>
                                            </div>
                                        </div>

                                        <div class="table">
                                    		<table class="display" style="width:100%;">
												<thead>
													<tr>
                                                        <th>ID</th>
                                                        <th>#Solicitud</th>
                                                        <th>Fecha</th>
                                                        <th>Documento</th>
                                                        <th>Aspirante</th>
                                                        <th>Año</th>
                                                        <th>Estado</th>
                                                        <th>Comprobante</th>
                                                        <th>Grado</th>
                                                        <th>Acciones</th>
													</tr>
												</thead>
                                                <tbody>
                                                <?php
												include("includes/consulta-paginacion-inscripciones.php");
                                                try{
                                                    $consulta = mysqli_query($conexion, "SELECT * FROM academico_matriculas
                                                    INNER JOIN ".$baseDatosAdmisiones.".aspirantes ON asp_id=mat_solicitud_inscripcion
                                                    LEFT JOIN academico_grados ON gra_id=asp_grado
                                                    WHERE mat_estado_matricula=5 $filtro
                                                    ORDER BY mat_primer_apellido
                                                    LIMIT $inicio,$registros");
                                                } catch (Exception $e) {
                                                    include("../compartido/error-catch-to-report.php");
                                                }
                                                while ($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
                                                ?>
                                                <tr id="data1" class="odd gradeX">
                                                    <td><?= $resultado["mat_id"]; ?></td>
                                                    <td><?= $resultado["asp_id"]; ?></td>
                                                    <td><?= $resultado["asp_fecha"]; ?></td>
                                                    <td><?= $resultado["mat_documento"]; ?></td>
                                                    <td><?= strtoupper($resultado["mat_nombres"] . " " . $resultado["mat_primer_apellido"]); ?></td>
                                                    <td><?= $resultado["asp_agno"]; ?></td>
                                                    <td><span style="background-color: <?= $fondoSolicitud[$resultado["asp_estado_solicitud"]]; ?>; padding: 5px;"><?= $estadosSolicitud[$resultado["asp_estado_solicitud"]]; ?></span></td>
                                                    <td><a href="../admisiones/files/comprobantes/<?= $resultado["asp_comprobante"]; ?>" target="_blank" style="text-decoration: underline;"><?= $resultado["asp_comprobante"]; ?></a></td>
                                                    <td><?= $resultado["gra_nombre"]; ?></td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-primary"><?=$frases[54][$datosUsuarioActual[8]];?></button>
                                                            <button type="button" class="btn btn-primary dropdown-toggle m-r-20" data-toggle="dropdown">
                                                                <i class="fa fa-angle-down"></i>
                                                            </button>
                                                            <ul class="dropdown-menu" role="menu">
                                                                <li><a href="../admisiones/formulario.php?token=<?= md5($resultado["asp_id"]); ?>&id=<?= base64_encode($resultado["asp_id"]); ?>&idInst=<?=base64_encode($config["conf_id_institucion"])?>" target="_blank">Ver información</a></li>
                                                                <li><a href="../admisiones/admin-formulario-editar.php?token=<?= md5($resultado["asp_id"]); ?>&id=<?= base64_encode($resultado["asp_id"]); ?>&idInst=<?=base64_encode($config["conf_id_institucion"])?>" target="_blank">Editar</a></li>
                                                                
                                                                <?php if ($resultado["asp_estado_solicitud"] == 6) { ?>
                                                                    
                                                                <li><a href="javascript:void(0);" 
                                                                onClick="sweetConfirmacion('Alerta!','Va a eliminar la documentación de este aspirante. Recuerde descargarla primero. Esta acción es irreversible. Desea continuar?','question','inscripciones-eliminar-documentacion.php?matricula=<?= base64_encode($resultado["mat_id"]); ?>')"
                                                                >Borrar documentación</a></li>

                                                                <li><a href="javascript:void(0);" 
                                                                onClick="sweetConfirmacion('Alerta!','Va a pasar este estudiante al <?=($agnoBD+1); ?>. Desea continuar?','question','inscripciones-pasar-estudiante.php?matricula=<?= base64_encode($resultado["mat_id"]); ?>')"
                                                                >Pasar a <?=($agnoBD+1); ?></a></li>

                                                                <?php } ?>

                                                                <?php if ($resultado["asp_estado_solicitud"] == 1 or $resultado["asp_estado_solicitud"] == 2 or $resultado["asp_estado_solicitud"] == 7) { ?>
                                                                <li><a href="javascript:void(0);" 
                                                                onClick="sweetConfirmacion('Alerta!','Va a eliminar este aspirante. Esta acción es irreversible. Desea continuar?','question','inscripciones-eliminar-aspirante.php?matricula=<?= base64_encode($resultado["mat_id"]); ?>')"
                                                                >Eliminar aspirante</a></li>
                                                                <?php } ?>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                            </div>
                                        </div>
                      				    <?php include("enlaces-paginacion.php");?>
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