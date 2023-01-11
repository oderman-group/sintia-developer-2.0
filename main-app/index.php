<?php
session_start();
if (isset($_SESSION["id"]) and $_SESSION["id"] != "") {
	if (isset($_GET["urlDefault"]) and $_GET["urlDefault"] != "") {

		include("modelo/conexion.php");
		$sesionAbierta = mysql_fetch_array(mysql_query("SELECT * FROM usuarios WHERE uss_id='" . $_SESSION["id"] . "'", $conexion));

		switch ($sesionAbierta[3]) {
			case 1:
				$url = 'directivo/' . $_GET["urlDefault"];
				break;
			case 2:
				$url = 'docente/' . $_GET["urlDefault"];
				break;
			case 3:
				$url = 'acudiente/' . $_GET["urlDefault"];
				break;
			case 4:
				$url = 'estudiante/' . $_GET["urlDefault"];
				break;
			case 5:
				$url = '../directivo/index.php';
				break;
			default:
				$url = 'controlador/salir.php';
				break;
		}

		header("Location:" . $url);
		exit();
	} else {
		echo "
		<div style='font-family:Arial;'>
		Ya hay una sesi&oacute;n de SINTIA abierta en este navegador: " . $_SESSION["id"] . "<br>
		<a href='controlador/salir.php'>[Cerrar la sesi&oacute;n actual]</a>
		</div>";
		exit();
	}
}
include("../conexion-datos.php");
$conexionBaseDatosServicios = mysqli_connect($servidorConexion, $usuarioConexion, $claveConexion, $baseDatosServicios);
$institucionesConsulta = mysqli_query($conexionBaseDatosServicios, "SELECT * FROM ".$baseDatosServicios.".instituciones WHERE ins_estado = 1");
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Plataforma Educativa SINTIA | Login</title>

  <!-- favicon -->
  <link rel="shortcut icon" href="sintia-icono.png" />


	<!-- Font Awesome -->
<link
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
  rel="stylesheet"
/>
<!-- Google Fonts -->
<link
  href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
  rel="stylesheet"
/>
<!-- MDB -->
<link
  href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.1.0/mdb.min.css"
  rel="stylesheet"
/>

	<link href="index-nuevo.css" rel="stylesheet" type="text/css" />
</head>

<body>
	

<section class="vh-100">
  <div class="container-fluid h-custom">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-md-9 col-lg-6 col-xl-5">
        <img src="sintia-logo-2023.png"
          class="img-fluid" alt="Sample image">
      </div>
      <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
        <form method="post" action="controlador/autentico.php">
			
          <!--<div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
            <p class="lead fw-normal mb-0 me-3">Sign in with</p>
			
            <button type="button" class="btn btn-primary btn-floating mx-1">
              <i class="fab fa-facebook-f"></i>
            </button>

            <button type="button" class="btn btn-primary btn-floating mx-1">
              <i class="fab fa-twitter"></i>
            </button>

            <button type="button" class="btn btn-primary btn-floating mx-1">
              <i class="fab fa-linkedin-in"></i>
            </button>
          </div>-->

		  <input type="hidden" name="agnoIngreso" value="2022" />
		  <input type="hidden" name="urlDefault" value="<?php if(isset($_GET["urlDefault"])) echo $_GET["urlDefault"]; ?>" />

          <div class="divider d-flex align-items-center my-4">
            <p class="text-center fw-bold mx-3 mb-0">Ingreso a la plataforma SINTIA</p>
          </div>

		   <!-- Email input -->
		   <div class="form-outline mb-4">
		   <label for="Usuario">Institución</label>
		   <select class="form-control form-control-lg" name="bd" required>
							<option value="">Seleccione su Institución</option>
							<?php
							while($instituciones = mysqli_fetch_array($institucionesConsulta, MYSQLI_BOTH)){
							?>
								<option value="<?=$instituciones['ins_id'];?>"><?=$instituciones['ins_siglas'];?></option>
							<?php }?>
							

						</select>

          </div>

		  <!-- Email input -->
          <div class="form-outline mb-4">
		  <label for="Usuario">Usuario</label>
            <input type="text" id="Usuario" name="Usuario" class="form-control form-control-lg"
              placeholder="Usuario" />
          </div>

          <!-- Password input -->
          <div class="form-outline mb-3">
		  <label for="Clave">Contraseña</label>
            <input type="password" id="Clave" name="Clave" class="form-control form-control-lg"
              placeholder="Ingrese la contraseña" />
            
          </div>

          <div class="d-flex justify-content-between align-items-center">
            <!-- Checkbox -->
            <div class="form-check mb-0">
              <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3" />
              <label class="form-check-label" for="form2Example3">
                Recuerda me
              </label>
            </div>
            <a href="#!" class="text-body">Olvidaste tu contraseña?</a>
          </div>

          <div class="text-center text-lg-start mt-4 pt-2">
            <button type="submit" class="btn btn-lg"
              style="padding-left: 2.5rem; padding-right: 2.5rem; background-color:#41c4c4; color:#fff;">Empezar la aventura</button>
            <p class="small fw-bold mt-2 pt-1 mb-0">Tu institución aún no tiene la plataforma SINTIA? 
              <a href="#!" style="color:#6017dc;" target="_blank">Solicitar prueba gratis</a></p>
          </div>

        </form>
      </div>
    </div>
  </div>
  <div
    class="d-flex flex-column flex-md-row text-center text-md-start justify-content-between py-4 px-4 px-xl-5" style="background-color:#6017dc;">
    <!-- Copyright -->
    <div class="text-white mb-3 mb-md-0">
      Copyright © 2022. All rights reserved.
    </div>
    <!-- Copyright -->

    <!-- Right -->
    <div>
      <a href="https://www.facebook.com/plataformasintia/" class="text-white me-4">
        <i class="fab fa-facebook-f"></i>
      </a>
      <a href="https://twitter.com/platsintia" class="text-white me-4">
        <i class="fab fa-twitter"></i>
      </a>
      <a href="https://www.instagram.com/platsintia/" class="text-white me-4">
        <i class="fab fa-instagram"></i>
      </a>
    </div>
    <!-- Right -->
  </div>
</section>

<!-- MDB -->
<script
  type="text/javascript"
  src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.1.0/mdb.min.js"
></script>

</body>
</html>