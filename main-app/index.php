<?php
if(!isset($_GET['nodb'])) {
    require_once("index-logica.php");
}
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
                        <form method="post" action="controlador/autentico.php" class="needs-validation" novalidate>
                            <?php include("../config-general/mensajes-informativos.php"); ?>
		                        <input type="hidden" name="urlDefault" value="<?php if(isset($_GET["urlDefault"])) echo $_GET["urlDefault"]; ?>" />
                            <img class="mb-4" src="../config-general/assets-login-2023/img/logo.png" width="100">

                            <div class="form-floating mt-3">
                                <select class="form-select select2" id="institution" name="bd"
                                    aria-label="Default select example" required>
                                    <option value="">Seleccione una institución</option>
                                    <?php
                                    while($instituciones = mysqli_fetch_array($institucionesConsulta, MYSQLI_BOTH)){
                                      $selected = (isset($_GET['inst']) and $_GET['inst']==$instituciones['ins_id']) ? 'selected' : '';
                                    ?>
                                      <option value="<?=$instituciones['ins_id'];?>" <?=$selected;?>><?=$instituciones['ins_siglas'];?></option>
                                    <?php }?>
                                </select>
                                <label for="institution">Institucion</label>
                                <div class="invalid-feedback">Por favor seleccione una institución.</div>
                            </div>
                            
                            <div class=" form-floating mt-3">
                                <input type="text" class="form-control input-login" id="emailInput" name="Usuario"
                                    placeholder="Usuario" required>
                                <label for="emailInput">Usuario</label>
                                <div class="invalid-feedback">Por favor ingrese un correo electrónico válido.</div>
                            </div>

                            <div class="form-floating input-group mt-3">
                                <input type="password" class="form-control input-login" id="password" name="Clave"
                                    placeholder="Password" required>
                                <button class="btn btn-outline-secondary input-group-text toggle-password"
                                    type="button">
                                    <i class="bi bi-eye-slash"></i>
                                </button>
                                <label for="password">Contraseña</label>
                                <div class="invalid-feedback">usuario y/o contraseña invalido</div>
                                <div class="form-text" id="caps-lock-message" style="display: none;">Mayúsculas
                                    activadas</div>
                            </div>
                            
                            <div class="form-floating mt-3" style="display: none;">
                                <select class="form-select select-invalid" id="year" name="year"
                                    aria-label="Default select example">
                                    <option value="" disabled selected>Seleccione un año</option>
                                    <option value="2022" selected>2022</option>
                                </select>
                                <label for="year">Año</label>
                                <div class="invalid-feedback">Por favor seleccione un año.</div>
                            </div>

                            <div class="d-flex justify-content-end mt-5">
                                <a class="forgot-password" id="forgot-password" href="recuperar-clave.php">¿Ha olvidado su contraseña?</a>
                            </div>
                            <button class="w-75 btn btn-lg btn-primary btn-rounded mt-3" type="submit">Empezar la aventura</button>
                            <div class="d-flex justify-content-center mt-5">
                                <p><a href="https://docs.google.com/forms/d/e/1FAIpQLSdiugXhzAj0Ysmt2gthO07tbvjxTA7CHcZqgzBpkefZC6T2qg/viewform" class="text-body" target="_blank">¿Requieres soporte?</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="logo-container vertical-center">
            <lottie-player src="<?=$datosContactoSintia['dtc_animacion_login'];?>" background="transparent"
                speed="1" style="width: 500px; height: 500px;" loop autoplay></lottie-player>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
    <script src="../config-general/assets-login-2023/js/pages/login.js"></script>
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