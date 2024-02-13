<?php
include("session-compartida.php");
$idPaginaInterna = 'CM0060';
require_once(ROOT_PATH . "/main-app/class/EvaluacionGeneral.php");
require_once(ROOT_PATH . "/main-app/class/PreguntaGeneral.php");
include("../compartido/historial-acciones-guardar.php");
include("../compartido/head.php");

$id = "";
if (!empty($_GET["id"])) {
	$id = base64_decode($_GET["id"]);
}
$evaluacion = EvaluacionGeneral::consultar($id);
?>
</head>
<!-- END HEAD -->
<?php include("../compartido/body.php"); ?>
<div class="page-wrapper">
	<?php include("../compartido/panel-color.php"); ?>
	<!-- start page container -->
	<div class="page-container">
		<div class="page-content">
			<div class="page-bar">
				<div class="page-title-breadcrumb">
					<div class=" pull-left">
						<div class="page-title"><?= $evaluacion['evag_nombre']; ?></div>
					</div>
					<ol class="breadcrumb page-breadcrumb pull-right">
						<li class="active"><?= $evaluacion['evag_nombre']; ?></li>
					</ol>
				</div>
			</div>

			<div class="row">
				<div class="col-md-3">
					<!-- BEGIN PROFILE SIDEBAR -->
					<div class="profile-sidebar" style="position: sticky; top:0;">
						<div class="card">
							<div class="card-head card-topline-aqua">
								<header><?= $evaluacion['evag_nombre']; ?></header>
							</div>
							<div class="card-body no-padding height-9">
								<div class="profile-desc">
									<?= $evaluacion['evag_descripcion']; ?>
								</div>
							</div>
						</div>
					</div>
					<!-- END BEGIN PROFILE SIDEBAR -->
				</div>
				<div class="col-md-8">
					<form name="evaluacionEstudiante" action="evaluaciones-guardar-respuesta.php" method="post" enctype="multipart/form-data">
						<input type="hidden" name="id" value="<?= $id; ?>">
						<?php
						$contPreguntas = 1;
						$preguntasConsulta = EvaluacionGeneral::traerPreguntasEvaluacion($conexion, $config, $id);
						while ($preguntas = mysqli_fetch_array($preguntasConsulta, MYSQLI_BOTH)) {
							$respuestasConsulta = PreguntaGeneral::traerRespuestasPreguntas($conexion, $config, $preguntas['pregg_id']);
						?>
							<div class="panel">
								<header class="panel-heading panel-heading-blue"><?=$preguntas['pregg_descripcion']; ?> </header>
								<div class="panel-body">
									<input type="hidden" value="<?= $preguntas['pregg_id']; ?>" name="P<?= $contPreguntas; ?>">
									<?php
										$contRespuestas = 1;
										while ($respuestas = mysqli_fetch_array($respuestasConsulta, MYSQLI_BOTH)) {
									?>
										<div>
											<?php if ($preguntas['pregg_tipo_pregunta'] == MULTIPLE || $preguntas['pregg_tipo_pregunta'] == SINGLE) { ?>
												<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="option-<?= $contPreguntas; ?><?= $contRespuestas; ?>">
													<input type="radio" id="option-<?= $contPreguntas; ?><?= $contRespuestas; ?>" class="mdl-radio__button" name="R<?= $contPreguntas; ?>" value="<?=$respuestas['resg_id']; ?>">
												</label>
												<span class="mdl-radio__label"><?=$respuestas['resg_descripcion']; ?></span>
											<?php } ?>
										</div>
										<hr>
									<?php
										$contRespuestas++;
									}
									?>
									<?php if ($preguntas['pregg_tipo_pregunta'] == TEXT) { ?>
										<div>
											<textarea cols="100" id="option-<?= $contPreguntas; ?><?= $contRespuestas; ?>" name="R<?= $contPreguntas; ?>" rows="8" placeholder="Escribe tu respuesta" style="margin-top: 0px; margin-bottom: 0px; height: 100px; resize: none;"></textarea>
										</div>
									<?php } ?>
								</div>
							</div>
						<?php
							$contPreguntas++;
						}
						$enlace = UsuariosPadre::verificarTipoUsuario($datosUsuarioActual['uss_tipo'], "encuestas-pendientes.php");
						?>
						<hr>
						<div align="right">
							<a href="<?=$enlace?>" style="margin-bottom: 20px;" class="btn btn-primary" onClick="if(!confirm('Te recomendamos verificar que todas las preguntas estén contestadas antes de enviar. Si ya lo hiciste puedes continuar. Deseas terminar con la encuesta?')){return false;}">Terminar Encuesta</a>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- end page container -->
		<?php include("../compartido/footer.php"); ?>
	</div>
	<!-- start js include path -->
	<script src="../../config-general/assets/plugins/jquery/jquery.min.js"></script>
	<script src="../../config-general/assets/plugins/popper/popper.js"></script>
	<script src="../../config-general/assets/plugins/jquery-blockui/jquery.blockui.min.js"></script>
	<script src="../../config-general/assets/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
	<!-- bootstrap -->
	<script src="../../config-general/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<script src="../../config-general/assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
	<!-- Common js-->
	<script src="../../config-general/assets/js/app.js"></script>

	<!-- notifications -->
	<script src="../../config-general/assets/plugins/jquery-toast/dist/jquery.toast.min.js"></script>
	<script src="../../config-general/assets/plugins/jquery-toast/dist/toast.js"></script>

	<script src="../../config-general/assets/js/layout.js"></script>
	<script src="../../config-general/assets/js/theme-color.js"></script>
	<!-- Material -->
	<script src="../../config-general/assets/plugins/material/material.min.js"></script>
	<script src="../../config-general/assets/js/pages/material-select/getmdl-select.js"></script>
	<script src="../../config-general/assets/plugins/material-datetimepicker/moment-with-locales.min.js"></script>
	<script src="../../config-general/assets/plugins/material-datetimepicker/bootstrap-material-datetimepicker.js"></script>
	<script src="../../config-general/assets/plugins/material-datetimepicker/datetimepicker.js"></script>
	<!-- end js include path -->
	</body>

	<!-- Mirrored from radixtouch.in/templates/admin/smart/source/light/course_details.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 18 May 2018 17:31:36 GMT -->

	</html>