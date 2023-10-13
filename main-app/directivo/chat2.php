<?php include("session.php"); ?>
<?php $idPaginaInterna = 'DT0004'; ?>
<?php include("../compartido/historial-acciones-guardar.php"); ?>
<?php include("../compartido/head.php"); ?>

<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
<link href="../../config-general/assets/css/chat2.css" rel="stylesheet">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />

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
                </br>
                <div class="row">
                    <div class="col-3">
                        <div class="col-12 chat-height">
                            <div class="row">
                                <div class="col-12">
                                    </br>
                                    <div class="form-icon-container">
                                        <input class="form-control form-icon-input" type="text" onkeyup="buscarUsuario(this.value)" style="background-color: rgb(0, 0, 0,0.1); margin: 0;" placeholder="Buscar usuarios..">
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
                                            <input type="radio" name="options" onchange="mostrarListarUsuarios(1)" autocomplete="off" checked>
                                            <i class="fa-regular fa-comment"></i>Chat
                                        </label>
                                        <label id="btn-all" class="btn btn-secondary  col-6">
                                            <input type="radio" name="options" onchange="mostrarListarUsuarios(2)" autocomplete="off">
                                            <i class="fa-solid fa-earth-americas"></i>All
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    </br>
                                    <div id="chat-list" class="people-list scrollable-div">
                                        <ul class="list-unstyled chat-list mt-2 mb-0" id="listaChat">
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
                                                    if(strlen($datosUsuriosOffline['ulitmo_msj'])>20){
                                                        $mensaje = substr($datosUsuriosOffline['ulitmo_msj'], 0, 20)."..."; 
                                                    }else{
                                                        $mensaje = $datosUsuriosOffline['ulitmo_msj'];
                                                    }
                                                    $cantidad = $datosUsuriosOffline['cantidad'];
                                                    $estado = $datosUsuriosOffline['uss_estado']=="1"?"online":"offline";
                                                    if($cantidad!="0"){
                                                        $styleName="font-weight: 700;";
                                                        $className="badge headerBadgeColor2";
                                                    }else{
                                                        $cantidad=""; 
                                                        $styleName="";
                                                        $className="";
                                                    }
                                                   
                                            ?>
                                                    <li class="clearfix" onclick="mostrarChat(this)" id="<?= $datosUsuriosOffline['uss_id'] ?>">
                                                        <img src="<?= $fotoPerfilUsrOffline ?>" alt="avatar">
                                                        <div class="about">
                                                            <div class="name" Style="<?=$styleName?>" id="nombre_<?= $datosUsuriosOffline['uss_id'] ?>"><?= $datosUsuriosOffline['uss_nombre'] . ' ' . $datosUsuriosOffline['uss_apellido1'] ?></div>
                                                            <div class="status"> <i class="fa fa-circle <?=$estado ?>"></i> <?=$mensaje ?> <span class="<?=$className?>" id="notificacion_<?= $datosUsuriosOffline['uss_id']?>"> <?=$cantidad?></div>
                                                        </div>
                                                    </li>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                    <div id="plist" style="display: none" class="people-list scrollable-div">
                                        <ul class="list-unstyled chat-list mt-2 mb-0" id="listaUsuario" >
                                            <?php
                                            $consultaUsuariosOnline = mysqli_query($conexion, "SELECT uss_id, uss_nombre, uss_apellido1, uss_foto, uss_estado FROM usuarios 
                                                WHERE uss_estado=1 AND uss_bloqueado=0 AND uss_id!='" . $_SESSION['id'] . "' 
                                                ORDER BY uss_ultimo_ingreso DESC
                                                LIMIT 10");
                                            if (mysqli_num_rows($consultaUsuariosOnline) > 0) {
                                                while ($datosUsuriosOnline = mysqli_fetch_array($consultaUsuariosOnline, MYSQLI_BOTH)) {
                                                    $fotoPerfilUsrOnline = $usuariosClase->verificarFoto($datosUsuriosOnline['uss_foto']);
                                            ?>
                                                    <li class="clearfix" onclick="mostrarChat(this)" id="<?= $datosUsuriosOnline['uss_id'] ?>_uss">
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
                                                WHERE uss_estado=0 AND uss_bloqueado=0  AND uss_id!='" . $_SESSION['id'] . "'
                                                ORDER BY uss_ultima_salida DESC
                                                LIMIT 5");
                                            if (mysqli_num_rows($consultaUsuariosOffline) > 0) {
                                                while ($datosUsuriosOffline = mysqli_fetch_array($consultaUsuariosOffline, MYSQLI_BOTH)) {
                                                    $fotoPerfilUsrOffline = $usuariosClase->verificarFoto($datosUsuriosOffline['uss_foto']);
                                                ?>
                                                    <li class="clearfix" onclick="mostrarChat(this)" id="<?= $datosUsuriosOffline['uss_id'] ?>_uss">
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
                    </div>
                    <div class="col-9" style="padding-left: 0px;padding-right: 0px;">
                        <div class="chat-height" id="contenedorChat">

                            <div class="table-cell__container">
                                <span class="table-cell__content">
                                    <div class="div-center image">
                                        <img src="<?=$fotoPerfilUsr;?>" class="img-circle user-img-circle" alt="User Image">
                                        <h1>
                                            <span style='font-family:Arial; font-weight:bold;'>Te damos la bienvenida, <?= $datosUsuarioActual['uss_nombre'] ?></samp>
                                        </h1>
                                        <p>¡Todo listo para chatear! Comencemos.</p>
                                    </div>

                                </span>
                            </div>

                        </div>
                    </div>
                    <div id="modalImagen" class="modal">
                        <span class="close"></span>
                        <img class="modal-content" id="imagenModal">
                        <div id="caption"></div>
                    </div>



                </div>
            </div>
        </div>
        <script>            
            // Datos del usuario  remitente
            var chat_remite_usuario = <?php echo $idSession ?>;            
            var remite_foto_url_uss = "<?php echo $fotoPerfilUsr ?>";
            var remite_nombre_uss = "<?php echo $datosUsuarioActual["uss_nombre"] . " " . $datosUsuarioActual["uss_apellido1"] ?>";
             // Datos del usuario  destinatario
            var chat_destino_usuario = "";
            let destino_foto_url_uss = "";
            var destino_nombre_uss = "";

            let iconGrabar = document.getElementById('iconGrabar');
            let audioReprodutor = document.getElementById('audioReprodutor');
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
            const constraints = {
                audio: true
            };
            let mediaRecorder;
            let audioChunks = [];
            let audioBlob;

            navigator.mediaDevices.getUserMedia(constraints)
                .then(function(stream) {
                    mediaRecorder = new MediaRecorder(stream);
                    mediaRecorder.ondataavailable = function(e) {
                        if (e.data.size > 0) {
                            audioChunks.push(e.data);
                        }
                    };

                    mediaRecorder.onstop = function() {
                        audioBlob = new Blob(audioChunks, {
                            type: 'audio/wav'
                        });
                        document.getElementById('audioReprodutor').src = URL.createObjectURL(audioBlob);
                        const audioURL = URL.createObjectURL(audioBlob);
                        audioReprodutor.src = audioURL;
                        audioReprodutor.style.display = 'block';
                        inputAudio = document.getElementById('cargarAudio');
                        inputAudio.src = audioURL
                    };


                })
                .catch(function(err) {
                    console.error('No se puede acceder al micrófono: ' + err);
                });
        </script>
        <script src="../../config-general/assets/js/chat.js"></script>
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