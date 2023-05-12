<fieldset>

	<div class="form-group row">
		<label class="col-sm-2 control-label">Curso <span style="color: red;">(*)</span></label>
		<div class="col-sm-4">
			<?php
			$cv = mysqli_query($conexion, "SELECT * FROM academico_grados WHERE gra_tipo ='grupal'");
			?>
			<select class="form-control" name="grado">
				<option value="">Seleccione una opción</option>
				<?php while ($rv = mysqli_fetch_array($cv, MYSQLI_BOTH)) {
					if ($rv[0] == $datosEstudianteActual[6])
						echo '<option value="' . $rv[0] . '" selected>' . $rv[2] . '</option>';
					else
						echo '<option value="' . $rv[0] . '">' . $rv[2] . '</option>';
				} ?>
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
				<?php while ($rv = mysqli_fetch_array($cv, MYSQLI_BOTH)) {
					if ($rv[0] == $datosEstudianteActual[7])
						echo '<option value="' . $rv[0] . '" selected>' . $rv[1] . '</option>';
					else
						echo '<option value="' . $rv[0] . '">' . $rv[1] . '</option>';
				} ?>
			</select>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-sm-2 control-label">Tipo estudiante</label>
		<div class="col-sm-4">
			<?php
			$op = mysqli_query($conexion, "SELECT * FROM " . $baseDatosServicios . ".opciones_generales WHERE ogen_grupo=5");
			?>
			<select class="form-control" name="tipoEst">
				<option value="">Seleccione una opción</option>
				<?php while ($o = mysqli_fetch_array($op, MYSQLI_BOTH)) {
					if ($o[0] == $datosEstudianteActual[21])
						echo '<option value="' . $o[0] . '" selected>' . $o[1] . '</option>';
					else
						echo '<option value="' . $o[0] . '">' . $o[1] . '</option>';
				} ?>
			</select>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-sm-2 control-label">Estado Matricula</label>
		<div class="col-sm-4">
			<select class="form-control" name="matestM">
				<option value="">Seleccione una opción</option>
				<option value="1" <?php if (1 == $datosEstudianteActual["mat_estado_matricula"]) echo 'selected' ?>>Matriculado</option>
				<option value="2" <?php if (2 == $datosEstudianteActual["mat_estado_matricula"]) echo 'selected' ?>>Asistente </option>
				<option value="3" <?php if (3 == $datosEstudianteActual["mat_estado_matricula"]) echo 'selected' ?>>Cancelado </option>
				<option value="4" <?php if (4 == $datosEstudianteActual["mat_estado_matricula"]) echo 'selected' ?>>No matriculado </option>
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
				<option value="1" <?php if ($datosEstudianteActual['mat_estado_agno'] == 1) {
										echo "selected";
									} ?>>Ganado</option>
				<option value="2" <?php if ($datosEstudianteActual['mat_estado_agno'] == 2) {
										echo "selected";
									} ?>>Perdido</option>
				<option value="2" <?php if ($datosEstudianteActual['mat_estado_agno'] == '0') {
										echo "selected";
									} ?>>En curso</option>
			</select>
		</div>
	</div>
	<?php if (array_key_exists(10, $_SESSION["modulos"])) { ?>
		<div class="form-group row">
			<label class="col-sm-2 control-label"> Puede estar en multiples cursos? </label>
			<div class="col-sm-2">
				<select class="form-control  select2" id="tipoMatricula" name="tipoMatricula" onchange="javascript:mostrarCursosAdicionales()">
					<option value="grupal" 
					<?php if ($datosEstudianteActual['mat_tipo_matricula'] == 'grupal') {echo 'selected';} ?>
					>NO</option>
					<option value="individual" 
					<?php if ($datosEstudianteActual['mat_tipo_matricula'] == 'individual') {echo 'selected';} ?>
					>SI</option>
				</select>
			</div>
		</div>
	<?php } ?>

</fieldset>

