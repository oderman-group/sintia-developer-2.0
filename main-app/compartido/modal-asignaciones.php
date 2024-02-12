<?php
if($numAsignacionesEncuesta > 0  && ($idPaginaInterna != 'DC0146' && $idPaginaInterna != 'AC0038' && $idPaginaInterna != 'ES0062' && $idPaginaInterna != 'DT0324')){
?>

	<div class="modal fade" id="modalAsignaciones" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	   <div class="modal-dialog"  style="max-width: 1350px!important;">
		  <div class="modal-content">

			<div class="modal-header">
				<h1 class="modal-title" align="center">ENCUESTAS PENDIENTES</h1>
				<?php if($asignacionesObligatorias < 1){ ?>
					<a href="#" data-dismiss="modal" class="btn btn-danger" aria-label="Close" id="boton-cerrar-asignaciones"><i class="fa fa-window-close"></i></a>
				<?php } ?>
			</div>

			<div class="modal-body" align="center">

				<p>Hola! <b><?=$datosUsuarioActual['uss_nombre'];?></b><br>
				Usted tiene <b><?=$numAsignacionesEncuesta;?> encuestas por completar,</b><br>
				de las cuales <b><?=$asignacionesObligatorias;?> son obligatorias</b>, para ver las encuestas pendientes de click en el botón <b>"VER ENCUESTAS"</b></p>

			</div>

			<div class="modal-footer">
                <a href="encuestas-pendientes.php" class="btn btn-info">VER ENCUESTAS</a>
				<?php if($asignacionesObligatorias < 1){ ?>
					<a href="#" data-dismiss="modal" class="btn btn-danger" id="boton-cerrar-asignaciones-2">CERRAR</a>
				<?php } ?>
			</div>

		  </div>
	   </div>
	</div>

<?php }?>