<link href="../compartido/comentarios.css" rel="stylesheet" type="text/css"/>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<div class="row">

    <div class="col-md-12">
        <?php include("../compartido/barra-superior-noticias.php");
            include("../class/SocialComentarios.php");?>
        <div class="row">

            <div class="col-md-4 col-lg-3">

                <?php
                    include("../compartido/datos-fechas.php");
                    if((($datosUsuarioActual['uss_tipo'] == TIPO_DEV) || ($datosUsuarioActual['uss_tipo'] == TIPO_DIRECTIVO)) && ($datosUnicosInstitucion['ins_deuda']==1 || $dfDias<=1)){
                        $monto=0;
                        $descripcion='Pago de';
                        if($datosUnicosInstitucion['ins_deuda']==1 && !empty($datosUnicosInstitucion['ins_valor_deuda'])){
                            $monto+=$datosUnicosInstitucion['ins_valor_deuda'];
                            $descripcion.=' saldo pendiente';
                        }
                        if($dfDias<=1 && ($datosUnicosInstitucion['ins_deuda']==1 && !empty($datosUnicosInstitucion['ins_valor_deuda']))){
                            $descripcion.=' y';
                        }
                        if($dfDias<=1){
                            $consultaPlan = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".planes_sintia 
                            WHERE plns_id='".$datosUnicosInstitucion['ins_id_plan']."'");
                            $datosPlan = mysqli_fetch_array($consultaPlan, MYSQLI_BOTH);
                            
                            $monto+=$datosPlan['plns_valor'];
                            $descripcion.=' renovación de la plataforma';
                        }
                ?>
                <div class="panel animate__animated animate__heartBeat animate__delay-1s animate__repeat-2">
                    <header class="panel-heading panel-heading-red">Pagos</header>
                    <div class="panel-body">
                        <p style="text-align: justify;">
                            Estimado <b><?=UsuariosPadre::nombreCompletoDelUsuario($datosUsuarioActual)?></b>, le recordamos que,
                            su Institución, <b><?=strtoupper($datosUnicosInstitucion['ins_nombre'])?></b>, tiene un
                            saldo pendiente con nuestra compañía.<br>
                            A continuación los detalles y las opciónes de pago:<br>
                            <b>Saldo pendiente:</b> <?php if(is_numeric($monto) && $monto > 0) echo "$".number_format($monto,0,".",".");?>.<br>
                            <b>Descripción:</b> <?=$datosUnicosInstitucion['ins_concepto_deuda'];?>.<br>
                            <hr>
                            Puede hacer una transferencia bancaria a la cuenta siguiente cuenta:<br>
                            <a href="javascript:void(0);" onclick="verCuentaBancaria()" id="cuentaBancaria" style="text-decoration: underline;">Ver número de cuenta</a>,<br> o tambien puede hacer el pago en linea, de forma segura, en el siguiente botón.
                        </p>
                        <div class="col-sm-4">
                            <form action="../pagos-online/index.php" method="post" target="_target">
                                <input type="hidden" class="form-control" name="idUsuario" value="<?=$datosUsuarioActual['uss_id'];?>">
                                <input type="hidden" class="form-control" name="emailUsuario" value="<?=$datosUsuarioActual['uss_email'];?>">
                                <input type="hidden" class="form-control" name="documentoUsuario" value="<?=$datosUsuarioActual['uss_documento'];?>">
                                <input type="hidden" class="form-control" name="nombreUsuario" value="<?=UsuariosPadre::nombreCompletoDelUsuario($datosUsuarioActual);?>">
                                <input type="hidden" class="form-control" name="celularUsuario" value="<?=$datosUsuarioActual['uss_celular'];?>">
                                <input type="hidden" class="form-control" name="idInstitucion" value="<?=$config['conf_id_institucion'];?>">
                                <input type="hidden" class="form-control" name="monto" value="<?=$monto;?>">
                                <input type="hidden" class="form-control" name="nombre" value="<?=$descripcion;?>">

                                <button type="submit" class="btn btn-success"><i class="fa fa-credit-card" aria-hidden="true"></i>PAGA EN LINEA AQUÍ</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php }?>

                <?php include("../compartido/modulo-frases-lateral.php");?>

                <?php include("../compartido/publicidad-lateral.php");?>

            </div>


            <div class="col-md-4 col-lg-6">
                <div class="card card-box">
                    <div class="card-head">
                        <header><?=$frases[168][$datosUsuarioActual['uss_idioma']];?></header>
                    </div>
                        <?php
                        $fotoUsrActual = $usuariosClase->verificarFoto($datosUsuarioActual['uss_foto']);
                        ?>
                    <div class="card-body " id="bar-parent1">
                        <form class="form-horizontal" action="../compartido/noticia-rapida-guardar.php" method="post">
                            <input type="hidden" id="infoGeneral" value="<?=base64_encode($datosUsuarioActual['uss_id']);?>|<?=$fotoUsrActual;?>|<?=$datosUsuarioActual['uss_nombre'];?>">
                            <div class="form-group row">
                                <div class="col-sm-12" data-hint="Realiza una publicación rápida, con solo texto.">
                                    <textarea id="contenido" name="contenido" class="form-control" rows="3"
                                        placeholder="<?=$frases[169][$datosUsuarioActual['uss_idioma']];?>"
                                        style="margin-top: 0px; margin-bottom: 0px; height: 100px; resize: none;"
                                        required></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="offset-md-5 col-md-7">
                                    <button 
                                        type="button"
                                        class="btn deepPink-bgcolor"
                                        onClick="crearNoticia()"
                                    >
                                        <?=$frases[170][$datosUsuarioActual['uss_idioma']];?>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <?php include("../compartido/encuestas.php");?>

                <div id="nuevaPublicacion"></div>

                <?php
									$arrayEnviar = array("tipo"=>4, "descripcionTipo"=>"Para ocultar fila del registro.");
									$arrayDatos = json_encode($arrayEnviar);
									$objetoEnviar = htmlentities($arrayDatos);
									?>


                <?php 
											$filtro = '';
											if(!empty($_GET["busqueda"])){$filtro .= " AND (not_titulo LIKE '%".$_GET["busqueda"]."%') OR (not_descripcion LIKE '%".$_GET["busqueda"]."%') OR (not_keywords LIKE '%".$_GET["busqueda"]."%')";}
											if(!empty($_GET["usuario"])){$filtro .= " AND not_usuario='".base64_decode($_GET["usuario"])."'";}
									
											$consulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".social_noticias
											LEFT JOIN ".BD_GENERAL.".usuarios uss ON uss_id=not_usuario AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
											WHERE (not_estado=1 or (not_estado=0 and not_usuario='".$_SESSION["id"]."')) 
											AND (not_para LIKE '%".$datosUsuarioActual['uss_tipo']."%' OR not_usuario='".$_SESSION["id"]."')
											AND not_year='" . $_SESSION["bd"] . "' AND (not_institucion='".$config['conf_id_institucion']."' OR not_global = 'SI')
											$filtro
											ORDER BY not_id DESC
											");
											$not = 1;
											$contReg = 1;
											while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
												$colorFondo = 'style="background: #FFF;"';
												if($resultado['not_estado']==0){$colorFondo = 'style="background: #999; opacity:0.7;"';}
												
												$consultaReacciones = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".social_noticias_reacciones
												INNER JOIN ".BD_GENERAL.".usuarios uss ON uss_id=npr_usuario AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
												WHERE npr_noticia='".$resultado['not_id']."'
												ORDER BY npr_id DESC
												");
												$numReacciones = mysqli_num_rows($consultaReacciones);
												$usrReacciones = mysqli_fetch_array(mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".social_noticias_reacciones 
												WHERE npr_noticia='".$resultado['not_id']."' AND npr_usuario='".$_SESSION["id"]."'"), MYSQLI_BOTH);
												
												if($datosUsuarioActual['uss_tipo']==4){
													include("verificar-usuario.php");
													$noticiasCursos = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".social_noticias_cursos WHERE notpc_noticia='".$resultado['not_id']."'");
													$notCursoNum = mysqli_num_rows($noticiasCursos);
													if($notCursoNum>0){
														$noticiaPermitida=0;
														while($notCursosInfo = mysqli_fetch_array($noticiasCursos, MYSQLI_BOTH)){
															if($notCursosInfo['notpc_curso']==$datosEstudianteActual['mat_grado']) {$noticiaPermitida=1;}
														}
														if($noticiaPermitida==0) continue;
													}
												}
												
												
												
												$fotoUsr = $usuariosClase->verificarFoto($resultado['uss_foto']);

                                                $clasesNoticiaGlobal = '';
                                                if( $resultado['not_global'] == 'SI' ) {
                                                    $clasesNoticiaGlobal = ' border border-info animate__animated animate__pulse animate__delay-1s animate__repeat-2 mt-5 mb-5';
                                                    $colorFondo = 'style="background: #FFFBE4;"';
                                                }
												
												
											?>
                <div id="PUB<?=base64_encode($resultado['not_id']);?>" class="row">
                    <div class="col-sm-12">
                        <div id="PANEL<?=base64_encode($resultado['not_id']);?>" class="panel <?=$clasesNoticiaGlobal;?>" <?=$colorFondo;?>>

                            <div class="card-head">
                                <header><?=$resultado['not_titulo'];?></header>

                                <?php if($_SESSION["id"]==$resultado['not_usuario'] || $datosUsuarioActual['uss_tipo']==1 || $datosUsuarioActual['uss_tipo']==5){?>

                                    <button id="panel-<?=$resultado['not_id'];?>"
                                        class="mdl-button mdl-js-button mdl-button--icon pull-right"
                                        data-upgraded=",MaterialButton">
                                        <i class="material-icons">more_vert</i>
                                    </button>
                                
                                    <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect"
                                        data-mdl-for="panel-<?=$resultado['not_id'];?>">

                                    <li class="mdl-menu__item">
                                        <a
                                        href="javascript:void(0);"
                                        id="<?=base64_encode($resultado['not_id']);?>|1"  
                                        name="../compartido/noticias-gestionar.php?e=<?=base64_encode(1)?>&idR=<?=base64_encode($resultado['not_id']);?>"
                                        onClick="ocultarNoticia(this)"
                                        >
                                        <i class="fa fa-eye"></i><?=$frases[172][$datosUsuarioActual['uss_idioma']];?></a>
                                    </li>
                                    <li class="mdl-menu__item">
                                    <a
                                    href="javascript:void(0);"
                                    id="<?=base64_encode($resultado['not_id']);?>|2"  
                                    name="../compartido/noticias-gestionar.php?e=<?=base64_encode(0)?>&idR=<?=base64_encode($resultado['not_id']);?>"
                                    onClick="ocultarNoticia(this)"
                                    >
                                        <i class="fa fa-eye-slash"></i><?=$frases[173][$datosUsuarioActual['uss_idioma']];?>
                                    </a>
                                    </li>
                                    
                                    <?php if($_SESSION["id"]==$resultado['not_usuario'] || $datosUsuarioActual['uss_tipo']==1){?>
                                        <li class="mdl-menu__item"><a
                                                href="noticias-editar.php?idR=<?=base64_encode($resultado['not_id']);?>"><i
                                                    class="fa fa-pencil-square-o"></i><?=$frases[165][$datosUsuarioActual['uss_idioma']];?></a>
                                        </li>
                                        
                                        <li class="mdl-menu__item"><a href="javascript:void(0);" title="<?=$objetoEnviar;?>"
                                                id="<?=base64_encode($resultado['not_id']);?>"
                                                name="../compartido/noticias-gestionar.php?e=<?=base64_encode(2)?>&idR=<?=base64_encode($resultado['not_id']);?>"
                                                onClick="deseaEliminar(this)"><i
                                                    class="fa fa-trash"></i><?=$frases[174][$datosUsuarioActual['uss_idioma']];?></a>
                                        </li>
                                    <?php }?>


                                    </ul>
                                <?php }?>
                            </div>

                            <div class="user-panel">
                                <div class="pull-left image">
                                    <img src="<?=$fotoUsr;?>" class="img-circle user-img-circle" alt="User Image"
                                        height="50" width="50" />
                                </div>
                                <div class="pull-left info">
                                    <p><a
                                            href="<?=$_SERVER['PHP_SELF'];?>?usuario=<?=base64_encode($resultado['uss_id']);?>"><?=$resultado['uss_nombre'];?></a><br><span
                                            style="font-size: 11px;"><?=$resultado['not_fecha'];?></span></p>
                                </div>
                            </div>

                            <script>
                            var images = document.getElementsByClassName('imagenes');
                            var modal = document.getElementById('myModal');
                            var modalImg = document.getElementById("img");
                            var captionText = document.getElementById("caption");
                            for (var i = 0; i <images.length; i++) {
                            images[i].onclick = function() {
                                modal.style.display = "block";
                                modalImg.src = this.src;
                                modalImg.alt = this.alt;

                            }}
                            var span = document.getElementsByClassName("close")[0];
                            span.onclick = function() {
                                modal.style.display = "none";
                            }
                            window.onclick = function(event) {
                                if (event.target == document.getElementById("myModal"))
                                    modal.style.display = "none";
                            }
                            </script>

                            <div id="myModal" class="modal">
                                <span class="close"></span>
                                <img class="modal-content" id="img">
                                <div id="caption"></div>
                            </div>

                            <div class="panel-body">
                                <p><?=$resultado['not_descripcion'];?></p>
                                <?php 
                                $urlImagen= $storage->getBucket()->object(FILE_PUBLICACIONES.$resultado["not_imagen"])->signedUrl(new DateTime('tomorrow')); 
                                $existe=$storage->getBucket()->object(FILE_PUBLICACIONES.$resultado["not_imagen"])->exists();
                                if($resultado['not_imagen']!="" and $existe){?>
                                <div class="item"><a><img class="imagenes" src="<?=$urlImagen?>"
                                            alt="<?=$resultado['not_titulo'];?>"></ah>
                                </div>
                                <p>&nbsp;</p>
                                <?php }?>

                                <?php if(!empty($resultado['not_video'])){?>
                                    <div>
                                        <iframe width="450" height="400"
                                        src="https://www.youtube.com/embed/<?=$resultado['not_video'];?>?rel=0&amp;"
                                        frameborder="0" allow="autoplay; encrypted-media" allowfullscreen
                                        volume="0"></iframe>
                                    </div>
                                    <p>&nbsp;</p>
                                <?php }?>

                                <?php if(!empty($resultado['not_enlace_video2'])){?>
                                    <div style="position: relative; padding-bottom: 56.25%; height: 0;">
                                        <iframe src="https://www.loom.com/embed/<?=$resultado['not_enlace_video2'];?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></iframe>
                                    </div>
                                    <p>&nbsp;</p>
                                <?php }?>

                                <?php
                                $urlArchivo= $storage->getBucket()->object(FILE_PUBLICACIONES.$resultado["not_archivo"])->signedUrl(new DateTime('tomorrow')); 
                                $existeArchivo=$storage->getBucket()->object(FILE_PUBLICACIONES.$resultado["not_archivo"])->exists();
                                if($resultado['not_archivo']!="" and $existeArchivo){?>
                                    <div align="right">
                                        <a href="<?=$urlArchivo?>" target="_blank"><i class="fa fa-download"></i> Descargar Archivo</a>
                                    </div>
                                    <p>&nbsp;</p>
                                <?php }?>
                                
                                <?php if(!empty($resultado['not_descripcion_pie'])){ echo $resultado['not_descripcion_pie']; }?>

                        </div>

                        <div id="car-body-<?= $resultado['not_id']; ?>" class="card-body" >
                                <?php
								 $rName = array("","Me gusta","Me encanta","Me divierte","Me entristece");
								 $rIcons = array("","fa-thumbs-o-up","fa-heart","fa-smile-o","fa-frown-o");
								 if(isset($usrReacciones['npr_reaccion']) AND $usrReacciones['npr_reaccion']!=""){$reaccionP = $usrReacciones['npr_reaccion'];}
								 else{$reaccionP = 1;}
								?>
                                <a id="panel-<?=$resultado['not_id'];?>1" style="margin-right: 10px;" class="pull-left"><i
                                        class="fa <?=$rIcons[$reaccionP];?>"></i> <?=$rName[$reaccionP];?></a>


                                <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect"
                                    data-mdl-for="panel-<?=$resultado['not_id'];?>1">
                                    <?php
								  $i=1;
								 while($i<=4){
									if(!empty($usrReacciones['npr_reaccion']) && $i==$usrReacciones['npr_reaccion']){$estilos1='style="background:#6d84b4;"'; $estilos2='style="color:#FFF;"';}else{$estilos1=''; $estilos2='';}
								  ?>
                                    <li class="mdl-menu__item" onclick="reaccionar('<?= base64_encode($resultado['not_id']); ?>','<?= base64_encode($i) ?>','<?= base64_encode($resultado['not_titulo']); ?>','<?= base64_encode($datosUsuarioActual['uss_nombre']); ?>','<?= base64_encode($resultado['not_usuario']) ?>')">
                                                <i class="fa <?= $rIcons[$i]; ?>"></i><?= $rName[$i]; ?></a>
                                            </li>
                                    <?php $i++;}?>
                                </ul>
                                <?php if($numReacciones>0){?>
                                
                                <a id="reacciones-<?= $resultado['not_id']; ?>" class="pull-left" onClick="mostrarDetalles(this)"
                                    name="<?=base64_encode($resultado['not_id']);?>"><?=number_format($numReacciones,0,",",".");?>
                                    reacciones</a>
                                <?php }?>
                                <?php  
                                $parametros =["ncm_noticia"=>$resultado['not_id'],"ncm_padre"=>0];
                                $numcomentarios = SocialComentarios::contar($parametros); 
                                ?>
                                <a id="comentarios-<?= $resultado['not_id']; ?>" class="pull-right" data-bs-toggle="collapse" data-bs-target="#collapseExample-<?= $resultado['not_id']; ?>" aria-expanded="false" aria-controls="collapseExample-<?= $resultado['not_id']; ?>">
                                
                                <?php if($numcomentarios>0){?> <?=$numcomentarios?> <?php }?> Comentarios
                                <i class="fa fa-comments-o" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="collapse" id="collapseExample-<?= $resultado['not_id']; ?>">
                                    <div class="card-body">
                                        <div class="input-group">

                                            <textarea id="comentario-<?= $resultado['not_id']; ?>" class="form-control" rows="2" placeholder="<?= UsuariosPadre::nombreCompletoDelUsuario($datosUsuarioActual); ?> DICE..." style="margin-top: 0px; margin-bottom: 0px; height: 100px; resize: none;"></textarea>

                                            <button class="input-group-text btn btn-primary " type="button" onclick="enviarComentario('<?= $resultado['not_id'] ?>','comentario')"><i class="fa fa-send" aria-hidden="true"></i></button>
                                        </div>
                                        <ul id="comments-list-<?=$resultado['not_id']?>" class="comments-list">
                                            <?php
                                            $parametros =["ncm_noticia"=> $resultado['not_id'],"ncm_padre"=> 0];
                                            $comentarios = SocialComentarios::listar($parametros);
                                            if ($comentarios) {
                                                foreach ($comentarios as $comentario) {
                                                    include '../compartido/comentario-li.php';
                                                };
                                            } ?>
                                        </ul>

                                    </div>
                            </div>

                        </div>
                        <script type="application/javascript">
                        function mostrarDetalles(dato) {
                            var id = 'pub' + dato.name;
                            document.getElementById(id).style.display = "block";
                        }

                        function ocultarDetalles(dato) {
                            var id = 'pub' + dato.name;
                            document.getElementById(id).style.display = "none";
                        }
                        </script>
                        <div class="panel" id="pub<?=base64_encode($resultado['not_id']);?>" style="display: none;">
                            <header class="panel-heading panel-heading-purple">
                                Reacciones (<?=number_format($numReacciones,0,",",".");?>)
                                <a class="pull-right" onClick="ocultarDetalles(this)"
                                    name="<?=base64_encode($resultado['not_id']);?>">Ocultar</a>
                            </header>
                            <div class="panel-body">
                                <?php
																while($datoReacciones = mysqli_fetch_array($consultaReacciones, MYSQLI_BOTH)){
																?>
                                <p><a><?=$datoReacciones['uss_nombre'];?></a>
                                    (<?=$rName[$datoReacciones['npr_reaccion']];?>)<br>
                                    <span
                                        style="font-size: 10px; color: darkgray;"><?=$datoReacciones['npr_fecha'];?></span>
                                </p>
                                <?php }?>
                            </div>
                        </div>

                    </div>
                </div>

               

                <?php
												$not++;
												$contReg ++;
											}
											?>
            </div>


            <!--<div class="col-md-4 col-lg-3">

                <div class="panel" data-hint="Se muestran las personas que están de cumpleaños en este día."
                    id="../compartido/cumplimentados.php" title="cumplimentados" onClick="axiosAjax(this)">
                    <header class="panel-heading panel-heading-red">
                        <?php echo $frases[215][$datosUsuarioActual['uss_idioma']]; ?></header>

                    <div id="RESP_cumplimentados" class="panel-body"></div>
                </div>


            </div>-->

            <script type="text/javascript">
                function reaccionar(id, reaccion, postname, usrname, postowner) {
                    var url = "../compartido/noticias-reaccionar-fetch.php";
                    var data = {
                        "id": id,
                        "reaccion": reaccion,
                        "postname": postname,
                        "usrname": usrname,
                        "postowner": postowner
                    };
                    metodoFetch(url, data, 'json', false, 'respuesta');
                }                
                function respuesta(response) {
                    if (response["ok"]) {
                        reaccionesNombre = ["", " Me gusta ", " Me encanta ", " Me divierte ", " Me entristece "];
                        reaccionesIconos = ["", "fa-thumbs-o-up", "fa-heart", "fa-smile-o", "fa-frown-o"];
                        index = parseInt(response["reaccion"]);

                        reacion = document.getElementById("reacciones-" + response["id"]);
                        carBody = document.getElementById("car-body-" + response["id"]);
                        panel = document.getElementById("panel-" + response["id"] + "1");
                        

                        if (reacion) {
                            reacion.classList=[];
                            reacion.innerText = response["cantidad"] + " Reacciones";
                            reacion.classList.add('pull-left','animate__animated', 'animate__fadeInDown');
                        }else{
                            var reacionNueva = document.createElement('a'); // se crea la etiqueta a
                            reacionNueva.id="reacciones-" + response["id"]
                            reacionNueva.classList.add('pull-left','animate__animated', 'animate__fadeInDown');
                            reacionNueva.innerText = response["cantidad"] + " Reacciones";
                            carBody.appendChild(reacionNueva);
                        }

                        panel.innerText = '';
                        var icon = document.createElement('i'); // se crea la icono
                        icon.classList.add('fa', reaccionesIconos[index]);
                        panel.appendChild(icon);
                        var texto = document.createTextNode(reaccionesNombre[index]);
                        panel.appendChild(texto);
                        panel.classList.add('animate__animated', 'animate__fadeInDown');

                        $.toast({
                            heading: 'Acción realizada',
                            text: response["msg"],
                            position: 'bottom-right',
                            showHideTransition: 'slide',
                            loaderBg: '#26c281',
                            icon: 'success',
                            hideAfter: 5000,
                            stack: 6
                        });
                    }
                }
                function enviarComentario(id, tipo, padre) {
                    if (tipo == "comentario") {
                        comentario = document.getElementById(tipo + "-" + id).value;
                    } else {
                        comentario = document.getElementById(tipo + "-" + id + "-" + padre).value;
                    }
                    var url = "../compartido/noticias-comentario-fetch.php";
                    var data = {
                        "id": id,
                        "comentario": comentario,
                        "padre": padre,
                        "tipo": tipo
                    };

                    metodoFetch(url, data, 'json', false, 'respuestaComentario');
                }

                function respuestaComentario(response) {                    
                    if (response["tipo"] == "comentario") {
                        var url = "../compartido/comentario-li.php";
                        metodoFetch(url, response, 'html', false, 'pintarComentarioLi');
                        document.getElementById("comentario-" + response["idNotica"]).value = "";
                    } else {
                        var url = "../compartido/respuesta-li.php";
                        metodoFetch(url, response, 'html', false, 'pintarRespuestaLi');
                        document.getElementById("respuesta-" + response["idNotica"] + "-" + response["padre"]).value = "";
                    }
                }

                function pintarComentarioLi(response, data) {                  
                    var lista = document.getElementById("comments-list-" + data["idNotica"]);
                    var i = document.createElement('li');
                    i.innerHTML = response;
                    lista.insertBefore(i, lista.firstChild);
                    // cambiar el valor de los comentarios
                    comentarios = document.getElementById("comentarios-" + data["idNotica"]);
                    comentarios.classList = [];
                    comentarios.innerText = data["cantidad"] + " Comentarios ";
                    var icon = document.createElement('i'); 
                    icon.classList.add('fa', 'fa-comments-o');
                    comentarios.appendChild(icon);
                    comentarios.classList.add('pull-right', 'animate__animated', 'animate__fadeInDown');
                    //notificacion de registro exitoso
                    $.toast({
                        heading: 'Acción realizada',
                        text: data["msg"],
                        position: 'bottom-right',
                        showHideTransition: 'slide',
                        loaderBg: '#26c281',
                        icon: 'success',
                        hideAfter: 5000,
                        stack: 6
                    });
                }

                function pintarRespuestaLi(response, data) {
                    console.log(response);
                    console.log(data);
                    var lista = document.getElementById("lista-respuesta-" + data["padre"]);
                    var miDiv = document.getElementById("div-respuesta-" + data["padre"]);
                    miDiv.classList.remove('show');
                    lista.classList.add('show');
                    var i = document.createElement('li');
                    i.innerHTML = response;
                    lista.insertBefore(i, lista.firstChild);
                     // cambiar el valor de los comentarios
                    respuestasCanatidad = document.getElementById("cantidad-respuestas-" + data["padre"]);
                    respuestasCanatidad.classList = [];
                    respuestasCanatidad.innerText = data["cantidad"] + " Respuestas ";
                    var icon = document.createElement('i'); 
                    icon.classList.add('fa', 'fa-comments-o');
                    respuestasCanatidad.appendChild(icon);
                    respuestasCanatidad.classList.add('pull-right', 'animate__animated', 'animate__fadeInDown');
                    //notificacion de registro exitoso
                    $.toast({
                        heading: 'Acción realizada',
                        text: data["msg"],
                        position: 'bottom-right',
                        showHideTransition: 'slide',
                        loaderBg: '#26c281',
                        icon: 'success',
                        hideAfter: 5000,
                        stack: 6
                    });
                }
            </script>
        </div>
    </div>
</div>