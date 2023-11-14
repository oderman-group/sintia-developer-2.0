<?php
	$filtro = '';
	$busqueda = '';
	if (!empty($_GET['busqueda'])) {
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
		$usuario = base64_decode($_GET['usuario']);
		$filtro .= " AND fcu_usuario='".$usuario."'";
	}
	$tipo = '';
	if (!empty($_GET['tipo'])) {
		$tipo = base64_decode($_GET['tipo']);
		$filtro .= " AND fcu_tipo='".$tipo."'";
	}
	$estadoM = '';
	if (!empty($_GET['estadoM'])) {
		$estadoM = base64_decode($_GET['estadoM']);
		$filtro .= " AND mat_estado_matricula='".$estadoM."'";
	}
	$fecha = '';
	if (!empty($_GET['fecha'])) {
		$fecha = base64_decode($_GET['fecha']);
		$filtro .= " AND fcu_fecha='".$fecha."'";
	}
?>
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #41c4c4;">
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto">
			<?php if(Modulos::validarPermisoEdicion() && Modulos::validarSubRol(['DT0105'])){?>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="javascript:void(0);" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#FFF;">
						Menú movimiento financiero
						<span class="fa fa-angle-down"></span>
					</a>
					<div class="dropdown-menu" aria-labelledby="navbarDropdown">
					<?php if( Modulos::validarSubRol(['DT0105']) ){?>
						<a class="dropdown-item" href="movimientos-importar.php">Importar saldos</a>
					<?php }?>
					</div>
				</li>
			<?php }?>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="javascript:void(0);" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#FFF;">
					Resúmen
					<span class="fa fa-angle-down"></span>
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">	
					<a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>?usuario=<?= base64_encode($usuario); ?>&tipo=<?=base64_encode(1)?>&busqueda=<?= $busqueda; ?>&estadoM=<?= base64_encode($estadoM); ?>&fecha=<?= base64_encode($fecha); ?>">Ingresos</a>
					<a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>?usuario=<?= base64_encode($usuario); ?>&tipo=<?=base64_encode(2)?>&busqueda=<?= $busqueda; ?>&estadoM=<?= base64_encode($estadoM); ?>&fecha=<?= base64_encode($fecha); ?>">Egresos</a>
					<a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>">Ver todos</a>

				</div>
			</li>

			<?php if (!empty($filtro)) { ?>
				<li class="nav-item"> <a class="nav-link" href="javascript:void(0);" style="color:<?= $Plataforma->colorUno; ?>;">|</a></li>

				<li class="nav-item"> <a class="nav-link" href="<?= $_SERVER['PHP_SELF']; ?>" style="color:<?= $Plataforma->colorUno; ?>;">Quitar filtros</a></li>
			<?php } ?>
		</ul> 

		<form class="form-inline my-2 my-lg-0" action="<?= $_SERVER['PHP_SELF']; ?>" method="get">
			<input type="hidden" name="usuario" value="<?= base64_encode($usuario); ?>" />
			<input type="hidden" name="tipo" value="<?= base64_encode($tipo); ?>" />
			<input type="hidden" name="estadoM" value="<?= base64_encode($estadoM); ?>" />
			<input type="hidden" name="fecha" value="<?= base64_encode($fecha); ?>" />
			<input class="form-control mr-sm-2" type="search" placeholder="Búsqueda..." aria-label="Search" name="busqueda" value="<?= $busqueda; ?>">
			<button class="btn deepPink-bgcolor my-2 my-sm-0" type="submit">Buscar</button>
		</form>
	</div>
</nav>