<?php
include("session.php");
$idPaginaInterna = 'DT0137';
include("../compartido/historial-acciones-guardar.php");
include("../compartido/head.php");
require_once("../class/Estudiantes.php");
require_once("../class/servicios/UsuarioServicios.php");
require_once("../class/servicios/MatriculaServicios.php");
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
<!-- Script -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- jQuery UI -->
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/bootstrap-tokenfield.js"></script>
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
                            <div class="page-title">Acudidos</div>
                            <?php include("../compartido/texto-manual-ayuda.php"); ?>
                        </div>
                        <ol class="breadcrumb page-breadcrumb pull-right">
                            <li><a class="parent-item" href="#" name="usuarios.php?cantidad=10&tipo=3" onClick="deseaRegresar(this)">Usuarios</a>&nbsp;<i class="fa fa-angle-right"></i></li>
                            <li class="active">Acudidos</li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <?php include("../../config-general/mensajes-informativos.php"); ?>
                    </div>
                    <?php $acudienteActural = UsuarioServicios::consultar($_GET['id']); ?>
                    <div class="col-sm-4">
                        <div class="panel">
                            <header class="panel-heading panel-heading-purple">Datos Acudiente</header>
                            <div class="panel-body">
                                <div class="form-group row">
                                    <label class="col-sm-2 control-label">Nombre:</label>
                                    <label class="col-sm-10 control-label"><?= UsuarioServicios::nombres($acudienteActural) ?></label>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 control-label">Apellido:</label>
                                    <label class="col-sm-10 control-label"><?= UsuarioServicios::apellidos($acudienteActural) ?></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-8">

                        <div class="panel">
                            <header class="panel-heading panel-heading-purple">Acudidos</header>
                            <div class="panel-body">
                                <form name="formularioGuardar" action="usuarios-acudidos-actualizar.php" method="post">
                                    <input type="hidden" value="<?= $_GET['id']; ?>" name="id">
                                    <div class="form-group row">
                                       
                                        
                                            <label class="col-sm-2 control-label">Estudiantes</label>
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <input type="text" id="search_data" placeholder="Ingrese Nombre" autocomplete="off" class="form-control input-lg" />
                                                    <div class="input-group-btn">
                                                        <button type="button" class="btn btn-primary btn-lg" id="search">Get Value</button>
                                                    </div>
                                                </div>
                                                <br />
                                                <span id="country_name"></span>
                                            </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Estudiantes</label>
                                        <div class="col-sm-8">
                                            <?php
                                            $opcionesConsulta = Estudiantes::listarEstudiantes(0, '', 'LIMIT 0, 10');
                                            $listaEstudiante = MatriculaServicios::listarEstudianteNombre('ALME');
                                            ?>
                                            <select id="select_estudiante" class="form-control  select2-multiple" disabled name="acudidos" required multiple>
                                                <option value="">Seleccione una opción</option>
                                                <?php
                                                while($opcionesDatos = mysqli_fetch_array($opcionesConsulta, MYSQLI_BOTH)){
                                                    try{
                                                        $consultaUsuarioAcudiente=mysqli_query($conexion, "SELECT * FROM usuarios_por_estudiantes WHERE upe_id_usuario='".$_GET['id']."' AND upe_id_estudiante='".$opcionesDatos['mat_id']."'");
                                                    } catch (Exception $e) {
                                                        include("../compartido/error-catch-to-report.php");
                                                    }
                                                    $num = mysqli_num_rows($consultaUsuarioAcudiente);
                                                    $nombre = Estudiantes::NombreCompletoDelEstudiante($opcionesDatos);
                                                    $selected = '';
                                                    if ($opcionesDatos['mat_acudiente'] == $_GET['id'] and $num > 0) $selected = 'selected';
                                                ?>
                                                    <option value="<?= $opcionesDatos['mat_id']; ?>" <?= $selected; ?>><?= $nombre; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>


                                        <input type="submit" class="btn btn-primary" value="Guardar Cambios">&nbsp;

                                        <a href="#" name="usuarios.php?cantidad=10&tipo=3" class="btn btn-secondary" onClick="deseaRegresar(this)"><i class="fa fa-long-arrow-left"></i>Regresar</a>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>

                </div>
                <!-- end page content -->
                <?php // include("../compartido/panel-configuracion.php");
                ?>
            </div>
            <!-- JQuery busqueda Like on Change -->
            <script>
        //  success: function( data ) {
        //                 if ( data.length == 1 ) {
        //                     $('#language').val( data[0] );
        //                 } else {
        //                     if ( data.length > 1 ) {
        //                         $("#multiple").find('option').remove();
        //                         data.forEach( function(e) {
        //                             $("#multiple").append("<option>" + e + "</option>");
        //                         });
        //                         $("#multiple").prop( 'size', data.length );
        //                         $("#multiple").show();
        //                     }
        //                 } 
        //             },
        //             dataType: 'json',
      
      $('#search_data').tokenfield({
          autocomplete :{
              source: function(request, response)
              {
                  jQuery.get('ajax-listar-estudiantes.php', {
                        nombre : request.term
                  }, function(data){
                      data1 = JSON.parse(data);
                      console.log(data1);
                      response(data1);
                  });
              },
              delay: 100
          }
      });
  
      $('#search').click(function(){
          $('#country_name').text($('#search_data').val());
      });
  
   
                // let clases = document.getElementsByClassName("select2-search__field");
                // console.log(clases);
                // clases[0].addEventListener("keyup", mostrarAlerta);

                // function mostrarAlerta() {

                //     console.log(clases[0].value);
                // }

                // $("#estudiantes").select2({
                //     placeholder:"Buscar Datos",
                //     multiple:true,
                //     processResults: function(data){
                //         return {
                //           result: $.map(data, function(valor){
                //             return{
                //                 id:valor.id,
                //                 nombre_completo:valor.nombre_completo
                //             }
                //           } ) 
                //         };
                //     }
                // });
                // Multiple select
                // $("#multi_autocomplete").autocomplete({
                //     source: function(request, response) {

                //         var searchText = extractLast(request.term);
                //         console.log(searchText);
                //         $.ajax({
                //             url: "ajax-listar-estudiantes.php",
                //             data: parametros,
                //             type: 'post',
                //             dataType: "json",
                //             data: {
                //                 nombre: searchText
                //             },
                //             success: function(data) {
                //                 console.log(data[0].nombre_completo);
                //                 response(data);
                //             }
                //         });
                //     },
                //     select: function(event, ui) {

                //         var terms = split($('#multi_autocomplete').val());
                //         console.log("Valor del terms-->" + terms);
                //         terms.pop();

                //         terms.push(ui.item.label);

                //         terms.push(terms.mat_id);
                //         $('#multi_autocomplete').val(terms.join(", "));

                //         // Id
                //         terms = split($('#selectuser_ids').val());

                //         terms.pop();

                //         terms.push(ui.item.value);

                //         terms.push("");
                //         $('#selectuser_ids').val(terms.join(", "));

                //         return false;
                //     }

                // });

                // function split(val) {
                //     return val.split(/,\s*/);
                // }

                // function extractLast(term) {
                //     return split(term).pop();
                // }


                //         $( "#language" ).on( 'input', function() {
                //         $('#multiple').hide();
                //         $.ajax(
                //             {
                //                 url:'ajax-listar-estudiantes.php',
                //                 data:  parametros,
                // 				dataType: 'json',
                //                 type:  'post',
                //                 success: function( data ) {
                //                     alert("Mostrar data=>"+data);
                //                     if ( data.length == 1 ) {
                //                         $('#language').val( data[0] );
                //                     } else {
                //                         if ( data.length > 1 ) {
                //                             $("#multiple").find('option').remove();
                //                             data.forEach( function(e) {
                //                                 $("#multiple").append("<option>" + e + "</option>");
                //                             });
                //                             $("#multiple").prop( 'size', data.length );
                //                             $("#multiple").show();
                //                         }
                //                     } 
                //                 },
                //                 dataType: 'json',
                //             }
                //         );
                //     }
                // );

                // $( "#multiple" ).click( function() {
                //     $('#language').val( $(this).val() );
                //     $('#multiple').hide();
                // }
                // );
            </script>
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