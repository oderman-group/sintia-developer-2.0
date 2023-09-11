<div class="row mb-2 mt-2">
	<div class="col-sm-12">
		<img class="img-responsive" src="../../files-general/instituciones/marketplace/marketplace1.png">
	</div>
</div>	
<!-- start course list -->
<?php include("barra-superior-marketplace.php"); ?>
<div class="row mt-2">
	<div class="col-sm-12">
		<div class="row">
			<?php
			$serviciosConsulta = mysqli_query($conexion, "SELECT * FROM " . $baseDatosMarketPlace . ".productos
						INNER JOIN " . $baseDatosMarketPlace . ".categorias_productos ON catp_id=prod_categoria
						INNER JOIN " . $baseDatosMarketPlace . ".empresas ON emp_id=prod_empresa
						WHERE prod_id=prod_id $filtro ");

			$numProductos = mysqli_num_rows($serviciosConsulta);
			if ($numProductos == 0) {
				echo '
					<p style="padding:10px; color:tomato;">No se econtraron productos.</p>
					<p style="padding:10px;"><a href="marketplace.php">VER TODOS</a></p>
				';
			}
			while ($datosConsulta = mysqli_fetch_array($serviciosConsulta, MYSQLI_BOTH)) {
				$foto = 'course3.jpg';
				if (!empty($datosConsulta['prod_foto'])) {
					$foto = $datosConsulta['prod_foto'];
				}

				$class = 'col-lg-3 col-md-6 col-12 col-sm-6';
				if (is_numeric($prod)) {
					$class = 'col-sm-12';
				}


				//JSON PARA ELIMINAR
				$arrayEnviar = array("tipo" => 1, "descripcionTipo" => "Para ocultar fila del registro.");
				$arrayDatos = json_encode($arrayEnviar);
				$objetoEnviar = htmlentities($arrayDatos);
			?>
				<div class="<?= $class; ?>" id="reg<?= $datosConsulta['prod_id']; ?>">
					<div class="blogThumb">
						<div class="thumb-center">
							<a href="marketplace.php?prod=<?= $datosConsulta['prod_id']; ?>"><img class="img-responsive" src="../files/marketplace/productos/<?= $foto; ?>"></a>
						</div>
						<div class="course-box">
							<h5><a href="marketplace.php?prod=<?= $datosConsulta['prod_id']; ?>"><?= strtoupper($datosConsulta['prod_nombre']); ?></a></h5>
							<div class="text-muted">
								<span class="m-r-10" style="font-size: 10px;"> <?= $datosConsulta['catp_nombre']; ?></span>
							</div>
							<p><span style="font-weight: bold;"> $<?= number_format($datosConsulta['prod_precio'], 0, ",", "."); ?></span></p>
							<?php
							if (is_numeric($prod)) {
								$consultaVisita = mysqli_query($conexion, "SELECT * FROM " . $baseDatosMarketPlace . ".productos_visitas 
										WHERE pxvis_producto='" . $prod . "' AND pxvis_usuario='" . $_SESSION["id"] . "' AND pxvis_institucion='" . $config['conf_id_institucion'] . "'");

								$numVisita = mysqli_num_rows($consultaVisita);
								$datoVisita = mysqli_fetch_array($consultaVisita, MYSQLI_BOTH);
								if ($numVisita > 0) {
									mysqli_query($conexion, "UPDATE " . $baseDatosMarketPlace . ".productos_visitas SET pxvis_cantidad=pxvis_cantidad+1 
											WHERE pxvis_usuario='" . $_SESSION["id"] . "' AND pxvis_institucion='" . $config['conf_id_institucion'] . "' AND pxvis_producto='" . $prod . "'");
								} else {
									mysqli_query($conexion, "INSERT INTO " . $baseDatosMarketPlace . ".productos_visitas(pxvis_producto, pxvis_institucion, pxvis_usuario, pxvis_fecha, pxvis_cantidad)VALUES('" . $prod . "', '" . $config['conf_id_institucion'] . "', '" . $_SESSION["id"] . "', now(), 1)");
								}
							?>

								<p><?= $datosConsulta['prod_descripcion']; ?></p>

								<hr>
								<p><span> <b>Negocio:</b><br> <?= $datosConsulta['emp_nombre']; ?></span></p>
								<p><span> <b>Email:</b><br> <?= $datosConsulta['emp_email']; ?></span></p>

								<a href="marketplace.php" class="btn btn-secondary"><i class="fa fa-long-arrow-left"></i> <?= $frases[184][$datosUsuarioActual[8]]; ?></a>

								<a href="#" class="btn btn-info" name="<?= $datosConsulta['emp_usuario']; ?>" title="<?= $datosConsulta['prod_nombre']; ?>" onClick="msjMarketplace(this)"><i class="fa fa-envelope"></i> Enviar mensaje</a>

								<?php
								if ($datosConsulta['prod_precio'] >= 10000) {
								?>
									<a href="productos-comprar.php?id=<?= $datosConsulta['prod_id']; ?>" class="btn btn-success"><i class="fa fa-money"></i> Comprar</a>
								<?php
								}
								?>

								<?php } else {
								if (!empty($_SESSION["empresa"]) && $_SESSION["empresa"]==$datosConsulta['emp_id']) {
								?>
									<a href="#" class="btn btn-success"><i class="fa fa-edit"></i></a>
									<a href="#" title="<?= $objetoEnviar; ?>" id="<?= $datosConsulta['prod_id']; ?>" name="../compartido/guardar.php?get=25&idR=<?= $datosConsulta['prod_id']; ?>" onClick="deseaEliminar(this)" class="btn btn-danger"><i class="fa fa-trash"></i></a>
									<?php
								} else {
									if ($datosConsulta['prod_precio'] >= 10000) {
									?>
										<a href="productos-comprar.php?id=<?= $datosConsulta['prod_id']; ?>" class="btn btn-success"><i class="fa fa-money"></i> Comprar</a>
									<?php
									}
									?>
									<a href="#" class="btn btn-info" name="<?= $datosConsulta['emp_usuario']; ?>" title="<?= $datosConsulta['prod_nombre']; ?>" onClick="msjMarketplace(this)"><i class="fa fa-envelope"></i></a>
								<?php } ?>



						</div>
					</div>
				</div>
		<?php }
						} ?>
		</div>
	</div>



</div>
<!-- End course list -->