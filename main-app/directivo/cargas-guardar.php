<?php
include("session.php");
include("../class/CargaAcademica.php");

$cargasNoCreadas = 0;
$cargasCreadas = 0;
$numCurso = (count($_POST["curso"]));
$contCurso = 0;
while ($contCurso < $numCurso) {

    $numGrupo = (count($_POST["grupo"]));
    $contGrupo = 0;
    while ($contGrupo < $numGrupo) {

        $numAsignatura = (count($_POST["asignatura"]));
        $contAsignatura = 0;
        while ($contAsignatura < $numAsignatura) {

            $existeCarga = CargaAcademica::validarExistenciaCarga($_POST["docente"], $_POST["curso"][$contCurso], $_POST["grupo"][$contGrupo], $_POST["asignatura"][$contAsignatura]);

            if(!$existeCarga) {
                try{
                    mysqli_query($conexion, "INSERT INTO academico_cargas (car_docente, car_curso, car_grupo, car_materia, car_periodo, car_activa, car_permiso1, car_director_grupo, car_ih, car_fecha_creada, car_responsable, car_maximos_indicadores, car_maximas_calificaciones, car_configuracion, car_valor_indicador, car_permiso2, car_indicador_automatico)VALUES('" . $_POST["docente"] . "', '" . $_POST["curso"][$contCurso] . "', '" . $_POST["grupo"][$contGrupo] . "','" . $_POST["asignatura"][$contAsignatura] . "', '" . $_POST["periodo"] . "', 1, 1, '" . $_POST["dg"] . "', '" . $_POST["ih"] . "', now(), '" . $_SESSION["id"] . "', '" . $_POST["maxIndicadores"] . "', '" . $_POST["maxActividades"] . "', '" . $_POST["valorActividades"] . "', '" . $_POST["valorIndicadores"] . "', '" . $_POST["permiso2"] . "', '" . $_POST["indicadorAutomatico"] . "')");
                } catch (Exception $e) {
                    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                    exit();
                }
                $idInsercion = mysqli_insert_id($conexion);
                $cargasCreadas ++;
            } else {
                $cargasNoCreadas ++;
            }

            
            $contAsignatura++;

        }
        $contGrupo++;
    }
    $contCurso++;
}

echo '<script type="text/javascript">window.location.href="cargas.php?docente='.$_POST["docente"].'&id='.$idInsercion.'&success=SC_DT_6&creadas='.$cargasCreadas.'&noCreadas='.$cargasNoCreadas.'";</script>';
exit();