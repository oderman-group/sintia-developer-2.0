<?php
include("session.php");
require_once("../class/AjaxNotas.php");
require_once(ROOT_PATH."/main-app/docente/verificar-carga.php");

$data = [
	'codEst'           => $_POST["codEst"],
	'nombreEst'        => null,
	'codNota'          => null,
	'nota'             => $_POST["nota"],
	'notaAnterior'     => null,
	'tipoNota'         => 2,
	'target'           => Calificaciones::TIPO_GUARDAR_NIVELACION_CARGA,
	'carga'            => $_COOKIE["carga"],
	'periodo'          => null,
	'observaciones'    => 'Nivelación.',
	'datosCargaActual' => $datosCargaActual,
];

$datosMensaje = Calificaciones::direccionarCalificacion($data);

include("../compartido/guardar-historial-acciones.php");
?>
<script type="text/javascript">
function notifica(){
	$.toast({
		heading: '<?=$datosMensaje['heading']?>',  
		text: '<?=$datosMensaje['mensaje']?>',
		position: 'bottom-right',
		showHideTransition: 'slide',
		loaderBg:'#ff6849',
		icon: '<?=$datosMensaje['iconToast']?>',
		hideAfter: 10000, 
		stack: 6
	});
}
setTimeout ("notifica()", 100);
</script>

<div class="alert alert-<?=$datosMensaje['estado']?>">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<i class="icon-exclamation-sign"></i><strong>INFORMACI&Oacute;N:</strong> <?=$datosMensaje['mensaje']?>
</div>