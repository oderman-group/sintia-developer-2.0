<div class="col-sm-12">
	<?php
		$estadoCompra = array("Devuelto", "En Confirmación", "En Preparación", "En Camino", "Finalizada");
		include("barra-superior-mis-compras.php");
	?>
	<div class="card card-topline-purple" id="idElemento">
		<div class="card-head">
			<header>Mis compras</header>
			<div class="tools">
				<a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>
				<a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
				<a class="t-close btn-color fa fa-times" href="javascript:;"></a>
			</div>
		</div>
		<div class="card-body">
			<div class="table-scrollable">
				<table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle" id="example4">
					<thead>
						<tr>
							<th>#</th>
							<th>Producto</th>
							<th>Precio Unitario</th>
							<th>Cantidad</th>
							<th>Precio Total</th>
							<th>Estado Compra</th>
							<th>Estado Pago</th>
							<th>Institución</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$consulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosMarketPlace.".mis_compras
													INNER JOIN ".$baseDatosMarketPlace.".productos ON prod_id=misc_producto
													INNER JOIN ".$baseDatosServicios.".instituciones ON ins_id=misc_institucion AND ins_enviroment='".ENVIROMENT."'
													WHERE misc_usuario='".$_SESSION['id']."' $filtro");
							$contReg = 1;
							$totalPrecio=0;
							$cantidadTotal=0;
							$totalPrecioNeto=0;
							while ($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
						?>
							<tr>
								<td><?=$contReg?></td>
								<td><?=$resultado['prod_nombre']?></td>
								<td><?=number_format($resultado['misc_precio_producto'],0,".",".")?></td>
								<td><?=$resultado['misc_cantidad']?></td>
								<td><?=number_format($resultado['misc_valor_final'],0,".",".")?></td>
								<td><?=$estadoCompra[$resultado['misc_estado_compra']]?></td>
								<td><?=$resultado['misc_estado_pago']?></td>
								<td><?=$resultado['ins_siglas']?></td>
							</tr>

						<?php
								$contReg++;
								$totalPrecio += $resultado['misc_precio_producto'];
								$cantidadTotal += $resultado['misc_cantidad'];
								$totalPrecioNeto += $resultado['misc_valor_final'];
							}
						?>
					</tbody>
					<tfoot>
						<tr style="font-weight:bold;">
							<td colspan="2"><?= strtoupper($frases[107][$datosUsuarioActual['uss_idioma']]); ?></td>
							<td>$ <?=number_format($totalPrecio,0,".",".")?></td>
							<td><?=$cantidadTotal?></td>
							<td>$ <?=number_format($totalPrecioNeto,0,".",".")?></td>
							<td colspan="4"></td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>