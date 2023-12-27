<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #41c4c4;">
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto">
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#FFF;">
					Mas Opciones
					<span class="fa fa-angle-down"></span>
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
					<a class="dropdown-item" href="movimientos-factura-venta.php?id=<?=base64_encode($resultado['fcu_id']);?>" target="_blank"><?=$frases[380][$datosUsuarioActual['uss_idioma']];?></a>
				</div>
			</li>
		</ul> 
	</div>
</nav><br>