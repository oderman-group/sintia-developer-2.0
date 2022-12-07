<?php
//Consultas a la BD
$aceptacion= mysql_query("SELECT * FROM mobiliar_sintia_admin.contratos_usuarios WHERE cxu_id_usuario='".$idSession."'",$conexion);
$datosAceptacion = mysql_fetch_array($aceptacion);

$contrato= mysql_query("SELECT * FROM mobiliar_sintia_admin.contratos WHERE cont_id='".$datosAceptacion['cxu_id_contrato']."'",$conexion);
$datosContrato = mysql_fetch_array($contrato);

//Condición para mostrar o no el modal
if($datosContrato['cont_fecha_modificacion'] > $datosAceptacion['cxu_fecha_aceptacion']){
?>

<div class="modal fade" id="modalContrato" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
	   <div class="modal-dialog"  style="max-width: 1350px!important;">
		  <div class="modal-content">
			  
			  <div class="modal-header">
				<h1 class="modal-title" align="center">Contrato de Licencia</h1>
				<a href="#" data-dismiss="modal" class="btn btn-danger" aria-label="Close" id="boton-cerrar-contrato"><i class="fa fa-window-close"></i></a>
			  </div>
			  
			 <div class="modal-body" align="center">

                <?=$datosContrato['cont_descripcion'];?>

			 </div>
			  
			 <div class="modal-footer">
				<form class="form-horizontal" action="../compartido/modal-contrato-guardar.php" method="post">
                    <input type="hidden" name="id" value="<?=$datosAceptacion['cxu_id'];?>">
					<button type="submit" class="btn btn-info">Aceptar Contrato</button>
				</form>
		 	 </div>
			  
		  </div>
	   </div>
	</div>

<?php }?>