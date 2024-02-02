<?php
include("session.php");
$input = json_decode(file_get_contents("php://input"), true);
include("verificar-usuario.php");
require_once("../class/servicios/GradoServicios.php");
require_once("../class/servicios/MediaTecnicaServicios.php");
$codigo = $input['codigo'];
$curso = GradoServicios::consultarCurso($codigo, $input['institucion'], $input['year']);
if (empty($curso["gra_cover_image"])) {
    $urlImagen = "https://picsum.photos/50" + $cont + "/500";
} else {
    $urlImagen = $curso["gra_cover_image"];
};
?>

<div class="container">
    <div class="course-details">
        <div class="card" style="width:100%">
            <img class="card-img-top course-image" width="100%" height="200px" src="<?= $urlImagen ?>" alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title  course-title"><?= $curso["gra_nombre"]; ?></h5>
                <p class="card-text">
                    <?= $curso["gra_overall_description"]; ?>

                </p>
                <p class="text-right" style=" flex-flow: row wrap; color:green"> $<?= number_format(100, 0, ",", "."); ?></p>

            </div>

            <div id="accordion">

                <div class="card">
                    <div class="card-header" id="head1" data-target="#card1" data-toggle="collapse">
                        Contenido del curso
                    </div>
                    <div id="card1" class="collapse show" aria-labelledby="head1" data-parent="#accordion">
                        <div class="card-body">
                            <?= $curso["gra_course_content"]; ?>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="head2" data-target="#card2" data-toggle="collapse">
                        Detalles adicionales
                    </div>
                    <div id="card2" class="collapse" aria-labelledby="head2" data-parent="#accordion">
                        <div class="card-body">
                            <ul>
                                <li>Duración: <?= $curso["gra_duration_hours"]; ?> Hrs.</li>
                                <?php
                                $parametros = [
                                    'matcur_institucion' => $config['conf_id_institucion'],
                                    'matcur_years' => $config['conf_agno'],
                                    'matcur_id_curso' => $curso["gra_id"]
                                ];
                                $listaMatriculados = MediaTecnicaServicios::listar($parametros);
                                $hidden = '';
                                if (!empty($listaMatriculados)) {
                                    $numInscritos = count($listaMatriculados);
                                    foreach ($listaMatriculados as $inscrito) {
                                        if ($inscrito['matcur_id_matricula'] == $datosEstudianteActual['mat_id']) {
                                            $hidden = "hidden";
                                        }
                                    }
                                }
                                $porcentaje = ($numInscritos / $curso["gra_maximum_quota"]) * 100;
                                ?>

                            </ul>
                            <div style="height: 30px;">
                                Inscritos
                                <i class="fas fa-user mr-2"></i>(<?= $numInscritos ?>/<?= $curso["gra_maximum_quota"] ?>)
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: <?= $porcentaje ?>%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="text-align: center;">
                    <button type="button" <?= $hidden ?>  class="btn btn-primary">Inscribirme</button>

                    <p style="color:green" <?= empty($hidden) ? "hidden" : "" ?>><i class="fa fa-check"></i> Estoy inscrito</p>
                </div>


            </div>
        </div>
    </div>
</div>