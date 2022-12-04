<?php
include("bd-conexion.php");
include("php-funciones.php");

if (md5($_GET['id']) != $_GET['token']) {
    redireccionMal('respuestas-usuario.php', 4);
}

$estQuery = "SELECT * FROM academico_matriculas
LEFT JOIN usuarios ON uss_id=mat_acudiente
WHERE mat_solicitud_inscripcion = :id";
$est = $pdoI->prepare($estQuery);
$est->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
$est->execute();
$num = $est->rowCount();
$datos = $est->fetch();

//Documentos
$documentosQuery = "SELECT * FROM academico_matriculas_documentos WHERE matd_matricula = :id";
$documentos = $pdoI->prepare($documentosQuery);
$documentos->bindParam(':id', $datos['mat_id'], PDO::PARAM_INT);
$documentos->execute();
$datosDocumentos = $documentos->fetch();

//Padre
$padreQuery = "SELECT * FROM usuarios WHERE uss_id = :id";
$padre = $pdoI->prepare($padreQuery);
$padre->bindParam(':id', $datos['mat_padre'], PDO::PARAM_INT);
$padre->execute();
$datosPadre = $padre->fetch();

//Madre
$madreQuery = "SELECT * FROM usuarios WHERE uss_id = :id";
$madre = $pdoI->prepare($madreQuery);
$madre->bindParam(':id', $datos['mat_madre'], PDO::PARAM_INT);
$madre->execute();
$datosMadre = $madre->fetch();
?>

<!DOCTYPE html>

<html lang="en">



<head>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Formulario de inscripción</title>

    <style>
        .link {
            text-decoration: underline;
        }
        .link:hover{
            font-size: 17px;
        }
    </style>

</head>



