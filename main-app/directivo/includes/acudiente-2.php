<?php if($config['conf_solicitar_acudiente_2'] === "SI"){?>
	<hr>
	<hr>
<?php
try{
	$consultaAcudiente2=mysqli_query($conexion, "SELECT * FROM usuarios WHERE uss_id='".$datosEstudianteActual["mat_acudiente2"]."'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
	$acudiente2 = mysqli_fetch_array($consultaAcudiente2, MYSQLI_BOTH);
?>  
<h2><b>ACUDIENTE 2</b></h2>
<input type="hidden" name="idAcudiente2" value="<?php if(!empty($datosEstudianteActual["mat_acudiente2"])){ echo $datosEstudianteActual["mat_acudiente2"];}?>">

<div class="form-group row">
	<label class="col-sm-2 control-label">Tipo de documento</label>
	<div class="col-sm-3">
		<?php
		try{
			$op = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".opciones_generales WHERE ogen_grupo=1");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		?>
		<select class="form-control" name="tipoDAcudiente2">
			<?php while($o = mysqli_fetch_array($op, MYSQLI_BOTH)){
				if($o[0]==$acudiente2["uss_tipo_documento"])
				echo '<option value="'.$o[0].'" selected>'.$o[1].'</option>';
			else
				echo '<option value="'.$o[0].'">'.$o[1].'</option>';	
			}?>
		</select>
	</div>
	
	<label class="col-sm-2 control-label">Documento</label>
	<div class="col-sm-3">
		<input type="text" name="documentoA2" class="form-control" autocomplete="off" value="<?php if(isset($acudiente2['uss_usuario'])){ echo $acudiente2['uss_usuario'];}?>">
	</div>
</div>
	
<div class="form-group row">
	<label class="col-sm-2 control-label">Lugar de expedición</label>
	<div class="col-sm-3">
		<select class="form-control" name="lugardA2">
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
			?>
			<option value="<?=$opg['ciu_id'];?>" <?php if(isset($acudiente2["uss_lugar_expedicion"])&&$opg['ciu_id']==$acudiente2["uss_lugar_expedicion"]){echo "selected";}?>><?=$opg['ciu_nombre'].", ".$opg['dep_nombre'];?></option>
			<?php }?>
		</select>
	</div>	

	<label class="col-sm-2 control-label">Ocupaci&oacute;n</label>
	<div class="col-sm-3">
		<input type="text" name="ocupacionA2" class="form-control" autocomplete="off" value="<?php if(isset($acudiente2["uss_ocupacion"])){ echo $acudiente2["uss_ocupacion"];}?>">
	</div>
</div>

<div class="form-group row">												
	<label class="col-sm-2 control-label">Primer Apellido</label>
	<div class="col-sm-3">
		<input type="text" name="apellido1A2" class="form-control" autocomplete="off" value="<?php if(isset($acudiente2["uss_apellido1"])){ echo $acudiente2["uss_apellido1"];}?>">
	</div>
												
	<label class="col-sm-2 control-label">Segundo Apellido</label>
	<div class="col-sm-3">
		<input type="text" name="apellido2A2" class="form-control" autocomplete="off" value="<?php if(isset($acudiente2["uss_apellido2"])){ echo $acudiente2["uss_apellido2"];}?>">
	</div>
</div>

<div class="form-group row">												
	<label class="col-sm-2 control-label">Nombre</label>
	<div class="col-sm-3">
		<input type="text" name="nombreA2" class="form-control" autocomplete="off" value="<?php if(isset($acudiente2["uss_nombre"])){ echo $acudiente2["uss_nombre"];}?>">
	</div>
													
	<label class="col-sm-2 control-label">Otro Nombre</label>
	<div class="col-sm-3">
		<input type="text" name="nombre2A2" class="form-control" autocomplete="off" value="<?php if(isset($acudiente2["uss_nombre2"])){ echo $acudiente2["uss_nombre2"];}?>">
	</div>
</div>	
	
<div class="form-group row">
	<label class="col-sm-2 control-label">Genero</label>
	<div class="col-sm-3">
		<?php
		try{
			$op = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".opciones_generales WHERE ogen_grupo=4");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		?>
		<select class="form-control" name="generoA2">
			<option value="">Seleccione una opción</option>
			<?php while($o = mysqli_fetch_array($op, MYSQLI_BOTH)){
				if($o[0]==$acudiente2[16])
					echo '<option value="'.$o[0].'" selected>'.$o[1].'</option>';
				else
					echo '<option value="'.$o[0].'">'.$o[1].'</option>';	
			}?>
		</select>
	</div>
</div>
<?php }?>