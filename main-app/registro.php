<?php
session_start();
require_once("../conexion.php");
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
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />
    <link href="../config-general/assets-login-2023/css/styles.css" rel="stylesheet" />

</head>

<body>
    <div class="login-container">
        <div class=" vertical-center text-center">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 offset-md-2" id="login">
                        <form method="post" action="registro-guardar.php" class="needs-validation" novalidate>
                            <?php include("../config-general/mensajes-informativos.php"); ?>
                            <input type="hidden" name="urlDefault" value="<?= !empty($_GET["urlDefault"]) ? $_GET["urlDefault"] : ""; ?>" />
                            <img class="mb-4" src="../config-general/assets-login-2023/img/logo.png" width="100">

                            <div class="form-floating mt-3">
                                <select class="form-select select2" id="institucion" name="institucion" aria-label="Default select example" required>
                                    <option value="">Tipo de Institución</option>
                                    <option value="<?=SCHOOL?>" <?= !empty($_GET["institucion"]) && $_GET["institucion"] == SCHOOL ? "selected" : ""; ?>>Colegio</option>
                                    <option value="<?=UNIVERSITY?>" <?= !empty($_GET["institucion"]) && $_GET["institucion"] == UNIVERSITY ? "selected" : ""; ?>>Universidad</option>
                                    <option value="<?=INSTITUTE?>" <?= !empty($_GET["institucion"]) && $_GET["institucion"] == INSTITUTE ? "selected" : ""; ?>>Instituto</option>
                                    <option value="<?=KINDERGARTEN?>" <?= !empty($_GET["institucion"]) && $_GET["institucion"] == KINDERGARTEN ? "selected" : ""; ?>>Jardin infantil</option>
                                </select>
                                <label for="institucion">Institucion</label>
                                <div class="invalid-feedback">Por favor seleccione una institución.</div>
                            </div>

                            <div class="form-floating mt-3">
                                <input type="text" class="form-control input-login" name="nombreIns" placeholder="Institución" onchange="generarSiglas(this)" value="<?= !empty($_GET["nombreIns"]) ? $_GET["nombreIns"] : ""; ?>" required>
                                <input type="hidden" name="siglasInst" id="siglasInst">
                                <label for="emailInput">Institución</label>
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
                                            primerasLetras += palabras[i][0]+palabras[i][1]; // Añade la primera letra de la palabra
                                        }
                                    }

                                    return primerasLetras;
                                }

                                function generarSiglas(datos){
                                    var institucion = datos.value;
                                    var siglas = obtenerPrimerasLetras(institucion);
                                    document.getElementById("siglasInst").value = siglas.toUpperCase();
                                }
                            </script> 

                            <div class="form-floating mt-3">
                                <select class="form-select select2" id="plan" name="plan" aria-label="Default select example" required>
                                    <option value="">Escoge un plan</option>
                                    <?php
                                        $consultaPlanes = mysqli_query($conexion, "SELECT * FROM ".BD_ADMIN.".planes_sintia");
                                        while ($planes = mysqli_fetch_array($consultaPlanes, MYSQLI_BOTH)) {
                                    ?>
                                    <option value="<?=$planes['plns_id']?>" <?= !empty($_GET["plan"]) && $_GET["plan"] == $planes['plns_id'] ? "selected" : ""; ?>><?=$planes['plns_nombre']?></option>
                                    <?php } ?>
                                </select>
                                <label for="plan">Escoja un plan</label>
                                <div class="invalid-feedback">Por favor seleccione un plan.</div>
                            </div>
                            
                            <div class="form-floating mt-3">
                                <input type="text" class="form-control input-login" id="emailInput" name="usuario" value="<?= !empty($_GET["usuario"]) ? $_GET["usuario"] : ""; ?>" placeholder="documento" required>
                                <label for="emailInput">Documento</label>
                                <div class="invalid-feedback">Por favor ingrese su numero de documento sin puntos.</div>
                            </div>

                            <div class="form-floating input-group mt-3">
                                <input type="password" class="form-control input-login" id="password" name="clave" value="<?= !empty($_GET["clave"]) ? $_GET["clave"] : ""; ?>" placeholder="Password" required>
                                <button class="btn btn-outline-secondary input-group-text toggle-password" type="button">
                                    <i class="bi bi-eye-slash"></i>
                                </button>
                                <label for="password">Contraseña</label>
                                <div class="invalid-feedback">usuario y/o contraseña invalido</div>
                                <div class="form-text" id="caps-lock-message" style="display: none;">Mayúsculas activadas</div>
                            </div>

                            <div class="form-floating mt-3">
                                <input type="text" class="form-control input-login" name="nombre" value="<?= !empty($_GET["nombre"]) ? $_GET["nombre"] : ""; ?>" placeholder="Nombres" required>
                                <label for="emailInput">Nombres</label>
                                <div class="invalid-feedback">Por favor ingrese su nombre.</div>
                            </div>

                            <div class="form-floating mt-3">
                                <input type="text" class="form-control input-login" name="apellidos" value="<?= !empty($_GET["apellidos"]) ? $_GET["apellidos"] : ""; ?>" placeholder="Apellidos" required>
                                <label for="emailInput">Apellidos</label>
                                <div class="invalid-feedback">Por favor ingrese sus apellidos.</div>
                            </div>

                            <div class="form-floating mt-3">
                                <input type="email" class="form-control input-login" name="email" value="<?= !empty($_GET["email"]) ? $_GET["email"] : ""; ?>" placeholder="email" required>
                                <label for="emailInput">Email</label>
                                <div class="invalid-feedback">Por favor ingrese un correo electrónico válido.</div>
                            </div>

                            <div class="form-floating mt-3">
                                <input type="text" data-mask="(999) 999-9999" data-mask-reverse="true" class="form-control input-login" name="celular" value="<?= !empty($_GET["celular"]) ? $_GET["celular"] : ""; ?>" placeholder="Celular" required>
                                <label for="emailInput">Celular</label>
                                <div class="invalid-feedback">Por favor ingrese un numero celular válido.</div>
                            </div>

                            <div class="form-floating mt-3">
                                <?php
                                    if(!empty($_GET['error']) && $_GET['error']==1){
                                        echo '<p class="text-center text-danger fs-12px mb-30">La validación ha sido incorrecta.</p>';
                                    }
                                    $numA1 = rand(1, 10);
                                    $numA2 = rand(1, 10);
                                    $resultadoA = $numA1 + $numA2;
                                ?>
                                <input type="hidden" name="sumaReal" value="<?= md5($resultadoA); ?>" />
                                <input type="text" class="form-control input-login" name="suma" value="<?= !empty($_GET["suma"]) ? $_GET["suma"] : ""; ?>" placeholder="Valida que no eres un Robot. ¿Cuánto es <?= $numA1 . "+" . $numA2; ?>?" required>
                                <label for="emailInput">Valida que no eres un Robot. ¿Cuánto es <?= $numA1 . "+" . $numA2; ?>?</label>
                                <div class="invalid-feedback">Por favor ingrese el resultado de la suma.</div>
                            </div>
                            
                            <button class="w-75 btn btn-lg btn-primary btn-rounded mt-3" type="submit">Registrarme</button>
                            <div class="d-flex justify-content-center mt-5">
                                <p><a href="index.php" class="text-body">Login</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="logo-container vertical-center">
            <img src="registerM.png" alt="Mariana" style="width: 100%;">
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
    <script src="../config-general/assets-login-2023/js/pages/login.js"></script>
    <script src="../config-general/assets/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js" ></script>
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