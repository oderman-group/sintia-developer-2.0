<?php
if (!empty($data["dataTotal"])) {
	require_once("../Estudiantes.php");
	require_once("../Modulos.php");
}

$permisoReportesNotas = Modulos::validarSubRol(['DT0238']);
$permisoedicion       = Modulos::validarSubRol(['DT0049','DT0148','DT0129']);
$permisoEditar        = Modulos::validarSubRol(['DT0049']);
$permisoEliminar      = Modulos::validarSubRol(['DT0148']);
$permisoAutologin     = Modulos::validarSubRol(['DT0129']);
$permisoHorarios      = Modulos::validarSubRol(['DT0041']);
$permisoResumen       = Modulos::validarSubRol(['DT0111']);
$permisoIndicadores   = Modulos::validarSubRol(['DT0034']);
$permisoPlanilla      = Modulos::validarSubRol(['DT0239']);
$permisoPlanillaNotas = Modulos::validarSubRol(['DT0237']);

$contReg = 1;

foreach ($data["data"] as $resultado) {
	//Para calcular el porcentaje de actividades en las cargas
	$cargaSP = $resultado['car_id'];
	$periodoSP = $resultado['car_periodo'];

	$marcaMediaTecnica = '';
	$filtroDocentesParaListarEstudiantes = " AND mat_grado='" . $resultado['car_curso'] . "' AND mat_grupo='" . $resultado['car_grupo'] . "'";
	if ($resultado['gra_tipo'] == GRADO_INDIVIDUAL) {
		$cantidadEstudiantes = $resultado['cantidad_estudaintes_mt'];
		$marcaMediaTecnica = '<i class="fa fa-bookmark" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Media técnica"></i> ';
	} else {
		$cantidadEstudiantes = $resultado['cantidad_estudaintes'];
	}

	$infoTooltipCargas = "<b>COD:</b> 
						  {$resultado['car_id']}<br>
						  <b>Director de grupo:</b> 
						  {$opcionSINO[$resultado['car_director_grupo']]}<br>
						  <b>I.H:</b> 
						  {$resultado['car_ih']}<br>
						  <b>Puede editar en otros periodos?:</b> 
						  {$opcionSINO[$resultado['car_permiso2']]}<br>
						  <b>Indicadores automáticos?:</b> 
						  {$opcionSINO[$resultado['car_indicador_automatico']]}<br>
						  <b>Max. Indicadores:</b> 
						  {$resultado['car_maximos_indicadores']}<br>
						  <b>Max. Calificaciones:</b> 
						  {$resultado['car_maximas_calificaciones']}<br>
						  <b>Nro. Estudiantes:</b> 
						  {$cantidadEstudiantes}";

	$marcaDG = '';
	if ($resultado['car_director_grupo'] == 1) {
		$marcaDG = '<i class="fa fa-star text-info" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Director de grupo"></i> ';
	}


?>

	<trt>
		<td><?= $contReg; ?></td>
		<td><a tabindex="0" role="button" data-toggle="popover" data-trigger="focus" title="Información adicional" data-content="<?= $infoTooltipCargas; ?>" data-html="true" data-placement="top" style="border-bottom: 1px dotted #000;"><?= $resultado['id_nuevo_carga']; ?></a></td>
		<td><?= $marcaDG . "" . strtoupper($resultado['uss_nombre'] . " " . $resultado['uss_nombre2'] . " " . $resultado['uss_apellido1'] . " " . $resultado['uss_apellido2']); ?></td>
		<td><?= $marcaMediaTecnica . "[" . $resultado['gra_id'] . "] " . strtoupper($resultado['gra_nombre'] . " " . $resultado['gru_nombre']); ?></td>
		<td><?= "[" . $resultado['mat_id'] . "] " . strtoupper($resultado['mat_nombre']) . " (" . $resultado['mat_valor'] . "%)"; ?></td>
		<td><?= $resultado['car_ih']; ?></td>
		<td><?= $resultado['car_periodo']; ?></td>
		<?php
		$porcentajeCargas =  $resultado['actividades'] . "%&nbsp;&nbsp;-&nbsp;&nbsp;" . $resultado['actividades_registradas'] . "%";
		if ($permisoReportesNotas) {
			$porcentajeCargas = '<a href="../compartido/reporte-notas.php?carga=' . base64_encode($resultado['car_id']) . '&per=' . base64_encode($resultado['car_periodo']) . '&grado=' . base64_encode($resultado["car_curso"]) . '&grupo=' . base64_encode($resultado["car_grupo"]) . '" target="_blank" style="text-decoration:underline; color:#00F;" title="Calificaciones">' . $resultado['actividades'] . '%&nbsp;&nbsp;-&nbsp;&nbsp;' .$resultado['actividades_registradas'] . '%</a>';
		}
		?>
		<td><?= $porcentajeCargas ?></td>
		<td>
			<div class="btn-group">
				<button type="button" class="btn btn-primary"><?= $frases[54][$datosUsuarioActual['uss_idioma']]; ?></button>
				<button type="button" class="btn btn-primary dropdown-toggle m-r-20" data-toggle="dropdown">
					<i class="fa fa-angle-down"></i>
				</button>
				<ul class="dropdown-menu" role="menu" style="z-index: 9000;">
					<?php if (Modulos::validarPermisoEdicion() && $permisoReportesNotas) { ?>
						<?php if ($permisoEditar) { ?>
							<li><a href="cargas-editar.php?idR=<?= base64_encode($resultado['car_id']); ?>"><?= $frases[165][$datosUsuarioActual['uss_idioma']]; ?></a></li>
						<?php }
						if ($config['conf_permiso_eliminar_cargas'] == 'SI' && $permisoEliminar) { ?>
							<li>
								<a href="javascript:void(0);" title="Eliminar" onClick="sweetConfirmacion('Alerta!','Deseas eliminar esta accion?','question','cargas-eliminar.php?id=<?= base64_encode($resultado['car_id']); ?>')"><?= $frases[174][$datosUsuarioActual['uss_idioma']]; ?></a>
							</li>
						<?php }
						if ($permisoAutologin) { ?>
							<li>
								<a href="javascript:void(0);" onClick="sweetConfirmacion('Alerta!','Esta acción te permitirá entrar como docente y ver todos los detalles de esta carga. Deseas continuar?','question','auto-login.php?user=<?= base64_encode($resultado['car_docente']); ?>&tipe=<?= base64_encode(2) ?>&carga=<?= base64_encode($resultado['car_id']); ?>&periodo=<?= base64_encode($resultado['car_periodo']); ?>')">Ver como docente</a>
							<?php }
					}
					if ($permisoAutologin) { ?>
							<li><a href="cargas-horarios.php?id=<?= base64_encode($resultado['car_id']); ?>" title="Ingresar horarios">Ingresar Horarios</a></li>
						<?php }
					if ($permisoHorarios) { ?>
							<li><a href="periodos-resumen.php?carga=<?= base64_encode($resultado['car_id']); ?>" title="Resumen Periodos"><?= $frases[84][$datosUsuarioActual['uss_idioma']]; ?></a></li>
						<?php }
					if ($permisoIndicadores) { ?>
							<li><a href="cargas-indicadores.php?carga=<?= base64_encode($resultado['car_id']); ?>&docente=<?= base64_encode($resultado['car_docente']); ?>">Indicadores</a></li>
						<?php } ?>
						<?php if ($permisoPlanilla) { ?>
							<li><a href="../compartido/planilla-docentes.php?carga=<?= base64_encode($resultado['car_id']); ?>" target="_blank">Ver Planilla</a></li>
						<?php }
						if ($permisoPlanillaNotas) { ?>
							<li><a href="../compartido/planilla-docentes-notas.php?carga=<?= base64_encode($resultado['car_id']); ?>" target="_blank">Ver Planilla con notas</a></li>
						<?php } ?>
				</ul>
			</div>
		</td>
		</tr>
	<?php $contReg++;
} ?>