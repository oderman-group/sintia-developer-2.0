<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");

if (isset($_SESSION["id"]) and $_SESSION["id"] != "") {

    require_once(ROOT_PATH."/main-app/modelo/conexion.php");
	require_once(ROOT_PATH."/main-app/class/UsuariosPadre.php");
    
    $consultaSesion = UsuariosPadre::obtenerTodosLosDatosDeUsuarios(" AND uss_id='" . $_SESSION["id"] . "'");
	$sesionAbierta = mysqli_fetch_array($consultaSesion, MYSQLI_BOTH);

    $usuario = $sesionAbierta['uss_usuario'];
    $mensaje = '';
    if( $sesionAbierta['uss_tipo'] != TIPO_ESTUDIANTE ) {
        $usuario = $sesionAbierta['uss_usuario']."-estudiante";
        $mensaje = 'Crearemos tu usuario como estudiante para que puedas tomar el curso';
    }

} else {

    $conexion = mysqli_connect($servidorConexion, $usuarioConexion, $claveConexion, $baseDatosServicios);

}

$consultaCurso = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_grados 
WHERE id_nuevo = '".$_GET["course"]."' AND gra_estado=1 AND gra_active=1 AND gra_auto_enrollment=1 AND gra_tipo='".GRADO_INDIVIDUAL."'");
$resultado = mysqli_fetch_array($consultaCurso, MYSQLI_BOTH);

require_once(ROOT_PATH."/main-app/class/Plataforma.php");
$datosContactoSintia = Plataforma::infoContactoSintia();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$resultado['gra_nombre'];?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
</head>
<body>

    <div class="course-details">
        <div class="course-header">
            <img class="course-image" src="https://cdn.pixabay.com/photo/2014/12/30/13/44/programming-583923_1280.jpg" alt="Curso 1">
            <div class="course-info">
                <div class="course-title"><?=$resultado['gra_nombre'];?></div>
                <div class="course-price"><?=$resultado['gra_price'];?></div>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Inscribirme</button>
            </div>
        </div>
        <div class="course-content">
            <div class="section">
                <h2>Descripción del Curso</h2>
                <p><?=$resultado['gra_overall_description'];?></p>
            </div>
            <div class="section">
                <h2>Contenido del Curso</h2>
                <p><?=$resultado['gra_course_content'];?></p>
            </div>
            <div class="section">
                <h2>Detalles Adicionales</h2>
                <ul>
                    <li>Duración: <?=$resultado['gra_duration_hours'];?> horas</li>
                    <li>Nivel: Intermedio</li>
                    <li>Instructores: Juan Pérez, María Gómez</li>
                    <!-- Agrega más detalles según tu curso -->
                </ul>
            </div>
        </div>
    </div>


    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="container">
                <div class="row m-2">
                    <!-- Formulario a la izquierda -->
                    <div class="col-md-6">
                        <form>

                            <div class="form-group">
                                <label for="nombre">Tipo de usuario actual:</label>
                                <input type="text" class="form-control" id="usuario" placeholder="Ingrese su nombre" value="<?=$sesionAbierta['pes_nombre'];?>" disabled>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Usuario:</label>
                                <input type="text" class="form-control" id="usuario" placeholder="Ingrese su nombre" value="<?=$usuario;?>">
                                <span style="font-size: 10px; color:darkblue;"><?=$mensaje;?></span>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Nombre:</label>
                                <input type="text" class="form-control" id="nombre" placeholder="Ingrese su nombre" value="<?=$sesionAbierta['uss_nombre'];?>">
                            </div>
                            <div class="form-group">
                                <label for="nombre">Apellido:</label>
                                <input type="text" class="form-control" id="apellido" placeholder="Ingrese su apellido" value="<?=$sesionAbierta['uss_apellido1'];?>">
                            </div>
                            <div class="form-group">
                                <label for="tarjeta">Número de Tarjeta:</label>
                                <input type="text" class="form-control" id="tarjeta" placeholder="Ingrese el número de tarjeta">
                            </div>
                            <!-- Agrega más campos según tus necesidades -->

                            <button type="submit" class="btn btn-primary">Pagar</button>
                        </form>
                    </div>

                    <!-- Resumen de Pago a la derecha -->
                    <div class="col-md-6 ml-auto">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Resumen de Pago</h5>
                                <!-- Agrega información del curso y detalles de pago aquí -->
                                <p class="card-text">Curso: <?=$resultado['gra_nombre'];?></p>
                                <p class="card-text">Precio: <?=$resultado['gra_precio'];?></p>
                                <!-- Agrega más detalles según tus necesidades -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>
</html>
