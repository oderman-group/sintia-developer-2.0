<div class="modal fade" id="modalMarketplaceDetalles<?= $datosConsulta['prod_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true" style="z-index:9999;">
	<div class="modal-dialog" style="max-width: 1350px!important;">
		<div class="modal-content" style="max-width: 1350px!important;">
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
				<button data-dismiss="modal" class="btn btn-danger"><?=$frases[171][$datosUsuarioActual[8]];?></button>
				<a href="#" class="btn btn-info" name="<?= $datosConsulta['emp_usuario']; ?>" title="<?= $datosConsulta['prod_nombre']; ?>" onClick="msjMarketplace(this)"><i class="fa fa-envelope"></i> Enviar mensaje</a>
				<?php if ($precio >= 10000 && (empty($_SESSION["empresa"]) || (!empty($_SESSION["empresa"]) && $_SESSION["empresa"] != $datosConsulta['emp_id']))) { ?>
					<a href="productos-comprar.php?id=<?= base64_encode($datosConsulta['prod_id']); ?>" class="btn btn-success"><i class="fa fa-money"></i> Comprar</a>
				<?php } ?>
			</div>
		</div>
	</div>
</div>