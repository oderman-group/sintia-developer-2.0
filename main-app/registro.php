<?php
require_once("index-logica.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../config-general/assets-login-2023/img/logo.png" type="image/x-icon">
    <title>Plataforma Educativa SINTIA | Login</title>
    <!-- Google fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap">
    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <!-- Or for RTL support -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />
    <link href="../config-general/assets-login-2023/css/styles.css" rel="stylesheet" />
    <!-- steps -->
    <link rel="stylesheet" href="../config-general/assets/plugins/steps/steps.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <style>
        /* Estilos básicos para el wizard */
        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        .buttons {
            margin-top: 20px;
        }
    </style>

</head>

<body>
    <a href="https://api.whatsapp.com/send?phone=573006075800&text=Hola, me gustaria recibir mas información de la plataforma." class="float" target="_blank"><i class="fa fa-whatsapp my-float"></i></a>

    <div class="login-container register-container">
        <div class=" vertical-center text-center">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 offset-md-2" id="login">
                        <form method="post" name="example_advanced_form" id="example-advanced-form" action="registro-guardar.php" class="needs-validation" novalidate>
                            <?php include("../config-general/mensajes-informativos.php"); ?>

                            <h3>Datos Básicos</h3>
                            <fieldset>
                                <input type="hidden" name="urlDefault" value="<?= !empty($_REQUEST["urlDefault"]) ? $_REQUEST["urlDefault"] : ""; ?>" />
                                <input type="hidden" name="plan" value="<?= !empty($_REQUEST["plan"]) ? $_REQUEST["plan"] : ""; ?>" />
                                <input type="hidden" name="modAdicional[]" value="<?= !empty($_REQUEST["modAdicional"]) ? $_REQUEST["modAdicional"] : ""; ?>" />
                                <input type="hidden" name="paquetes[]" value="<?= !empty($_REQUEST["paquetes"]) ? $_REQUEST["paquetes"] : ""; ?>" />
                                <input type="hidden" name="cuotas" value="<?= !empty($_REQUEST["cuotas"]) ? $_REQUEST["cuotas"] : ""; ?>" />

                                <div class="form-floating mt-3">
                                    <input type="text" class="form-control input-login" name="nombre" value="<?= !empty($_REQUEST["nombre"]) ? $_REQUEST["nombre"] : ""; ?>" placeholder="Nombres" required>
                                    <label for="emailInput">Nombres</label>
                                    <div class="invalid-feedback">Por favor ingrese su nombre.</div>
                                </div>

                                <div class="form-floating mt-3">
                                    <input type="text" class="form-control input-login" name="apellidos" value="<?= !empty($_REQUEST["apellidos"]) ? $_REQUEST["apellidos"] : ""; ?>" placeholder="Apellidos" required>
                                    <label for="emailInput">Apellidos</label>
                                    <div class="invalid-feedback">Por favor ingrese sus apellidos.</div>
                                </div>

                                <div class="form-floating mt-3">
                                    <input type="email" class="form-control input-login" name="Correo electrónico" value="<?= !empty($_REQUEST["email"]) ? $_REQUEST["email"] : ""; ?>" placeholder="email" required>
                                    <label for="emailInput">Correo electrónico</label>
                                    <div class="invalid-feedback">Por favor ingrese un correo electrónico válido.</div>
                                </div>

                                <div class="form-floating mt-3">
                                    <input type="text" class="form-control input-login" name="Número de celular" value="<?= !empty($_REQUEST["celular"]) ? $_REQUEST["celular"] : ""; ?>" placeholder="Celular" required>
                                    <label for="emailInput">Número de celular</label>
                                    <div class="invalid-feedback">Por favor ingrese un número celular válido.</div>
                                </div>

                                <div class="form-floating mt-5">
                                    <input type="text" class="form-control input-login" name="nombreIns" placeholder="Nombre de la institución" onchange="generarSiglas(this)" value="<?= !empty($_REQUEST["nombreIns"]) ? $_REQUEST["nombreIns"] : ""; ?>" required>
                                    <input type="hidden" name="siglasInst" id="siglasInst">
                                    <label for="emailInput">Nombre de la institución</label>
                                    <div class="invalid-feedback">Por favor ingrese el nombre de su institución.</div>
                                </div>

                                <script type="text/javascript">
                                    function obtenerPrimerasLetras(frase) {
                                        // Divide la frase en palabras
                                        var palabras = frase.split(" ");
                                        var primerasLetras = "";

                                        // Itera sobre cada palabra y obtén la primera letra
                                        for (var i = 0; i < palabras.length; i++) {
                                            // Asegúrate de que la palabra no esté vacía antes de obtener la primera letra
                                            if (palabras[i].length > 0) {
                                                primerasLetras += palabras[i][0] + palabras[i][1]; // Añade la primera letra de la palabra
                                            }
                                        }

                                        return primerasLetras;
                                    }

                                    function generarSiglas(datos) {
                                        var institucion = datos.value;
                                        var siglas = obtenerPrimerasLetras(institucion);
                                        document.getElementById("siglasInst").value = siglas.toUpperCase();
                                    }
                                </script>

                                <div class="form-floating mt-3">
                                    <input type="text" class="form-control input-login" name="ciudad" placeholder="Municipio/Ciudad" value="<?= !empty($_REQUEST["ciudad"]) ? $_REQUEST["ciudad"] : ""; ?>" required>
                                    <label for="emailInput">Municipio/Ciudad</label>
                                    <div class="invalid-feedback">Por favor ingrese la ciudad de su institución.</div>
                                </div>

                                <div class="form-floating mt-3">
                                    <input type="text" class="form-control input-login" name="cargo" placeholder="Cargo que ocupa" value="<?= !empty($_REQUEST["cargo"]) ? $_REQUEST["cargo"] : ""; ?>" required>
                                    <label for="emailInput">Cargo que ocupa</label>
                                    <div class="invalid-feedback">Por favor ingrese su cargo en la institución.</div>
                                </div>

                            </fieldset>

                            <h3>Seleción de Plan</h3>
                            <fieldset>

                                <div class="d-flex justify-content-center align-items-stretch gap-4">
                                    <?php
                                        $consultaPlanes = mysqli_query($conexionBaseDatosServicios, "SELECT * FROM ".BD_ADMIN.".planes_sintia WHERE plns_tipo='".PLANES."'");
                                        while ($planes = mysqli_fetch_array($consultaPlanes, MYSQLI_BOTH)) {
                                            $background = $planes['plns_id'] == 2 ? "background-color: #ffffff; border: 2px solid #000;": "background-color: #f8f9fa; border: 1px solid #ddd;";

                                            $iconoPlan = !empty($planes['plns_imagen']) ? $planes['plns_imagen']: "default.png";
                                    ?>
                                        <div class="card text-center" style="width: 200rem; padding: 20px; border-radius: 10px; <?=$background?>">
                                            <input type="radio" name="plan" id="plan<?=$planes['plns_id']?>" value="<?=$planes['plns_id']?>" required class="form-check-input">
                                            <label for="plan<?=$planes['plns_id']?>" class="form-check-label d-flex flex-column align-items-center">
                                                <img src="files/planes/<?=$iconoPlan?>" alt="<?=$planes['plns_nombre']?> Plan Icon" width="50" height="50">
                                                <h4><?=$planes['plns_nombre']?></h4>
                                                <p>$<?=number_format($planes['plns_valor'],0,",",".")?>/Anual</p>
                                                <button type="button" onclick="enviarFormulario('registro-guardar.php')" class="btn btn-outline-dark btn-sm mt-2">EMPEZAR GRATIS</button>
                                                <ul class="list-unstyled mt-3" style="text-align: left;">
                                                    <?php if ( $planes['plns_id'] == 1 ) { ?>
                                                        <li><i class="bi bi-check-circle mt-2"></i> 1. Escritorio </li>
                                                        <li><i class="bi bi-check-circle mt-2"></i> 2. Publicacines </li>
                                                        <li><i class="bi bi-check-circle mt-2"></i> 3. G. Académica </li>
                                                        <li><i class="bi bi-check-circle mt-2"></i> 4. G. De Usuarios </li>
                                                        <li><i class="bi bi-check-circle mt-2"></i> 5. Inscripciones </li>
                                                        <li><i class="bi bi-check-circle mt-2"></i> 6. Correo Interno </li>
                                                        <li><i class="bi bi-check-circle mt-2"></i> 7. Multi Idioma </li>
                                                        <li><i class="bi bi-check-circle mt-2"></i> 8. Informes Principales </li>
                                                    <?php } if ( $planes['plns_id'] == 2 ) { ?>
                                                        <li><i class="bi bi-check-circle mt-2"></i> 1. M. Plan Básico </li>
                                                        <li><i class="bi bi-check-circle mt-2"></i> 2. G. De Comportamiento </li>
                                                        <li><i class="bi bi-check-circle mt-2"></i> 3. Roles y Permisos </li>
                                                    <?php } if ( $planes['plns_id'] == 3 ) { ?>
                                                        <li><i class="bi bi-check-circle mt-2"></i> 1. M. Plan Intermedio </li>
                                                        <li><i class="bi bi-check-circle mt-2"></i> 2. Cuestionarios Evaluativos </li>
                                                        <li><i class="bi bi-check-circle mt-2"></i> 3. G. Financiera </li>
                                                        <li><i class="bi bi-check-circle mt-2"></i> 4. Media Técnica </li>
                                                        <li><i class="bi bi-check-circle mt-2"></i> 5. Chat </li>
                                                        <li><i class="bi bi-check-circle mt-2"></i> 6. Informes Premium </li>
                                                        <li><i class="bi bi-check-circle mt-2"></i> 7. Sop. y Atención Prioritaria </li>
                                                    <?php } ?>
                                                </ul>
                                            </label>
                                        </div>
                                    <?php } ?>
                                </div>
                            </fieldset>

                            <h3>Activar Cuenta</h3>
                            <fieldset></fieldset>
                        </form>

                        <script>
                            function enviarFormulario(accion) {
                                var formulario = document.getElementById('miFormulario');
                                formulario.action = accion; // Cambia la acción del formulario
                                formulario.submit(); // Envía el formulario
                            }
                        </script>
                        <div id="wizard" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="logo-container position-relative vertical-center" style="width: 100%; height: 100vh; overflow: hidden;">
            <!-- Lottie como fondo -->
            <lottie-player src="<?= !empty($datosContactoSintia['dtc_animacion_register']) ? $datosContactoSintia['dtc_animacion_register'] : ""; ?>" background="transparent" speed="1" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" loop autoplay></lottie-player>

            <!-- Contenido centrado encima de la animación -->
            <div class="content-overlay text-center" style="position: relative; z-index: 1; color: #000;">
                <h3 class="mt-5">¿Necesitas ayuda?</h3>

                <div class="button-container d-flex justify-content-center mt-3" style="gap: 15px;">
                    <a class="btn deepPink-bgcolor btn-lg btn-rounded" target="_blank" href="https://api.whatsapp.com/send?phone=573006075800&text=Hola, me gustaria recibir mas información de la plataforma.">3006075800 </a>
                    <a class="btn btn-primary btn-lg btn-rounded" target="_blank" href="mailto:info@plataformasintia.com">info@plataformasintia.com</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
    <script src="../config-general/assets/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>
    <script src="../config-general/assets/plugins/jquery-validation/js/jquery.validate.min.js"></script>
    <!-- steps -->
    <script src="../config-general/assets/plugins/steps/jquery.steps.js"></script>
    <script src="../config-general/assets/js/pages/steps/steps-data.js"></script>
    <script src="../config-general/assets-login-2023/js/pages/login_1.js"></script>
    <script>
        $(document).ready(function () {
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
        });
    </script>
    <!-- Core theme JS-->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

</body>

</html>