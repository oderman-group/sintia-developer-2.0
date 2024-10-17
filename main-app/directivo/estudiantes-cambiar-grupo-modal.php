<?php

$idPaginaInterna = 'DT0083';

if (empty($_SESSION["id"])) {
    include("session.php");
    $input = json_decode(file_get_contents("php://input"), true);
    if (!empty($input)) {
        $_GET = $input;
    }
}

require_once(ROOT_PATH . "/main-app/class/Grados.php");
require_once(ROOT_PATH . "/main-app/class/Grupos.php");
require_once(ROOT_PATH . "/main-app/class/Estudiantes.php");
require_once(ROOT_PATH . "/main-app/class/CargaAcademica.php");
require_once(ROOT_PATH . "/main-app/class/componentes/botones-guardar.php");
?>

<div class="col-sm-12">

    <?php
    $id = "";
    if (!empty($_GET["id"])) {
        $id = base64_decode($_GET["id"]);
    }
    $e = Estudiantes::obtenerDatosEstudiante($id);
    ?>

    <form action="estudiantes-cambiar-grupo-estudiante.php" method="post" class="form-horizontal" enctype="multipart/form-data">
        <input type="hidden" value="<?= $e['mat_id']; ?>" name="estudiante">


        <div class="form-group row">
            <label class="col-sm-1 control-label">Estudiante</label>
            <div class="col-sm-1">
                <input type="text" name="codigoE" class="form-control" autocomplete="off" value="<?= $e['mat_id']; ?>" readonly>
            </div>

            <div class="col-sm-10">
                <input type="text" name="nombre" class="form-control" autocomplete="off" value="<?= Estudiantes::NombreCompletoDelEstudiante($e); ?>" readonly>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-1 control-label">Curso</label>

            <?php
            $gradoActual = Grados::obtenerGrado($e["mat_grado"]);
            ?>
            <div class="col-sm-1">
                <input type="text" name="cursoActual" class="form-control" autocomplete="off" value="<?= $gradoActual["gra_id"] ?>" readonly>
            </div>
            <div class="col-sm-10">
                <input type="text" class="form-control" autocomplete="off" value="<?= $gradoActual["gra_nombre"] ?>" readonly>
            </div>

        </div>

        <div class="form-group row">
            <label class="col-sm-1 control-label">Grupo</label>
            <div class="col-sm-11">
                <select class="form-control  select2" id="estudianteGrupo" name="grupoNuevo" onchange="traerCargaCursoGrupo('<?= $gradoActual['gra_id'] ?>',this.value)" required>
                    <option value="0"></option>
                    <?php
                    $opcionesConsulta = Grupos::traerGrupos($conexion, $config);
                    while ($c = mysqli_fetch_array($opcionesConsulta, MYSQLI_BOTH)) {
                        if ($c["gru_id"] == $e['mat_grupo'])
                            echo '<option value="' . $c["gru_id"] . '" selected style="color:blue; font-weight:bold;">Actual: ' . $c["gru_nombre"] . '</option>';
                        else
                            echo '<option value="' . $c["gru_id"] . '">' . $c["gru_nombre"] . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-1 control-label"></label>
            <div class="col-sm-11">
                <table style="width:100%;">

                    <thead>

                        <tr>
                            <th width="5%" rowspan="2">#</th>
                            <th rowspan="2">ID</th>
                            <th rowspan="2">Carga</th>
                            <th width="50%" rowspan="2">Materia</th>
                            <th colspan="<?= $config["conf_periodos_maximos"] ?>">Notas Periodos</th>
                            <th width="30%" rowspan="2">Materias Relacionada</th>
                        </tr>
                        <tr>
                            <?php for ($i = 1; $i <= $config["conf_periodos_maximos"]; $i++) { ?>
                                <th width="5%">P<?= $i ?></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $filtroLimite = '';


                        $consulta = CargaAcademica::consultarEstudianteMateriasNotasPeridos($e['mat_grado'], $e['mat_id'], $e['mat_grupo']);
                        $consultaR = CargaAcademica::traerCargasMateriasPorCursoGrupo($config, $e['mat_grado'], $e['mat_grupo']);
                        $arraysDatos = array();
                        if (!empty($consulta)) {
                            while ($fila = $consultaR->fetch_assoc()) {
                                $arraysDatos[] = $fila;
                            }
                            $consultaR->free();
                        }
                        // agrupamos los datos por periodos
                        $materiasPeriodos = [];
                        $carga_id        = "";
                        foreach ($consulta  as $registro) {
                            $periodo = 1;
                            if ($carga_id != $registro["car_id"]) {
                                $materiasPeriodos[$registro["car_id"]] = [
                                    "car_id"             => $registro["car_id"],
                                    "car_ih"             => $registro["car_ih"],
                                    "car_materia"        => $registro["car_materia"],
                                    "car_docente"        => $registro["car_docente"],
                                    "car_director_grupo" => $registro["car_director_grupo"],
                                    "mat_nombre"         => $registro["mat_nombre"],
                                    "mat_area"           => $registro["mat_area"],
                                    "mat_valor"          => $registro["mat_valor"],
                                    "ar_nombre"          => $registro["ar_nombre"],
                                    "ar_posicion"        => $registro["ar_posicion"],
                                    "bol_estudiante"     => $registro["bol_estudiante"],
                                    "bol_periodo"        => $registro["bol_periodo"],
                                    "bol_nota"           => $registro["bol_nota"],
                                    "bol_id"             => $registro["bol_id"],
                                    "notaArea"           => $registro["notaArea"],
                                    "periodos"           => []
                                ];
                                $carga_id = $registro["car_id"];
                            }
                            $materiasPeriodos[$registro["car_id"]]["periodos"][] = [
                                "bol_periodo"      => $registro["bol_periodo"],
                                "bol_nota"         => $registro["bol_nota"]
                            ];
                        }

                        foreach ($materiasPeriodos as $resultado) { ?>
                            <tr border="1">
                                <td><?= $resultado['ar_posicion'] ?></td>
                                <td><?= $resultado['car_id'] ?></td>
                                <td><?= $resultado['car_materia'] ?></td>
                                <td><?= $resultado['mat_nombre'] ?></td>
                                <?php for ($i = 1; $i <= $config["conf_periodos_maximos"]; $i++) { ?>
                                    <th width="5%"><?= !empty($resultado['periodos'][$i - 1]['bol_nota']) ? $resultado['periodos'][$i - 1]['bol_nota'] : '-' ?></th>
                                <?php } ?>
                                <td id="<?= $resultado['car_id'] ?>_relacion">
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <select class="form-control  select2" name="selectCargas" id="carga_<?= $resultado['car_id']; ?>_<?= $resultado['car_materia'] ?>">
                                                <option value="">Seleccione una opción</option>
                                                <?php
                                                foreach ($arraysDatos as $cargaGrupo) {
                                                    $selected = $resultado['car_materia'] == $cargaGrupo['car_materia'] ? 'selected' : '' ?>
                                                    <option value="<?= $cargaGrupo['car_id']; ?>" <?= $selected; ?>><?= $cargaGrupo['car_materia'] . ". " . strtoupper($cargaGrupo['mat_nombre']); ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php $botones = new botonesGuardar(null, Modulos::validarPermisoEdicion()); ?> 
        <button class='btn  btn-danger' style='text-transform:uppercase'>
            No pasar notas
        </button>"
    </form>
</div>
<!-- end js include path -->
<script>
    async function traerCargaCursoGrupo(cruso, grupo) {
        $.toast({
                heading: 'Consultando Cargas',
                position: 'bottom-right',
                showHideTransition: 'slide',
                icon: 'success',
                hideAfter: 3500, 
                stack: 6
            })
        var data = {
            "curso": cruso,
            "grupo": grupo
        };
        // contenido.innerHTML = "";
        resultado = await metodoFetchAsync('../compartido/ajax_carga_grupo.php', data, 'json', false);
        resultData = resultado["data"];
        console.log(resultData);
        const cargasElements = document.getElementsByName("selectCargas");
       
        if (cargasElements.length > 0) {
            if(resultData["result"].length > 0){
                $.toast({
                heading: 'Cargas consultadas',
                text: 'Se cargaron las cargas del grupo: '+grupo,
                position: 'bottom-right',
                showHideTransition: 'slide',
                icon: 'success',
                hideAfter: 3500, 
                stack: 6
            })
            }else{
                Swal.fire({
                position: "top-end",
                title: 'No hay Cargas',
                text: 'No se encontraro cargas para este grupo',
                icon: 'warning',
                showCancelButton: false,
                confirmButtonText: 'Si!',
                cancelButtonText: 'No!',
                timer: 1500
            });
            }
            
            cargasElements.forEach((element) => {
                console.log(element.id); // Imprime el valor de cada input con name="cargas"
                var claves = element.id.split("_");
                console.log(claves[2]);
                var selectCargas = $('#' + element.id);
                selectCargas.empty();
                if (resultData["ok"]) {
                    cargas = resultData["result"];
                    let cargasSelect = [];
                    var selecione = new Option('Relacione una carga', '', true, true);
                    selectCargas.append(selecione);
                    resultData["result"].forEach(function(opcion) {
                        var estaSeleccionado = opcion.car_materia === claves[2];
                        var nuevaOpcion = new Option(opcion.car_materia + '.' + opcion.mat_nombre, opcion.car_id, estaSeleccionado, estaSeleccionado);
                        selectCargas.append(nuevaOpcion);
                    });

                }
            });
        }




    }
</script>