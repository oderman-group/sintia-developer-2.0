<?php
//Consultas a la BD
for($i=1;$i<=3;$i++){

	$datosTerminos = Plataforma::mostrarModalTerminos($i);
	if($datosTerminos['ttp_visible']==='SI'){

		switch($datosTerminos['ttp_id']){
			case 1:
				$modal="modalTerminos";
				break;
			case 2:
				$modal="modalTratamientos";
				break;
			case 3:
				$modal="modalPoliticas";
				break;
		}
?>

	<div class="modal fade" id="<?=$modal;?>" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
		<div class="modal-dialog"  style="max-width: 1350px!important;">
			<div class="modal-content">
				
				<div class="modal-header">
					<h1 class="modal-title" align="center"><?=$datosTerminos['ttp_nombre'];?></h1>
					<a href="#" data-dismiss="modal" class="btn btn-danger" aria-label="Close" id="boton-cerrar-<?=$modal;?>"><i class="fa fa-window-close"></i></a>
				</div>
				
				<div class="modal-body" align="center">
								
					<?=$datosTerminos['ttp_descripcion'];?>

				</div>
				
				<div class="modal-footer">
					<form class="form-horizontal" action="../compartido/modal-terminos-guardar.php" method="post">
						<input type="hidden" name="idUsuario" value="<?=$idSession;?>">
						<input type="hidden" name="id" value="<?=$datosTerminos['ttp_id'];?>">
						<button type="submit" class="btn btn-info">Aceptar Terminos</button>
					</form>
				</div>
				
			</div>
		</div>
	</div>

<?php 
	}
}
?>