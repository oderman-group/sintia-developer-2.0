<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/app-sintia/config-general/constantes.php");
$readonly = "";
$usuario = "";
$nombre = "";
$apellido = "";
$correo = "";
$tipoUsuario = "Estudiante";
$mensaje = "";
$identificacion = "";
$hidden = "";
$inscrito = "";


if (isset($_SESSION["id"]) and $_SESSION["id"] != "") {

    require_once(ROOT_PATH . "/main-app/modelo/conexion.php");
    require_once(ROOT_PATH . "/main-app/class/UsuariosPadre.php");

    $consultaSesion = UsuariosPadre::obtenerTodosLosDatosDeUsuarios(" AND uss_id='" . $_SESSION["id"] . "'");
    $sesionAbierta = mysqli_fetch_array($consultaSesion, MYSQLI_BOTH);
    $readonly = "readonly";
    $hidden = "hidden";
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
// solo mostramos cuando la consulta no venga null 
if (!empty($resultado)) {
    $page = $resultado['gra_nombre'];
    require_once("../class/servicios/MediaTecnicaServicios.php");
    // validamos que ademas de estar en session tambien ya este matriculado en el curso
    if (isset($_SESSION["id"]) and $_SESSION["id"] != "") {
        $consultaInscrito = mysqli_query($conexion, "SELECT mts.* FROM " . BD_ACADEMICA . ".academico_matriculas
    INNER JOIN mobiliar_sintia_admin.mediatecnica_matriculas_cursos mts ON(mat_id=matcur_id_matricula) 
    WHERE mat_documento = '" . $identificacion . "' 
    AND matcur_id_curso='" . $resultado["gra_id"] . "'  
    AND matcur_id_institucion='" . $resultado["institucion"] . "'
    AND matcur_years='" . $resultado["year"] . "'");
        $matriculaCurso = mysqli_fetch_array($consultaInscrito, MYSQLI_BOTH);
        if ($matriculaCurso != null) {
            $inscrito = "hidden";
        }
    }
    require_once(ROOT_PATH . "/main-app/class/Plataforma.php");
    $datosContactoSintia = Plataforma::infoContactoSintia();
} else {

    $page = "PAGE BLOCK";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php if (!empty($resultado)) { ?>
        <div class="course-details">
            <div class="card" style="width:100%">
                <img class="card-img-top course-image" src="../files/cursos/<?= empty($resultado["gra_cover_image"]) ? "curso.png" : $resultado["gra_cover_image"] ?>" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title  course-title"><?= $resultado['gra_nombre']; ?></h5>
                    <p class="card-text">
                        <?= $resultado['gra_overall_description']; ?>
                    </p>
                    <p class="text-right" style=" flex-flow: row wrap; font-size: 3.2rem;color:green"> $<?= number_format($resultado['gra_price'], 0, ",", "."); ?></p>
                    <div style="height: 30px;">
                        <?php
                        $parametros = [
                            'matcur_institucion' => $resultado['institucion'],
                            'matcur_years' => $resultado['year'],
                            'matcur_id_curso' => $resultado["gra_id"]
                        ];
                        $listaMatriculados = MediaTecnicaServicios::listar($parametros);
                        $numInscritos = 0;
                        if (!empty($listaMatriculados)) {
                            $numInscritos = count($listaMatriculados);
                        }
                       
                        $porcentaje = ($numInscritos / $resultado["gra_maximum_quota"]) * 100;
                        $ocultar="";
                        if ($numInscritos >= $resultado["gra_maximum_quota"]) {
                            $ocultar = "hidden";
                        }
                        ?>
                        Inscritos
                        <i class="fas fa-user mr-2"></i>(<?= $numInscritos ?>/<?= $resultado["gra_maximum_quota"] ?>)
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: <?= $porcentaje ?>%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>

                <div id="accordion">
                    <div class="card">
                        <div class="card-header" id="head1" data-target="#card1" data-toggle="collapse">
                            <h2 class="mb-0">Contenido del curso </h2>
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

                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header text-center">
                            <i class="bi bi-cart-check"></i>
                            <button type="button" <?= $inscrito ?>   <?= $ocultar ?>  class="btn btn-primary" data-toggle="modal" data-target="#Modal2">Inscribirme</button>
                            <h2 class="mb-0" style="color:green" <?= empty($inscrito) ? "hidden" : "" ?>>Estoy inscrito</h2>
                            <button type="button" <?= $hidden ?> class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Iniciar session</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="container" style="text-align: center;">
                        <form method="post" action="../controlador/autentico.php" onsubmit="return validarDatos()" class="needs-validation" novalidate>

                            <input type="hidden" name="bd" value="<?= $resultado["institucion"] ?>" />
                            <input type="hidden" name="guest" value="<?= $resultado["gra_id"] ?>" />
                            <img class="mb-4" src="../../config-general/assets-login-2023/img/logo.png" width="100">


                            <div class="form-group row">
                                <label class="col-sm-12 control-label">Usuario</label>
                                <div class="col-sm-2">
                                </div>
                                <div class="col-sm-8 ">
                                    <input type="text" class="form-control input-login" id="emailInput" name="Usuario" placeholder="Usuario" required>
                                </div>
                                <div class="invalid-feedback col-sm-12">Por favor ingrese un correo electrónico válido.</div>
                            </div>


                            <div class="form-group row">
                                <label class="col-sm-12 control-label">Contraseña</label>
                                <div class="col-sm-2">
                                </div>
                                <div class="col-sm-8 ">
                                    <input type="password" class="form-control input-login" id="password" name="Clave" placeholder="Password" required>
                                </div>
                                <div class="invalid-feedback col-sm-12">Por favor ingrese un correo electrónico válido.</div>
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

        <div class="modal fade bd-example-modal-lg" id="Modal2" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="container">

                        <!-- Formulario a la izquierda -->
                        <form action="details-guardar.php" method="post">

                        <div class="row m-2">
                            <div class="col-md-12">
                                <h3 class="card-title">INSCRIBIR AL CURSO</h3>
                                <input type="text" hidden name="institucion" class="form-control" id="tipoUsuario" value="<?= $resultado["institucion"] ?>">
                                <input type="text" hidden name="year" class="form-control" id="tipoUsuario" value="<?= $resultado["year"] ?>">
                                <input type="text" hidden name="curso" class="form-control" id="curso" value="<?= $resultado["gra_id"] ?>">
                                <input type="text" hidden name="curso_id" class="form-control" id="curso_id" value="<?= $_GET["course"] ?>">
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre">Tipo de usuario actual:</label>
                                    <input type="text" name="tipoUsuario" class="form-control" id="tipoUsuario" placeholder="Ingrese su nombre" value="<?= $tipoUsuario ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="nombre">Identificacion:<span style="color: blue; font-size: 15px;" id="nDocu"></span></label>
                                    <div id="grupo-identificacion" class="input-group mb-3">
                                        <input type="text" name="identificacion" class="form-control" required id="identificacion" onChange="validarExistencia(this,'<?= IDENTIFICAION ?>','nDocu','grupo-identificacion')" placeholder="Ingrese su Identificacion " value="<?= $identificacion ?>" <?= $readonly; ?>>
                                    </div>
                                </div>


                                    <div class="form-group">
                                        <label for="nombre">Usuario: <span style="color: blue; font-size: 15px;" id="vUsua"></label>
                                        <div id="grupo-usuario" class="input-group mb-3">
                                            <input type="text" name="usuario" required class="form-control" id="usuario" onChange="validarExistencia(this,'<?= USUARIO ?>','vUsua','grupo-usuario')" placeholder="Ingrese su nombre" value="<?= $usuario ?>" <?= $readonly; ?>>
                                        </div>
                                        <span style="font-size: 10px; color:darkblue;"><?= $mensaje; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="nombre">Nombre:</label>
                                        <input type="text" name="nombre" required class="form-control" id="nombre" placeholder="Ingrese su nombre" value="<?= $nombre ?>" <?= $readonly; ?>>
                                    </div>
                                    <div class="form-group">
                                        <label for="nombre">Apellido:</label>
                                        <input type="text" name="apellido" class="form-control" id="apellido" placeholder="Ingrese su apellido" value="<?= $apellido; ?>" <?= $readonly; ?>>
                                    </div>
                                    <div class="form-group">
                                        <label for="tarjeta">Correo: <span style="color: blue; font-size: 15px;" id="vCorreo"></label>
                                        <div id="grupo-correo" class="input-group mb-3">
                                            <input type="text" name="correo" required class="form-control" id="correo" onChange="validarExistencia(this,'<?= CORREO ?>','vCorreo','grupo-correo')" placeholder="Ingrese correo Electronico" value="<?= $correo; ?>" <?= $readonly; ?>>
                                        </div>
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
    <?php  } else { ?><style type="text/css">
            .container {
                width: 84%;
                margin: 0 auto;
                max-width: 1140px;
            }

            header {
                width: 100%;
                margin: 0px auto;
            }

            h1 {
                background: rgba(0, 0, 0, 0.3);
                text-align: center;
                color: #fff;
                font: 95px/1 "Impact";
                text-transform: uppercase;
                display: block;
                margin: 5% auto 5%;
            }
        </style>

        <div class="course-details">
            <div class="card" style="width:100%">
                <img class="card-img-top course-image" src="https://cdn.dribbble.com/users/285475/screenshots/2083086/dribbble_1.gif" alt="Card image cap">

            </div>
        </div>
        <div class="container">
            <header>
                <h1 id="fittext1">CURSO NO ENCONTRADO</h1>
            </header>
        </div>


    <?php } ?>
    <!-- steps -->
    <link rel="stylesheet" href="../../config-general/assets/plugins/steps/steps.css">
    <!--select2-->
    <link href="../../config-general/assets/plugins/select2/css/select2.css" rel="stylesheet" type="text/css" />
    <link href="../../config-general/assets/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />

    <!--bootstrap -->
    <link href="../../config-general/assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    <link href="../../config-general/assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" media="screen">
    <script type="application/javascript">
        if ('<?php echo $inscrito ?>' == 'hidden') {
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Estoy inscrito! ",
                showConfirmButton: false,
                timer: 1500
            });

        }

        function validarDatos() {
            var nombre = document.getElementById("emailInput").value;

            if (nombre.trim() === "") {
                alert("Por favor, ingresa un nombre.");
                return false; // Evitar el envío del formulario si los datos no son válidos
            }

            return true; // Permitir el envío del formulario si los datos son válidos
        }

        function validarExistencia(enviada, tipo, span, grupo) {
            var valor = enviada.value;
            if (valor.trim() != '') {
                var spanMensage = $('#' + span);
                // se inicia el mensaje validando en azul
                spanMensage.css("color", "blue");
                spanMensage.empty().hide().html("Validando...").show(1);



                if (tipo === '<?php echo CORREO ?>') {
                    var regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!regexCorreo.test(valor)) {
                        var response = new Array("ok", 2, 3, 4, 5);
                        response["ok"] = "false";
                        validar(response, spanMensage, grupo, "El correo no es correcto");
                    } else {
                        enviarPeticion(tipo, valor, spanMensage, grupo, tipo + " ya existe en el sitema");
                    }
                } else {
                    enviarPeticion(tipo, valor, spanMensage, grupo, tipo + " ya existe en el sitema");
                }

            } else {
                idGrupo = "sub-" + grupo;
                subDiv = document.getElementById(idGrupo);
                if (subDiv) {
                    subDiv.remove();
                }
            }


        }

        function enviarPeticion(tipo, valor, spanMensage, grupo, msgError) {
            var url = "ajax-details-validar.php";
            var data = {
                "tipo": tipo,
                "valor": (valor),
                "year": <?php echo $resultado["year"] ?>,
                "institucion": <?php echo $resultado["institucion"] ?>
            };
            fetch(url, {
                    method: "POST", // or 'PUT'
                    body: JSON.stringify(data), // data can be `string` or {object}!
                    headers: {
                        "Content-Type": "application/json"
                    },
                })
                .then((res) => res.json())
                .catch((error) => console.error("Error:", error))
                .then(
                    function(response) {
                        spanMensage.empty().hide().show(1);
                        validar(response, spanMensage, grupo, msgError);

                    });
        }

        function validar(response, spanMensage, grupo, msgError) {
            idGrupo = "sub-" + grupo;
            subDiv = document.getElementById(idGrupo);
            if (subDiv) {
                subDiv.remove();
            }
            grupo = document.getElementById(grupo); // identificamos el grupo a editar

            const nuevoDiv = document.createElement('div'); // se crea el div del grupo
            nuevoDiv.classList.add('input-group-append');
            nuevoDiv.id = idGrupo;

            const nuevoBoton = document.createElement('button'); // se crea un  boton para mostrar la validacion
            nuevoBoton.classList.add('btn', 'btn-outline-secondary');

            const nuevaImg = document.createElement('img'); // se crea la imagen

            if (response.ok) {
                nuevaImg.src = "../files/iconos/1363803022_001_05.png";
                nuevoBoton.appendChild(nuevaImg); // sea agrega la imagen al boton
                nuevoDiv.name = "formIvalido";
                nuevoDiv.value = "false";
                spanMensage.css("color", "red");
                spanMensage.empty().hide().html(msgError + "...").show(1);
            } else {
                nuevaImg.src = "../files/iconos/check1.png";
                nuevoBoton.appendChild(nuevaImg); // sea agrega la imagen al boton
            }
            nuevoDiv.appendChild(nuevoBoton); // se agrega el boton al div
            grupo.appendChild(nuevoDiv); // se añade el boton al div
        }
    </script>
    <!-- start js include path -->
    <script src="../../config-general/assets/plugins/jquery/jquery.min.js"></script>
    <!-- bootstrap -->
    <script src="../../config-general/assets/plugins/bootstrap/js/bootstrap.min.js"></script>

</body>

</html>