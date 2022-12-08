<?php
//Consultas a la BD


//Condición para mostrar o no el modal
if(true){
?>

<div class="modal fade" id="modalTerminos" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
	   <div class="modal-dialog"  style="max-width: 1350px!important;">
		  <div class="modal-content">
			  
			  <div class="modal-header">
				<h1 class="modal-title" align="center">TÉRMINOS Y CONDICIONES</h1>
				<a href="#" data-dismiss="modal" class="btn btn-danger" aria-label="Close" id="boton-cerrar-terminos"><i class="fa fa-window-close"></i></a>
			  </div>
			  
			 <div class="modal-body" align="center">
                               
             	<p>
					<b><?=$datosUsuarioActual['uss_nombre'];?></b>, Lorem ipsum dolor sit amet consectetur adipisicing elit. Ad molestias excepturi corrupti illum totam error unde ipsum enim. Eligendi sapiente tenetur voluptates ad tempore nobis, voluptatum aut facere eum ullam?
				</p>

			 </div>
			  
			 <div class="modal-footer">
				<form class="form-horizontal" action="../compartido/guardar.php" method="post">
					<button type="submit" class="btn btn-info">Aceptar términos</button>
				</form>
		 	 </div>
			  
		  </div>
	   </div>
	</div>

<?php }?>