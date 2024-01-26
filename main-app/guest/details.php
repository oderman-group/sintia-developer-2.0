<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/app-sintia/config-general/constantes.php");
$disabled = "";
$usuario = "";
$nombre = "";
$apellido = "";
$correo = "";
$tipoUsuario = "Estudiante";
$mensaje = "";
$identificacion = "";
if (isset($_SESSION["id"]) and $_SESSION["id"] != "") {

    require_once(ROOT_PATH . "/main-app/modelo/conexion.php");
    require_once(ROOT_PATH . "/main-app/class/UsuariosPadre.php");

    $consultaSesion = UsuariosPadre::obtenerTodosLosDatosDeUsuarios(" AND uss_id='" . $_SESSION["id"] . "'");
    $sesionAbierta = mysqli_fetch_array($consultaSesion, MYSQLI_BOTH);
    $disabled = "disabled";
    $identificacion = $sesionAbierta['uss_documento'];;
    $tipoUsuario = $sesionAbierta['pes_nombre'];
    $usuario = $sesionAbierta['uss_usuario'];
    $nombre = $sesionAbierta['uss_nombre'];
    $apellido = $sesionAbierta['uss_apellido1'];
    $correo = $sesionAbierta['uss_email'];

    if ($sesionAbierta['uss_tipo'] != TIPO_ESTUDIANTE) {
        $usuario = $sesionAbierta['uss_usuario'];
        $mensaje = 'Crearemos tu usuario como estudiante para que puedas tomar el curso';
    }
} else {

    $conexion = mysqli_connect($servidorConexion, $usuarioConexion, $claveConexion, $baseDatosServicios);
}

