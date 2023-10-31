<?php
	include("session-compartida.php");
	$idPaginaInterna = 'CM0012';
	include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

    $id = "";
    if (!empty($_GET["id"])) {
        $id = base64_decode($_GET["id"]);
    }

	$serviciosConsulta = mysqli_query($conexion, "SELECT * FROM " . $baseDatosMarketPlace . ".productos
	INNER JOIN " . $baseDatosMarketPlace . ".categorias_productos ON catp_id=prod_categoria
	INNER JOIN " . $baseDatosMarketPlace . ".empresas ON emp_id=prod_empresa
	WHERE prod_id='".$id."'");
	$datosConsulta = mysqli_fetch_array($serviciosConsulta, MYSQLI_BOTH);

	$ruta = '../files/marketplace/productos/';
	$foto = 'https://via.placeholder.com/510?text=Sin+Imagen';
	if (!empty($datosConsulta['prod_foto']) && file_exists($ruta.$datosConsulta['prod_foto'])) {
		$foto = $ruta.$datosConsulta['prod_foto'];
	}
				
	$precio = 0;
	if (!empty($datosConsulta['prod_precio'])) {
		$precio = $datosConsulta['prod_precio'];
	}
?>
<div class="modal-body">
	<div class="row">
		<div class="col-sm-12">
			<div class="card card-box">
				<div class="card-head">
					<header><?= strtoupper($datosConsulta['prod_nombre']); ?></header>
				</div>
				<div class="card-body" id="bar-parent6">
					<div style="display: grid; grid-template-columns: 3fr 4fr; gap: 10px;">
						<div >
							<img class="img-responsive" src="<?= $foto; ?>"></a>
						</div>
						<div>
							<div class="text-muted">
								<span class="m-r-10" style="font-size: 20px;">Cat: <?= $datosConsulta['catp_nombre']; ?></span>
							</div>
							<p><span style="font-weight: bold;"> $<?= number_format($precio, 0, ",", "."); ?></span></p>
							<p><?= $datosConsulta['prod_descripcion']; ?></p>
							<hr>
							<p><span> <b>Negocio:</b><br> <?= $datosConsulta['emp_nombre']; ?></span></p>
							<p><span> <b>Email:</b><br> <?= $datosConsulta['emp_email']; ?></span></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button data-dismiss="modal" class="btn btn-danger">Cerrar</button>
	<a href="#" class="btn btn-info" name="<?= $datosConsulta['emp_usuario']; ?>" title="<?= $datosConsulta['prod_nombre']; ?>" onClick="msjMarketplace(this)"><i class="fa fa-envelope"></i> Enviar mensaje</a>
	<?php if ($precio >= 10000 && (empty($_SESSION["empresa"]) || (!empty($_SESSION["empresa"]) && $_SESSION["empresa"] != $datosConsulta['emp_id']))) { ?>
		<a href="productos-comprar.php?id=<?= base64_encode($datosConsulta['prod_id']); ?>" class="btn btn-success"><i class="fa fa-money"></i> Comprar</a>
	<?php } ?>
</div>

<?php include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php"); ?>