<?php
session_start();
include($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
if (isset($_SESSION["id"]) and $_SESSION["id"] != "") {

  $pagina = 'index.php';

  if (isset($_GET["urlDefault"]) and $_GET["urlDefault"] != "") {
      $pagina = $_GET["urlDefault"];
	}

    include("modelo/conexion.php");
    $consultaSesion=mysqli_query($conexion,"SELECT * FROM usuarios 
    WHERE uss_id='" . $_SESSION["id"] . "'");
		$sesionAbierta = mysqli_fetch_array($consultaSesion, MYSQLI_BOTH);

		switch ($sesionAbierta[3]) {
			case 1:
				$url = 'directivo/'.$pagina;
				break;
			case 2:
				$url = 'docente/'.$pagina;
				break;
			case 3:
				$url = 'acudiente/'.$pagina;
				break;
			case 4:
				$url = 'estudiante/'.$pagina;
				break;
			case 5:
				$url = 'directivo/'.$pagina;
				break;
			default:
				$url = 'controlador/salir.php';
				break;
		}

    header("Location:" . $url);
		exit();
}

include(ROOT_PATH."/conexion-datos.php");
$conexionBaseDatosServicios = mysqli_connect($servidorConexion, $usuarioConexion, $claveConexion, $baseDatosServicios);
$institucionesConsulta = mysqli_query($conexionBaseDatosServicios, "SELECT * FROM ".$baseDatosServicios.".instituciones WHERE ins_estado = 1");
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Plataforma Educativa SINTIA | Recuperar clave</title>

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
  <script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
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
        <form method="post" action="recuperar-clave-guardar.php">

          <?php include("../config-general/mensajes-informativos.php"); ?>

          <div class="divider d-flex align-items-center my-4">
            <p class="text-center fw-bold mx-3 mb-0">Recuperar contraseña</p>
          </div>

		   <!-- Colegios input -->
		   <div class="form-outline mb-4 bd">
		    <label for="bd">Institución</label>
		    <select class="form-control form-control-lg" name="bd" required id="bd" onchange="traerYears(this)">
							<option value="">Seleccione su Institución</option>
							<?php
							while($instituciones = mysqli_fetch_array($institucionesConsulta, MYSQLI_BOTH)){
                $selected = (isset($_GET['inst']) and $_GET['inst']==$instituciones['ins_id']) ? 'selected' : '';
							?>
								<option value="<?=$instituciones['ins_id'];?>" <?=$selected;?>><?=$instituciones['ins_siglas'];?></option>
							<?php }?>
						</select>
        </div>

        <!-- Año input -->
		   <div class="form-outline mb-4">
		    <label for="agnoIngreso">Año de consulta</label>
		      <select class="form-control form-control-lg" name="agnoIngreso" id="agnoIngreso" required>
            <option value="">Seleccione el año</option>
          </select>
          <script type="application/javascript">
            $(document).ready(traerYears(document.getElementById('bd')));
            
            function traerYears(enviada){
              var idInsti = enviada.value;

              datos = "idInsti="+(idInsti);
              console.log(datos);
              $.ajax({
                      type: "POST",
                      url: "ajax-detectar-years.php",
                      data: datos,
                      success: function(response)
                      {
                          $('#agnoIngreso').empty();
                          $('#agnoIngreso').append(response);
                      }
              });
            }
          </script>
        </div>

		  <!-- Email input -->
          <div class="form-outline mb-4">
		  <label for="Usuario">Usuario, documento o Email</label>
            <input type="text" id="Usuario" name="Usuario" class="form-control form-control-lg"
              placeholder="Usuario" required />
          </div>

          <div class="d-flex justify-content-between align-items-center">
            <!-- Checkbox -->
            <a href="index.php" class="text-body">Regresar al login</a>
            <a href="https://docs.google.com/forms/d/e/1FAIpQLSdiugXhzAj0Ysmt2gthO07tbvjxTA7CHcZqgzBpkefZC6T2qg/viewform" class="text-body" target="_blank">¿Requieres soporte?</a>
          </div>

          <div class="text-center text-lg-start mt-4 pt-2">
            <button type="submit" class="btn btn-lg"
              style="padding-left: 2.5rem; padding-right: 2.5rem; background-color:#41c4c4; color:#fff;">Recuperar contraseña</button>
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