$consultaCurso = mysqli_query($conexion, "SELECT * FROM " . BD_ACADEMICA . ".academico_grados 
WHERE id_nuevo = '" . $_GET["course"] . "' AND gra_estado=1 AND gra_active=1 AND gra_auto_enrollment=1 AND gra_tipo='" . GRADO_INDIVIDUAL . "'");
$resultado = mysqli_fetch_array($consultaCurso, MYSQLI_BOTH);

require_once(ROOT_PATH . "/main-app/class/Plataforma.php");
$datosContactoSintia = Plataforma::infoContactoSintia();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $resultado['gra_nombre']; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
</head>

<body>
    <div class="course-details">
        <div class="card" style="width:100%">
            <img class="card-img-top course-image" src="<?= $resultado['gra_cover_image']; ?>" alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title  course-title"><?= $resultado['gra_nombre']; ?></h5>
                <p class="card-text">
                    <?= $resultado['gra_overall_description']; ?>
                </p>
                <p class="text-right" style=" flex-flow: row wrap; font-size: 3.2rem;"> $<?= number_format($resultado['gra_price'], 0, ",", "."); ?></p>
                <i class="fa fa-check"></i>

            </div>

            <div id="accordion">

                <div class="card">
                    <div class="card-header" id="head1" data-target="#card1" data-toggle="collapse">
                        <h2 class="mb-0">Contenido del curso <?= $disabled ?></h2>
                    </div>
                    <div id="card1" class="collapse show" aria-labelledby="head1" data-parent="#accordion">
                        <div class="card-body">
                            <?= $resultado['gra_course_content']; ?>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="head2" data-target="#card2" data-toggle="collapse">
                        <h2 class="mb-0">Detalles adicionales</h2>
                    </div>
                    <div id="card2" class="collapse" aria-labelledby="head2" data-parent="#accordion">
                        <div class="card-body">
                            <ul>
                                <li>Duración: <?= $resultado['gra_duration_hours']; ?> horas</li>
                                <li>Nivel: <?= $resultado['gra_duration_hours']; ?></li>
                                <!-- <li>Instructor: Juan Pérez, María Gómez</li> -->

                            </ul>
                            <i class="fas fa-car"></i>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header text-center">
                        <i class="bi bi-cart-check"></i>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Inscribirme</button>
                    </div>
                </div>

            </div>
        </div>
    </div>



    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="container">

                    <!-- Formulario a la izquierda -->
                    <form action="details-guardar.php" method="post">

                        <div class="row m-2">
                            <div class="col-md-12">
                                <h3 class="card-title">INSCRIBIR AL CURSO</h3>
                            </div>
                            <div class="col-md-6">


                                <div class="form-group">
                                    <label for="nombre">Tipo de usuario actual:</label>
                                    <input type="text" class="form-control" id="usuario" placeholder="Ingrese su nombre" value="<?= $tipoUsuario ?>" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="nombre">Identificacion:</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="identificacion" onChange="nuevoEstudiante(this)" placeholder="Ingrese su Identificacion " value="<?= $identificacion ?>" <?= $disabled; ?>>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button">Button</button>
                                        </div>
                                    </div>
                                    <span style="color: blue; font-size: 15px;" id="nDocu"></span>

                                </div>


                                <div class="form-group">
                                    <label for="nombre">Usuario:</label>
                                    <input type="text" class="form-control" id="usuario" placeholder="Ingrese su nombre" value="<?= $usuario ?>" <?= $disabled; ?>>
                                    <span style="font-size: 10px; color:darkblue;"><?= $mensaje; ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="nombre">Nombre:</label>
                                    <input type="text" class="form-control" id="nombre" placeholder="Ingrese su nombre" value="<?= $nombre ?>" <?= $disabled; ?>>
                                </div>
                                <div class="form-group">
                                    <label for="nombre">Apellido:</label>
                                    <input type="text" class="form-control" id="apellido" placeholder="Ingrese su apellido" value="<?= $apellido; ?>" <?= $disabled; ?>>
                                </div>
                                <div class="form-group">
                                    <label for="tarjeta">Correo:</label>
                                    <input type="text" class="form-control" id="correo" placeholder="Ingrese correo Electronico" value="<?= $correo; ?>" <?= $disabled; ?>>
                                </div>
                                <!-- Agrega más campos según tus necesidades -->

                            </div>

                            <!-- Resumen de Pago a la derecha -->
                            <div class="col-md-6 ml-auto">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Resumen de Pago</h5>
                                        <!-- Agrega información del curso y detalles de pago aquí -->
                                        <p class="card-text">Curso: <?= $resultado['gra_nombre']; ?></p>
                                        <p class="card-text">Precio:$<?= number_format($resultado['gra_price'], 0, ",", "."); ?></p>

                                        <!-- Agrega más detalles según tus necesidades -->
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label for="tarjeta">Número de Tarjeta:</label>
                                    <input type="text" class="form-control" id="tarjeta" required placeholder="Ingrese el número de tarjeta">
                                </div>
                                <div class="mx-auto" style="height: 30px;">

                                </div>
                                <button type="submit" class="btn btn-primary btn-lg btn-block">Pagar</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- steps -->
    <link rel="stylesheet" href="../../config-general/assets/plugins/steps/steps.css">


    <!--select2-->
    <link href="../../config-general/assets/plugins/select2/css/select2.css" rel="stylesheet" type="text/css" />
    <link href="../../config-general/assets/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />

    <!--bootstrap -->
    <link href="../../config-general/assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    <link href="../../config-general/assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" media="screen">
    <script type="application/javascript">
        function nuevoEstudiante(enviada) {
            var nDoct = enviada.value;
            $('#nDocu').empty().hide().html("Validando documento...").show(1);


            var url = "ajax-details-validar.php";
            var data = {
                "nDoct": (nDoct),
                "tipo": "Identificacion"
            };
            fetch(url, {
                    method: "POST", // or 'PUT'
                    body: JSON.stringify(data), // data can be `string` or {object}!
                    headers: {
                        "Content-Type": "application/json",
                    },
                })
                .then((res) => res.json())
                .catch((error) => console.error("Error:", error))
                .then(
                    function(response) {
                        $('#nDocu').empty().hide().show(1);
                        if (response.ok) {
                            console.log("Respuesta de red OK");
                        } else {
                            console.log("Respuesta de red OK pero respuesta HTTP no OK");
                        }
                    })
                .catch(function(error) {
                    console.log("Hubo un problema con la petición Fetch:" + error.message);
                });

        }
    </script>
    <!-- start js include path -->
    <script src="../../config-general/assets/plugins/jquery/jquery.min.js"></script>
    <!-- bootstrap -->
    <script src="../../config-general/assets/plugins/bootstrap/js/bootstrap.min.js"></script>

</body>

</html>