<?php include("session.php");?>
<?php $idPaginaInterna = 'DT0004';?>
<?php include("../compartido/historial-acciones-guardar.php");?>
<?php include("../compartido/head.php");?>
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
<link href="../../config-general/assets/css/chat.css" rel="stylesheet">
</head>
<!-- END HEAD -->
<?php include("../compartido/body.php");?>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <div class="page-wrapper">
        <?php include("../compartido/encabezado.php");?>
		
        <?php include("../compartido/panel-color.php");?>
        <!-- start page container -->
        <div class="page-container">
 			
			<?php include("../compartido/menu.php");?>
			
			<!-- start page content -->
            <div class="page-content-wrapper">
                <?php include("../compartido/texto-manual-ayuda.php");?>
                <div class="page-content" style="background-color: #41c4c4;">
                    <div class="row">
                        <div class="card2 chat-app">
                            <div id="plist" class="people-list">
                                <div class="input-group">
                                    <form class="search-form-opened" action="#" method="GET" name="busqueda" style="background-color: rgb(0, 0, 0,0.3); margin: 0;">
                                        <div class="input-group">
                                            <input type="text" id="search" name="search" class="form-control" placeholder="Buscar usuario...">
                                            <span class="input-group-btn">
                                            <span class="input-group-btn">
                                                <a href="javascript:;" class="btn submit" style="color: #000;">
                                                    <i class="icon-magnifier"></i>
                                                </a>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                                <ul class="list-unstyled chat-list mt-2 mb-0" id="contenedorOriginial">
                                    <?php
                                        $consultaUsuariosOnline= mysqli_query($conexion,"SELECT uss_id, uss_nombre, uss_apellido1, uss_foto, uss_estado FROM usuarios 
                                        WHERE uss_estado=1 AND uss_bloqueado=0 AND uss_id!='".$_SESSION['id']."' 
                                        ORDER BY uss_ultimo_ingreso DESC
                                        LIMIT 10");
                                        if(mysqli_num_rows($consultaUsuariosOnline)>0){
                                            while($datosUsuriosOnline=mysqli_fetch_array($consultaUsuariosOnline, MYSQLI_BOTH)){
                                                $fotoPerfilUsrOnline = $usuariosClase->verificarFoto($datosUsuriosOnline['uss_foto']);
                                    ?>
                                    <li class="clearfix" onclick="mostrarChat(this)" id="<?=$datosUsuriosOnline['uss_id']?>">
                                        <img src="<?=$fotoPerfilUsrOnline?>" alt="avatar">
                                        <div class="about">
                                            <div class="name"><?=$datosUsuriosOnline['uss_nombre'].' '.$datosUsuriosOnline['uss_apellido1']?></div>
                                            <div class="status"> <i class="fa fa-circle online"></i> online </div>
                                        </div>
                                    </li>
                                    <?php
                                            }
                                        }
                                        $consultaUsuariosOffline= mysqli_query($conexion,"SELECT uss_id, uss_nombre, uss_apellido1, uss_foto, uss_estado FROM usuarios 
                                        WHERE uss_estado=0 AND uss_bloqueado=0 
                                        ORDER BY uss_ultima_salida DESC
                                        LIMIT 5");
                                        if(mysqli_num_rows($consultaUsuariosOffline)>0){
                                            while($datosUsuriosOffline=mysqli_fetch_array($consultaUsuariosOffline, MYSQLI_BOTH)){
                                                $fotoPerfilUsrOffline = $usuariosClase->verificarFoto($datosUsuriosOffline['uss_foto']);
                                    ?>
                                    <li class="clearfix" onclick="mostrarChat(this)" id="<?=$datosUsuriosOffline['uss_id']?>">
                                        <img src="<?=$fotoPerfilUsrOffline?>" alt="avatar">
                                        <div class="about">
                                            <div class="name"><?=$datosUsuriosOffline['uss_nombre'].' '.$datosUsuriosOffline['uss_apellido1']?></div>
                                            <div class="status"> <i class="fa fa-circle offline"></i> offline </div>
                                        </div>
                                    </li>
                                    <?php
                                            }
                                        }
                                    ?>
                                </ul>
                                <ul class="list-unstyled chat-list mt-2 mb-0" id="contenedorSearch" style="display:none;">
                                </ul>
                            </div>
                            <div class="chat2" id="contenedorChat" style="display:none;">
                            </div>
                            <div class="bienvenida" id="contenedorBienvenida">
                                <div class="mensajeBienvenida">
                                    <h1><span style='font-family:Arial; font-weight:bold;'>Te damos la bienvenida, <?=$datosUsuarioActual['uss_nombre']?></samp></h1>
                                    <p>¡Todo listo para chatear! Comencemos.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                function mostrarChat(datos){
                    var id= datos.id;
                    $("#contenedorChat").empty().hide();

                    if (id !== '') {
                        $.ajax({
                            type: "POST",
                            url: "ajax-chat.php",
                            data: { id: id },
                            success: function(response) {
                                $("#contenedorBienvenida").hide();
                                $("#contenedorChat").show();

                                $.each(response, function(index, item) {
                                    var html = '<div class="chat-header2 clearfix">'+
                                                    '<div class="row">'+
                                                        '<div class="col-lg-6">'+
                                                            '<a href="javascript:void(0);" data-toggle="modal" data-target="#view_info">'+
                                                                '<img src="'+item.fotoPerfil+'" alt="avatar">'+
                                                            '</a>'+
                                                            '<div class="chat-about">'+
                                                                '<h6 class="m-b-0">'+item.nombre+'</h6>'+
                                                                '<small>Last seen: 2 hours ago</small>'+
                                                            '</div>'+
                                                        '</div>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="chat-history" id="chatHistory">'+
                                                    '<ul class="m-b-0">'+
                                                        '<li class="clearfix">'+
                                                            '<div class="message-data text-right">'+
                                                                '<span class="message-data-time">10:10 AM, Today</span>'+
                                                                '<img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="avatar">'+
                                                            '</div>'+
                                                            '<div class="message other-message float-right"> Hi Aiden, how are you? How is the project coming along? </div>'+
                                                        '</li>'+
                                                        '<li class="clearfix">'+
                                                            '<div class="message-data">'+
                                                                '<span class="message-data-time">10:12 AM, Today</span>'+
                                                            '</div>'+
                                                            '<div class="message my-message">Are we meeting today?</div>'+
                                                        '</li>'+
                                                        '<li class="clearfix">'+
                                                            '<div class="message-data">'+
                                                                '<span class="message-data-time">10:15 AM, Today</span>'+
                                                            '</div>'+
                                                            '<div class="message my-message">Project has been already finished and I have results to show you.</div>'+
                                                        '</li>'+
                                                        '<li class="clearfix">'+
                                                            '<div class="message-data text-right">'+
                                                                '<span class="message-data-time">10:10 AM, Today</span>'+
                                                                '<img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="avatar">'+
                                                            '</div>'+
                                                            '<div class="message other-message float-right"> Hi Aiden, how are you? How is the project coming along? </div>'+
                                                        '</li>'+
                                                        '<li class="clearfix">'+
                                                            '<div class="message-data">'+
                                                                '<span class="message-data-time">10:15 AM, Today</span>'+
                                                            '</div>'+
                                                            '<div class="message my-message">Project has been already finished and I have results to show you.</div>'+
                                                        '</li>'+
                                                    '</ul>'+
                                                '</div>'+
                                                '<div class="chat-message clearfix">'+
                                                    '<div class="input-group mb-0">'+
                                                        '<div class="input-group-prepend">'+
                                                            '<span class="input-group-text"><i class="fa fa-send"></i></span>'+
                                                        '</div>'+
                                                        '<input type="text" class="form-control" placeholder="Enter text here...">'+
                                                    '</div>'+
                                                '</div>';
                                    $("#contenedorChat").append(html);
                                    var chatElement = document.getElementById("chatHistory");
                                    chatElement.scrollTop = chatElement.scrollHeight;
                                });
                            }
                        });
                    }
                }

                $(document).ready(function() {
                    $('#search').on('input', function() {
                        var search = $(this).val();

                        if (search.length > 2) {
                            $.ajax({
                                type: "POST",
                                url: "ajax-buscador.php",
                                data: { search: search },
                                success: function(response) {
                                    if (search === '') {
                                        // Si el término de búsqueda está vacío, vaciar el contenedor de resultados y ocultarlo
                                        $("#contenedorSearch").empty().hide();
                                        $("#contenedorOriginial").show();
                                    } else {
                                        // Limpiar los resultados anteriores
                                        $("#contenedorSearch").empty();

                                        if (response.length > 0) {
                                            // Agregar los nuevos productos a la lista
                                            $("#contenedorSearch").show();
                                            $("#contenedorOriginial").hide();
                                            $.each(response, function(index, item) {
                                                var estado = 'offline';
                                                if (item.datosUsuarios.uss_estado == 1) {
                                                    var estado = 'online';
                                                }
                                                var html = '<li class="clearfix" id="'+item.datosUsuarios.uss_id+'">' +
                                                    '<img src="'+item.fotoPerfil+'" alt="avatar">' +
                                                    '<div class="about">' +
                                                    '<div class="name">'+item.nombre+'</div>' +
                                                    '<div class="status"> <i class="fa fa-circle '+estado+'"></i> '+estado+' </div>' +
                                                    '</div>' +
                                                    '</li>';

                                                $("#contenedorSearch").append(html);
                                            });
                                        } else {
                                            $("#contenedorSearch").hide();
                                            $("#contenedorOriginial").show();
                                        }
                                    }
                                }
                            });
                        } else {
                            // Si el término de búsqueda tiene dos letras, vaciar el contenedor de resultados y ocultarlo
                            $("#contenedorSearch").empty().hide();
                            $("#contenedorOriginial").show();
                        }
                    });
                });
            </script>
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
    <script src="../../config-general/assets/plugins/sparkline/jquery.sparkline.js" ></script>
	<script src="../../config-general/assets/js/pages/sparkline/sparkline-data.js" ></script>
    <!-- Common js-->
	<script src="../../config-general/assets/js/app.js" ></script>
    <script src="../../config-general/assets/js/layout.js" ></script>
    <script src="../../config-general/assets/js/theme-color.js" ></script>
	<!-- notifications -->
	<script src="../../config-general/assets/plugins/jquery-toast/dist/jquery.toast.min.js" ></script>
	<script src="../../config-general/assets/plugins/jquery-toast/dist/toast.js" ></script>
    <!-- material -->
    <script src="../../config-general/assets/plugins/material/material.min.js"></script>
    <!-- chart js -->
    <script src="../../config-general/assets/plugins/chart-js/Chart.bundle.js" ></script>
    <script src="../../config-general/assets/plugins/chart-js/utils.js" ></script>
    <script src="../../config-general/assets/js/pages/chart/chartjs/home-data.js" ></script>
    <!-- summernote -->
    <script src="../../config-general/assets/plugins/summernote/summernote.js" ></script>
    <script src="../../config-general/assets/js/pages/summernote/summernote-data.js" ></script>
    <!-- end js include path -->
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
	
  </body>

</html>