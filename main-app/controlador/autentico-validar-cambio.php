<?php
session_start();
$idPaginaInterna = 'GN0001';
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Plataforma.php");
$Plataforma = new Plataforma;

$conexionBaseDatosServicios = mysqli_connect($servidorConexion, $usuarioConexion, $claveConexion, $baseDatosServicios);

$consultaUsuario = mysqli_query($conexionBaseDatosServicios, "SELECT uss_usuario,uss_tipo,COUNT(DISTINCT institucion) AS cantidad_instituciones,GROUP_CONCAT(DISTINCT institucion ORDER BY institucion SEPARATOR ', ') AS instituciones FROM ".BD_GENERAL.".usuarios WHERE uss_usuario LIKE '".trim($_REQUEST["Usuario"])."%' AND uss_cambio_notificacion=0");
$datosUsuario = mysqli_fetch_array($consultaUsuario, MYSQLI_BOTH);
if(!empty($datosUsuario) && $datosUsuario['cantidad_instituciones'] > 1){
	$institucionesConsulta = mysqli_query($conexionBaseDatosServicios, "SELECT * FROM ".BD_ADMIN.".instituciones 
	WHERE ins_id IN (".$datosUsuario['instituciones'].") AND ins_estado = 1 AND ins_enviroment='".ENVIROMENT."'");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Autenticando | Plataforma sintia</title>

        <!-- favicon -->
        <link rel="shortcut icon" href="../sintia-icono.png" />
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<!-- Google fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap">
		<!-- Styles -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
		<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
		<!-- Or for RTL support -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />
		<link href="../config-general/assets-login-2023/css/styles.css" rel="stylesheet" />
		<style>
			body {
			background-image: url('./../../config-general/assets-login-2023/img/bg-login.png');
			display: grid;
			grid-template-columns: 100%;
			height: 100vh;
			width: 100vw;
		}
		/* Estilo del contenedor del mensaje */
		.espera-container {
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			height: 100vh;
		}

		/* Estilo del mensaje */
		.espera-mensaje {
			font-size: 24px;
			font-weight: bold;
			text-align: center;
			padding: 20px;
			background-color: #5846d2;
			color:#fff;
			border-radius: 10px;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
		}
		</style>
	</head>
	<body>
		<div class="modal fade" id="modalInstituciones" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog"  style="max-width: 1350px!important;">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title">INFORMACIÓN IMPORTANTE!!!</h1>
					</div>

					<div class="modal-body" align="center">
						<p>
							Hola! Queremos informarte que por motivos de seguridad hemos realizado un cambio en tu cuenta<br> para garantizar la protección de tu información personal y mantener la integridad de nuestra<br> plataforma.<br>
							<br>
							Hemos modificado tu usuario de acceso.<br>
							<br>
							Este cambio ha sido implementado como parte de nuestras medidas proactivas para fortalecer la<br> seguridad de todos nuestros usuarios. Te pedimos que utilices este nuevo nombre de usuario<br> para acceder a tu cuenta a partir de ahora.<br>
							<br>
							Recuerda que tu contraseña y demás detalles de acceso permanecen sin cambios. Si tienes<br> alguna pregunta o necesitas asistencia, no dudes en contactar a nuestro equipo de soporte<br> técnico.<br>
							<br>
							Acontinuación escoge tu institución y haremos llegar el nuevo usuario de acceso a tu correo registrado<br>
						</p>

						<form method="post" action="correo-validar-cambio.php" class="needs-validation" novalidate>
							<input type="hidden" name="urlDefault"  value="<?php if(isset($_REQUEST["urlDefault"]))  echo $_REQUEST["urlDefault"]; ?>" />
							<input type="hidden" name="directory"  value="<?php if(isset($_REQUEST["directory"]))  echo $_REQUEST["directory"]; ?>" />

							<input type="hidden" name="Usuario"  value="<?php if(isset($_REQUEST["Usuario"]))  echo $_REQUEST["Usuario"]; ?>" />
							<input type="hidden" name="Clave"  value="<?php if(isset($_REQUEST["Clave"]))  echo $_REQUEST["Clave"]; ?>" />

							<input type="hidden" name="suma"  value="<?php if(isset($_REQUEST["suma"]))  echo $_REQUEST["suma"]; ?>" />
							<input type="hidden" name="sumaReal"  value="<?php if(isset($_REQUEST["sumaReal"]))  echo $_REQUEST["sumaReal"]; ?>" />
							
							<div class="form-floating mt-3" style="width: 50%;">
								<select class="form-select select2" id="institution" name="bd" aria-label="Default select example" required>
									<option value="">Seleccione una institución</option>
									<?php
									while($instituciones = mysqli_fetch_array($institucionesConsulta, MYSQLI_BOTH)){
									?>
										<option value="<?=$instituciones['ins_id'];?>"><?=$instituciones['ins_siglas'];?></option>
									<?php }?>
								</select>
								<label for="institution">Institucion</label>
                                <div class="invalid-feedback">Por favor escoge una institución.</div>
								<?php if(!empty($_GET['error'])){ 
									if($_GET['error']==1){ ?>
										<div style="width: 100%; margin-top: 0.25rem; font-size: 0.875em; color: #dc3545;">El usuario no fue encontrado para esta institución en este año. Por favor verifique.</div>
									<?php }  
									if($_GET['error']==2){ ?>
										<div style="width: 100%; margin-top: 0.25rem; font-size: 0.875em; color: #dc3545;">Por favor escoge una institución.</div>
									<?php } 
								} ?>
							</div>

							<a class="w-10 btn btn-secondary btn-rounded mt-3" href="<?=REDIRECT_ROUTE?>">REGRESAR AL INICIO</a>
							<button class="w-10 btn btn-rounded mt-3" style="background: <?=$Plataforma->colorUno;?>; color:#FFF;" type="submit">CONTINUAR A LA AVENTURA</button>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="espera-container">
			<div class="espera-mensaje">
			Estoy verificando tus datos, dame un momento...
			</div>
			<a class="w-10 btn btn-secondary btn-rounded mt-3" href="<?=REDIRECT_ROUTE?>">REGRESAR AL INICIO</a>
			<button class="w-10 btn btn-rounded mt-3" style="background: <?=$Plataforma->colorUno;?>; color:#FFF;" onclick="mostrarModalInstituciones()">CONTINUAR A LA AVENTURA</button>
		</div>
		
		<!-- jQuery -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<!-- Bootstrap JS -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
		<script src="../config-general/assets-login-2023/js/pages/login.js"></script>
		<script>
			function mostrarModalInstituciones() {
				$("#modalInstituciones").modal("show");
			}
			$(document).ready(function() {
				$('.form-select').select2({
					theme: 'bootstrap-5'
				});

				$('.select2').on('select2:open', function () {
					$(this).parent().find('.select2-selection--single').addClass('form-control');
				});

				$('form').on('submit', function (e) {
					if (!this.checkValidity()) {
						$('#institution').addClass('is-invalid');
						this.classList.add('was-validated');
						e.preventDefault();
					} else {
						$('#institution').removeClass('is-invalid');
					}
					
				});
				
				mostrarModalInstituciones();
			});
		</script>
	</body>
</html>
<?php
	exit();
}else{
	header("Location:autentico.php?Usuario=".base64_encode($datosUsuario["uss_usuario"])."&Clave=".base64_encode($_REQUEST["Clave"])."&suma=".base64_encode($_REQUEST["suma"])."&sumaReal=".base64_encode($_REQUEST["sumaReal"])."&urlDefault=".base64_encode($_REQUEST["urlDefault"])."&directory=".base64_encode($_REQUEST["directory"]));
	exit();
}