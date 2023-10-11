<?php include("session.php"); ?>
<?php $idPaginaInterna = 'DT0004'; ?>
<?php include("../compartido/historial-acciones-guardar.php"); ?>
<?php include("../compartido/head.php"); ?>
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
<link href="../../config-general/assets/css/chat.css" rel="stylesheet">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
<style>
    .form-icon-container .form-icon {
        position: absolute;
        top: 12px;
        left: 1rem;
        border-radius: 20px;
    }

    .svg-inline--fa {
        display: var(--fa-display, inline-block);
        height: 1em;
        overflow: visible;
        vertical-align: -0.125em;
    }

    .form-icon-container {
        position: relative;
    }


    .form-control {
        display: block;
        width: 100%;

        padding: 0.5rem 1rem;
        font-size: 0.8rem;
        font-weight: 600;
        line-height: 1.49;
        color: var(--phoenix-gray-900);
        background-color: var(--phoenix-input-bg);
        background-clip: padding-box;
        border: 1px solid var(--phoenix-input-border-color);
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        border-radius: 0.375rem;
        -webkit-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0);
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0);
        -webkit-transition: border-color .15s ease-in-out, -webkit-box-shadow .15s ease-in-out;
        transition: border-color .15s ease-in-out, -webkit-box-shadow .15s ease-in-out;
        -o-transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out, -webkit-box-shadow .15s ease-in-out;
    }

    .form-icon-input {
        border-radius: 20px;
    }

    .form-icon-input:focus {
        background-color: white !important;
        border: 1px solid;
    }

    .form-icon-container .form-icon-input,
    .form-icon-container .form-icon-label {
        padding-left: 2.5rem;
    }

    .scrollable-div {
        width: 100%;
        box-shadow: 0 1px 2px 0 rgb(0 0 0 / 10%);
        height: 65vh;
        overflow: auto;
        /* Habilitar el desplazamiento */
    }
    .chat {
        height: 100%;
        background-color: white;
        border-radius: 0.55rem 0.55rem 0 0;
        color: black;
    }
