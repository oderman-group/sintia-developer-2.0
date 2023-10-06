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
    <script src="https://cdn.socket.io/3.1.3/socket.io.min.js" integrity="sha384-cPwlPLvBTa3sKAgddT6krw0cJat7egBga3DJepJyrLl4Q9/5WLra3rrnMcyTyOnh" crossorigin="anonymous"></script>
   
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
                                            <div class="name"  id="nombre_<?=$datosUsuriosOnline['uss_id']?>"><?=$datosUsuriosOnline['uss_nombre'].' '.$datosUsuriosOnline['uss_apellido1']?></div>
                                            
                                            <div class="status" > <i class="fa fa-circle online"></i> online <span id="notificacion_<?=$datosUsuriosOnline['uss_id']?>" > </div>
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
                                            <div class="name" id="nombre_<?=$datosUsuriosOffline['uss_id']?>" ><?=$datosUsuriosOffline['uss_nombre'].' '.$datosUsuriosOffline['uss_apellido1']?></div>
                                            <div class="status"> <i class="fa fa-circle offline"></i> offline <span id="notificacion_<?=$datosUsuriosOnline['uss_id']?>" > </div>
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
                var urlApi= 'http://localhost:3000';
                var socket = io(urlApi, { transports: ['websocket', 'polling', 'flashsocket'] });
                var chat_remite_usuario= <?php echo $idSession ?>;
                var chat_destino_usuario= "";
                var foto_url_uss= "<?php echo $datosUsuarioActual["uss_foto"] ?>"; 
                var nombre_uss="<?php echo $datosUsuarioActual["uss_nombre"]." ".$datosUsuarioActual["uss_apellido1"] ?>";                             
                  
                socket.emit('join', "sala_" + chat_remite_usuario);

                socket.on("notificacion_chat", (data) => {
                    console.log(data);
                    uss_id = data["chat_remite_usuario"];
                    nombre_uss_notifica =data["nombre_uss"];
                    foto_url_uss_notifica="../files/fotos/"+data["foto_url_uss"];
                    

                    listaUsuarios = document.getElementById('contenedorOriginial');
                    liUsuario = document.getElementById(uss_id );                    
                    divNombre = document.getElementById("nombre_"+uss_id );                    
                    
                     // si existe se elimina de la lista
                    if (liUsuario !== null) {                   
                        listaUsuarios.removeChild(liUsuario);
                    }                    
                   // Crea un nuevo elemento li
                   const elementoHTML = notificacionUsuario(uss_id,nombre_uss_notifica,foto_url_uss_notifica,'online');
                   const nuevoElemento = document.createElement('li');
                   nuevoElemento.innerHTML =elementoHTML ;



                    // Agrega el nuevo elemento li al principio de la lista
                    listaUsuarios.insertBefore(nuevoElemento, listaUsuarios.firstChild);
                    spanNotificacion = document.getElementById("notificacion_"+uss_id );
                    spanNotificacion.className = "badge headerBadgeColor2";
                    spanNotificacion.innerHTML ="Nuevo";

                });


                function mostrarChat(datos){
                    console.log("entre a la sala_chat_"+ chat_remite_usuario + "_" + chat_destino_usuario);
                    socket.emit("leave","sala_chat_"+ chat_remite_usuario + "_" + chat_destino_usuario);                   
                    var id= datos.id;
                    console.log("id--->"+id);
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
                                                            '</div>'+
                                                        '</div>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="chat-history" id="chatHistory">'+
                                                    '<ul class="m-b-0" id="contenido_chat">'+
                                                       
                                                    '</ul>'+
                                                '</div>'+
                                                '<div class="chat-message clearfix">'+
                                                    '<div class="input-group mb-0">'+
                                                        '<div class="input-group-prepend">'+
                                                            '<span class="input-group-text" onClick="enviarMensaje()"><i class="fa fa-send"></i></span>'+
                                                        '</div>'+
                                                        '<input type="text" id="mensaje"  class="form-control" placeholder="Escriba su mensaje aqui...">'+
                                                    '</div>'+
                                                '</div>';
                                    $("#contenedorChat").append(html);
                                    var chatElement = document.getElementById("chatHistory");
                                    var contenido_chat = document.getElementById("contenido_chat");
                                    chat_remite_usuario= <?php echo $idSession ?>;
                                    chat_destino_usuario= item.datosUsuarios["uss_id"];
                                    listarChat(chat_remite_usuario,chat_destino_usuario); 
                                    socket.emit('join', "sala_chat_" + chat_remite_usuario + "_" + chat_destino_usuario);                                    
                                    socket.on("nuevo_mensaje_chat", (data) => {
                                        chatElement = document.getElementById("chatHistory");
                                        console.log(data);
                                        mensaje = data["body"]["chat_mensaje"];
                                        fecha = data["body"]["chat_fecha_registro"];
                                        fechaCompleta=verificarFecha(fecha);
                                        contenido_chat.innerHTML += htmlDestino(mensaje,fechaCompleta,foto_url_uss_destino);
                                        chatElement.scrollTop = chatElement.scrollHeight;
                                    });
                                    divNombre = document.getElementById("nombre_"+chat_destino_usuario );
                                    spanNotificacion = document.getElementById("notificacion_"+chat_destino_usuario );
                                    foto_url_uss_destino=item.fotoPerfil;
                                    divNombre.style.fontWeight ="400";
                                    spanNotificacion.className = "";
                                    spanNotificacion.innerHTML ="";
                                });
                            }
                        });
                    }
                }
                function notificacionUsuario(id, nombreCompleto,fotoPerfil,estado) {
                    Html="";
                    Html='<li class="clearfix" onclick="mostrarChat(this)"  id="'+id+'"   >' +
                        '<img src="'+fotoPerfil+'" alt="avatar" />' +
                            '<div class="about">' +
                                '<div class="name" id="nombre_'+id+'" style="font-weight: bold;" >'+nombreCompleto+'</div>' +
                                '<div class="status" > <i class="fa fa-circle online"></i> '+estado+' <span  id="notificacion_'+id+'"> </div>' +
                            '</div>'+
                    '</li>';
                    return Html;     
                };
                function htmlEmisor(mensaje, hora ) {
                    return '<li class="clearfix">' +
                        '<div class="message-data">' +
                        '<span class="message-data-time">' + hora + '</span>' +
                        '</div>' +
                        '<div class="message my-message">' + mensaje + '</div>' +
                        '</li>';
                };
                function htmlDestino(mensaje, hora,imagenUrl) {
                    return '<li class="clearfix">' +
                        '<div class="message-data text-right">' +
                        '<span class="message-data-time">' + hora + '</span>' +
                        '<img src="'+ imagenUrl +'" alt="avatar">' +
                        '</div>' +
                        '<div class="message other-message float-right"> ' + mensaje + ' </div>' +
                        '</li>';
                };
                function listarChat(uss_remite,uss_detino) {
                    const url = urlApi+'/chat/find/' + uss_remite + "/" +uss_detino;
                    chatElement = document.getElementById("chatHistory");
                    console.log("esta listando en url " + url);
                    const opciones = {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json' // Indica que esperas una respuesta en formato JSON
                        }
                    };
                    // Realizar la solicitud GET
                    fetch(url, opciones)
                        .then(response => response.json()) // Analiza la respuesta como JSON
                        .then(data => {
                            console.log('Respuesta del servidor:', data);
                            data.forEach(elemento => {
                                console.log(chat_remite_usuario +"-"+elemento.chat_remite_usuario +" = "+elemento.chat_mensaje);
                                if(chat_remite_usuario == elemento.chat_remite_usuario ){
                                    fechaCompleta=verificarFecha(elemento.chat_fecha_registro);
                                    contenido_chat.innerHTML += htmlEmisor(elemento.chat_mensaje,fechaCompleta);
                                }else{
                                    fechaCompleta=verificarFecha(elemento.chat_fecha_registro);
                                    contenido_chat.innerHTML += htmlDestino(elemento.chat_mensaje,fechaCompleta,foto_url_uss_destino);
                                }
                                chatElement.scrollTop = chatElement.scrollHeight;                                 
                            });
                        })
                        .catch(error => {
                            alert('Error al realizar la solicitud:'+error);
                        });
                };
                function enviarMensaje() {
                    mensaje = document.getElementById("mensaje").value;
                    chatElement = document.getElementById("chatHistory");
                    
                    console.log(mensaje);
                    socket.emit("enviar_mensaje_chat", {
                        foto_url_uss:foto_url_uss,
                        nombre_uss:nombre_uss,
                        chat_fecha_registro:new Date(),
                        chat_remite_usuario:chat_remite_usuario,
                        chat_destino_usuario:chat_destino_usuario,
                        sala: "sala_" + chat_destino_usuario,
                        salaChat: "sala_chat_" + chat_destino_usuario + "_" + chat_remite_usuario,
                        chat_mensaje: mensaje
                    });
                    contenido_chat.innerHTML += htmlEmisor(mensaje,verificarFecha(new Date()));
                    document.getElementById("mensaje").value = "";
                    chatElement.scrollTop = chatElement.scrollHeight;
                } ;                
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
                function obtenerDiaDeLaSemana(fecha) {
                    const diasDeLaSemana = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];
                    const fechaIngresada = new Date(fecha);
                    const numeroDeDia = fechaIngresada.getDay();
                    const nombreDelDia = diasDeLaSemana[numeroDeDia];
                    return nombreDelDia;
                }
                function validarFachasIguales(fechaInicial,fechaFinal){
                    if (
                        fechaInicial.getDate() === fechaFinal.getDate() &&
                        fechaInicial.getMonth() === fechaFinal.getMonth() &&
                        fechaInicial.getFullYear() === fechaFinal.getFullYear()
                    ) {
                        return true;
                    }else{
                        return false;
                    }
                }
                function verificarFecha(fecha) {
                     fechaActual = new Date(); // Obtener la fecha actual
                     fechaIngresada = new Date(fecha); // Convertir la fecha ingresada en un objeto Date
                     fechahora= fechaIngresada.toLocaleTimeString('en-US');
                     const [month, day, year] = [
                        fechaIngresada.getMonth(),
                        fechaIngresada.getDate(),
                        fechaIngresada.getFullYear(),
                    ];

                    // Verificar si es hoy
                    if (validarFachasIguales(fechaIngresada,fechaActual)) {
                        return fechahora;
                    }

                    // Calcular la fecha de ayer
                    const ayer = new Date(fechaActual);
                    ayer.setDate(ayer.getDate() - 1);
                     // Verificar si es ayer
                    if (validarFachasIguales(fechaIngresada,ayer)) {
                        return fechahora+',Ayer';
                    }
                     // Calcular la fecha de la semana pasada
                    const semanaPasada = new Date(fechaActual);
                    semanaPasada.setDate(semanaPasada.getDate() - 7);
                    // Verificar si es la semana pasada
                    if (fechaIngresada > semanaPasada && fechaIngresada < ayer) {
                        return fechahora+' ,'+obtenerDiaDeLaSemana(fecha);
                    }

                    return "("+day+"/"+month+"/"+year+")";
                    }
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