<?php include("session.php");?>
<?php $idPaginaInterna = 'DT0084';?>
<?php include("../compartido/historial-acciones-guardar.php");?>
<?php include("../compartido/head.php");?>
<?php include("includes/variables-estudiantes-agregar.php");?>
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
	<link href="../../config-general/assets/css/pages/formlayout.css" rel="stylesheet" type="text/css" />
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
		function nuevoEstudiante(enviada){
			var nDoct = enviada.value;

			if(nDoct!=""){
				$('#nDocu').empty().hide().html("Validado documento...").show(1);

				datos = "nDoct="+(nDoct);
					$.ajax({
					type: "POST",
					url: "ajax-estudiantes-agregar.php",
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
                                <div class="page-title">Crear matrículas</div>
                            </div>
                            <ol class="breadcrumb page-breadcrumb pull-right">
                                <li><a class="parent-item" href="#" name="estudiantes.php?cantidad=10" onClick="deseaRegresar(this)">Matrículas</a>&nbsp;<i class="fa fa-angle-right"></i></li>
                                <li class="active">Crear matrículas</li>
                            </ol>
                        </div>
                    </div>

					
					<div class="card-body">

                        <div class="row" style="margin-bottom: 10px;">
                           	<div class="col-sm-12" align="center">
	                         	<p style="color: darkblue;"></p>
	                        </div>
                        </div>
						<span style="color: blue; font-size: 15px;" id="nDocu"></span>
                         
                    <!-- wizard with validation-->
                    <div class="row">
                    	<div class="col-sm-12">
							<?php include("../../config-general/mensajes-informativos.php"); ?>
                             <div class="card-box">
                                 <div class="card-head">
                                     <header>Matrículas</header>
                                 </div>

								 <div class="card-body">

                                    

                                 <div class="card-body">
                                 	<form name="example_advanced_form" id="example-advanced-form" action="estudiantes-guardar.php" method="post">
									  
										<h3>Información personal</h3>
									    <fieldset>
											

											
											<div class="form-group row">
												<label class="col-sm-2 control-label">Tipo de documento</label>
												<div class="col-sm-4">
													<?php
													try{
														$opcionesConsulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".opciones_generales
														WHERE ogen_grupo=1");
													} catch (Exception $e) {
														include("../compartido/error-catch-to-report.php");
													}
													?>
													<select class="form-control  select2" name="tipoD">
														<option value="">Seleccione una opción</option>
														<?php while($o = mysqli_fetch_array($opcionesConsulta, MYSQLI_BOTH)){
															if($o[0]==$datosMatricula['tipoD'])
															echo '<option value="'.$o[0].'" selected>'.$o[1].'</option>';
														else
															echo '<option value="'.$o[0].'">'.$o[1].'</option>';	
														}?>
													</select>
												</div>
											</div>

											
											
											<div class="form-group row">
												<label class="col-sm-2 control-label">Número de documento <span style="color: red;">(*)</span></label>
												<div class="col-sm-4">
													<input type="text" id="nDoc" name="nDoc" required class="form-control" autocomplete="off"  tabindex="<?=$contReg;?>" onChange="nuevoEstudiante(this)" value="<?=$datosMatricula['documento'];?>">
												</div>

											</div>	
												
											<div class="form-group row">
												<label class="col-sm-2 control-label">Lugar de expedición</label>
												<div class="col-sm-4">
													<select class="form-control  select2" name="lugarD">
														<option value="">Seleccione una opción</option>
														<?php
														try{
															$opcionesG = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".localidad_ciudades
															INNER JOIN ".$baseDatosServicios.".localidad_departamentos ON dep_id=ciu_departamento
															");
														} catch (Exception $e) {
															include("../compartido/error-catch-to-report.php");
														}
														while($opg = mysqli_fetch_array($opcionesG, MYSQLI_BOTH)){
														?>
														<option value="<?=$opg['ciu_id'];?>" <?php if($opg['ciu_id']==$datosMatricula['lugarEx']){echo "selected";}?>><?=$opg['ciu_nombre'].", ".$opg['dep_nombre'];?></option>
														<?php }?>
													</select>
												</div>
											</div>
											
											<?php if($config['conf_id_institucion']==1){ ?>
											<div class="form-group row">
												<label class="col-sm-2 control-label">Folio</label>
												<div class="col-sm-2">
													<input type="text" name="folio" class="form-control" autocomplete="off" value="<?=$datosMatricula['folio'];?>">
												</div>
												
												<label class="col-sm-2 control-label">Codigo Tesoreria</label>
												<div class="col-sm-2">
													<input type="text" name="codTesoreria" class="form-control" autocomplete="off" value="<?=$datosMatricula['tesoreria'];?>">
												</div>
											</div>
											<?php }?>
											
											<div class="form-group row">
												<label class="col-sm-2 control-label">Primer apellido <span style="color: red;">(*)</span></label>
												<div class="col-sm-4">
													<input type="text" id="apellido1" name="apellido1" class="form-control" autocomplete="off" required value="<?=$datosMatricula['apellido1'];?>">
												</div>
												
												<label class="col-sm-2 control-label">Segundo apellido</label>
												<div class="col-sm-4">
													<input type="text" id="apellido2" name="apellido2" class="form-control" autocomplete="off" value="<?=$datosMatricula['apellido2'];?>">
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-sm-2 control-label">Primer Nombre <span style="color: red;">(*)</span></label>
												<div class="col-sm-4">
													<input type="text" id="nombres" name="nombres" class="form-control" autocomplete="off" required value="<?=$datosMatricula['nombre'];?>">
												</div>

												<label class="col-sm-2 control-label">Otro Nombre</label>
												<div class="col-sm-4">
													<input type="text" name="nombre2" class="form-control" autocomplete="off" value="<?=$datosMatricula['nombre2'];?>">
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-sm-2 control-label">Email</label>
												<div class="col-sm-6">
													<input type="text" name="email" class="form-control" value="<?=$datosMatricula['email'];?>" autocomplete="off">
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-sm-2 control-label">Fecha de nacimiento</label>
												<div class="col-sm-4">
													<div class="input-group date form_date" data-date-format="dd MM yyyy" data-link-field="dtp_input1" data-link-format="yyyy-mm-dd">
													<input class="form-control" size="16" type="text" value="<?=$datosMatricula['nacimiento'];?>">
													<span class="input-group-addon"><span class="fa fa-calendar"></span></span>
													</div>
												</div>
												<input type="hidden" id="dtp_input1" name="fNac">
											</div>
												
											<div class="form-group row">
												<label class="col-sm-2 control-label">Lugar de Nacimiento</label>
												<div class="col-sm-4">
													<select class="form-control  select2" name="lNacM">
														<option value="">Seleccione una opción</option>
														<?php
														try{
															$opcionesG = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".localidad_ciudades
															INNER JOIN ".$baseDatosServicios.".localidad_departamentos ON dep_id=ciu_departamento
															");
														} catch (Exception $e) {
															include("../compartido/error-catch-to-report.php");
														}
														while($opg = mysqli_fetch_array($opcionesG, MYSQLI_BOTH)){
														?>
														<option value="<?=$opg['ciu_id'];?>" <?php if($opg['ciu_id']==$datosMatricula['lugarNac']){echo "selected";}?>><?=$opg['ciu_nombre'].", ".$opg['dep_nombre'];?></option>
														<?php }?>
													</select>
												</div>
												
												<label class="col-sm-2 control-label">Ciudad de Procedencia</label>
												<div class="col-sm-4" id="ciudadPro2" >
													<input type="text" name="ciudadPro" class="form-control" autocomplete="off">
												</div>
											</div>
												
											<div class="form-group row">
												<label class="col-sm-2 control-label">Género</label>
												<div class="col-sm-4">
													<select class="form-control  select2" name="genero">
														<option value="">Seleccione una opción</option>
														<?php
														try{
															$op = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".opciones_generales WHERE ogen_grupo=4");
														} catch (Exception $e) {
															include("../compartido/error-catch-to-report.php");
														}
														while($o = mysqli_fetch_array($op, MYSQLI_BOTH)){
															if($o[0]==$datosMatricula['genero'])
																echo '<option value="'.$o[0].'" selected>'.$o[1].'</option>';
															else
																echo '<option value="'.$o[0].'">'.$o[1].'</option>';	
														}?>
													</select>
												</div>
											</div>

											<?php if($config['conf_id_institucion']==1){ ?>
											<div class="form-group row">
												<label class="col-sm-2 control-label">Grupo Sanguineo</label>
												<div class="col-sm-2">
													<input type="text" name="tipoSangre" class="form-control" autocomplete="off" value="<?=$datosMatricula['tipoSangre'];?>">
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-sm-2 control-label">EPS</label>
												<div class="col-sm-2">
													<input type="text" name="eps" class="form-control" autocomplete="off" value="<?=$datosMatricula['eps'];?>">
												</div>
											</div>
												
											<div class="form-group row">
												<label class="col-sm-2 control-label">Estudiante de Inclusión</label>
												<div class="col-sm-2">
													<select class="form-control  select2" name="inclusion">
														<option value="">Seleccione una opción</option>
														<option value="1"<?php if ($datosMatricula['inclusion']==1){echo "selected";}?>>Si</option>
														<option value="0"<?php if ($datosMatricula['inclusion']==0){echo "selected";}?>>No</option>
													</select>
												</div>
												
												
												<label class="col-sm-2 control-label">Religi&oacute;n</label>
												<div class="col-sm-2">
													<select class="form-control  select2" name="religion">
														<option value="">Seleccione una opción</option>
														<?php
														try{
															$op = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".opciones_generales WHERE ogen_grupo=2");
														} catch (Exception $e) {
															include("../compartido/error-catch-to-report.php");
														}
														while($o = mysqli_fetch_array($op, MYSQLI_BOTH)){
															if($o[0]==$datosMatricula['religion'])
																echo '<option value="'.$o[0].'" selected>'.$o[1].'</option>';
															else
																echo '<option value="'.$o[0].'">'.$o[1].'</option>';	
														}?>
													</select>
												</div>
											</div>
											<?php }?>
												
											<div class="form-group row">
												<label class="col-sm-2 control-label">Extranjero?</label>
												<div class="col-sm-2">
													<select class="form-control  select2" name="extran">
														<option value="">Seleccione una opción</option>
														<option value="1"<?php if ($datosMatricula['extran']==1){echo "selected";}?>>Si</option>
														<option value="0"<?php if ($datosMatricula['extran']==0){echo "selected";}?>>No</option>
													</select>
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-sm-2 control-label">Direcci&oacute;n</label>
												<div class="col-sm-4">
													<input type="text" name="direccion" class="form-control" autocomplete="off" value="<?=$datosMatricula['direcion'];?>">
												</div>
												<div class="col-sm-4">
													<input type="text" name="barrio" class="form-control" placeholder="Barrio" autocomplete="off" value="<?=$datosMatricula['barrio'];?>">
												</div>
											</div>
												
											<div class="form-group row">
												<label class="col-sm-2 control-label">Ciudad de residencia</label>
												<div class="col-sm-4">
													<select class="form-control  select2" name="ciudadR">
														<option value="">Seleccione una opción</option>
														<?php
														try{
															$opcionesG = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".localidad_ciudades
															INNER JOIN ".$baseDatosServicios.".localidad_departamentos ON dep_id=ciu_departamento 
															ORDER BY ciu_nombre
															");
														} catch (Exception $e) {
															include("../compartido/error-catch-to-report.php");
														}
														while($opg = mysqli_fetch_array($opcionesG, MYSQLI_BOTH)){
															$selected='';
															$opg['ciu_codigo'] = trim($opg['ciu_codigo']);
															if($opg['ciu_codigo']==$datosMatricula['ciudadR']){
																$selected='selected';
															}
	
															?>
															<option value="<?=$opg['ciu_codigo'];?>" <?=$selected;?>><?=$opg['ciu_nombre'].", ".$opg['dep_nombre'];?></option>
															<?php }?>
													</select>
												</div>
											</div>
											<?php if($config['conf_id_institucion']==1){ ?>	
											<div class="form-group row">
												<label class="col-sm-2 control-label">Estrato</label>
												<div class="col-sm-2">
													<select class="form-control  select2" name="estrato">
														<option value="">Seleccione una opción</option>
														<?php
														try{
															$op = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".opciones_generales WHERE ogen_grupo=3");
														} catch (Exception $e) {
															include("../compartido/error-catch-to-report.php");
														}
														while($o = mysqli_fetch_array($op, MYSQLI_BOTH)){
															if($o[0]==$datosMatricula['estrato'])
																echo '<option value="'.$o[0].'" selected>'.$o[1].'</option>';
															else
																echo '<option value="'.$o[0].'">'.$o[1].'</option>';	
														}?>
													</select>
												</div>
											</div>
											<?php }?>
											
											<div class="form-group row">
												<label class="col-sm-2 control-label">Contactos</label>
												<div class="col-sm-2">
													<input type="text" name="telefono" class="form-control" placeholder="Telefono" autocomplete="off" value="<?=$datosMatricula['telefono'];?>">
												</div>
												<div class="col-sm-2">
													<input type="text" name="celular" class="form-control" placeholder="celular" autocomplete="off" value="<?=$datosMatricula['celular'];?>">
												</div>
												<div class="col-sm-2">
													<input type="text" name="celular2" class="form-control" placeholder="celular #2" autocomplete="off" value="<?=$datosMatricula['celular2'];?>">
												</div>
											</div>								   
									       
										</fieldset>
										
									    <h3>Información académica</h3>
									    <fieldset>

											<div class="form-group row">
												<label class="col-sm-2 control-label">Curso <span style="color: red;">(*)</span></label>
												<div class="col-sm-4">
													<?php
													try{
														$opcionesConsulta = mysqli_query($conexion, "SELECT * FROM academico_grados
														WHERE gra_estado=1
														");
													} catch (Exception $e) {
														include("../compartido/error-catch-to-report.php");
													}
													?>
													<select class="form-control" name="grado" required>
														<option value="">Seleccione una opción</option>
														<?php
														while($opcionesDatos = mysqli_fetch_array($opcionesConsulta, MYSQLI_BOTH)){
															if($opcionesDatos[0]==$datosMatricula['grado'])
																echo '<option value="'.$opcionesDatos[0].'" selected>'.$opcionesDatos[2].'</option>';
															else
																echo '<option value="'.$opcionesDatos[0].'">'.$opcionesDatos[2].'</option>';	
														}?>
													</select>
												</div>
											</div>
												
											<div class="form-group row">
												<label class="col-sm-2 control-label">Grupo</label>
												<div class="col-sm-2">
													<?php
													try{
														$opcionesConsulta = mysqli_query($conexion, "SELECT * FROM academico_grupos");
													} catch (Exception $e) {
														include("../compartido/error-catch-to-report.php");
													}
													?>
													<select class="form-control" name="grupo">
														<option value="">Seleccione una opción</option>
														<?php
														while($rv = mysqli_fetch_array($opcionesConsulta, MYSQLI_BOTH)){
															if($rv[0]==$datosMatricula['grupo'])
																echo '<option value="'.$rv[0].'" selected>'.$rv['gru_nombre'].'</option>';
															else
																echo '<option value="'.$rv[0].'">'.$rv['gru_nombre'].'</option>';	
														}?>
													</select>
												</div>
											</div>
												
											<div class="form-group row">
												<label class="col-sm-2 control-label">Tipo estudiante</label>
												<div class="col-sm-4">
													<?php
													try{
														$opcionesConsulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".opciones_generales
														WHERE ogen_grupo=5
														");
													} catch (Exception $e) {
														include("../compartido/error-catch-to-report.php");
													}
													?>
													<select class="form-control" name="tipoEst">
														<option value="">Seleccione una opción</option>
														<?php
														while($o = mysqli_fetch_array($opcionesConsulta, MYSQLI_BOTH)){
															if($o[0]==$datosMatricula['tipoE'])
																echo '<option value="'.$o[0].'" selected>'.$o[1].'</option>';
															else
																echo '<option value="'.$o[0].'">'.$o[1].'</option>';	
														}?>
													</select>
												</div>
											</div>
												
											<div class="form-group row">
												<label class="col-sm-2 control-label">Estado Matricula</label>
												<div class="col-sm-4">
													<select class="form-control" name="matestM">
														<option value="">Seleccione una opción</option>
														<option value="1"  <?php if(1==$datosMatricula["matestM"]) echo 'selected'?>>Matriculado</option>
														<option value="2"  <?php if(2==$datosMatricula["matestM"]) echo 'selected'?>>Asistente </option>
														<option value="3"  <?php if(3==$datosMatricula["matestM"]) echo 'selected'?>>Cancelado </option>
														<option value="4"  <?php if(4==$datosMatricula["matestM"]) echo 'selected'?>>No matriculado </option>
													</select>
												</div>
											</div>

											<div class="form-group row">												
												<label class="col-sm-2 control-label">Valor Matricula</label>
												<div class="col-sm-2">
													<input type="text" name="va_matricula" class="form-control" autocomplete="off" value="<?=$datosMatricula['vaMatricula'];?>">
												</div>
											</div>	
											
									    </fieldset>
										   
										<h3>Información del Acudiente</h3>
										<fieldset>
                      
											<div class="form-group row">
												<label class="col-sm-2 control-label">Tipo de documento</label>
												<div class="col-sm-3">
													<?php
													try{
														$opcionesConsulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".opciones_generales
														WHERE ogen_grupo=1
														");
													} catch (Exception $e) {
														include("../compartido/error-catch-to-report.php");
													}
													?>
													<select class="form-control" name="tipoDAcudiente">
														<option value="">Seleccione una opción</option>
														<?php
														while($o = mysqli_fetch_array($opcionesConsulta, MYSQLI_BOTH)){
															if($o[0]==$datosMatricula['tipoDocA'])
															echo '<option value="'.$o[0].'" selected>'.$o[1].'</option>';
														else
															echo '<option value="'.$o[0].'">'.$o[1].'</option>';	
														}?>
													</select>
												</div>
                        
												<label class="col-sm-2 control-label">Documento <span style="color: red;">(*)</span></label>
												<div class="col-sm-3">
                          
                        <div class="cargando row">       
                        <div class="d-flex justify-content-center">
                          <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Verificando Documento, Espere Por Favor!</span>
                          </div>
                        </div>
                      </div>

													<input type="text" name="documentoA" id="doc" onblur="buscar_datos();" class="form-control"  required value="<?=$datosMatricula['documentoA'];?>">
												</div>
											</div>
												
											<div class="form-group row">
												<label class="col-sm-2 control-label">Lugar de expedición</label>
												<div class="col-sm-4">
													<select class="form-control" id="lugardE" name="lugarDa">
														<option value="">Seleccione una opción</option>
														<?php
														try{
															$opcionesG = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".localidad_ciudades
															INNER JOIN ".$baseDatosServicios.".localidad_departamentos ON dep_id=ciu_departamento
															");
														} catch (Exception $e) {
															include("../compartido/error-catch-to-report.php");
														}
														while($opg = mysqli_fetch_array($opcionesG, MYSQLI_BOTH)){
														?>
														<option value="<?=$opg['ciu_id'];?>" <?php if($opg['ciu_id']==$datosMatricula['expedicionA']){echo "selected";}?>><?=$opg['ciu_nombre'].", ".$opg['dep_nombre'];?></option>
														<?php }?>
													</select>
												</div>

												<?php if($config['conf_id_institucion']==1){ ?>
												<label class="col-sm-2 control-label">Ocupaci&oacute;n</label>
												<div class="col-sm-3">
													<input type="text" name="ocupacionA" class="form-control" autocomplete="off" value="<?=$datosMatricula['ocupacionA'];?>">
												</div>
												<?php }?>

											</div>

											<div class="form-group row">												
												<label class="col-sm-2 control-label">Primer Apellido</label>
												<div class="col-sm-3">
													<input type="text" name="apellido1A" id="apellido1A" class="form-control"  value="<?=$datosMatricula['apellido1A'];?>">
												</div>
																							
												<label class="col-sm-2 control-label">Segundo Apellido</label>
												<div class="col-sm-3">
													<input type="text" name="apellido2A" id="apellido2A" class="form-control"  value="<?=$datosMatricula['apellido2A'];?>">
												</div>
											</div>

											<div class="form-group row">												
												<label class="col-sm-2 control-label">Nombre <span style="color: red;">(*)</span></label>
												<div class="col-sm-3">
													<input type="text" name="nombresA" id="nombresA" class="form-control"  required value="<?=$datosMatricula['nombreA'];?>">
												</div>
																								
												<label class="col-sm-2 control-label">Otro Nombre</label>
												<div class="col-sm-3">
													<input type="text" name="nombre2A" id="nombre2A" class="form-control"  value="<?=$datosMatricula['documentoA'];?>">
												</div>
											</div>	
												
											<?php if($config['conf_id_institucion']==1){ ?>
											<div class="form-group row">
												<label class="col-sm-2 control-label">Fecha de nacimiento</label>
												<div class="col-sm-3">
													<div class="input-group date form_date" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
													<input class="form-control" size="16" type="text">
													<span class="input-group-addon"><span class="fa fa-calendar"></span></span>
													</div>
												</div>
												<input type="hidden" id="dtp_input2" name="fechaNA">

												<label class="col-sm-2 control-label">Genero</label>
												<div class="col-sm-3">
													<select class="form-control select2" name="generoA">
														<option value="">Seleccione una opción</option>
														<?php
														try{
															$op = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".opciones_generales WHERE ogen_grupo=4");
														} catch (Exception $e) {
															include("../compartido/error-catch-to-report.php");
														}
														while($o = mysqli_fetch_array($op, MYSQLI_BOTH)){
															if($o[0]==$datosMatricula['generoA'])
																echo '<option value="'.$o[0].'" selected>'.$o[1].'</option>';
															else
																echo '<option value="'.$o[0].'">'.$o[1].'</option>';	
														}?>
													</select>
												</div>
											</div>
											<?php }?>									   
									       
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
  <script type="text/javascript">
  $(document).ready(function(){
        $('.cargando').hide();
      });  
  function buscar_datos()
  {
    doc = $("#doc").val();
    var parametros = 
    {
      "buscar": "1",
      "uss_usuario" : doc
    };
    $.ajax(
    {
      data:  parametros,
      dataType: 'json',
      url:   'ajax-comprobar-acudiente.php',
      type:  'post',
      beforeSend: function() 
      {
        $('.cargando').show();
      }, 
      error: function()
      {alert("Error");},
      complete: function() 
      {
        $('.cargando').hide();
      },
      success:  function (valores) 
      {
         $("#apellido1A").val(valores.apellido1);
          $("#apellido2A").val(valores.apellido2);
          $("#nombresA").val(valores.nombre1);
          $("#nombre2A").val(valores.nombre2);
          $("#lugardE").val(valores.lugardE);
      }
    }) 
  }
  </script>
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

<!-- Mirrored from radixtouch.in/templates/admin/smart/source/light/wizard.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 18 May 2018 17:32:55 GMT -->
</html>