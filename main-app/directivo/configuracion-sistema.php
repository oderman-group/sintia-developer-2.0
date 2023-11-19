<?php include("session.php");?>
<?php $idPaginaInterna = 'DT0057';?>
<?php include("../compartido/historial-acciones-guardar.php");?>
<?php include("../compartido/head.php");?>
<?php

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
try{
    $consultaCfg=mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".configuracion 
    WHERE conf_base_datos='".$_SESSION["inst"]."' AND conf_agno='".$_SESSION["bd"]."'");
} catch (Exception $e) {
    include("../compartido/error-catch-to-report.php");
}
$cfg = mysqli_fetch_array($consultaCfg, MYSQLI_BOTH);

$disabledPermiso = "";
if(!Modulos::validarPermisoEdicion()){
	$disabledPermiso = "disabled";
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
                                <div class="page-title"><?=$frases[17][$datosUsuarioActual[8]];?> del Sistema</div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
							<ol class="breadcrumb page-breadcrumb pull-right">
                                <li class="active"><?=$frases[17][$datosUsuarioActual[8]];?> del Sistema</li>
                            </ol>
                        </div>
                    </div>
                    <div class="row">
						
                        <div class="col-sm-12">
                                
								<div class="panel">
									<header class="panel-heading panel-heading-purple"><?=$frases[17][$datosUsuarioActual[8]];?> </header>
                                	<div class="panel-body">

                                   
									<form name="formularioGuardar" action="configuracion-sistema-guardar.php" method="post">

                                    <p class="h3">General</p>
										
									<div class="form-group row">
											<label class="col-sm-2 control-label">Año Actual</label>
											<div class="col-sm-8">
												<input type="text" name="agno" class="form-control col-sm-2" value="<?=$cfg[1];?>" readonly <?=$disabledPermiso;?>>
											</div>
										</div>

                                        <div class="form-group row">
											<label class="col-sm-2 control-label">
                                                Periodos a trabajar <span style="color: red;">(*)</span> 
                                                <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Las instituciones normalmente manejan 4 periodos. Los colegios semestralizados o de bachillerato acelerado manejan 2 periodos."><i class="fa fa-question"></i></button>
                                        </label>
											<div class="col-sm-8">
												<input type="text" name="periodoTrabajar" class="form-control col-sm-2" value="<?=$cfg[19];?>" required pattern="[0-9]+" <?=$disabledPermiso;?>>
											</div>
										</div>
										
										
										
										<div class="form-group row">
											<label class="col-sm-2 control-label">Periodo Actual <span style="color: red;">(*)</span>
                                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Este valor solo se verá reflejado en los informes que obtienen los directivos."><i class="fa fa-question"></i></button>    
                                        </label>
											<div class="col-sm-3">
                                                <select class="form-control  select2" name="periodo" required <?=$disabledPermiso;?>>
                                                    <option value="">Seleccione una opción</option>
													<?php
													$p = 1;
                                                    $pFinal = $config[19] + 1;
													while($p <= $pFinal){
                                                        $label = 'Periodo '.$p;
                                                        if($p == $pFinal) {
                                                            $label = 'AÑO FINALIZADO';
                                                        }

														if($p==$cfg['conf_periodo'])
															echo '<option value="'.$p.'" selected>'.$label.'</option>';
														else
															echo '<option value="'.$p.'">'.$label.'</option>';	
														$p++;
													}
													?>
                                                </select>
											</div>
										</div>
										
										
										<div class="form-group row">
											<label class="col-sm-2 control-label">Rango de las notas (Desde - Hasta) <span style="color: red;">(*)</span></label>
											<div class="col-sm-10">
												<input type="text"style="margin-top: 20px;" name="desde" class="col-sm-1" value="<?=$cfg[3];?>" <?=$disabledPermiso;?>>
												<input type="text"style="margin-top: 20px;" name="hasta" class="col-sm-1" value="<?=$cfg[4];?>" <?=$disabledPermiso;?>>
											</div>
										</div>
										
										<div class="form-group row">
											<label class="col-sm-2 control-label">Nota minima para aprobar <span style="color: red;">(*)</span></label>
											<div class="col-sm-2">
												<input type="text" name="notaMinima" class="form-control" value="<?=$cfg[5];?>" <?=$disabledPermiso;?>>
											</div>
										</div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label">Asignar porcentaje a las asignaturas? 
                                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Indica si las asignaturas tendrán un porcentaje diferente dentro del área al momento de calcular las notas en el boletín."><i class="fa fa-question"></i></button> 
                                            </label>
                                            <div class="col-sm-2">
                                                <select class="form-control  select2" name="porcenAsigna" <?=$disabledPermiso;?>>
                                                    <option value="SI" <?php if($cfg['conf_agregar_porcentaje_asignaturas']=='SI'){ echo "selected";} ?>>SI</option>
                                                    <option value="NO" <?php if($cfg['conf_agregar_porcentaje_asignaturas']=='NO'){ echo "selected";} ?>>No</option>
                                                </select>
                                            </div>
                                        </div>
										
                                        <p class="h3">Estilos y apariencia</p>

                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label">Estilo de calificación <span style="color: red;">(*)</span></label>
                                            <div class="col-sm-4">
                                                <select class="form-control  select2" name="estiloNotas" required <?=$disabledPermiso;?>>
                                                    <option value="">Seleccione una opción</option>
                                                    <?php 
                                                        try{
                                                            $opcionesGeneralesConsulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_categorias_notas WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
                                                        } catch (Exception $e) {
                                                            include("../compartido/error-catch-to-report.php");
                                                        }
                                                        while($opcionesGeneralesDatos = mysqli_fetch_array($opcionesGeneralesConsulta, MYSQLI_BOTH)){
                                                            if($cfg[22]==$opcionesGeneralesDatos['catn_id'])
                                                                echo '<option value="'.$opcionesGeneralesDatos['catn_id'].'" selected>'.$opcionesGeneralesDatos['catn_nombre'].'</option>';
                                                            else
                                                                echo '<option value="'.$opcionesGeneralesDatos['catn_id'].'">'.$opcionesGeneralesDatos['catn_nombre'].'</option>';	
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
											<label class="col-sm-2 control-label">Cantidad de decimales en las notas <span style="color: red;">(*)</span>
                                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Indica cuántos decimales aparecerán en los cálculos de las notas."><i class="fa fa-question"></i></button> 
                                        </label>
											<div class="col-sm-2">
												<input type="number" name="decimalesNotas" class="form-control" value="<?=$cfg['conf_decimales_notas'];?>">
											</div>
										</div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label">Estilo de certificado</label>
                                            <div class="col-sm-2">
                                                <select class="form-control  select2" id="tipoCertificado" name="certificado" onchange="cambiarTipo()" <?=$disabledPermiso;?>>
                                                    <option value="1" <?php if($cfg['conf_certificado']==1){ echo "selected";} ?>>Certificado 1</option>
                                                    <option value="2" <?php if($cfg['conf_certificado']==2){ echo "selected";} ?>>Certificado 2</option>
                                                </select>
                                            </div>
                                            <button type="button" titlee="Ver formato certificado" class="btn btn-sm" data-toggle="popover" ><i class="fa fa-eye"></i></button>
                                            <script>
                                                    $(document).ready(function(){
                                                    $('[data-toggle="popover"]').popover({
                                                        html: true, // Habilitar contenido HTML
                                                        content: function () {
                                                            valor = document.getElementById("tipoCertificado");
                                                        return '<div id="myPopover" class="popover-content"><label id="lbl_tipo">Estilo Certificado '+valor.value+'</label>'+
                                                        '<img id="img-boletin" src="../files/images/certificados/tipo'+valor.value+'.png" class="w-100" />'+                                                       
                                                        '</div>';}
                                                        });                                                    
                                                    });
                                                    function cambiarTipo(){  
                                                        var imagen_boletin = document.getElementById('img-boletin'); 
                                                        if(imagen_boletin){                                                     
                                                        var valor = document.getElementById("tipoCertificado");  
                                                        var lbl_tipo = document.getElementById('lbl_tipo');
                                                        imagen_boletin.src ="../files/images/certificados/tipo"+valor.value+".png";
                                                        lbl_tipo.textContent='Estilo Certificado '+valor.value;
                                                        }
                                                    }
                                            </script>
                                        </div>

										<div class="form-group row">
											<label class="col-sm-2 control-label">Color de las notas (Perdidas -  Ganadas) <span style="color: red;">(*)</span></label>
											<div class="col-sm-10">
												<input type="color"style="margin-top: 20px;" name="perdida" class="col-sm-1" value="<?=$cfg[6];?>" <?=$disabledPermiso;?>>
												<input type="color"style="margin-top: 20px;" name="ganada" class="col-sm-1" value="<?=$cfg[7];?>" <?=$disabledPermiso;?>>
											</div>
										</div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label">En qué orden desea ver el nombre de los estudiantes?</label>
                                            <div class="col-sm-4">
                                                <select class="form-control  select2" name="ordenEstudiantes" <?=$disabledPermiso;?>>
                                                    <option value="1" <?php if($cfg['conf_orden_nombre_estudiantes']==1){ echo "selected";} ?>>Nombres y Apellidos (Andres David Arias Pertuz)</option>
                                                    <option value="2" <?php if($cfg['conf_orden_nombre_estudiantes']==2){ echo "selected";} ?>>Apellidos y nombres (Arias Pertuz Andres David)</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label">Numero de registros en listados
                                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Esta opción permite escoger la cantidad de registros que desea que se listen al entrar, por ejemplo, a matrículas, cargas académicas o usuarios."><i class="fa fa-question"></i></button> 
                                            </label>
                                            <div class="col-sm-8">
                                                <select class="form-control col-sm-2 select2" name="numRegistros" <?=$disabledPermiso;?>>
                                                    <option value="20" <?php if($cfg['conf_num_registros']==20){ echo "selected";} ?>>20</option>
                                                    <option value="30" <?php if($cfg['conf_num_registros']==30){ echo "selected";} ?>>30</option>
                                                    <option value="50" <?php if($cfg['conf_num_registros']==50){ echo "selected";} ?>>50</option>
                                                    <option value="100" <?php if($cfg['conf_num_registros']==100){ echo "selected";} ?>>100</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
											<label class="col-sm-2 control-label">Medidas del Logo en los informes (Ancho -  Alto)</label>
											<div class="col-sm-10">
												<input type="text"style="margin-top: 20px;" name="logoAncho" class="col-sm-1" value="<?=$cfg[30];?>" <?=$disabledPermiso;?>>
												<input type="text"style="margin-top: 20px;" name="logoAlto" class="col-sm-1" value="<?=$cfg[31];?>" <?=$disabledPermiso;?>>
											</div>
										</div>
										
										<div class="form-group row">
											<label class="col-sm-2 control-label">Mostrar Nombre del colegio en el encabezado de los informes</label>
											<div class="col-sm-2">
                                                <select class="form-control  select2" name="mostrarNombre" <?=$disabledPermiso;?>>
                                                    <option value="1" <?php if($cfg[32]==1){ echo "selected";} ?>>SI</option>
                                                    <option value="2" <?php if($cfg[32]==2){ echo "selected";} ?>>NO</option>
                                                </select>
											</div>
										</div>

                                        <div class="form-group row">
											<label class="col-sm-2 control-label">Forma para mostrar las notas <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Esta opción mostrará a los usuarios las notas en formato numérico o con frases de desempeño que corresponden a las notas numéricas, dependiendo la opción que seleccione."><i class="fa fa-question"></i></button> </label>
											<div class="col-sm-2">
                                                <select class="form-control  select2" name="formaNotas" <?=$disabledPermiso;?>>
                                                    <option value="<?=CUALITATIVA?>" <?php if($cfg['conf_forma_mostrar_notas'] == CUALITATIVA){ echo "selected";} ?>>CUALITATIVA (sin numéros)</option>
                                                    <option value="<?=CUANTITATIVA?>" <?php if($cfg['conf_forma_mostrar_notas'] == CUANTITATIVA){ echo "selected";} ?>>CUANTITATIVA (con números)</option>
                                                </select>
											</div>
										</div>
										
                                        
                                        <p class="h3">Permisos</p>

										<div class="form-group row">
                                            <label class="col-sm-2 control-label">Mostrar calificaciones a los acudientes?</label>
                                            <div class="col-sm-2">
                                                <select class="form-control  select2" name="caliAcudientes" <?=$disabledPermiso;?>>
                                                    <option value="1" <?php if($cfg['conf_calificaciones_acudientes']==1){ echo "selected";} ?>>SI</option>
                                                    <option value="0" <?php if($cfg['conf_calificaciones_acudientes']==0){ echo "selected";} ?>>No</option>
                                                </select>
                                            </div>
                                        </div>
                                        
										<div class="form-group row">
                                            <label class="col-sm-2 control-label">Mostrar calificaciones a los estudiantes?</label>
                                            <div class="col-sm-2">
                                                <select class="form-control  select2" name="caliEstudiantes" <?=$disabledPermiso;?>>
                                                    <option value="1" <?php if($cfg['conf_mostrar_calificaciones_estudiantes']==1){ echo "selected";} ?>>SI</option>
                                                    <option value="0" <?php if($cfg['conf_mostrar_calificaciones_estudiantes']==0){ echo "selected";} ?>>No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label">Permiso de actualizar las definitivas en consolidado final?
                                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Esta acción permite o no actualizar las definitivas, en el consolidado final, en cualquier momento."><i class="fa fa-question"></i></button> 
                                            </label>
                                            <div class="col-sm-8">
                                                <select class="form-control col-sm-2 select2" name="permisoConsolidado" <?=$disabledPermiso;?>>
                                                    <option value="1" <?php if($cfg['conf_editar_definitivas_consolidado']==1){ echo "selected";} ?>>SI</option>
                                                    <option value="0" <?php if($cfg['conf_editar_definitivas_consolidado']==0){ echo "selected";} ?>>NO</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label">Observaciones multiples en comportamiento?</label>
                                            <div class="col-sm-2">
                                                <select class="form-control  select2" name="observacionesMultiples" <?=$disabledPermiso;?>>
                                                    <option value="1" <?php if($cfg['conf_observaciones_multiples_comportamiento']==1){ echo "selected";} ?>>SI</option>

                                                    <option value="0" <?php if($cfg['conf_observaciones_multiples_comportamiento'] == 0 || $cfg['conf_observaciones_multiples_comportamiento'] == null){ echo "selected";} ?>>No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label">Permitir descargar informe parcial a acudientes</label>
                                            <div class="col-sm-2">
                                                <select class="form-control  select2" name="informeParcial" <?=$disabledPermiso;?>>
                                                    <option value="1" <?php if($cfg['conf_informe_parcial']==1){ echo "selected";} ?>>SI</option>
                                                    <option value="0" <?php if($cfg['conf_informe_parcial']==0){ echo "selected";} ?>>No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label">Generar informes con estudiantes con menos de 100%?</label>
                                            <div class="col-sm-4">
                                                <select class="form-control  select2" name="generarInforme" <?=$disabledPermiso;?>>
                                                    <option value="1" <?php if($cfg['conf_porcentaje_completo_generar_informe']==1){ echo "selected";} ?>>No generar informe hasta que todos estén al 100%</option>
                                                    <option value="2" <?php if($cfg['conf_porcentaje_completo_generar_informe']==2){ echo "selected";} ?>>Omitir los estudiantes que no tengan el 100%</option>
                                                    <option value="3" <?php if($cfg['conf_porcentaje_completo_generar_informe']==3){ echo "selected";} ?>>Registrar la definitiva con el porcentaje actual</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label">Permitir a acudientes descargar el boletín?
                                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Esta acción permite a los acudientes descargar el boletín de sus acudidos."><i class="fa fa-question"></i></button> 
                                            </label>
                                            <div class="col-sm-8">
                                                <select class="form-control col-sm-2 select2" name="descargarBoletin" <?=$disabledPermiso;?>>
                                                    <option value="1" <?php if($cfg['conf_permiso_descargar_boletin']==1){ echo "selected";} ?>>SI</option>
                                                    <option value="0" <?php if($cfg['conf_permiso_descargar_boletin']==0){ echo "selected";} ?>>NO</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label">Permitir a docentes ver puestos en el informe de sábanas?
                                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Esta acción permite o no a los docentes ver el listado de los puestos de los estudiantes, por periodo, en el informe de sábanas."><i class="fa fa-question"></i></button> 
                                            </label>
                                            <div class="col-sm-8">
                                                <select class="form-control col-sm-2 select2" name="permisoDocentesPuestosSabanas" <?=$disabledPermiso;?>>
                                                    <option value="1" <?php if($cfg['conf_ver_promedios_sabanas_docentes']==1){ echo "selected";} ?>>SI</option>
                                                    <option value="0" <?php if($cfg['conf_ver_promedios_sabanas_docentes']==0){ echo "selected";} ?>>NO</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label">Activar encuesta de reserva de cupos?
                                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Esta acción permite a los acudientes responder si desean reservar o no el cupo para sus acudidos para el siguiente año."><i class="fa fa-question"></i></button> 
                                            </label>
                                            <div class="col-sm-8">
                                                <select class="form-control col-sm-2 select2" name="activarEncuestaReservaCupo" <?=$disabledPermiso;?>>
                                                    <option value="1" <?php if($cfg['conf_activar_encuesta']==1){ echo "selected";} ?>>SI</option>
                                                    <option value="0" <?php if($cfg['conf_activar_encuesta']==0){ echo "selected";} ?>>NO</option>
                                                </select>
                                            </div>
                                        </div>

                                        
										<p class="h3">Otras</p>

										<div class="form-group row">
											<label class="col-sm-2 control-label">Fecha que aparecerá en el proximo Informe Parcial</label>
											<div class="col-sm-2">
												<input type="text" name="fechapa" class="form-control" value="<?=$cfg[28];?>" <?=$disabledPermiso;?>>
											</div>
										</div>
										
										<div class="form-group row">
											<label class="col-sm-2 control-label">Texto de arriba del informe parcial</label>
											<div class="col-sm-10">
                                                <textarea cols="80" id="editor1" name="descrip" rows="10" <?=$disabledPermiso;?>><?=$cfg[29];?></textarea>
											</div>
										</div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label">Mostrar campo para firma del estudiante en reporte de asistencia?
                                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Esta opción permite dar un espacio para que el estudiante firme en el reporte de asistencia a la entrega de informes."><i class="fa fa-question"></i></button> 
                                            </label>
                                            <div class="col-sm-8">
                                                <select class="form-control col-sm-2 select2" name="firmaEstudiante" <?=$disabledPermiso;?>>
                                                    <option value="1" <?php if($cfg['conf_firma_estudiante_informe_asistencia']==1){ echo "selected";} ?>>SI</option>
                                                    <option value="0" <?php if($cfg['conf_firma_estudiante_informe_asistencia']==0){ echo "selected";} ?>>NO</option>
                                                </select>
                                            </div>
                                        </div>

                                        <?php if(Modulos::validarPermisoEdicion()){?>
										    <button type="submit" class="btn  btn-info">
										<i class="fa fa-save" aria-hidden="true"></i> Guardar cambios 
									</button>
                                        <?php }?>
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