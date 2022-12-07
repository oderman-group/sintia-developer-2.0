<?php
//Consultas a la BD
$terminos= mysql_query("SELECT * FROM terminos_tratamiento_politica WHERE ttp_id=1",$conexion);
$datosTerminos = mysql_fetch_array($terminos);

$aceptacion= mysql_query("SELECT * FROM terminos_tratamiento_politicas_usuarios WHERE ttpxu_id_usuario='".$idSession."' AND ttpxu_id_termino_tratamiento_politicas='".$datosTerminos['ttp_id']."'",$conexion);
$datosAceptacion = mysql_fetch_array($aceptacion);

//Condición para mostrar o no el modal
if($datosTerminos['ttp_fecha_modificacion'] > $datosAceptacion['ttpxu_fecha_aceptacion']){
?>

<div class="modal fade" id="modalTerminos" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
	   <div class="modal-dialog"  style="max-width: 1350px!important;">
		  <div class="modal-content">
			  
			  <div class="modal-header">
				<h1 class="modal-title" align="center"><?=$datosTerminos['ttp_nombre'];?></h1>
				<a href="#" data-dismiss="modal" class="btn btn-danger" aria-label="Close" id="boton-cerrar-terminos"><i class="fa fa-window-close"></i></a>
			  </div>
			  
			 <div class="modal-body" align="center">
                               
			 <?=$datosTerminos['ttp_descripcion'];?>

			 </div>
			  
			 <div class="modal-footer">
			 	<form class="form-horizontal" action="../compartido/modal-Terminos-guardar.php" method="post">
					<input type="hidden" name="idUsuario" value="<?=$idSession;?>">
					<input type="hidden" name="id" value="<?=$datosTerminos['ttp_id'];?>">
					<button type="submit" class="btn btn-info">Aceptar Terminos</button>
				</form>
		 	 </div>
			  
		  </div>
	   </div>
	</div>

<?php }?>

<?php
//Consultas a la BD
$terminos= mysql_query("SELECT * FROM terminos_tratamiento_politica WHERE ttp_id=2",$conexion);
$datosTerminos = mysql_fetch_array($terminos);

$aceptacion= mysql_query("SELECT * FROM terminos_tratamiento_politicas_usuarios WHERE ttpxu_id_usuario='".$idSession."' AND ttpxu_id_termino_tratamiento_politicas='".$datosTerminos['ttp_id']."'",$conexion);
$datosAceptacion = mysql_fetch_array($aceptacion);

//Condición para mostrar o no el modal
if($datosTerminos['ttp_fecha_modificacion'] > $datosAceptacion['ttpxu_fecha_aceptacion']){
?>

<div class="modal fade" id="modalTratamientos" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
	   <div class="modal-dialog"  style="max-width: 1350px!important;">
		  <div class="modal-content">
			  
			  <div class="modal-header">
				<h1 class="modal-title" align="center"><?=$datosTerminos['ttp_nombre'];?></h1>
				<a href="#" data-dismiss="modal" class="btn btn-danger" aria-label="Close" id="boton-cerrar-terminos"><i class="fa fa-window-close"></i></a>
			  </div>
			  
			 <div class="modal-body" align="center">
                               
			 <?=$datosTerminos['ttp_descripcion'];?>

			 </div>
			  
			 <div class="modal-footer">
			 	<form class="form-horizontal" action="../compartido/modal-Terminos-guardar.php" method="post">
					<input type="hidden" name="idUsuario" value="<?=$idSession;?>">
					<input type="hidden" name="id" value="<?=$datosTerminos['ttp_id'];?>">
					<button type="submit" class="btn btn-info">Aceptar Tratamiento de datos</button>
				</form>
		 	 </div>
			  
		  </div>
	   </div>
	</div>

<?php }?>

<?php
//Consultas a la BD
$terminos= mysql_query("SELECT * FROM terminos_tratamiento_politica WHERE ttp_id=3",$conexion);
$datosTerminos = mysql_fetch_array($terminos);

$aceptacion= mysql_query("SELECT * FROM terminos_tratamiento_politicas_usuarios WHERE ttpxu_id_usuario='".$idSession."' AND ttpxu_id_termino_tratamiento_politicas='".$datosTerminos['ttp_id']."'",$conexion);
$datosAceptacion = mysql_fetch_array($aceptacion);

//Condición para mostrar o no el modal
if($datosTerminos['ttp_fecha_modificacion'] > $datosAceptacion['ttpxu_fecha_aceptacion']){
?>

<div class="modal fade" id="modalPoliticas" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
	   <div class="modal-dialog"  style="max-width: 1350px!important;">
		  <div class="modal-content">
			  
			  <div class="modal-header">
				<h1 class="modal-title" align="center"><?=$datosTerminos['ttp_nombre'];?></h1>
				<a href="#" data-dismiss="modal" class="btn btn-danger" aria-label="Close" id="boton-cerrar-terminos"><i class="fa fa-window-close"></i></a>
			  </div>
			  
			 <div class="modal-body" align="center">
                               
			 <?=$datosTerminos['ttp_descripcion'];?>

			 </div>
			  
			 <div class="modal-footer">
			 	<form class="form-horizontal" action="../compartido/modal-Terminos-guardar.php" method="post">
					<input type="hidden" name="idUsuario" value="<?=$idSession;?>">
					<input type="hidden" name="id" value="<?=$datosTerminos['ttp_id'];?>">
					<button type="submit" class="btn btn-info">Aceptar Politicas</button>
				</form>
		 	 </div>
			  
		  </div>
	   </div>
	</div>

<?php }?>