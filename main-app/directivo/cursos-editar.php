<?php
include("session.php");
$idPaginaInterna = 'DT0064';
include("../compartido/historial-acciones-guardar.php");
include("../compartido/head.php");
require_once("../class/Estudiantes.php");
require_once("../class/servicios/GradoServicios.php");
require_once("../class/servicios/CargaServicios.php");
require_once("../class/servicios/MatriculaServicios.php");

try{
    $resultadoCurso=GradoServicios::consultarCurso($_GET["id"]);
    $resultadoCargaCurso=CargaServicios::cantidadCursos($_GET["id"]);
} catch (Exception $e) {
    include("../compartido/error-catch-to-report.php");
}
$disabledPermiso = "";
if(!Modulos::validarPermisoEdicion()){
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
<?php include("../compartido/body.php");?>
    <div class="page-wrapper">
        <?php include("../compartido/encabezado.php");?>
		
        <?php include("../compartido/panel-color.php");?>
        <!-- start page container -->
        <div class="page-container">
 			<?php include("../compartido/menu.php");?>
			<!-- start page content -->
            <div class="page-content-wrapper">
                <div class="page-content">
                    <div class="page-bar">
                        <div class="page-title-breadcrumb">
                            <div class=" pull-left">
                                <div class="page-title">Editar Cursos</div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
							<ol class="breadcrumb page-breadcrumb pull-right">
                                <li><a class="parent-item" href="#" name="cursos.php" onClick="deseaRegresar(this)"><?=$frases[5][$datosUsuarioActual['uss_idioma']];?></a>&nbsp;<i class="fa fa-angle-right"></i></li>
                                <li class="active">Editar Cursos</li>
                            </ol>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                    
                            <?php include("../../config-general/mensajes-informativos.php"); ?>
                            <div class="panel">
                                <header class="panel-heading panel-heading-purple"><?=$frases[119][$datosUsuarioActual[8]];?> </header>
                                <div class="panel-body">
                                
                                    <form name="formularioGuardar" action="cursos-actualizar.php" method="post">
                                        <input type="hidden" name="id_curso" value="<?php echo $_GET["id"] ?>">
                                        
                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label">Codigo</label>
                                            <div class="col-sm-2">
                                                <input type="text" name="codigoC" class="form-control"  value="<?=$resultadoCurso["gra_codigo"]; ?>" <?=$disabledPermiso;?>>
                                            </div>
                                        </div>	
                                        
                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label">Nombre Curso</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="nombreC" class="form-control" value="<?=$resultadoCurso["gra_nombre"]; ?>" <?=$disabledPermiso;?>>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label">Formato Boletin</label>
                                            <div class="col-sm-2">
                                                <select id="tipoBoletin" class="form-control  select2"  name="formatoB" onchange="cambiarTipo()" required <?=$disabledPermiso;?>>
                                                    <option value="">Seleccione una opción</option>
                                                    <?php
                                                        try{
                                                            $consultaBoletin=mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".opciones_generales WHERE ogen_grupo=15");
                                                        } catch (Exception $e) {
                                                            include("../compartido/error-catch-to-report.php");
                                                        }
                                                        while($datosBoletin = mysqli_fetch_array($consultaBoletin, MYSQLI_BOTH)){
                                                    ?>
                                                        <option value="<?=$datosBoletin['ogen_id'];?>" <?php if($resultadoCurso["gra_formato_boletin"]==$datosBoletin['ogen_id']){ echo 'selected';} ?>><?=$datosBoletin['ogen_nombre'];?></option>
                                                    <?php }?>
                                                </select>
                                            </div>                                           
                                            <button type="button" titlee="Ver formato del boletin" class="btn btn-sm" data-toggle="popover" ><i class="fa fa-eye"></i></button>
                                            <script>
                                                    $(document).ready(function(){
                                                    $('[data-toggle="popover"]').popover({
                                                        html: true, // Habilitar contenido HTML
                                                        content: function () {
                                                            valor = document.getElementById("tipoBoletin");
                                                        return '<div id="myPopover" class="popover-content"><label id="lbl_tipo">Formato tipo '+valor.value+'</label>'+
                                                        '<img id="img-boletin" src="../files/images/boletines/tipo'+valor.value+'.png" class="w-100" />'+                                                       
                                                        '</div>';}
                                                        });                                                    
                                                    });
                                                    function cambiarTipo(){  
                                                        var imagen_boletin = document.getElementById('img-boletin'); 
                                                        if(imagen_boletin){                                                     
                                                        var valor = document.getElementById("tipoBoletin");  
                                                        var lbl_tipo = document.getElementById('lbl_tipo');
                                                        imagen_boletin.src ="../files/images/boletines/tipo"+valor.value+".png";
                                                        lbl_tipo.textContent='Formato tipo '+valor.value;
                                                        }
                                                    }
                                            </script>
                                        </div>
                                        
                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label">Nota Minima</label>
                                            <div class="col-sm-2">
                                                <input type="text" name="notaMin" class="form-control"  value="<?=$resultadoCurso["gra_nota_minima"]; ?>" <?=$disabledPermiso;?>>
                                            </div>
                                        </div>	
                                        
                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label">Periodos</label>
                                            <div class="col-sm-2">
                                                <input type="text" name="periodosC" class="form-control"  value="<?=$resultadoCurso["gra_periodos"]; ?>" <?=$disabledPermiso;?>>
                                            </div>
                                        </div>	
                                        
                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label">Valor Matricula</label>
                                            <div class="col-sm-2">
                                                <input type="text" name="valorM" class="form-control" value="<?=$resultadoCurso["gra_valor_matricula"]; ?>" <?=$disabledPermiso;?>>
                                            </div>
                                        </div>	
                                        
                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label">Valor Pension</label>
                                            <div class="col-sm-2">
                                                <input type="text" name="valorP" class="form-control" value="<?=$resultadoCurso["gra_valor_pension"]; ?>" <?=$disabledPermiso;?>>
                                            </div>
                                        </div>	
                                        
                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label">Curso Siguiente</label>
                                            <div class="col-sm-8">
                                                <?php
                                                $opcionesConsulta = Grados::listarGrados(1);
                                                ?>
                                                <select class="form-control  select2" name="graSiguiente"  <?=$disabledPermiso;?>>
                                                    <option value="">Seleccione una opción</option>
                                                    <?php
                                                    while($opcionesDatos = mysqli_fetch_array($opcionesConsulta, MYSQLI_BOTH)){
                                                            $select='';
					                                        if($resultadoCurso["gra_grado_siguiente"]==$opcionesDatos[0]){$select='selected';}
                                                    ?>
                                                        <option value="<?=$opcionesDatos[0];?>" <?=$select; ?>><?=strtoupper($opcionesDatos['gra_nombre']);?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label">Curso Anterior</label>
                                            <div class="col-sm-8">
                                                <?php
                                                $opcionesConsulta = Grados::listarGrados(1);
                                                ?>
                                                <select class="form-control  select2" name="graAnterior"  <?=$disabledPermiso;?>>
                                                    <option value="">Seleccione una opción</option>
                                                    <?php
                                                    while($opcionesDatos = mysqli_fetch_array($opcionesConsulta, MYSQLI_BOTH)){
                                                            $select='';
					                                        if($resultadoCurso["gra_grado_anterior"]==$opcionesDatos[0]){$select='selected';}
                                                    ?>
                                                        <option value="<?=$opcionesDatos[0];?>" <?=$select; ?>><?=strtoupper($opcionesDatos['gra_nombre']);?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label">Nivel Educativo</label>
                                            <div class="col-sm-10">
                                                <select class="form-control  select2" name="nivel"  <?=$disabledPermiso;?>>
                                                    <option value="">Seleccione una opción</option>
                                                    <option value="1" <?php if($resultadoCurso['gra_nivel']==1){ echo 'selected'; } ?>>Educación Precolar</option>
                                                    <option value="2" <?php if($resultadoCurso['gra_nivel']==2){ echo 'selected'; } ?>>Educación Basica Primaria</option>
                                                    <option value="3" <?php if($resultadoCurso['gra_nivel']==3){ echo 'selected'; } ?>>Educación Basica Secundaria</option>
                                                    <option value="4" <?php if($resultadoCurso['gra_nivel']==4){ echo 'selected'; } ?>>Educación Media</option>
                                                </select>
                                            </div>
                                        </div>
                                        <?php if($datosUsuarioActual[3]==1) {?>
                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label">Estado</label>
                                            <div class="col-sm-2">
                                                <select class="form-control  select2" name="estado"  <?=$disabledPermiso;?>>
                                                    <option value="">Seleccione una opción</option>
                                                    <option value="1" <?php if($resultadoCurso['gra_estado']==1){ echo 'selected'; } ?>>Activo</option>
                                                    <option value="0" <?php if($resultadoCurso['gra_estado']==0){ echo 'selected'; } ?>>Inactivo</option>
                                                </select>
                                            </div>
                                        </div>
                                        <?php 
                                        }
                                        if(array_key_exists(10,$arregloModulos)){
                                        ?>
                                            <div class="form-group row">
                                                <label class="col-sm-2 control-label">Tipo de grado</label>
                                                <div class="col-sm-2">
                                                    <?php
                                                    if($resultadoCargaCurso["cargas_curso"]<1){
                                                    ?>
                                                        <select class="form-control  select2" name="tipoG" id="tipoG" onchange="mostrarEstudiantes(this)">
                                                            <option value="">Seleccione una opción</option>
                                                            <option value=<?=GRADO_GRUPAL;?> <?php if($resultadoCurso['gra_tipo']==GRADO_GRUPAL){ echo 'selected'; } ?>>Grupal</option>
                                                            <option value=<?=GRADO_INDIVIDUAL;?> <?php if($resultadoCurso['gra_tipo']==GRADO_INDIVIDUAL){ echo 'selected'; } ?>>Individual</option>
                                                        </select>
                                                    <?php 
                                                        }else{
                                                    ?>
                                                        <select class="form-control  select2"  name="tipoG" id="tipoG" disabled>
                                                            <?php 
                                                                if($resultadoCurso['gra_tipo']==GRADO_GRUPAL){
                                                                    echo '<option value="'.GRADO_GRUPAL.'" selected>Grupal</option>';
                                                                }elseif($resultadoCurso['gra_tipo']==GRADO_INDIVIDUAL){
                                                                    echo '<option value="'.GRADO_INDIVIDUAL.'" selected>Individual</option>';
                                                                }else{
                                                                    echo ' ';
                                                                }
                                                            ?>
                                                        </select>
                                                    <?php }?>
                                                </div>
                                            </div>

                                            <div id="escogerEstudiantes">
                                                <div class="form-group row">
                                                    <label class="col-sm-2 control-label">Estudiantes:</label>
                                                    <div class="col-sm-8">
                                                        <?php
                                                        $parametros = [
                                                            'matcur_id_curso'=>$_GET['id'],
                                                            'matcur_id_institucion'=>$config['conf_id_institucion'],
                                                            'arreglo'=>false
                                                        ];
                                                        $consulta = MediaTecnicaServicios::listarEstudiantes($parametros);
                                                        ?>
                                                        <select id="select_estudiante" class="form-control select2-multiple" style="width: 100% !important" name="estudiantesMT[]" multiple onchange="mostrarSelects(this)">
                                                            <option value="">Seleccione una opción</option>
                                                            <?php
                                                            foreach($consulta as $idEstudiante){
                                                                $matricualaEstudiante=MatriculaServicios::consultar($idEstudiante["matcur_id_matricula"]);
                                                                if(!is_null($matricualaEstudiante)){
                                                                    $nombre = Estudiantes::NombreCompletoDelEstudiante($matricualaEstudiante);
                                                            ?>
                                                                <option value="<?= $matricualaEstudiante['mat_id']; ?>" title="<?= $matricualaEstudiante['mat_nombres'].' '.$matricualaEstudiante['mat_primer_apellido']; ?>" id="<?=$idEstudiante["matcur_id_grupo"]?>" selected><?= $nombre; ?></option>
                                                            <?php }} ?>
                                                        
                                                        </select>
                                                    </div>
                                                </div>
                                            
                                                <div id="selectsContainer" style="display: none;">
                                                </div>
                                            </div>
                                        <?php 
                                        }
                                        ?>
                                        <script type="text/javascript">
                                            $(document).ready(function() {mostrarEstudiantes(document.getElementById("tipoG"))});
                                            function mostrarEstudiantes(data) {
                                                if(data.value == "<?=GRADO_INDIVIDUAL?>"){
                                                    document.getElementById("escogerEstudiantes").style.display = "block";
                                                }else{
                                                    document.getElementById("escogerEstudiantes").style.display = "none";
                                                    document.getElementById("escogerEstudiantes").value = '';
                                                }
                                            }
                                            $(document).ready(function() {
                                                $('#select_estudiante').select2({
                                                placeholder: 'Seleccione los estudiantes...',
                                                theme: "bootstrap",
                                                multiple: true,
                                                    ajax: {
                                                        type: 'GET',
                                                        url: 'ajax-listar-estudiantes.php',
                                                        processResults: function(data) {
                                                            data = JSON.parse(data);
                                                            return {
                                                                results: $.map(data, function(item) {                                  
                                                                    return {
                                                                        id: item.value,
                                                                        text: item.label,
                                                                        title: item.title
                                                                    }
                                                                })
                                                            };
                                                        }
                                                    }
                                                });
                                            });
                                            $(document).ready(function() {mostrarSelects(document.getElementById("select_estudiante"))});
                                            function mostrarSelects(selectElement) {
                                                // Obtener el div contenedor donde se mostrarán los selects adicionales
                                                var selectsContainer = document.getElementById('selectsContainer');

                                                // Limpiar los selects existentes en el contenedor
                                                selectsContainer.innerHTML = '';

                                                // Obtener las opciones seleccionadas del select múltiple
                                                var opcionesSeleccionadas = selectElement.selectedOptions;
                                                
                                                if(opcionesSeleccionadas.length>0){
                                                    selectsContainer.style.display = "block";
                                                    // Mostrar un select por cada opción seleccionada
                                                    for (var i = 0; i < opcionesSeleccionadas.length; i++) {
                                                        var opcion = opcionesSeleccionadas[i].value;
                                                        var nameEstu = opcionesSeleccionadas[i].title;
                                                        var grupoEstu = opcionesSeleccionadas[i].id;

                                                        // Creamos div form-group y añadimos sus clases css
                                                        var divFormGroup = document.createElement('div');
                                                        divFormGroup.classList.add('form-group','row');
                                                        selectsContainer.appendChild(divFormGroup);

                                                        // Creamos label y añadimos sus clases css
                                                        var label = document.createElement('label');
                                                        label.textContent = 'Escoge el grupo para '+nameEstu+':';
                                                        label.classList.add('col-sm-2','control-label');
                                                        divFormGroup.appendChild(label);

                                                        // Creamos div-col y añadimos sus clases css
                                                        var divCol = document.createElement('div');
                                                        divCol.classList.add('col-sm-3');
                                                        divFormGroup.appendChild(divCol);

                                                        // Crear y agregar el select al contenedor
                                                        var select = document.createElement('select');
                                                        select.name = 'grupo' + opcion; // Asignar un nombre único al select

                                                        // Agregar clases al select
                                                        select.classList.add('form-control','select2');

                                                        // Agregar opciones al select
                                                        var option = document.createElement('option');
                                                        option.value = '';
                                                        option.textContent = 'Seleccione el grupo...';
                                                        // Agregar las opciones al select
                                                        select.appendChild(option);

                                                        <?php
                                                            $opcionesConsulta = mysqli_query($conexion, "SELECT * FROM academico_grupos");
                                                            $cont=1;
                                                            while($rv = mysqli_fetch_array($opcionesConsulta, MYSQLI_BOTH)){
                                                        ?>
                                                                // Agregar opciones al select
                                                                var option<?=$cont?> = document.createElement('option');
                                                                option<?=$cont?>.value = '<?=$rv[0]?>';
                                                                option<?=$cont?>.textContent = '<?=$rv['gru_nombre']?>';

                                                                // Agregar las opciones al select
                                                                select.appendChild(option<?=$cont?>);

                                                                // Establecer la opción que estará seleccionada por defecto (por ejemplo, Opción 2)
                                                                if (grupoEstu == <?=$rv[0]?>) {
                                                                    option<?=$cont?>.selected = true;
                                                                }
                                                        <?php
                                                                $cont++;
                                                            }
                                                        ?>

                                                        divCol.appendChild(select);
                                                    }
                                                }
                                            }
                                        </script>


                                        <?php if(Modulos::validarPermisoEdicion()){?>
                                            <input type="submit" class="btn btn-primary" value="Guardar cambios">&nbsp;
                                        <?php }?>
                                        
                                        <a href="#" name="cursos.php" class="btn btn-secondary" onClick="deseaRegresar(this)"><i class="fa fa-long-arrow-left"></i>Regresar</a>
                                    </form>
                                </div>
                            </div>
                        </div>
						
                    </div>

                </div>
                <!-- end page content -->
             <?php // include("../compartido/panel-configuracion.php");?>
        </div>
        <!-- end page container -->
        <?php include("../compartido/footer.php");?>
    </div>
    <!-- start js include path -->
    <script src="../../config-general/assets/plugins/jquery/jquery.min.js" ></script>
    <script src="../../config-general/assets/plugins/popper/popper.js" ></script>
    <script src="../../config-general/assets/plugins/jquery-blockui/jquery.blockui.min.js" ></script>
	<script src="../../config-general/assets/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
    <!-- bootstrap -->
    <script src="../../config-general/assets/plugins/bootstrap/js/bootstrap.min.js" ></script>
    <script src="../../config-general/assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js" ></script>
    <script src="../../config-general/assets/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js" ></script>
    <script src="../../config-general/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"  charset="UTF-8"></script>
    <script src="../../config-general/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker-init.js"  charset="UTF-8"></script>
    <script src="../../config-general/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"  charset="UTF-8"></script>
    <script src="../../config-general/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker-init.js"  charset="UTF-8"></script>
    <!-- Common js-->
	<script src="../../config-general/assets/js/app.js" ></script>
    <script src="../../config-general/assets/js/layout.js" ></script>
	<script src="../../config-general/assets/js/theme-color.js" ></script>
	<!-- notifications -->
	<script src="../../config-general/assets/plugins/jquery-toast/dist/jquery.toast.min.js" ></script>
	<script src="../../config-general/assets/plugins/jquery-toast/dist/toast.js" ></script>	
	<!-- Material -->
	<script src="../../config-general/assets/plugins/material/material.min.js"></script>
	<!-- dropzone -->
    <script src="../../config-general/assets/plugins/dropzone/dropzone.js" ></script>
    <!--tags input-->
    <script src="../../config-general/assets/plugins/jquery-tags-input/jquery-tags-input.js" ></script>
    <script src="../../config-general/assets/plugins/jquery-tags-input/jquery-tags-input-init.js" ></script>
    <!--select2-->
    <script src="../../config-general/assets/plugins/select2/js/select2.js" ></script>
    <script src="../../config-general/assets/js/pages/select2/select2-init.js" ></script>
    <!-- end js include path -->
</body>

<!-- Mirrored from radixtouch.in/templates/admin/smart/source/light/advance_form.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 18 May 2018 17:32:54 GMT -->
</html>