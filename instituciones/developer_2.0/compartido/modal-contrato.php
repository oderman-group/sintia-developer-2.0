<?php
//Consultas a la BD
$contrato= mysql_query("SELECT * FROM mobiliar_sintia_admin.contratos WHERE cont_id=1",$conexion);
$datosContrato = mysql_fetch_array($contrato);

$aceptacion= mysql_query("SELECT * FROM mobiliar_sintia_admin.contratos_usuarios WHERE cxu_id_contrato='".$datosContrato['cont_id']."'  AND cxu_id_institucion='".$config['conf_id_institucion']."'",$conexion);
$datosAceptacion = mysql_fetch_array($aceptacion);

//Condición para mostrar o no el modal
if($datosContrato['cont_fecha_modificacion'] > $datosAceptacion['cxu_fecha_aceptacion']){
?>

<div class="modal fade" id="modalContrato" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
	   <div class="modal-dialog"  style="max-width: 1350px!important;">
		  <div class="modal-content">
			  
			  <div class="modal-header">
				<h1 class="modal-title" align="center"><?=$datosContrato['cont_nombre'];?></h1>
				<a href="#" data-dismiss="modal" class="btn btn-danger" aria-label="Close" id="boton-cerrar-contrato"><i class="fa fa-window-close"></i></a>
			  </div>
			  
			 <div class="modal-body" align="center">

                <?=$datosContrato['cont_descripcion'];?>

			 </div>
			  
			 <div class="modal-footer">
				<form class="form-horizontal" action="../compartido/modal-contrato-guardar.php" method="post">
					<input type="hidden" name="idUsuario" value="<?=$idSession;?>">
                    <input type="hidden" name="id" value="<?=$datosContrato['cont_id'];?>">
					<button type="submit" class="btn btn-info">Aceptar Contrato</button>
				</form>
		 	 </div>
			  
		  </div>
	   </div>
	</div>

<?php }?>