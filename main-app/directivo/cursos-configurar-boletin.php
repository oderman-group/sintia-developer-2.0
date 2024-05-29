<?php 
include("session.php"); 
$idPaginaInterna = 'DT0337';
include("../compartido/historial-acciones-guardar.php");
include("../compartido/head.php");
require_once(ROOT_PATH."/main-app/class/Grados.php");


if (!Modulos::validarSubRol([$idPaginaInterna])) {
    echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
    exit();
}

$datosConfiguracion = Grados::traerConfiguracionBoletin($config);

$disabledPermiso = "";
if (!Modulos::validarPermisoEdicion() && $datosUsuarioActual['uss_tipo'] == TIPO_DIRECTIVO) {
    $disabledPermiso = "disabled";
}
?>

<!--bootstrap -->
<link href="../../config-general/assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<link href="../../config-general/assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" media="screen">
<!-- Theme Styles -->
<link href="../../config-general/assets/css/pages/formlayout.css" rel="stylesheet" type="text/css" />
<!-- dropzone -->
<link href="../../config-general/assets/plugins/dropzone/dropzone.css" rel="stylesheet" media="screen">
<!--tagsinput-->
<link href="../../config-general/assets/plugins/jquery-tags-input/jquery-tags-input.css" rel="stylesheet">
<!--select2-->
<link href="../../config-general/assets/plugins/select2/css/select2.css" rel="stylesheet" type="text/css" />
<link href="../../config-general/assets/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
</head>
<!-- END HEAD -->
<?php include("../compartido/body.php"); ?>
<div class="page-wrapper">
    <?php include("../compartido/encabezado.php"); ?>

    <?php include("../compartido/panel-color.php"); ?>
    <!-- start page container -->
    <div class="page-container">
        <?php include("../compartido/menu.php"); ?>
        <!-- start page content -->
        <div class="page-content-wrapper">
            <div class="page-content">
                <div class="page-bar">
                    <div class="page-title-breadcrumb">
                        <div class=" pull-left">
                            <div class="page-title"><?= $frases[17][$datosUsuarioActual['uss_idioma']]; ?> del Boletin</div>
                            <?php include("../compartido/texto-manual-ayuda.php"); ?>
                        </div>
                        <ol class="breadcrumb page-breadcrumb pull-right">
                            <li><a class="parent-item" href="javascript:void(0);" name="cursos.php" onClick="deseaRegresar(this)">Cursos</a>&nbsp;<i class="fa fa-angle-right"></i></li>
                            <li class="active"><?= $frases[17][$datosUsuarioActual['uss_idioma']]; ?> del Boletin</li>
                        </ol>
                    </div>
                </div>
                <div class="row">

                    <div class="col-sm-12">
                        <?php
                        include("../../config-general/mensajes-informativos.php");
                        ?>
                        <br>
                        <div class="panel">
                            <header class="panel-heading panel-heading-purple"><?= $frases[17][$datosUsuarioActual['uss_idioma']]; ?></header>
                            <div class="panel-body">
                                <form name="formularioGuardar" action="cursos-configurar-boletin-guardar.php" method="post">
                                    <input type="hidden" name="id" value="<?= !empty($datosConfiguracion['conbol_id']) ? $datosConfiguracion['conbol_id'] : ""; ?>">
                                    <input type="hidden" name="bannerAnterior" value="<?= !empty($datosConfiguracion['conbol_banner_encabezado']) ? $datosConfiguracion['conbol_banner_encabezado'] : ""; ?>">

                                    <p class="h3">Estilos y apariencia</p>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Encabezado
                                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Como deseas que se vea el encabezado de tu boletin."><i class="fa fa-question"></i></button>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control  select2" id="encabezado" name="encabezado" onchange="mostrarEncabezados()" required <?= $disabledPermiso; ?>>
                                                <option value="">Seleccione una opción</option>
                                                <option value="<?=GENERAL?>" <?= !empty($datosConfiguracion['conbol_tipo_encabezado']) && $datosConfiguracion['conbol_tipo_encabezado'] == GENERAL ? "selected" : ""; ?>>General</option>
                                                <option value="<?=TABLA?>" <?= !empty($datosConfiguracion['conbol_tipo_encabezado']) && $datosConfiguracion['conbol_tipo_encabezado'] == TABLA ? "selected" : ""; ?>>Tabla</option>
                                                <option value="<?=BANNER?>" <?= !empty($datosConfiguracion['conbol_tipo_encabezado']) && $datosConfiguracion['conbol_tipo_encabezado'] == BANNER ? "selected" : ""; ?>>Banner</option>
                                            </select>
                                        </div>
                                        <button type="button" titlee="Ver diferentes encabezados" class="btn btn-sm" data-toggle="popover"><i class="fa fa-eye"></i></button>
                                        <script>
                                            function mostrarEncabezados() {
                                                var imagen_encabezado       = document.getElementById('img-encabezado');
                                                var encabezado              = document.getElementById("encabezado").value;

                                                var divPosicionLogo         = document.getElementById("divPosicionLogo");
                                                var posicionLogo            = document.getElementById('posicionLogo');
                                                var posicionLogoContainer   = document.getElementById('select2-posicionLogo-container');
                                                var posicionLogoDisplay     = window.getComputedStyle(divPosicionLogo).display;

                                                var divBanner               = document.getElementById("divBanner");
                                                var inputBanner             = document.getElementById("inputBanner");
                                                var bannerDisplay           = window.getComputedStyle(divBanner).display;

                                                if (imagen_encabezado) {
                                                    var lbl_tipo_encabezado = document.getElementById('lbl_tipo_encabezado');
                                                    imagen_encabezado.src = "../files/conf_boletin/encabezado" + encabezado + ".png";
                                                    lbl_tipo_encabezado.textContent = 'Encabezado Tipo ' + encabezado;
                                                }

                                                if (encabezado === '<?=GENERAL?>' && posicionLogoDisplay == 'none') {
                                                    divPosicionLogo.style.display = 'flex';
                                                } else {
                                                    divPosicionLogo.style.display = 'none';
                                                    posicionLogo.value = 0;
                                                    posicionLogoContainer.innerHTML = 'Select a State';
                                                }

                                                if (encabezado === '<?=BANNER?>' && bannerDisplay == 'none') {
                                                    divBanner.style.display = 'flex';
                                                } else {
                                                    divBanner.style.display = 'none';
                                                    inputBanner.value = "";
                                                }
                                            }
                                            
                                            $(document).ready(function() {
                                                mostrarEncabezados();

                                                $('[data-toggle="popover"]').popover({
                                                    html: true, // Habilitar contenido HTML
                                                    content: function() {
                                                        valor = document.getElementById("encabezado");
                                                        return '<div id="myPopover" class="popover-content"><label id="lbl_tipo_encabezado">Encabezado Tipo ' + valor.value + '</label>' +
                                                            '<img id="img-encabezado" src="../files/conf_boletin/encabezado' + valor.value + '.png" class="w-100" />' +
                                                            '</div>';
                                                    }
                                                });
                                            });
                                        </script>
                                    </div>

                                    <div class="form-group row" id="divPosicionLogo" style="display: none;">
                                        <label class="col-sm-2 control-label">Posición Logo
                                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Donde deseas que se vea el logo de la institucion."><i class="fa fa-question"></i></button>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control select2" style="width: 100%;" id="posicionLogo" name="posicionLogo" required <?= $disabledPermiso; ?>>
                                                <option value="">Seleccione una opción</option>
                                                <option value="<?=DERECHA?>" <?= !empty($datosConfiguracion['conbol_posicion_logo']) && $datosConfiguracion['conbol_posicion_logo'] == DERECHA ? "selected" : ""; ?>>Derecha</option>
                                                <option value="<?=IZQUIERDA?>" <?= !empty($datosConfiguracion['conbol_posicion_logo']) && $datosConfiguracion['conbol_posicion_logo'] == IZQUIERDA ? "selected" : ""; ?>>Izquierda</option>
                                            </select>
                                        </div>
                                    </div>

                                    <?php
                                        $infoLogo="encabezadoBANNER.png";
                                        if(!empty($datosConfiguracion["conbol_banner_encabezado"]) && file_exists(ROOT_PATH.'/main-app/files/conf_boletin/'.$datosConfiguracion["conbol_banner_encabezado"])){
                                            $infoLogo=$datosConfiguracion["conbol_banner_encabezado"];
                                        }
                                    ?>
                                    <div class="form-group row" id="divBanner" style="display: none;">
                                        <label class="col-sm-2 control-label">Banner
                                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Escoge el banner que desas se muestre en el boletin, las dimenciones requeridas son: 1252px de ancho por 132px de alto."><i class="fa fa-question"></i></button>
                                        </label>
                                        <div class="col-sm-4">
                                            <img src="<?=REDIRECT_ROUTE?>/files/conf_boletin/<?=$infoLogo;?>" alt="<?=$infoLogo;?>" style="width: 100%; height: 150px;">
                                            <input type="file" name="banner" class="form-control" id="inputBanner">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Mostrar Areas?
                                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Deseas que se vean las areas en el boletin?."><i class="fa fa-question"></i></button>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control  select2" id="areas" name="areas" required <?= $disabledPermiso; ?>>
                                                <option value="">Seleccione una opción</option>
                                                <option value="<?=AREA?>" <?= !empty($datosConfiguracion['conbol_mostrar_areas']) && $datosConfiguracion['conbol_mostrar_areas'] == AREA ? "selected" : ""; ?>>Mostrar areas</option>
                                                <option value="<?=AREA_NOTA?>" <?= !empty($datosConfiguracion['conbol_mostrar_areas']) && $datosConfiguracion['conbol_mostrar_areas'] == AREA_NOTA ? "selected" : ""; ?>>Mostrar areas y sus notas</option>
                                                <option value="<?=NO_MOSTRAR?>" <?= !empty($datosConfiguracion['conbol_mostrar_areas']) && $datosConfiguracion['conbol_mostrar_areas'] == NO_MOSTRAR ? "selected" : ""; ?>>No Mostrar</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Mostrar Materias?
                                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Deseas que se vean las materias en el boletin?."><i class="fa fa-question"></i></button>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control  select2" id="materias" name="materias" required <?= $disabledPermiso; ?>>
                                                <option value="">Seleccione una opción</option>
                                                <option value="<?=MATERIA?>" <?= !empty($datosConfiguracion['conbol_mostrar_materias']) && $datosConfiguracion['conbol_mostrar_materias'] == MATERIA ? "selected" : ""; ?>>Mostrar materias</option>
                                                <option value="<?=MATERIA_NOTA?>" <?= !empty($datosConfiguracion['conbol_mostrar_materias']) && $datosConfiguracion['conbol_mostrar_materias'] == MATERIA_NOTA ? "selected" : ""; ?>>Mostrar materias y sus notas</option>
                                                <option value="<?=NO_MOSTRAR?>" <?= !empty($datosConfiguracion['conbol_mostrar_materias']) && $datosConfiguracion['conbol_mostrar_materias'] == NO_MOSTRAR ? "selected" : ""; ?>>No Mostrar</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Mostrar Observaciones?
                                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Deseas que se vean las observaciones que coloca cada docente en el boletin?."><i class="fa fa-question"></i></button>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control  select2" id="observaciones" name="observaciones" required <?= $disabledPermiso; ?>>
                                                <option value="">Seleccione una opción</option>
                                                <option value="<?=MOSTRAR?>" <?= !empty($datosConfiguracion['conbol_mostrar_observaciones_materia']) && $datosConfiguracion['conbol_mostrar_observaciones_materia'] == MOSTRAR ? "selected" : ""; ?>>Mostrar</option>
                                                <option value="<?=NO_MOSTRAR?>" <?= !empty($datosConfiguracion['conbol_mostrar_observaciones_materia']) && $datosConfiguracion['conbol_mostrar_observaciones_materia'] == NO_MOSTRAR ? "selected" : ""; ?>>No Mostrar</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Mostrar Indicadores?
                                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Deseas que se vean los indicadores en el boletin?."><i class="fa fa-question"></i></button>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control  select2" id="indicadores" name="indicadores" required <?= $disabledPermiso; ?>>
                                                <option value="">Seleccione una opción</option>
                                                <option value="<?=INDICADOR?>" <?= !empty($datosConfiguracion['conbol_mostrar_indicadores']) && $datosConfiguracion['conbol_mostrar_indicadores'] == INDICADOR ? "selected" : ""; ?>>Mostrar indicadores</option>
                                                <option value="<?=INDICADOR_NOTA?>" <?= !empty($datosConfiguracion['conbol_mostrar_indicadores']) && $datosConfiguracion['conbol_mostrar_indicadores'] == INDICADOR_NOTA ? "selected" : ""; ?>>Mostrar indicadores y sus notas</option>
                                                <option value="<?=OTRA_HOJA?>" <?= !empty($datosConfiguracion['conbol_mostrar_indicadores']) && $datosConfiguracion['conbol_mostrar_indicadores'] == OTRA_HOJA ? "selected" : ""; ?>>Mostrar indicadores en otra hoja</option>
                                                <option value="<?=NO_MOSTRAR?>" <?= !empty($datosConfiguracion['conbol_mostrar_indicadores']) && $datosConfiguracion['conbol_mostrar_indicadores'] == NO_MOSTRAR ? "selected" : ""; ?>>No Mostrar</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Mostrar Rango de Notas?
                                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Deseas que se vean el rango de notas que maneja la institución en el boletin?."><i class="fa fa-question"></i></button>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control  select2" id="rangoNotas" name="rangoNotas" required <?= $disabledPermiso; ?>>
                                                <option value="">Seleccione una opción</option>
                                                <option value="<?=INICIO?>" <?= !empty($datosConfiguracion['conbol_mostrar_rango_notas']) && $datosConfiguracion['conbol_mostrar_rango_notas'] == INICIO ? "selected" : ""; ?>>Mostrar al inicio</option>
                                                <option value="<?=FIN?>" <?= !empty($datosConfiguracion['conbol_mostrar_rango_notas']) && $datosConfiguracion['conbol_mostrar_rango_notas'] == FIN ? "selected" : ""; ?>>Mostrar al final</option>
                                                <option value="<?=NO_MOSTRAR?>" <?= !empty($datosConfiguracion['conbol_mostrar_rango_notas']) && $datosConfiguracion['conbol_mostrar_rango_notas'] == NO_MOSTRAR ? "selected" : ""; ?>>No Mostrar</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Mostrar observaciones de comportamiento?
                                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Deseas que se vean las observaciones de comportamiento en el boletin?."><i class="fa fa-question"></i></button>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control  select2" id="observacionGeneral" name="observacionGeneral" required <?= $disabledPermiso; ?>>
                                                <option value="">Seleccione una opción</option>
                                                <option value="<?=MOSTRAR?>" <?= !empty($datosConfiguracion['conbol_mostrar_observaciones_generales']) && $datosConfiguracion['conbol_mostrar_observaciones_generales'] == MOSTRAR ? "selected" : ""; ?>>Mostrar</option>
                                                <option value="<?=NO_MOSTRAR?>" <?= !empty($datosConfiguracion['conbol_mostrar_observaciones_generales']) && $datosConfiguracion['conbol_mostrar_observaciones_generales'] == NO_MOSTRAR ? "selected" : ""; ?>>No Mostrar</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Mostrar nota de comportamiento?
                                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Deseas que se vean la nota de comportamiento en el boletin?."><i class="fa fa-question"></i></button>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control  select2" id="notaComportamiento" name="notaComportamiento" required <?= $disabledPermiso; ?>>
                                                <option value="">Seleccione una opción</option>
                                                <option value="<?=MOSTRAR?>" <?= !empty($datosConfiguracion['conbol_mostrar_nota_comportamiento']) && $datosConfiguracion['conbol_mostrar_nota_comportamiento'] == MOSTRAR ? "selected" : ""; ?>>Mostrar</option>
                                                <option value="<?=NO_MOSTRAR?>" <?= !empty($datosConfiguracion['conbol_mostrar_nota_comportamiento']) && $datosConfiguracion['conbol_mostrar_nota_comportamiento'] == NO_MOSTRAR ? "selected" : ""; ?>>No Mostrar</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Mostrar periodos anteriores?
                                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Deseas ver la definitiva en los periodos anteriores en el boletin?."><i class="fa fa-question"></i></button>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control  select2" id="periodoAnterior" name="periodoAnterior" required <?= $disabledPermiso; ?>>
                                                <option value="">Seleccione una opción</option>
                                                <option value="<?=MOSTRAR?>" <?= !empty($datosConfiguracion['conbol_acomulado_final']) && $datosConfiguracion['conbol_acomulado_final'] == MOSTRAR ? "selected" : ""; ?>>Mostrar</option>
                                                <option value="<?=NO_MOSTRAR?>" <?= !empty($datosConfiguracion['conbol_acomulado_final']) && $datosConfiguracion['conbol_acomulado_final'] == NO_MOSTRAR ? "selected" : ""; ?>>No Mostrar</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Mostrar Ausencias?
                                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Deseas que se vean las ausencias del estudiante en el boletin?."><i class="fa fa-question"></i></button>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control  select2" id="ausencias" name="ausencias" required <?= $disabledPermiso; ?>>
                                                <option value="">Seleccione una opción</option>
                                                <option value="<?=POR_PERIODO?>" <?= !empty($datosConfiguracion['conbol_ausencias']) && $datosConfiguracion['conbol_ausencias'] == POR_PERIODO ? "selected" : ""; ?>>Por cada periodo</option>
                                                <option value="<?=PERIODO_ACTUAL?>" <?= !empty($datosConfiguracion['conbol_ausencias']) && $datosConfiguracion['conbol_ausencias'] == PERIODO_ACTUAL ? "selected" : ""; ?>>Solo periodo actual</option>
                                                <option value="<?=NO_MOSTRAR?>" <?= !empty($datosConfiguracion['conbol_ausencias']) && $datosConfiguracion['conbol_ausencias'] == NO_MOSTRAR ? "selected" : ""; ?>>No Mostrar</option>
                                            </select>
                                        </div>
                                    </div>
                                    <script>
                                    $(document).ready(function() {
                                        $('#periodoAnterior').change(function() {
                                            const periodoAnteriorValue = $(this).val();
                                            const ausencias = $('#ausencias');

                                            // Clear current options in the 'ausencias' select
                                            ausencias.empty();

                                            // Add the appropriate options based on the selection in 'periodoAnterior'
                                            if (periodoAnteriorValue === '<?=MOSTRAR?>') {
                                                ausencias.append('<option value="<?=POR_PERIODO?>" <?= !empty($datosConfiguracion['conbol_ausencias']) && $datosConfiguracion['conbol_ausencias'] == POR_PERIODO ? "selected" : ""; ?>>Por cada periodo</option>');
                                            }
                                            
                                            ausencias.append('<option value="<?=PERIODO_ACTUAL?>" <?= !empty($datosConfiguracion['conbol_ausencias']) && $datosConfiguracion['conbol_ausencias'] == PERIODO_ACTUAL ? "selected" : ""; ?>>Solo periodo actual</option>');
                                            ausencias.append('<option value="<?=NO_MOSTRAR?>" <?= !empty($datosConfiguracion['conbol_ausencias']) && $datosConfiguracion['conbol_ausencias'] == NO_MOSTRAR ? "selected" : ""; ?>>No Mostrar</option>');

                                            // Update the select2 options
                                            ausencias.trigger('change');
                                        });
                                    });
                                    </script>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Mostrar desempeño?
                                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Deseas que se vean el desempeño del estudiante en el boletin?."><i class="fa fa-question"></i></button>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control  select2" id="desempeno" name="desempeno" required <?= $disabledPermiso; ?>>
                                                <option value="">Seleccione una opción</option>
                                                <option value="<?=COLUMNA?>" <?= !empty($datosConfiguracion['conbol_desempeno']) && $datosConfiguracion['conbol_desempeno'] == COLUMNA ? "selected" : ""; ?>>En una columna aparte</option>
                                                <option value="<?=EN_INSCRIPCION?>" <?= !empty($datosConfiguracion['conbol_desempeno']) && $datosConfiguracion['conbol_desempeno'] == EN_INSCRIPCION ? "selected" : ""; ?>>Debajo de la nota</option>
                                                <option value="<?=NO_MOSTRAR?>" <?= !empty($datosConfiguracion['conbol_desempeno']) && $datosConfiguracion['conbol_desempeno'] == NO_MOSTRAR ? "selected" : ""; ?>>No Mostrar</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Mostrar intensidad horaria?
                                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Deseas que se vean la intensidad horaria de cada materia en el boletin?."><i class="fa fa-question"></i></button>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control  select2" id="ih" name="ih" required <?= $disabledPermiso; ?>>
                                                <option value="">Seleccione una opción</option>
                                                <option value="<?=MOSTRAR?>" <?= !empty($datosConfiguracion['conbol_ih']) && $datosConfiguracion['conbol_ih'] == MOSTRAR ? "selected" : ""; ?>>Mostrar</option>
                                                <option value="<?=NO_MOSTRAR?>" <?= !empty($datosConfiguracion['conbol_ih']) && $datosConfiguracion['conbol_ih'] == NO_MOSTRAR ? "selected" : ""; ?>>No Mostrar</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Mostrar acomulado final?
                                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Deseas ver un acomulado final de lo que va en el año en el boletin?."><i class="fa fa-question"></i></button>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control  select2" id="acomulado" name="acomulado" required <?= $disabledPermiso; ?>>
                                                <option value="">Seleccione una opción</option>
                                                <option value="<?=MOSTRAR?>" <?= !empty($datosConfiguracion['conbol_acomulado_final']) && $datosConfiguracion['conbol_acomulado_final'] == MOSTRAR ? "selected" : ""; ?>>Mostrar</option>
                                                <option value="<?=NO_MOSTRAR?>" <?= !empty($datosConfiguracion['conbol_acomulado_final']) && $datosConfiguracion['conbol_acomulado_final'] == NO_MOSTRAR ? "selected" : ""; ?>>No Mostrar</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Firmas
                                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Deseas que las firmas se vean debajo de la observación de comportamiento o si hay segunda hoja, al final de la segunda hoja?."><i class="fa fa-question"></i></button>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control  select2" id="firmas" name="firmas" required <?= $disabledPermiso; ?>>
                                                <option value="">Seleccione una opción</option>
                                                <option value="<?=PRIMERA_HOJA?>" <?= !empty($datosConfiguracion['conbol_mostrar_firmas']) && $datosConfiguracion['conbol_mostrar_firmas'] == PRIMERA_HOJA ? "selected" : ""; ?>>Mostrar en la primera hoja</option>
                                                <option value="<?=SEGUNDA_HOJA?>" <?= !empty($datosConfiguracion['conbol_mostrar_firmas']) && $datosConfiguracion['conbol_mostrar_firmas'] == SEGUNDA_HOJA ? "selected" : ""; ?>>Mostrar en la segunda hoja</option>
                                            </select>
                                        </div>
                                    </div>

                                    <p class="h3">Calculos</p>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Notas
                                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Desea que se calculen las notas, de forma normal o teniendo en cuenta el porcentaje de cada materia en el areas?"><i class="fa fa-question"></i></button>
                                        </label>
                                        <div class="col-sm-4">
                                            <select class="form-control  select2" id="notas" name="notas" required <?= $disabledPermiso; ?>>
                                                <option value="">Seleccione una opción</option>
                                                <option value="<?=NORMAL?>" <?= !empty($datosConfiguracion['conbol_calcular_nota']) && $datosConfiguracion['conbol_calcular_nota'] == NORMAL ? "selected" : ""; ?>>De forma normal</option>
                                                <option value="<?=PORCENTAJE_MATERIA?>" <?= !empty($datosConfiguracion['conbol_calcular_nota']) && $datosConfiguracion['conbol_calcular_nota'] == PORCENTAJE_MATERIA ? "selected" : ""; ?>>Teniendo en cuenta el porcentaje</option>
                                            </select>
                                        </div>
                                    </div>

                                    <?php $botones = new botonesGuardar("cursos.php", Modulos::validarPermisoEdicion() || $datosUsuarioActual['uss_tipo'] == TIPO_DEV); ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page content -->
            <?php // include("../compartido/panel-configuracion.php");
            ?>
        </div>
        <!-- end page container -->
        <?php include("../compartido/footer.php"); ?>
    </div>
    <!-- start js include path -->
    <script src="../../config-general/assets/plugins/jquery/jquery.min.js"></script>
    <script src="../../config-general/assets/plugins/popper/popper.js"></script>
    <script src="../../config-general/assets/plugins/jquery-blockui/jquery.blockui.min.js"></script>
    <script src="../../config-general/assets/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
    <!-- bootstrap -->
    <script src="../../config-general/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="../../config-general/assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
    <script src="../../config-general/assets/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>
    <script src="../../config-general/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js" charset="UTF-8"></script>
    <script src="../../config-general/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker-init.js" charset="UTF-8"></script>
    <script src="../../config-general/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
    <script src="../../config-general/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker-init.js" charset="UTF-8"></script>
    <!-- Common js-->
    <script src="../../config-general/assets/js/app.js"></script>
    <script src="../../config-general/assets/js/layout.js"></script>
    <script src="../../config-general/assets/js/theme-color.js"></script>
    <!-- notifications -->
    <script src="../../config-general/assets/plugins/jquery-toast/dist/jquery.toast.min.js"></script>
    <script src="../../config-general/assets/plugins/jquery-toast/dist/toast.js"></script>
    <!-- Material -->
    <script src="../../config-general/assets/plugins/material/material.min.js"></script>
    <!-- dropzone -->
    <script src="../../config-general/assets/plugins/dropzone/dropzone.js"></script>
    <!--tags input-->
    <script src="../../config-general/assets/plugins/jquery-tags-input/jquery-tags-input.js"></script>
    <script src="../../config-general/assets/plugins/jquery-tags-input/jquery-tags-input-init.js"></script>
    <!--select2-->
    <script src="../../config-general/assets/plugins/select2/js/select2.js"></script>
    <script src="../../config-general/assets/js/pages/select2/select2-init.js"></script>
    <!-- end js include path -->
    </body>

    <!-- Mirrored from radixtouch.in/templates/admin/smart/source/light/advance_form.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 18 May 2018 17:32:54 GMT -->

    </html>