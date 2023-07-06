<fieldset>

											<div class="form-group row">
												<label class="col-sm-2 control-label">Curso <span style="color: red;">(*)</span></label>
												<div class="col-sm-4">
													<?php
													$cv = mysqli_query($conexion, "SELECT * FROM academico_grados
													WHERE gra_estado=1 AND gra_tipo='".GRADO_GRUPAL."'");
													?>
													<select class="form-control" name="grado">
														<option value="">Seleccione una opción</option>
														<?php while($rv = mysqli_fetch_array($cv, MYSQLI_BOTH)){
															if($rv[0]==$datosEstudianteActual[6])
																echo '<option value="'.$rv[0].'" selected>'.$rv[2].'</option>';
															else
																echo '<option value="'.$rv[0].'">'.$rv[2].'</option>';	
														}?>
													</select>
												</div>
											</div>
												
											<div class="form-group row">
												<label class="col-sm-2 control-label">Grupo</label>
												<div class="col-sm-2">
													<?php
													$cv = mysqli_query($conexion, "SELECT gru_id, gru_nombre FROM academico_grupos");
													?>
													<select class="form-control" name="grupo">
													<?php while($rv = mysqli_fetch_array($cv, MYSQLI_BOTH)){
														if($rv[0]==$datosEstudianteActual[7])
															echo '<option value="'.$rv[0].'" selected>'.$rv[1].'</option>';
														else
															echo '<option value="'.$rv[0].'">'.$rv[1].'</option>';	
													}?>
													</select>
												</div>
											</div>
												
											<div class="form-group row">
												<label class="col-sm-2 control-label">Tipo estudiante</label>
												<div class="col-sm-4">
													<?php
													$op = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".opciones_generales WHERE ogen_grupo=5");
													?>
													<select class="form-control" name="tipoEst">
														<option value="">Seleccione una opción</option>
														<?php while($o = mysqli_fetch_array($op, MYSQLI_BOTH)){
															if($o[0]==$datosEstudianteActual[21])
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
														<option value="1"  <?php if(1==$datosEstudianteActual["mat_estado_matricula"]) echo 'selected'?>>Matriculado</option>
														<option value="2"  <?php if(2==$datosEstudianteActual["mat_estado_matricula"]) echo 'selected'?>>Asistente </option>
														<option value="3"  <?php if(3==$datosEstudianteActual["mat_estado_matricula"]) echo 'selected'?>>Cancelado </option>
														<option value="4"  <?php if(4==$datosEstudianteActual["mat_estado_matricula"]) echo 'selected'?>>No matriculado </option>
													</select>
												</div>
											</div>

											<div class="form-group row">												
												<label class="col-sm-2 control-label">Valor Matricula</label>
												<div class="col-sm-2">
													<input type="text" name="va_matricula" class="form-control" autocomplete="off">
												</div>
											</div>	
												
											<div class="form-group row">
												<label class="col-sm-2 control-label">Estado del año</label>
												<div class="col-sm-4">
													<select class="form-control" name="estadoAgno">
														<option value="0">Seleccione una opción</option>
														<option value="1"<?php if ($datosEstudianteActual['mat_estado_agno']==1){echo "selected";}?>>Ganado</option>
														<option value="2"<?php if ($datosEstudianteActual['mat_estado_agno']==2){echo "selected";}?>>Perdido</option>
														<option value="2"<?php if ($datosEstudianteActual['mat_estado_agno']=='0'){echo "selected";}?>>En curso</option>
													</select>
												</div>
											</div>
	<?php if (array_key_exists(10, $arregloModulos)) { 
			require_once("../class/servicios/MediaTecnicaServicios.php");
			$parametros = ['gra_tipo' => GRADO_INDIVIDUAL, 'gra_estado' => 1];
			
			$listaIndividuales = GradoServicios::listarCursos($parametros);
			$parametros = ['matcur_id_matricula' => $_GET["id"]];
			$listaMediaTenicaActual=MediaTecnicaServicios::listar($parametros);
			$listaMediaActual=array();		
			if(!is_null($listaMediaTenicaActual) && count($listaMediaTenicaActual)>0){
				foreach($listaMediaTenicaActual as $llave=> $valor){
					$listaMediaActual[$valor["matcur_id_curso"]]='id_curso';
					
				}
				
			}
			?>
		<div class="form-group row">
			<label class="col-sm-2 control-label"> Puede estar en multiples cursos? </label>
			<div class="col-sm-2">
				<select class="form-control  select2" name="tipoMatricula" id="tipoMatricula" onchange="mostrarCursosAdicionales(this)">
					<option value="<?=GRADO_GRUPAL;?>" 
					<?php if ($datosEstudianteActual['mat_tipo_matricula'] == GRADO_GRUPAL) {echo 'selected';} ?>
					>NO</option>
					<option value="<?=GRADO_INDIVIDUAL;?>"
					<?php if ($datosEstudianteActual['mat_tipo_matricula'] == GRADO_INDIVIDUAL) {echo 'selected';} ?>
					>SI</option>
				</select>
			</div>
		</div>
		<script type="application/javascript">
			$(document).ready(mostrarCursosAdicionales(document.getElementById("tipoMatricula")))
			function mostrarCursosAdicionales(enviada) {
				var valor = enviada.value;
				if (valor == '<?=GRADO_INDIVIDUAL;?>') {
					document.getElementById("divCursosAdicionales").style.display='block';
				} else {
					document.getElementById("divCursosAdicionales").style.display='none';
				}
			}
		</script>
		
		<div id="divCursosAdicionales" style="display: none;">
			<div class="form-group row" >
				<label class="col-sm-2 control-label">Cursos adicionales</label>
				<div class="col-sm-4">
					<select id="multiple" class="form-control select2-multiple" style="width: 100% !important" name="cursosAdicionales[]" multiple>
						<option value="">Seleccione una opción</option>
						<?php
						foreach ($listaIndividuales as $dato) {
							$disabled = '';
							$selected = '';
							if (array_key_exists($dato["gra_id"], $listaMediaActual)){
								$selected = 'selected';
							}
							if ($dato['gra_estado'] == '0') {
								$disabled = 'disabled';
							};
							echo '<option value="' . $dato["gra_id"] . '" ' . $disabled . ' ' . $selected . '>' . $dato['gra_id'] . '.' . strtoupper($dato['gra_nombre']) . '</option>';
						}
						?>
					</select>
				</div>
			</div>
		</div>
	<?php } ?>
</fieldset>