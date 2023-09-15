<?php
	if(empty($_SESSION["empresa"])){
		$consultaEmpresa=mysqli_query($conexion, "SELECT * FROM $baseDatosMarketPlace.empresas WHERE emp_usuario='".$_SESSION["id"]."' AND emp_institucion='".$config['conf_id_institucion']."'");
		$empresa = mysqli_fetch_array($consultaEmpresa, MYSQLI_BOTH);
		if(!empty($empresa[0])){
			$_SESSION["empresa"] = $empresa[0];
		}
	}
?>
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
			WHERE prod_estado!=1 AND prod_activo=1 $filtro ");
			$numProductos = mysqli_num_rows($serviciosConsulta);
			if ($numProductos == 0) {
				echo '
					<p style="padding:10px; color:tomato;">No se econtraron productos.</p>
					<p style="padding:10px;"><a href="marketplace.php">VER TODOS</a></p>
				';
			}
			while ($datosConsulta = mysqli_fetch_array($serviciosConsulta, MYSQLI_BOTH)) {
				$ruta = '../files/marketplace/productos/';
				$foto = 'https://via.placeholder.com/510?text=Sin+Imagen';
				if (!empty($datosConsulta['prod_foto']) && file_exists($ruta.$datosConsulta['prod_foto'])) {
					$foto = $ruta.$datosConsulta['prod_foto'];
				}
				
				$precio = 0;
				if (!empty($datosConsulta['prod_precio'])) {
					$precio = $datosConsulta['prod_precio'];
				}
				//JSON PARA ELIMINAR
				$arrayEnviar = array("tipo" => 1, "descripcionTipo" => "Para ocultar fila del registro.");
				$arrayDatos = json_encode($arrayEnviar);
				$objetoEnviar = htmlentities($arrayDatos);
			?>
			<div class="col-lg-3 col-md-6 col-12 col-sm-6 mb-3" id="reg<?= $datosConsulta['prod_id']; ?>">
				<div class="blogThumb" style="height: 100%;">
					<div class="thumb-center" style="height: 55%;">
						<a name="modalMarketplaceDetalles<?= $datosConsulta['prod_id']; ?>" onClick="mostrarDetalles(this)"><img class="img-responsive" style="height: 300px;" src="<?= $foto; ?>"></a>
					</div>
					<div class="course-box" style="height: 45%;  display: flex; flex-direction: column; justify-content: flex-end;">
						<h5><a style="color:cadetblue;" name="modalMarketplaceDetalles<?= $datosConsulta['prod_id']; ?>" onClick="mostrarDetalles(this)"><?= strtoupper($datosConsulta['prod_nombre']); ?></a></h5>
						<div class="text-muted">
							<span class="m-r-10" style="font-size: 10px;"> <?= $datosConsulta['catp_nombre']; ?></span>
						</div>
						<p><span style="font-weight: bold;"> $<?=number_format($precio, 0, ",", ".")?></span></p>
						<p>
						<?php
						if (!empty($_SESSION["empresa"]) && $_SESSION["empresa"] == $datosConsulta['emp_id']) {
						?>
							<a href="#" class="btn btn-success"><i class="fa fa-edit"></i></a>
							<a href="#" title="<?= $objetoEnviar; ?>" id="<?= $datosConsulta['prod_id']; ?>" name="../compartido/guardar.php?get=<?= base64_encode(25); ?>&idR=<?= base64_encode($datosConsulta['prod_id']); ?>" onClick="deseaEliminar(this)" class="btn btn-danger"><i class="fa fa-trash"></i></a>
						<?php
							} else {
								if ($precio >= 10000) {
						?>
									<a href="productos-comprar.php?id=<?= base64_encode($datosConsulta['prod_id']); ?>" class="btn btn-success"><i class="fa fa-money"></i> Comprar</a>
						<?php
								}
						?>
								<a href="#" class="btn btn-info" name="<?= $datosConsulta['emp_usuario']; ?>" title="<?= $datosConsulta['prod_nombre']; ?>" onClick="msjMarketplace(this)"><i class="fa fa-envelope"></i></a>
						<?php } ?>
						</p>
					</div>
				</div>
			</div>
		<?php
			include('modal-marketplace-detalles.php');
			}
		?>
		</div>
	</div>
</div>
<!-- End course list -->
<script>
	function mostrarDetalles(datos){
		$("#"+datos.name).modal("show");
	}
</script>