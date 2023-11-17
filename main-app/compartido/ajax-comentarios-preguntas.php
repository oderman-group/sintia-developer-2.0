<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$filtro="";
if(!empty($_POST["usuario"]) && $_POST["usuario"]!=0){ $filtro= "AND cpp.cpp_usuario = '".$_POST["usuario"]."'";}
$preguntasConsulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_clases_preguntas cpp
INNER JOIN usuarios ON uss_id=cpp.cpp_usuario
WHERE cpp.cpp_id_clase='" . $_POST["claseId"] . "' AND cpp.institucion={$config['conf_id_institucion']} AND cpp.year={$_SESSION["bd"]} $filtro ORDER BY cpp.cpp_fecha DESC");
$usuarioActual= $_POST["usuarioActual"];
?>
<?php while ($preguntasDatos = mysqli_fetch_array($preguntasConsulta, MYSQLI_BOTH)) { ?>
	<div class="row">
		<div class="col-sm-12">

		<div class="panel card card-box">
				
				<div class="user-panel">
					<div class="pull-left image">
						<img src="../files/fotos/<?= $preguntasDatos['uss_foto']; ?>" class="img-circle user-img-circle" alt="User Image" height="50" width="50" />
					</div>

					<div class="pull-left info">
						<p><a href="clases-ver.php?idR=<?= base64_encode($_POST["claseId"]); ?>&usuario=<?= base64_encode($preguntasDatos['cpp_usuario']); ?>"><?= $preguntasDatos['uss_nombre']; ?></a><br><span style="font-size: 11px; color: #000;"><?= $preguntasDatos['cpp_contenido']; ?></span></p>
					</div>
				</div>
				<div class="panel-body">
					<p><span style="font-size: 11px; color: #000;"><?= $preguntasDatos['cpp_fecha']; ?></span>
					<?php if($usuarioActual === $preguntasDatos['cpp_usuario']){
						$href='../compartido/clases-eliminar-comentarios.php?idCom='.base64_encode($preguntasDatos['cpp_id']).'&idR='.base64_encode($_POST["claseId"]);?>
						
						<a href="javascript:void(0);" id="<?= base64_encode($preguntasDatos['cpp_id']); ?>" name="<?= $href ?>" onClick="deseaEliminar(this)">
							<i class="fa fa-trash"></i>
						</a>
				<?php } ?>	
				</p>
					
				</div>
			</div>
		</div>
	</div>
<?php } ?>