</style>
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
                <div class="row">
                    <div class="col-3 card2 ">
                        <div class="row">
                            <div class="col-12">
                                </br>
                                <div class="form-icon-container">
                                    <input class="form-control form-icon-input" type="text" style="background-color: rgb(0, 0, 0,0.2); margin: 0;" placeholder="Buscar usuarios..">
                                    <svg class="svg-inline--fa fa-user text-900 fs--1 form-icon" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="user" role="img" viewBox="0 0 448 512">
                                        <path fill="currentColor" d="M224 256c70.7 0 128-57.31 128-128s-57.3-128-128-128C153.3 0 96 57.31 96 128S153.3 256 224 256zM274.7 304H173.3C77.61 304 0 381.6 0 477.3c0 19.14 15.52 34.67 34.66 34.67h378.7C432.5 512 448 496.5 448 477.3C448 381.6 370.4 304 274.7 304z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-12">
                                </br>
                                <div class="btn-group btn-group-toggle col-12" data-toggle="buttons">
                                    <label id="btn-chat" class="btn btn-primary  col-6">
                                        <input type="radio" name="options" onchange="listarUsuarios(1)" autocomplete="off" checked>
                                        <i class="fa-regular fa-comment"></i>Chat
                                    </label>
                                    <label id="btn-all" class="btn btn-secondary  col-6">
                                        <input type="radio" name="options" onchange="listarUsuarios(2)" autocomplete="off">
                                        <i class="fa-solid fa-earth-americas"></i>All
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                </br>
                                <div id="chat-list" class="people-list scrollable-div">
                                    <ul class="list-unstyled chat-list mt-2 mb-0">
                                        <?php
                                        $consultaUsuariosOffline = mysqli_query(
                                            $conexion,
                                            "SELECT 
                                        uss_id,
                                        uss_nombre,
                                        uss_foto,
                                        uss_apellido1,
                                        uss_estado,
                                        chat_remite_usuario,
                                        chat_visto,
                                        mobiliar_sintia_social.fechaUltimoMensaje(chat_remite_usuario,chat_destino_usuario)as fecha_ulitmo_msj,
                                        mobiliar_sintia_social.ultimoMensaje(chat_remite_usuario,chat_destino_usuario)as ulitmo_msj,
                                        mobiliar_sintia_social.cantNoLeidos(chat_remite_usuario,chat_destino_usuario)as cantidad
                                        
                                        FROM mobiliar_sintia_social.chat  
                                        
                                        INNER JOIN mobiliar_dev_2023.usuarios 
                                        ON(uss_id=chat_destino_usuario)

                                        WHERE (chat_remite_usuario = '" . $_SESSION['id'] . "'  OR  chat_destino_usuario = '" . $_SESSION['id'] . "' ) 
                                        AND uss_id!='" . $_SESSION['id'] . "'  
                                        GROUP BY chat_remite_usuario,chat_destino_usuario
                                        ORDER BY fecha_ulitmo_msj DESC"
                                        );
                                        if (mysqli_num_rows($consultaUsuariosOffline) > 0) {
                                            while ($datosUsuriosOffline = mysqli_fetch_array($consultaUsuariosOffline, MYSQLI_BOTH)) {
                                                $fotoPerfilUsrOffline = $usuariosClase->verificarFoto($datosUsuriosOffline['uss_foto']);
                                        ?>
                                                <li class="clearfix" onclick="mostrarChat(this)" id="<?= $datosUsuriosOffline['uss_id'] ?>">
                                                    <img src="<?= $fotoPerfilUsrOffline ?>" alt="avatar">
                                                    <div class="about">
                                                        <div class="name" id="nombre_<?= $datosUsuriosOffline['uss_id'] ?>"><?= $datosUsuriosOffline['uss_nombre'] . ' ' . $datosUsuriosOffline['uss_apellido1'] ?></div>
                                                        <div class="status"> <i class="fa fa-circle offline"></i> <?= $datosUsuriosOffline['ulitmo_msj'] ?> <span id="notificacion_<?= $datosUsuriosOnline['uss_id'] ?>"> </div>
                                                    </div>
                                                </li>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </ul>
                                </div>
                                <div id="plist" style="display: none" class="people-list scrollable-div">
                                    <ul class="list-unstyled chat-list mt-2 mb-0" id="contenedorOriginial">
                                        <?php
                                        $consultaUsuariosOnline = mysqli_query($conexion, "SELECT uss_id, uss_nombre, uss_apellido1, uss_foto, uss_estado FROM usuarios 
                                        WHERE uss_estado=1 AND uss_bloqueado=0 AND uss_id!='" . $_SESSION['id'] . "' 
                                        ORDER BY uss_ultimo_ingreso DESC
                                        LIMIT 10");
                                        if (mysqli_num_rows($consultaUsuariosOnline) > 0) {
                                            while ($datosUsuriosOnline = mysqli_fetch_array($consultaUsuariosOnline, MYSQLI_BOTH)) {
                                                $fotoPerfilUsrOnline = $usuariosClase->verificarFoto($datosUsuriosOnline['uss_foto']);
                                        ?>
                                                <li class="clearfix" onclick="mostrarChat(this)" id="<?= $datosUsuriosOnline['uss_id'] ?>">
                                                    <img src="<?= $fotoPerfilUsrOnline ?>" alt="avatar">
                                                    <div class="about">
                                                        <div class="name" id="nombre_<?= $datosUsuriosOnline['uss_id'] ?>"><?= $datosUsuriosOnline['uss_nombre'] . ' ' . $datosUsuriosOnline['uss_apellido1'] ?></div>

                                                        <div class="status"> <i class="fa fa-circle online"></i> online <span id="notificacion_<?= $datosUsuriosOnline['uss_id'] ?>"> </div>
                                                    </div>
                                                </li>
                                            <?php
                                            }
                                        }
                                        $consultaUsuariosOffline = mysqli_query($conexion, "SELECT uss_id, uss_nombre, uss_apellido1, uss_foto, uss_estado FROM usuarios 
                                        WHERE uss_estado=0 AND uss_bloqueado=0 
                                        ORDER BY uss_ultima_salida DESC
                                        LIMIT 5");
                                        if (mysqli_num_rows($consultaUsuariosOffline) > 0) {
                                            while ($datosUsuriosOffline = mysqli_fetch_array($consultaUsuariosOffline, MYSQLI_BOTH)) {
                                                $fotoPerfilUsrOffline = $usuariosClase->verificarFoto($datosUsuriosOffline['uss_foto']);
                                            ?>
                                                <li class="clearfix" onclick="mostrarChat(this)" id="<?= $datosUsuriosOffline['uss_id'] ?>">
                                                    <img src="<?= $fotoPerfilUsrOffline ?>" alt="avatar">
                                                    <div class="about">
                                                        <div class="name" id="nombre_<?= $datosUsuriosOffline['uss_id'] ?>"><?= $datosUsuriosOffline['uss_nombre'] . ' ' . $datosUsuriosOffline['uss_apellido1'] ?></div>
                                                        <div class="status"> <i class="fa fa-circle offline"></i> offline <span id="notificacion_<?= $datosUsuriosOnline['uss_id'] ?>"> </div>
                                                    </div>
                                                </li>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </ul>
                                </div>

                            </div>

                        </div>
                    </div>
                    <div class="col-9">
                        <div class="chat2 chat" id="contenedorChat"  style="display:none;">
                            
                        </div>
                        <div class="chat bienvenida" id="contenedorBienvenida">
                            <div class="mensajeBienvenida">
                                <h1><span style='font-family:Arial; font-weight:bold;'>Te damos la bienvenida, <?= $datosUsuarioActual['uss_nombre'] ?></samp></h1>
                                <p>¡Todo listo para chatear! Comencemos.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            // preparar socket id
            var urlApi = 'http://localhost:3000';
            var socket = io(urlApi, {
                transports: ['websocket', 'polling', 'flashsocket']
            });
            // usuarios  y remitente
            var chat_remite_usuario = <?php echo $idSession ?>;
            var chat_destino_usuario = "";
            var foto_url_uss = "<?php echo $datosUsuarioActual["uss_foto"] ?>";
            var nombre_uss = "<?php echo $datosUsuarioActual["uss_nombre"] . " " . $datosUsuarioActual["uss_apellido1"] ?>";
            let iconGrabar = document.getElementById('iconGrabar');
            let audioReprodutor = document.getElementById('audioReprodutor');
            let btnlimpiar = document.getElementById('btnlimpiar');
            let btnDetener = document.getElementById('btnDetener');
            let imputmensaje = document.getElementById("imputMensaje");
            const _chatTipoMensaje = "<?php echo CHAT_TIPO_MENSAJE ?>";
            const _chatTipoDocumento = "<?php echo CHAT_TIPO_DOCUMENTO ?>";
            const _chatTipoImagen = "<?php echo CHAT_TIPO_IMAGEN ?>";
            const _chatTipoAudio = "<?php echo CHAT_TIPO_AUDIO ?>";
            let tipoMensaje = _chatTipoMensaje;
            const audioElement = document.getElementById('reproductor');
            const btnChat = document.getElementById('btn-chat');
            const btnAll = document.getElementById('btn-all');
            const plist = document.getElementById('plist');
            const chatList = document.getElementById('chat-list');

            function mostrarChat(datos) {
                console.log("entre a la sala_chat_" + chat_remite_usuario + "_" + chat_destino_usuario);
                socket.emit("leave", "sala_chat_" + chat_remite_usuario + "_" + chat_destino_usuario);
                socket.emit("leave", "listar_chat_" + chat_remite_usuario + "_" + chat_destino_usuario);
                var id = datos.id;

                console.log("id--->" + id);
                $("#contenedorChat").empty().hide();
                if (id !== '') {
                    $.ajax({
                        type: "POST",
                        url: "ajax-chat.php",
                        data: {
                            id: id
                        },
                        success: function(response) {
                            $("#contenedorBienvenida").hide();
                            $("#contenedorChat").show();

                            $.each(response, function(index, item) {
                                var html = '<div class="chat-header2 clearfix">' +
                                    '<div class="row">' +
                                    '<div class="col-lg-6">' +
                                    '<a href="javascript:void(0);" data-toggle="modal" data-target="#view_info">' +
                                    '<img src="' + item.fotoPerfil + '" alt="avatar">' +
                                    '</a>' +
                                    '<div class="chat-about">' +
                                    '<h6 class="m-b-0">' + item.nombre + '</h6>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '<div class="chat-history" id="chatHistory">' +
                                    '<ul class="m-b-0" id="contenido_chat">' +

                                    '</ul>' +
                                    '</div>' +

                                    '<div class="chat-message clearfix">' +
                                    '<div id="contenedorImagen"></div>' +
                                    '<audio controls id="audioReprodutor" style="display: none;"></audio>' +
                                    '<div id="miBoton" class="input-group mb-0">' +
                                    '<div class="input-group-prepend">' +
                                    '<span class="input-group-text" onClick="enviarMensaje()"><i class="fa fa-send"></i></span>' +
                                    '</div>' +
                                    '<div class="input-group-prepend">' +
                                    '<span class="input-group-text" onclick="cargarFile(&apos;' + _chatTipoImagen + '&apos;,cargarImagen)" data-toggle="popover" ><i class="fa fa-image"></i></span>' +
                                    '</div>' +
                                    '<div class="input-group-prepend">' +
                                    '<span class="input-group-text" onclick="cargarFile(&apos;' + _chatTipoDocumento + '&apos;,cargarArchivo)" ><i class="fa-solid fa-paperclip"></i></span>' +
                                    '</div>' +

                                    '<div class="input-group-prepend">' +
                                    '<span class="input-group-text" id="btnGrabar" onclick="iniciarGrabacion()" ><i id="iconGrabar" class="fa-solid fa-microphone"></i></span>' +
                                    '<span class="input-group-text" id="btnDetener" onclick="detenerGrabacion()"  style="display: none;" ><i class="fa-solid fa-stop"></i></span>' +
                                    '</div>' +
                                    '<input type="text" id="imputMensaje" onkeydown="ejecutarEnter(event)"  class="form-control" placeholder="Escriba su mensaje aqui...">' +
                                    '<div class="input-group-prepend">' +
                                    '<span id="btnlimpiar" class="input-group-text" onclick="limpiar()" style="display: none;"><i class="fa-solid fa-trash"></i></span>' +
                                    '</div>' +
                                    '</div>' +
                                    '<input type="file" id="cargarImagen"   name="imagen"   style="display: none;"  accept="image/*" >' +
                                    '<input type="file" id="cargarArchivo"  name="archivo"  style="display: none;">' +
                                    '<input type="file" id="cargarAudio"    name="audio"    style="display: none;"   accept="audio/*">   ' +
                                    '</div>';
                                $("#contenedorChat").append(html);

                            });
                        }
                    });
                }
            };


            function listarUsuarios(valor) {

                switch (valor) {
                    case 1:
                        btnChat.classList.remove("btn-secondary");
                        btnAll.classList.remove("btn-success");

                        btnChat.classList.add("btn-primary");
                        btnAll.classList.add("btn-secondary");
                        plist.style.display = "none";
                        chatList.style.display = "block";
                        break;
                    case 2:
                        btnAll.classList.remove("btn-secondary");
                        btnChat.classList.remove("btn-primary");

                        btnChat.classList.add("btn-secondary");
                        btnAll.classList.add("btn-success");
                        plist.style.display = "block";
                        chatList.style.display = "none";
                        break;
                }
            }
        </script>
        <!-- end page content -->
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
<!-- Common js-->
<script src="../../config-general/assets/js/app.js"></script>
<script src="../../config-general/assets/js/layout.js"></script>
<script src="../../config-general/assets/js/theme-color.js"></script>
<!-- notifications -->
<script src="../../config-general/assets/plugins/jquery-toast/dist/jquery.toast.min.js"></script>
<script src="../../config-general/assets/plugins/jquery-toast/dist/toast.js"></script>
<!-- Material -->
<script src="../../config-general/assets/plugins/material/material.min.js"></script>
<!-- end js include path -->

</body>

</html>