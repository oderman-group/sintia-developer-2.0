<?php
if (!empty($data["dataTotal"])) {
	include(ROOT_PATH . "/config-general/config-admisiones.php");
	require_once("../Estudiantes.php");
}
$contReg = 1;
foreach ($data["data"] as $resultado) {
	$observacion = strip_tags($resultado['asp_observacion']);
	$infoTooltipEstudiante = "<b>Nombre acudiente:</b><br>
                          {$resultado['asp_nombre_acudiente']}<br>
                          <b>Celular:</b><br>
                          {$resultado['asp_celular_acudiente']}<br>
                          <b>Documento:</b><br>
                          {$resultado['asp_documento_acudiente']}<br>
                          <b>Email:</b><br>
                          {$resultado['asp_email_acudiente']}<br><br>
                          <b>Observación:</b><br>
						  <span style='color:darkblue; font-size:11px; font-style:italic;'>{$observacion}</span>";
?>
	<tr id="registro_<?= $resultado["asp_id"]; ?>" class="odd gradeX">
		<td><?= $resultado["mat_id"]; ?></td>
		<td><?= $resultado["asp_id"]; ?></td>
		<td><?= $resultado["asp_fecha"]; ?></td>
		<td><?= $resultado["mat_documento"]; ?></td>
		<td><a tabindex="0" role="button" data-toggle="popover" data-trigger="focus" title="<?= Estudiantes::NombreCompletoDelEstudiante($resultado); ?>" data-content="<?= $infoTooltipEstudiante; ?>" data-html="true" data-placement="top" style="border-bottom: 1px dotted #000;"><?= Estudiantes::NombreCompletoDelEstudiante($resultado); ?></a></td>
		<td><?= $resultado["asp_agno"]; ?></td>
		<td><span style="background-color: <?= $fondoSolicitud[$resultado["asp_estado_solicitud"]]; ?>; padding: 5px;"><?= $estadosSolicitud[$resultado["asp_estado_solicitud"]]; ?></span></td>
		<td><a href="../admisiones/files/comprobantes/<?= $resultado["asp_comprobante"]; ?>" target="_blank" style="text-decoration: underline;"><?= $resultado["asp_comprobante"]; ?></a></td>
		<td><?= $resultado["gra_nombre"]; ?></td>
		<td>
			<div class="btn-group">
				<button type="button" class="btn btn-primary"><?= $frases[54][$datosUsuarioActual['uss_idioma']]; ?></button>
				<button type="button" class="btn btn-primary dropdown-toggle m-r-20" data-toggle="dropdown">
					<i class="fa fa-angle-down"></i>
				</button>
				<ul class="dropdown-menu" role="menu">
					<li><a href="inscripciones-formulario.php?token=<?= md5($resultado["asp_id"]); ?>&id=<?= base64_encode($resultado["asp_id"]); ?>&idInst=<?= base64_encode($config["conf_id_institucion"]) ?>" target="_blank">Ver información</a></li>
					<li><a href="inscripciones-formulario-editar.php?token=<?= md5($resultado["asp_id"]); ?>&id=<?= base64_encode($resultado["asp_id"]); ?>&idInst=<?= base64_encode($config["conf_id_institucion"]) ?>" target="_blank">Editar</a></li>

					<?php if ($resultado["asp_estado_solicitud"] == 6) { ?>

						<li><a href="javascript:void(0);" onClick="sweetConfirmacion('Alerta!','Va a eliminar la documentación de este aspirante. Recuerde descargarla primero. Esta acción es irreversible. Desea continuar?','question','inscripciones-eliminar-documentacion.php?matricula=<?= base64_encode($resultado["mat_id"]); ?>')">Borrar documentación</a></li>

						<?php if (!empty($configAdmisiones["cfgi_year_inscripcion"]) && $configAdmisiones["cfgi_year_inscripcion"] == $yearEnd && $configAdmisiones["cfgi_year_inscripcion"] != $agnoBD) { ?>

							<li><a href="javascript:void(0);" onClick="sweetConfirmacion('Alerta!','Va a pasar este estudiante al <?= $configAdmisiones["cfgi_year_inscripcion"]; ?>. Desea continuar?','question','inscripciones-pasar-estudiante.php?matricula=<?= base64_encode($resultado["mat_id"]); ?>')">Pasar a <?= $configAdmisiones["cfgi_year_inscripcion"]; ?></a></li>

					<?php }
					} ?>

					<?php if ($resultado["asp_estado_solicitud"] == 1 or $resultado["asp_estado_solicitud"] == 2 or $resultado["asp_estado_solicitud"] == 7) { ?>
						<li><a href="javascript:void(0);" onClick="sweetConfirmacion('Alerta!','Va a eliminar este aspirante. Esta acción es irreversible. Desea continuar?','question','inscripciones-eliminar-aspirante.php?matricula=<?= base64_encode($resultado["mat_id"]); ?>')">Eliminar aspirante</a></li>
					<?php } ?>
					
					<li><a href="javascript:void(0);" onClick="sweetConfirmacion('Alerta!', 'Va a ocultar a este aspirante del listado. Desea continuar?', 'question','inscripciones-ocultar-aspirante.php?matricula=<?= base64_encode($resultado["mat_id"]); ?>&aspirante=<?= base64_encode($resultado["asp_id"]); ?>', true, <?=$resultado["asp_id"];?>)">Ocultar aspirante</a></li>
				</ul>
			</div>
		</td>
	</tr>

<?php $contReg++;
} ?>