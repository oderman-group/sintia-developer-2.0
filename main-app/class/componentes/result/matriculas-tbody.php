<?php
if (!empty($data["dataTotal"])) {
	require_once(ROOT_PATH . "/main-app/class/UsuariosPadre.php");
	require_once(ROOT_PATH . "/main-app/class/Estudiantes.php");
	require_once(ROOT_PATH . "/main-app/class/Modulos.php");
	require_once(ROOT_PATH . "/main-app/compartido/sintia-funciones.php");
}

$contReg = 1;

foreach ($data["data"] as $resultado) {
	$usuariosClase = new UsuariosFunciones;
	$acudiente = isset($resultado["mat_acudiente"]) ? UsuariosPadre::sesionUsuario($resultado["mat_acudiente"]) : null;

	$bgColor = $resultado['uss_bloqueado'] == 1 ? 'style="background-color: #ff572238;"' : '';

	$cheked = '';
	if ($resultado['uss_bloqueado'] == 1) {
		$cheked = 'checked';
	}

	$color = $resultado["mat_inclusion"] == 1 ? 'style="color: blue;' : '';

	$nombreAcudiente = '';
	$idAcudiente = '';
	if (isset($acudiente['uss_id'])) {
		$nombreAcudiente = UsuariosPadre::nombreCompletoDelUsuario($acudiente);
		$idAcudiente = $acudiente['uss_id'];
	}

	$marcaMediaTecnica = '';
	if ($resultado['mat_tipo_matricula'] == GRADO_INDIVIDUAL && array_key_exists(10, $arregloModulos) && Modulos::validarModulosActivos($conexion, 10)) {
		$marcaMediaTecnica = '<i class="fa fa-bookmark" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Media técnica"></i> ';
	}

	$miArray = [
		'id_estudiante'    => $resultado['mat_id'],
		'estado_matricula' => $resultado['mat_estado_matricula'],
		'bloqueado' 	   => $resultado['uss_bloqueado'],
		'id_usuario'       => $resultado['uss_id'],
	];
	$dataParaJavascript = json_encode($miArray);

	$fotoEstudiante = $usuariosClase->verificarFoto($resultado['mat_foto']);

	$infoTooltipEstudiante = "
														<p>
															<img src='{$fotoEstudiante}' class='img-thumbnail' width='120px;' height='120px;'>
														</p>
														<b>Fecha de matrícula:</b><br>
														{$resultado['mat_fecha']}<br>
														<b>Teléfono:</b><br>
														{$resultado['mat_telefono']}<br>
														<b>Documento:</b><br>
														{$resultado['mat_documento']}<br>
														<b>Email:</b><br>
														{$resultado['mat_email']}<br>
														<b>Fecha de nacimiento:</b><br>
														{$resultado['mat_fecha_nacimiento']}
														";
?>
	<tr id="EST<?= $resultado['mat_id']; ?>" <?= $bgColor; ?>>
		<td>
			<?php if ($resultado["mat_compromiso"] == 1) { ?>
				<a href="javascript:void(0);" title="Activar para la matricula" onClick="sweetConfirmacion('Alerta!','Deseas ejecutar esta accion?','question','estudiantes-activar.php?id=<?= base64_encode($resultado["mat_id"]); ?>')"><img src="../files/iconos/agt_action_success.png" height="20" width="20"></a>
			<?php } else { ?>
				<a href="javascript:void(0);" title="Bloquear para la matricula" onClick="sweetConfirmacion('Alerta!','Deseas ejecutar esta accion?','question','estudiantes-bloquear.php?id=<?= base64_encode($resultado["mat_id"]); ?>')"><img src="../files/iconos/msn_blocked.png" height="20" width="20"></a>
			<?php } ?>
			<?= $resultado["mat_id"]; ?>
		</td>
		<td>
			<?php if (!empty($resultado['uss_usuario']) && Modulos::validarSubRol(['DT0087'])) { ?>
				<div class="input-group spinner col-sm-10" style="padding-top: 5px;">
					<label class="switchToggle">
						<input type="checkbox" value="1" onChange='cambiarBloqueo(<?= $dataParaJavascript; ?>)' <?= $cheked; ?>>
						<span class="slider red round"></span>
					</label>
				</div>
			<?php } ?>
		</td>
		<td>
			<?php
			$cambiarEstado = '';
			if (Modulos::validarSubRol(['DT0217'])) {
				$cambiarEstado = "onclick='cambiarEstadoMatricula(" . $dataParaJavascript . ")'";
			}
			?>
			<a style="cursor: pointer;" id="estadoMatricula<?= $resultado['mat_id']; ?>" <?= $cambiarEstado; ?>>
				<span class="<?= $estadosEtiquetasMatriculas[$resultado['mat_estado_matricula']]; ?>">
					<?= $estadosMatriculasEstudiantes[$resultado['mat_estado_matricula']]; ?>
				</span>
			</a>
		</td>
		<td><?= $resultado['mat_documento']; ?></td>
		<?php $nombre = Estudiantes::NombreCompletoDelEstudiante($resultado); ?>

		<td <?= $color; ?>><?= $marcaMediaTecnica; ?><a tabindex="0" role="button" data-toggle="popover" data-trigger="focus" title="<?= Estudiantes::NombreCompletoDelEstudiante($resultado); ?>" data-content="<?= $infoTooltipEstudiante; ?>" data-html="true" data-placement="top" style="border-bottom: 1px dotted #000;"><?= $nombre; ?></a></td>
		<td><?= strtoupper($resultado['gra_nombre'] . " " . $resultado['gru_nombre']); ?></td>
		<td><?= $resultado['uss_usuario']; ?></td>
		<?php
		$editarAcudiente = $nombreAcudiente;
		if (Modulos::validarSubRol(['DT0124'])) {
			$editarAcudiente = '<a href="usuarios-editar.php?id=' . base64_encode($idAcudiente) . '" style="text-decoration: underline;">' . $nombreAcudiente . '</a>';
		}
		?>
		<td><?= $editarAcudiente; ?>
			<?php if (!empty($acudiente['uss_id']) && !empty($nombreAcudiente)) { ?>
				<br><a href="mensajes-redactar.php?para=<?= base64_encode($acudiente['uss_id']); ?>" style="text-decoration:underline; color:blue;">Enviar mensaje</a>
			<?php } ?>
		</td>

		<td>
			<div class="btn-group">
				<button type="button" class="btn btn-primary"><?= $frases[54][$datosUsuarioActual['uss_idioma']]; ?></button>
				<button type="button" class="btn btn-primary dropdown-toggle m-r-20" data-toggle="dropdown">
					<i class="fa fa-angle-down"></i>
				</button>
				<ul class="dropdown-menu" role="menu" style="z-index: 10000;">
					<?php if (Modulos::validarPermisoEdicion()) { ?>
						<?php if (Modulos::validarSubRol(['DT0078'])) { ?>
							<li><a href="estudiantes-editar.php?id=<?= base64_encode($resultado['mat_id']); ?>"><?= $frases[165][$datosUsuarioActual['uss_idioma']]; ?> matrícula</a></li>
						<?php } ?>

						<?php if ($config['conf_id_institucion'] == ICOLVEN && Modulos::validarSubRol(['DT0218'])) { ?>
							<li><a href="javascript:void(0);" onClick="sweetConfirmacion('Alerta!','Esta seguro que desea transferir este estudiante a SION?','question','estudiantes-crear-sion.php?id=<?= base64_encode($resultado['mat_id']); ?>')">Transferir a SION</a></li>
						<?php } ?>

						<?php if (array_key_exists(4, $arregloModulos) && Modulos::validarModulosActivos($conexion, 4) && !empty($resultado['uss_id']) && Modulos::validarSubRol(['DT0124'])) { ?>
							<li><a href="usuarios-editar.php?id=<?= base64_encode($resultado['uss_id']); ?>"><?= $frases[165][$datosUsuarioActual['uss_idioma']]; ?> usuario</a></li>
						<?php } ?>


						<?php if (!empty($resultado['gra_nombre']) && Modulos::validarSubRol(['DT0083'])) { ?>
							<li><a href="javascript:void(0);" data-toggle="modal" data-target="#cambiarGrupoModal<?= $resultado['mat_id']; ?>">Cambiar de grupo</a></li>
						<?php } ?>
						<?php if (Modulos::validarSubRol(['DT0074']) && !empty($resultado['mat_id'])) {
							$retirarRestaurar = 'Retirar';
							if ($resultado['mat_estado_matricula'] == CANCELADO) {
								$retirarRestaurar = 'Restaurar';
							}
						?>
							<li><a href="javascript:void(0);" data-toggle="modal" data-target="#retirarModal<?= $resultado['mat_id']; ?>"><?= $retirarRestaurar ?></a></li>
						<?php } ?>
						<?php if (!empty($resultado['mat_grado']) && !empty($resultado['mat_grupo']) && Modulos::validarSubRol(['DT0219'])) { ?>
							<li><a href="javascript:void(0);" onClick="sweetConfirmacion('Alerta!','Esta seguro que desea reservar el cupo para este estudiante?','question','estudiantes-reservar-cupo.php?idEstudiante=<?= base64_encode($resultado['mat_id']); ?>')">Reservar cupo</a></li>
						<?php } ?>

						<?php if (Modulos::validarSubRol(['DT0162'])) { ?>
							<li><a href="javascript:void(0);" onClick="sweetConfirmacion('Alerta!','Esta seguro de ejecutar esta acción?','question','estudiantes-eliminar.php?idE=<?= base64_encode($resultado["mat_id"]); ?>&idU=<?= base64_encode($resultado["mat_id_usuario"]); ?>')">Eliminar</a></li>
						<?php } ?>

						<?php if (Modulos::validarSubRol(['DT0220'])) { ?>
							<li><a href="javascript:void(0);" onClick="sweetConfirmacion('Alerta!','Está seguro de ejecutar esta acción?','question','estudiantes-crear-usuario-estudiante.php?id=<?= base64_encode($resultado["mat_id"]); ?>')">Generar usuario</a></li>
						<?php } ?>

						<?php if (!empty($resultado['uss_usuario']) && Modulos::validarSubRol(['DT0129'])) { ?>
							<li><a href="auto-login.php?user=<?= base64_encode($resultado['mat_id_usuario']); ?>&tipe=<?= base64_encode(4) ?>">Autologin</a></li>
						<?php } ?>

					<?php } ?>

					<?php if (!empty($resultado['mat_grado']) && !empty($resultado['mat_grupo'])) { ?>
						<?php if (Modulos::validarSubRol(['DT0224']) && ($resultado['mat_estado_matricula'] != NO_MATRICULADO && $resultado['mat_estado_matricula'] != EN_INSCRIPCION)) { ?>
							<li><a href="../compartido/matricula-boletin-curso-<?= $resultado['gra_formato_boletin']; ?>.php?id=<?= base64_encode($resultado["mat_id"]); ?>&periodo=<?= base64_encode($config[2]); ?>" target="_blank">Boletín</a></li>
						<?php } ?>
						<?php if (Modulos::validarSubRol(['DT0247'])) { ?>
							<li><a href="../compartido/matricula-libro-curso-<?= $config['conf_libro_final'] ?>.php?id=<?= base64_encode($resultado["mat_id"]); ?>&periodo=<?= base64_encode($config[2]); ?>" target="_blank">Libro Final</a></li>
						<?php } ?>
						<?php if (Modulos::validarSubRol(['DT0248'])) { ?>
							<li><a href="../compartido/informe-parcial.php?estudiante=<?= base64_encode($resultado["mat_id"]); ?>" target="_blank">Informe parcial</a></li>
						<?php } ?>
					<?php } ?>

					<?php if (!empty($resultado['mat_matricula']) && Modulos::validarSubRol(['DT0249'])) { ?>
						<li><a href="../compartido/matriculas-formato3.php?ref=<?= base64_encode($resultado["mat_matricula"]); ?>" target="_blank">Hoja de matrícula</a></li>
					<?php } ?>

					<?php if ($config['conf_id_institucion'] == ICOLVEN && !empty($resultado['mat_codigo_tesoreria'])) { ?>
						<li><a href="http://sion.icolven.edu.co/Services/ServiceIcolven.svc/GenerarEstadoCuenta/<?= $resultado['mat_codigo_tesoreria']; ?>/<?= date('Y'); ?>" target="_blank">SION - Estado de cuenta</a></li>
					<?php } ?>

					<?php if (!empty($resultado['uss_usuario'])) { ?>
						<?php if (Modulos::validarSubRol(['DT0023'])) { ?>
							<li><a href="aspectos-estudiantiles.php?idR=<?= base64_encode($resultado['mat_id_usuario']); ?>">Ficha estudiantil</a></li>
						<?php }
						if (array_key_exists(2, $arregloModulos) && Modulos::validarModulosActivos($conexion, 2) && Modulos::validarSubRol(['DT0093'])) { ?>
							<li><a href="finanzas-cuentas.php?id=<?= base64_encode($resultado["mat_id_usuario"]); ?>" target="_blank">Estado de cuenta</a></li>
						<?php }
						if (array_key_exists(3, $arregloModulos) && Modulos::validarModulosActivos($conexion, 3) && Modulos::validarSubRol(['DT0117'])) { ?>
							<li><a href="reportes-lista.php?est=<?= base64_encode($resultado["mat_id_usuario"]); ?>&filtros=<?= base64_encode(1); ?>" target="_blank">Disciplina</a></li>
					<?php }
					} ?>
				</ul>
			</div>
			<?php
				$_GET["id"] = base64_encode($resultado['mat_id']);
				if (!empty($resultado['gra_nombre'])) {
					$idModal = "cambiarGrupoModal" . $resultado['mat_id'];
					$contenido = ROOT_PATH."/main-app/directivo/estudiantes-cambiar-grupo-modal.php";
					include(ROOT_PATH."/main-app/compartido/contenido-modal.php");
					
				}

				$idModal = "retirarModal" . $resultado['mat_id'];
				$contenido = ROOT_PATH."/main-app/directivo/estudiantes-retirar-modal.php";
				include(ROOT_PATH."/main-app/compartido/contenido-modal.php");
			?>
		</td>
	</tr>
<?php
	$contReg++;
}
?> 

<script>
	cargarPopover();	
</script>
