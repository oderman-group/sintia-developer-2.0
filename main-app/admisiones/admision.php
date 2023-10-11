<?php
include("bd-conexion.php");

$gradosConsulta = "SELECT * FROM academico_grados
WHERE gra_estado = 1";
$grados = $pdoI->prepare($gradosConsulta);
$grados->execute();
$num = $grados->rowCount();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admisiones | Plataforma sintia</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <!-- favicon -->
    <link rel="shortcut icon" href="../sintia-icono.png" />
</head>

<body>
    <div class="container">
        <?php include("menu.php"); ?>
        <div class="row justify-content-md-center">
            <div class="col col-lg-12">
                <!-- Button trigger modal -->
                
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Políticas</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <?=$datosConfig['cfgi_politicas_texto'];?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="jumbotron">
                    <h1 class="display-5">Hola, bienvenido!</h1>
                    <p class="lead">
                        El formulario de inscripción tiene un costo de $<?= number_format($valorInscripcion, 0, ".", "."); ?>.
                    </p>
                    <hr class="my-4">
                    <p><?=$datosConfig['cfgi_texto_inicial'];?></p>
                </div>

                <?php
                if($datosConfig['cfgi_mostrar_banner']==1 && !empty($datosConfig['cfgi_banner_inicial']) && file_exists('../files/imagenes-generales/'.$datosConfig['cfgi_banner_inicial'])){?>
                    <div class="row mb-1 mt-1">
                        <div class="col-sm-12">
                            <img class="img-responsive" src="<?='../files/imagenes-generales/'.$datosConfig['cfgi_banner_inicial'];?>" width="100%">
                        </div>
                    </div>
                <?php }?>

                <hr class="my-4">
                <?php include("alertas.php"); ?>
                <h3 style="text-align: center;">REGISTRO INICIAL</h3>
                <form action="index-guardar.php" method="post" class="border border-secondary rounded-top p-2">
                    <input type="hidden" name="iditoken" value="<?= md5($_REQUEST['idInst']); ?>">
                    <input type="hidden" name="idInst" value="<?= $_REQUEST['idInst']; ?>">
                    <p class="lead text-danger">
                        (*) Todos los campos de este formulario son obligatorios.
                    </p>
                    <h3>1. Datos del estudiante</h3>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="tipoDocumento">Tipo de documento</label>
                            <select id="tipoDocumento" name="tipoDocumento" class="form-control" required>
                                <option value="" selected>Escoja una opción...</option>
                                <option value="105">Cédula de ciudadanía</option>
                                <option value="106">NUIP</option>
                                <option value="107">Tarjeta de identidad</option>
                                <option value="108">Registro civil</option>
                                <option value="109">Cédula de extranjería</option>
                                <option value="110">Pasaporte</option>
                                <option value="139">PEP</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="documento">Número de documento</label>
                            <input type="text" class="form-control" id="documento" name="documento" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="apellido1">Primer apellido</label>
                            <input type="text" class="form-control" id="apellido1" name="apellido1" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="nombreEstudiante">Nombre</label>
                            <input type="text" class="form-control" id="nombreEstudiante" name="nombreEstudiante" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="grado">Grado al que aspira</label>
                            <select id="grado" name="grado" class="form-control" required>
                                <option value="" selected>Escoja una opción...</option>
                                <?php
                                while ($datosGrado = $grados->fetch()) {
                                ?>
                                    <option value="<?php echo $datosGrado['gra_id']; ?>"><?php echo $datosGrado['gra_nombre']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <h3>2. Datos del acudiente</h3>
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label for="documentoAcudiente">Número de documento</label>
                            <input type="text" class="form-control" id="documentoAcudiente" name="documentoAcudiente" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="nombreAcudiente">Nombre completo</label>
                            <input type="text" class="form-control" id="nombreAcudiente" name="nombreAcudiente" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="celular">Celular</label>
                            <input type="text" class="form-control" id="celular" name="celular" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="gridCheck" required>
                            <label class="form-check-label" for="gridCheck">
                                Autorizo al tratamiento de datos personales. 
                                <?php
                                    if(!empty($datosConfig['cfgi_politicas_adjunto']) && file_exists('../files/imagenes-generales/'.$datosConfig['cfgi_politicas_adjunto'])){
                                        switch($datosConfig['cfgi_mostrar_politicas']){
                                            case 1:
                                                $enlace='target="_blank" href="../files/imagenes-generales/'.$datosConfig['cfgi_politicas_adjunto'].'"';
                                            break;

                                            case 2:
                                                $enlace='href="javascript:void(0);" onClick="mostrarPoliticas()"';
                                            break;

                                            default:
                                            $enlace='target="_blank" href="../files/imagenes-generales/'.$datosConfig['cfgi_politicas_adjunto'].'"';
                                            break;
                                        }
                                ?>
                                    <a <?=$enlace?> style="text-decoration: underline;">Leer política de tratamiento</a>
                                <?php }?>
                            </label>
                        </div>
                    </div>
                    <p class="h6 text-info">
                        El formulario de inscripción tiene un costo de $<?= number_format($valorInscripcion, 0, ".", "."); ?>.
                    </p>
                    <div class="text-right">
                        <button type="submit" class="btn btn-lg" style="background-color:<?=$fondoBarra;?>; color:<?=$colorTexto;?>;"><i class="fa fa-credit-card" aria-hidden="true"></i> Enviar solicitud</button>
                    </div>
                </form>
                <hr class="my-4">
                <div class="jumbotron mt-4" style="text-align: center; background-color:<?=$fondoBarra;?>; color:<?=$colorTexto;?>;">
                    <p style="font-size: 20px;">Si usted ya hizo este registro por favor consulte el estado de su solicitud para validar el paso a seguir.</p>
                    <a class="btn btn-primary btn-lg" href="consultar-estado.php?idInst=<?= $_REQUEST['idInst'] ?>" role="button">Consultar estado de solicitud</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        function mostrarPoliticas(){
            $("#exampleModal").modal("show");
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>

</html>