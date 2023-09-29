<?php
include("session.php");
require_once("../class/AjaxNotas.php");
$datosMensaje = AjaxNotas::ajaxPeriodosRegistrar($_POST["codEst"],$_COOKIE["carga"],$_POST["per"],$_POST["nota"],$_POST["notaAnterior"]);
include("../compartido/guardar-historial-acciones.php");
?>
<script type="text/javascript">
function notifica(){
	$.toast({
		heading: '<?=$datosMensaje['heading']?>',  
		text: '<?=$datosMensaje['mensaje']?>',
		position: 'botom-left',
		loaderBg:'#ff6849',
		icon: '<?=$datosMensaje['estado']?>',
		hideAfter: 3000, 
		stack: 6
	});
}
setTimeout ("notifica()", 100);
</script>

<div class="alert alert-<?=$datosMensaje['estado']?>">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<i class="icon-exclamation-sign"></i><strong>INFORMACI&Oacute;N:</strong> <?=$datosMensaje['mensaje']?>
</div>