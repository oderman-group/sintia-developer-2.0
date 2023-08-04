<?php
	$busqueda = '';
	if (isset($_GET['busqueda'])) {
		$busqueda = $_GET['busqueda'];
		$filtro .= " AND (
			uss_id LIKE '%".$busqueda."%' 
			OR uss_nombre LIKE '%".$busqueda."%' 
			OR uss_nombre2 LIKE '%".$busqueda."%' 
			OR uss_apellido1 LIKE '%".$busqueda."%' 
			OR uss_apellido2 LIKE '%".$busqueda."%' 
			OR uss_usuario LIKE '%".$busqueda."%' 
			OR uss_email LIKE '%".$busqueda."%'
			OR CONCAT(TRIM(uss_nombre), ' ',TRIM(uss_apellido1), ' ', TRIM(uss_apellido2)) LIKE '%".$busqueda."%'
			OR CONCAT(TRIM(uss_nombre), TRIM(uss_apellido1), TRIM(uss_apellido2)) LIKE '%".$busqueda."%'
			OR CONCAT(TRIM(uss_nombre), ' ', TRIM(uss_apellido1)) LIKE '%".$busqueda."%'
			OR CONCAT(TRIM(uss_nombre), TRIM(uss_apellido1)) LIKE '%".$busqueda."%'
			OR fcu_detalle LIKE '%".$busqueda."%' 
			OR fcu_observaciones LIKE '%".$busqueda."%'
		)";
	}
	$usuario = '';
	if (!empty($_GET['usuario'])) {
		$usuario = $_GET['usuario'];
	}
	$tipo = '';
	if (!empty($_GET['tipo'])) {
		$tipo = $_GET['tipo'];
	}
	$estadoM = '';
	if (!empty($_GET['estadoM'])) {
		$estadoM = $_GET['estadoM'];
	}
	$fecha = '';
	if (!empty($_GET['fecha'])) {
		$fecha = $_GET['fecha'];
	}
?>
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #41c4c4;">
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto">
			<?php if(Modulos::validarPermisoEdicion()){?>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#FFF;">
						Menú movimiento financiero
						<span class="fa fa-angle-down"></span>
					</a>
					<div class="dropdown-menu" aria-labelledby="navbarDropdown">
						<a class="dropdown-item" href="movimientos-importar.php">Importar saldos</a>
					</div>
				</li>
			<?php }?>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#FFF;">
					Resúmen
					<span class="fa fa-angle-down"></span>
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">	
					<a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>?usuario=<?= $usuario; ?>&tipo=1&busqueda=<?= $busqueda; ?>&estadoM=<?= $estadoM; ?>&fecha=<?= $fecha; ?>">Ingresos</a>
					<a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>?usuario=<?= $usuario; ?>&tipo=2&busqueda=<?= $busqueda; ?>&estadoM=<?= $estadoM; ?>&fecha=<?= $fecha; ?>">Egresos</a>
					<a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>">Ver todos</a>

				</div>
			</li>
		</ul> 

		<form class="form-inline my-2 my-lg-0" action="<?= $_SERVER['PHP_SELF']; ?>" method="get">
			<input type="hidden" name="usuario" value="<?= $usuario; ?>" />
			<input type="hidden" name="tipo" value="<?= $tipo; ?>" />
			<input type="hidden" name="estadoM" value="<?= $estadoM; ?>" />
			<input type="hidden" name="fecha" value="<?= $fecha; ?>" />
			<input class="form-control mr-sm-2" type="search" placeholder="Búsqueda..." aria-label="Search" name="busqueda" value="<?= $busqueda; ?>">
			<button class="btn deepPink-bgcolor my-2 my-sm-0" type="submit">Buscar</button>
		</form>
	</div>
</nav>