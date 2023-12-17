<div class="modal fade bd-example-modal-lg" id="<?= $idModal; ?>" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" style="max-width: 1350px!important;" role="document">
		<div class="modal-content shadow" id="contenidoModal<?= $idModal; ?>" style="max-width: 1350px!important;">
			<?php include($contenido) ?>
			<div class="modal-footer">
				<a href="#" data-dismiss="modal" class="btn btn-danger">
				<i class="fa fa fa-window-close"></i> Cerrar
				</a>
			</div>
		</div>
	</div>
</div>