<body>

    <div class="container mb-4">

        <?php include("menu.php"); ?>

        <?php include("alertas.php"); ?>

        <div class="row">
            <div class="col-sm offset-sm-8">
                <?php if ($datos['mat_foto'] != "" and file_exists('files/fotos/' . $datos['mat_foto'])) { ?>

                    <img class="img-thumbnail float-right" src="files/fotos/<?= $datos['mat_foto']; ?>">

                <?php } ?>
            </div>
        </div>

        <form action="formulario-guardar.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="idMatricula" value="<?= $datos['mat_id']; ?>">
            <input type="hidden" name="solicitud" value="<?= $_GET["id"]; ?>">
            <input type="hidden" name="idAcudiente" value="<?= $datos['mat_acudiente']; ?>">
            <input type="hidden" name="idPadre" value="<?= $datos['mat_padre']; ?>">
            <input type="hidden" name="idMadre" value="<?= $datos['mat_madre']; ?>">

            <input type="hidden" name="fotoA" value="<?= $datos['mat_foto']; ?>">


            <h3 class="mb-4" style="text-align: center;">1. INFORMACIÓN PERSONAL DEL ASPIRANTE</h3>

            <div class="form-row">

                <div class="form-group col-md-4">

                    <label>Nombres <span style="color:red;">(*)</span> </label>

                    <input type="text" class="form-control" name="nombre" value="<?= $datos['mat_nombres']; ?>" required>

                </div>



                <div class="form-group col-md-3">

                    <label>Primer Apellido <span style="color:red;">(*)</span></label>

                    <input type="text" class="form-control" name="primerApellidos" value="<?= $datos['mat_primer_apellido']; ?>" required>

                </div>



                <div class="form-group col-md-3">

                    <label>Segundo Apellido</label>

                    <input type="text" class="form-control" name="segundoApellidos" value="<?= $datos['mat_segundo_apellido']; ?>">

                </div>





                <div class="form-group col-md-2">

                    <label>Género <span style="color:red;">(*)</span> </label>

                    <select class="form-control" name="genero" required>



                        <option value="">Escoger</option>

                        <option value="127" <?php if ($datos['mat_genero'] == 127) echo "selected"; ?>>Femenino</option>

                        <option value="126" <?php if ($datos['mat_genero'] == 126) echo "selected"; ?>>Masculino</option>



                    </select>

                </div>



            </div>







            <div class="form-row">





                <div class="form-group col-md-4">

                    <label>Tipo de documento <span style="color:red;">(*)</span></label>

                    <select class="form-control" name="tipoDoc" required>

                        <option value="">Escoger</option>

                        <option value="105" <?php if ($datos['mat_tipo_documento'] == 105) echo "selected"; ?>>Cédula de ciudadanía</option>

                        <option value="106" <?php if ($datos['mat_tipo_documento'] == 106) echo "selected"; ?>>NUIP</option>

                        <option value="107" <?php if ($datos['mat_tipo_documento'] == 107) echo "selected"; ?>>Tarjeta de identidad</option>

                        <option value="108" <?php if ($datos['mat_tipo_documento'] == 108) echo "selected"; ?>>Registro civil o NUIP</option>

                        <option value="109" <?php if ($datos['mat_tipo_documento'] == 109) echo "selected"; ?>>Cédula de Extranjería</option>

                        <option value="110" <?php if ($datos['mat_tipo_documento'] == 110) echo "selected"; ?>>Pasaporte</option>

                        <option value="139" <?php if ($datos['mat_tipo_documento'] == 139) echo "selected"; ?>>PEP</option>



                    </select>

                </div>



                <div class="form-group col-md-4">

                    <label>Numero de documento <span style="color:red;">(*)</span></label>

                    <input type="text" class="form-control" name="numeroDoc" value="<?= $datos['mat_documento']; ?>">

                </div>



                <div class="form-group col-md-4">

                    <label>Lugar de expedición <span style="color:red;">(*)</span></label>

                    <input type="text" class="form-control" name="LugarExp" value="<?= $datos['mat_lugar_expedicion']; ?>" required>

                </div>





            </div>





            <div class="form-row">



                <div class="form-group col-md-6">

                    <label>Lugar de nacimiento <span style="color:red;">(*)</span></label>

                    <input type="text" class="form-control" name="LugarNacimiento" value="<?= $datos['mat_lugar_nacimiento']; ?>" required>

                </div>



                <div class="form-group col-md-6">

                    <label>Fecha de Nacimiento <span style="color:red;">(*)</span></label>

                    <input type="date" class="form-control" name="fechaNacimiento" value="<?= $datos['mat_fecha_nacimiento']; ?>" required>

                </div>





            </div>



            <div class="form-row">



                <div class="form-group col-md-4">

                    <label>Dirección <span style="color:red;">(*)</span></label>

                    <input type="text" class="form-control" name="direccion" value="<?= $datos['mat_direccion']; ?>" required>

                </div>



                <div class="form-group col-md-4">

                    <label>Barrio <span style="color:red;">(*)</span></label>

                    <input type="text" class="form-control" name="barrio" value="<?= $datos['mat_barrio']; ?>" required>

                </div>



                <div class="form-group col-md-4">

                    <label>Municipio <span style="color:red;">(*)</span></label>

                    <input type="text" class="form-control" name="municipio" value="<?= $datos['mat_ciudad_actual']; ?>" required>

                </div>

            </div>


            <div class="form-row">





                <div class="form-group col-md-6">

                    <label>Curso al que aspira <span style="color:red;">(*)</span></label>

                    <select class="form-control" name="curso">

                        <option value="">Escoger</option>

                        <option value="13" <?php if ($datos['mat_grado'] == 13) echo "selected"; ?>>Pre-Jardín</option>
                        <option value="14" <?php if ($datos['mat_grado'] == 14) echo "selected"; ?>>Jardín</option>
                        <option value="15" <?php if ($datos['mat_grado'] == 15) echo "selected"; ?>>Transición</option>
                        <option value="1" <?php if ($datos['mat_grado'] == 1) echo "selected"; ?>>Primero</option>
                        <option value="2" <?php if ($datos['mat_grado'] == 2) echo "selected"; ?>>Segundo</option>
                        <option value="3" <?php if ($datos['mat_grado'] == 3) echo "selected"; ?>>Tercero</option>
                        <option value="4" <?php if ($datos['mat_grado'] == 4) echo "selected"; ?>>Cuarto</option>
                        <option value="5" <?php if ($datos['mat_grado'] == 5) echo "selected"; ?>>Quinto</option>
                        <option value="6" <?php if ($datos['mat_grado'] == 6) echo "selected"; ?>>Sexto</option>
                        <option value="7" <?php if ($datos['mat_grado'] == 7) echo "selected"; ?>>Séptimo</option>
                        <option value="8" <?php if ($datos['mat_grado'] == 8) echo "selected"; ?>>Octavo</option>
                        <option value="9" <?php if ($datos['mat_grado'] == 9) echo "selected"; ?>>Noveno</option>
                        <option value="10" <?php if ($datos['mat_grado'] == 10) echo "selected"; ?>>Décimo</option>
                        <option value="11" <?php if ($datos['mat_grado'] == 11) echo "selected"; ?>>Undécimo</option>

                    </select>

                </div>



                <div class="form-group col-md-6">

                    <label>Razón por la que desea ingresar al plantel <span style="color:red;">(*)</span></label>

                    <input type="text" class="form-control" name="razonPlantel" value="<?= $datos['mat_razon_ingreso_plantel']; ?>" required>

                </div>





            </div>


            <div class="form-row">





                <div class="form-group col-md-6">

                    <label>Colegio donde cursó su último año</label>

                    <input type="text" class="form-control" name="coleAnoAnterior" value="<?= $datos['mat_institucion_procedencia']; ?>">

                </div>



                <div class="form-group col-md-6">

                    <label>Lugar</label>

                    <input type="text" class="form-control" name="lugar" value="<?= $datos['mat_lugar_colegio_procedencia']; ?>">

                </div>



            </div>





            <div class="form-group">

                <label>Motivo de retiro</label>

                <input type="text" class="form-control" name="motivo" value="<?= $datos['mat_motivo_retiro_anterior']; ?>">

            </div>




            <h3 class="mb-4" style="text-align: center;">2. INFORMACIÓN FAMILIAR</h3>

            <h5 class="mb-4">INFORMACIÓN DEL PADRE</h5>
            <div class="form-row">



                <div class="form-group col-md-5">

                    <label>Nombres y Apellidos del padre <span style="color:red;">(*)</span> </label>

                    <input type="text" class="form-control" name="nombrePadre" value="<?= $datosPadre['uss_nombre']; ?>" required>

                </div>



                <div class="form-group col-md-3">

                    <label>Tipo de Documento <span style="color:red;">(*)</span> </label>

                    <select class="form-control" name="tipoDocumentoPadre" required>



                        <option value="">Escoger</option>

                        <option value="105" <?php if ($datosPadre['uss_tipo_documento'] == 105) echo "selected"; ?>>Cédula de ciudadanía</option>

                        <option value="106" <?php if ($datosPadre['uss_tipo_documento'] == 106) echo "selected"; ?>>NUIP</option>

                        <option value="107" <?php if ($datosPadre['uss_tipo_documento'] == 107) echo "selected"; ?>>Tarjeta de identidad</option>

                        <option value="108" <?php if ($datosPadre['uss_tipo_documento'] == 108) echo "selected"; ?>>Registro civil o NUIP</option>

                        <option value="109" <?php if ($datosPadre['uss_tipo_documento'] == 109) echo "selected"; ?>>Cédula de Extranjería</option>

                        <option value="110" <?php if ($datosPadre['uss_tipo_documento'] == 110) echo "selected"; ?>>Pasaporte</option>

                        <option value="139" <?php if ($datosPadre['uss_tipo_documento'] == 139) echo "selected"; ?>>PEP</option>

                    </select>

                </div>





                <div class="form-group col-md-4">

                    <label>Número de Documento<span style="color:red;">(*)</span></label>

                    <input type="text" class="form-control" value="<?= $datosPadre['uss_usuario']; ?>" name="documentoPadre">

                </div>



            </div>



            <div class="form-row">



                <div class="form-group col-md-4">

                    <label>Religión <span style="color:red;">(*)</span> </label>

                    <input type="text" class="form-control" name="religionPadre" value="<?= $datosPadre['uss_religion']; ?>" required>

                </div>





                <div class="form-group col-md-4">

                    <label>Teléfono</label>

                    <input type="text" class="form-control" name="telfonoPadre" value="<?= $datosPadre['uss_telefono']; ?>">

                </div>



                <div class="form-group col-md-4">

                    <label>Número celular<span style="color:red;">(*)</span> </label>

                    <input type="text" class="form-control" name="celularPadre" value="<?= $datosPadre['uss_celular']; ?>">

                </div>



            </div>



            <div class="form-row">



                <div class="form-group col-md-4">

                    <label>Dirección<span style="color:red;">(*)</span></label>

                    <input type="text" class="form-control" name="direccionPadre" value="<?= $datosPadre['uss_direccion']; ?>">

                </div>





                <div class="form-group col-md-4">

                    <label>Email<span style="color:red;">(*)</span></label>

                    <input type="email" class="form-control" name="emailPadre" value="<?= $datosPadre['uss_email']; ?>">

                </div>



                <div class="form-group col-md-4">

                    <label>Ocupación<span style="color:red;">(*)</span> </label>

                    <input type="text" class="form-control" name="ocupacionPadre" value="<?= $datosPadre['uss_ocupacion']; ?>">

                </div>



            </div>

            <h5 class="mb-4">INFORMACIÓN DE LA MADRE</h5>



            <div class="form-row">



                <div class="form-group col-md-5">

                    <label>Nombres y Apellidos de la Madre <span style="color:red;">(*)</span> </label>

                    <input type="text" class="form-control" name="nombreMadre" value="<?= $datosMadre['uss_nombre']; ?>" required>

                </div>



                <div class="form-group col-md-3">

                    <label>Tipo de Documento <span style="color:red;">(*)</span> </label>

                    <select class="form-control" name="tipoDocumentoMadre" required>



                        <option value="">Escoger</option>

                        <option value="105" <?php if ($datosMadre['uss_tipo_documento'] == 105) echo "selected"; ?>>Cédula de ciudadanía</option>

                        <option value="106" <?php if ($datosMadre['uss_tipo_documento'] == 106) echo "selected"; ?>>NUIP</option>

                        <option value="107" <?php if ($datosMadre['uss_tipo_documento'] == 107) echo "selected"; ?>>Tarjeta de identidad</option>

                        <option value="108" <?php if ($datosMadre['uss_tipo_documento'] == 108) echo "selected"; ?>>Registro civil o NUIP</option>

                        <option value="109" <?php if ($datosMadre['uss_tipo_documento'] == 109) echo "selected"; ?>>Cédula de Extranjería</option>

                        <option value="110" <?php if ($datosMadre['uss_tipo_documento'] == 110) echo "selected"; ?>>Pasaporte</option>

                        <option value="139" <?php if ($datosMadre['uss_tipo_documento'] == 139) echo "selected"; ?>>PEP</option>

                    </select>

                </div>





                <div class="form-group col-md-4">

                    <label>Número de Documento<span style="color:red;">(*)</span></label>

                    <input type="text" class="form-control" value="<?= $datosMadre['uss_usuario']; ?>" name="documentoMadre">

                </div>



            </div>



            <div class="form-row">



                <div class="form-group col-md-4">

                    <label>Religión <span style="color:red;">(*)</span> </label>

                    <input type="text" class="form-control" name="religionMadre" value="<?= $datosMadre['uss_religion']; ?>" required>

                </div>





                <div class="form-group col-md-4">

                    <label>Teléfono</label>

                    <input type="text" class="form-control" value="<?= $datosMadre['uss_telefono']; ?>" name="telfonoMadre">

                </div>



                <div class="form-group col-md-4">

                    <label>Número celular<span style="color:red;">(*)</span> </label>

                    <input type="text" class="form-control" name="celularMadre" value="<?= $datosMadre['uss_celular']; ?>">

                </div>



            </div>



            <div class="form-row">



                <div class="form-group col-md-4">

                    <label>Dirección<span style="color:red;">(*)</span></label>

                    <input type="text" class="form-control" name="direccionMadre" value="<?= $datosMadre['uss_direccion']; ?>">

                </div>





                <div class="form-group col-md-4">

                    <label>Email<span style="color:red;">(*)</span></label>

                    <input type="email" class="form-control" name="emailMadre" value="<?= $datosMadre['uss_email']; ?>">

                </div>



                <div class="form-group col-md-4">

                    <label>Ocupación<span style="color:red;">(*)</span> </label>

                    <input type="text" class="form-control" name="ocupacionMadre" value="<?= $datosMadre['uss_ocupacion']; ?>">

                </div>



            </div>


            <h5 class="mb-4">INFORMACIÓN DEL ACUDIENTE <span style="color:red;">(El acudiente es quien se reportará en la DIAN en la información exógena)</span></h5>



            <div class="form-row">

                <div class="form-group col-md-3">

                    <label>Documento<span style="color:red;">(*)</span></label>

                    <input type="text" class="form-control" name="documentoAcudiente" value="<?= $datos['uss_usuario']; ?>">

                </div>

                <div class="form-group col-md-6">

                    <label>Nombres y Apellidos del Acudiente <span style="color:red;">(*) Completos</span> </label>

                    <input type="text" class="form-control" name="nombreAcudiente" value="<?= $datos['uss_nombre']; ?>" required>

                </div>



                <div class="form-group col-md-3">

                    <label>Parentesco<span style="color:red;">(*)</span></label>

                    <input type="text" class="form-control" name="parentesco" value="<?= $datos['uss_parentezco']; ?>">

                </div>







            </div>



            <div class="form-row">

                <div class="form-group col-md-4">

                    <label>Religión <span style="color:red;">(*)</span> </label>

                    <input type="text" class="form-control" name="religionAcudiente" value="<?= $datos['uss_religion']; ?>" required>

                </div>



                <div class="form-group col-md-4">

                    <label>Teléfono</label>

                    <input type="text" class="form-control" name="telfonoAcudiente" value="<?= $datos['uss_telefono']; ?>">

                </div>



                <div class="form-group col-md-4">

                    <label>Número celular<span style="color:red;">(*)</span> </label>

                    <input type="text" class="form-control" name="celularAcudiente" value="<?= $datos['uss_celular']; ?>">

                </div>



            </div>



            <div class="form-row">



                <div class="form-group col-md-6">

                    <label>Dirección<span style="color:red;">(*)</span></label>

                    <input type="text" class="form-control" name="direccionAcudiente" value="<?= $datos['uss_direccion']; ?>">

                </div>





                <div class="form-group col-md-6">

                    <label>Email<span style="color:red;">(*)</span></label>

                    <input type="email" class="form-control" name="emailAcudiente" value="<?= $datos['uss_email']; ?>">

                </div>


            </div>



            <h3 class="mb-4" style="text-align: center;">3. DOCUMENTACIÓN DEL ASPIRANTE</h3>

            <div class="p-3 mb-2 bg-secondary text-white">Debe cargar solo un archivo por cada campo. Si necesita cargar más de un archivo en un solo campo por favor comprimalos(.ZIP, .RAR) y los carga.</div>

            <div class="form-row">

                <div class="form-group col-md-6">

                    <label>1. Foto <span class="text-primary">(En formato .jpg, .png, .jpeg)</span> </label>

                    <input type="file" class="form-control" name="foto">

                    <?php if ($datos['mat_foto'] != "" and file_exists('files/fotos/' . $datos['mat_foto'])) { ?>
                        <p><a href="files/fotos/<?= $datos['mat_foto']; ?>" target="_blank" class="link"><?= $datos['mat_foto']; ?></a></p>
                    <?php } ?>


                </div>


                <div class="form-group col-md-6">

                    <label>2. Paz y salvo a la fecha del colegio de procedencia</label>

                    <input type="file" class="form-control" name="pazysalvo">
                    <input type="hidden" name="pazysalvoA" value="<?= $datosDocumentos['matd_pazysalvo']; ?>">

                    <?php if ($datosDocumentos['matd_pazysalvo'] != "" and file_exists('files/otros/' . $datosDocumentos['matd_pazysalvo'])) { ?>
                        <p><a href="files/otros/<?= $datosDocumentos['matd_pazysalvo']; ?>" target="_blank" class="link"><?= $datosDocumentos['matd_pazysalvo']; ?></a></p>
                    <?php } ?>

                </div>


            </div>


            <div class="form-row">

                <div class="form-group col-md-6">

                    <label>3. Ficha acumulativa u observador del alumno </label>

                    <input type="file" class="form-control" name="observador">
                    <input type="hidden" name="observadorA" value="<?= $datosDocumentos['matd_observador']; ?>">

                    <?php if ($datosDocumentos['matd_observador'] != "" and file_exists('files/otros/' . $datosDocumentos['matd_observador'])) { ?>
                        <p><a href="files/otros/<?= $datosDocumentos['matd_observador']; ?>" target="_blank" class="link"><?= $datosDocumentos['matd_observador']; ?></a></p>
                    <?php } ?>

                </div>


                <div class="form-group col-md-6">

                    <label>4. Fotocopia de la EPS</label>

                    <input type="file" class="form-control" name="eps">
                    <input type="hidden" name="epsA" value="<?= $datosDocumentos['matd_eps']; ?>">

                    <?php if ($datosDocumentos['matd_eps'] != "" and file_exists('files/otros/' . $datosDocumentos['matd_eps'])) { ?>
                        <p><a href="files/otros/<?= $datosDocumentos['matd_eps']; ?>" target="_blank" class="link"><?= $datosDocumentos['matd_eps']; ?></a></p>
                    <?php } ?>

                </div>


            </div>


            <div class="form-row">

                <div class="form-group col-md-6">

                    <label>5. Hoja de recomendación </label>

                    <input type="file" class="form-control" name="recomendacion">
                    <input type="hidden" name="recomendacionA" value="<?= $datosDocumentos['matd_recomendacion']; ?>">

                    <?php if ($datosDocumentos['matd_recomendacion'] != "" and file_exists('files/otros/' . $datosDocumentos['matd_recomendacion'])) { ?>
                        <p><a href="files/otros/<?= $datosDocumentos['matd_recomendacion']; ?>" target="_blank" class="link"><?= $datosDocumentos['matd_recomendacion']; ?></a></p>
                    <?php } ?>

                </div>


            </div>


            <div class="form-row">

                <div class="form-group col-md-6">

                    <label>6. Vacunas </label>

                    <input type="file" class="form-control" name="vacunas">
                    <input type="hidden" name="vacunasA" value="<?= $datosDocumentos['matd_vacunas']; ?>">

                    <?php if ($datosDocumentos['matd_vacunas'] != "" and file_exists('files/otros/' . $datosDocumentos['matd_vacunas'])) { ?>
                        <p><a href="files/otros/<?= $datosDocumentos['matd_vacunas']; ?>" target="_blank" class="link"><?= $datosDocumentos['matd_vacunas']; ?></a></p>
                    <?php } ?>

                </div>

                <div class="form-group col-md-6">

                    <label>7. Boletines actuales </label>

                    <input type="file" class="form-control" name="boletines">
                    <input type="hidden" name="boletinesA" value="<?= $datosDocumentos['matd_boletines_actuales']; ?>">

                    <?php if ($datosDocumentos['matd_boletines_actuales'] != "" and file_exists('files/otros/' . $datosDocumentos['matd_boletines_actuales'])) { ?>
                        <p><a href="files/otros/<?= $datosDocumentos['matd_boletines_actuales']; ?>" target="_blank" class="link"><?= $datosDocumentos['matd_boletines_actuales']; ?></a></p>
                    <?php } ?>

                </div>


            </div>

            <div class="form-row">

                <div class="form-group col-md-6">

                    <label>8. Documento de identidad (Ambas caras) </label>

                    <input type="file" class="form-control" name="documentoIde">
                    <input type="hidden" name="documentoIdeA" value="<?= $datosDocumentos['matd_documento_identidad']; ?>">

                    <?php if ($datosDocumentos['matd_documento_identidad'] != "" and file_exists('files/otros/' . $datosDocumentos['matd_documento_identidad'])) { ?>
                        <p><a href="files/otros/<?= $datosDocumentos['matd_documento_identidad']; ?>" target="_blank" class="link"><?= $datosDocumentos['matd_documento_identidad']; ?></a></p>
                    <?php } ?>

                </div>

                <div class="form-group col-md-6">

                    <label>9. Certificado </label>

                    <input type="file" class="form-control" name="certificado">
                    <input type="hidden" name="certificadoA" value="<?= $datosDocumentos['matd_certificados']; ?>">

                    <?php if ($datosDocumentos['matd_certificados'] != "" and file_exists('files/otros/' . $datosDocumentos['matd_certificados'])) { ?>
                        <p><a href="files/otros/<?= $datosDocumentos['matd_certificados']; ?>" target="_blank" class="link"><?= $datosDocumentos['matd_certificados']; ?></a></p>
                    <?php } ?>

                </div>


            </div>




            <hr class="my-4">




            <div class="form-group">

                <div class="form-check">

                    <input class="form-check-input" type="checkbox" id="gridCheck" required>

                    <label class="form-check-label" for="gridCheck">

                        Estoy suficientemente informado del Manual de Convivencia y del Sistema Institucional de Evaluación que rigen en el Instituto Colombo Venezolano, según aparecen en la página web y en caso de ser aceptado me comprometo a acatarlos y cumplirlos fiel y cabalmente.

                    </label>

                </div>

            </div>


            <div class="p-2 mt-4 mb-4 bg-warning text-dark" style="text-align: center;">

                    <p style="font-size: 20px; font-weight: bold;">
                    Tenga en cuenta que debe tener completa toda la documentación cargada en la plataforma para que su solicitud continúe el proceso de admisión y sea agendada la respectiva entrevista y examen de admisión según sea el caso.
                </p>

                </div>


            <button type="submit" class="btn btn-success btn-lg btn-block">Guardar y enviar formulario</button>

        </form>

    </div>



    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

</body>



</html>