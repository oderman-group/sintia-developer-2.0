<?php
include("session-compartida.php");
$idPaginaInterna = 'CM0010';
include("historial-acciones-guardar.php");
require_once("../class/UsuariosPadre.php");
?>

<div class="panel">
								
    <header class="panel-heading panel-heading-purple" align="center"><?=$frases[258][$datosUsuarioActual['uss_idioma']];?> (5)</header>
    <div class="col-sm-12">
        <ul class="feed-blog">
        <?php	
            $queryOptimizado = "
            SELECT *
            FROM ".$baseDatosServicios.".seguridad_historial_acciones sha1
            INNER JOIN (
                SELECT hil_titulo, hil_usuario, hil_institucion, MAX(hil_id) AS max_hil_id
                FROM ".$baseDatosServicios.".seguridad_historial_acciones
                WHERE hil_usuario = '".$datosUsuarioActual['uss_id']."' 
                AND hil_institucion = ".$config['conf_id_institucion']."
                GROUP BY hil_titulo, hil_usuario, hil_institucion
            ) sha2 ON sha1.hil_id = sha2.max_hil_id
            INNER JOIN ".$baseDatosServicios.".paginas_publicidad pp ON pp.pagp_id = sha1.hil_titulo
            WHERE pp.pagp_navegable = 1 
            AND pp.pagp_pagina IS NOT NULL 
            AND pp.pagp_ruta IS NOT NULL
            ORDER BY sha1.hil_id DESC
            LIMIT 5;
            ";
            
            $ultimasPaginas = mysqli_query($conexion, $queryOptimizado);										 
            while($consultaReciente = mysqli_fetch_array($ultimasPaginas)){						                       
            ?>
            
            <li class="diactive-feed">
                <div class="feed-user-img">
                    <img src="<?=$_GET["fotoPerfilUsr"];?>" class="img-radius "
                        alt="User-Profile-Image">
                </div>
                <h6>
                    <span class="label label-sm label-success">
                    <a href="<?=$consultaReciente['pagp_ruta'];?>" style="color:#FFF;"><?php echo $consultaReciente["pagp_pagina"]; ?></a>
                    </span> 
                    </span>&nbsp;</span>
                    <small class="text-muted"><?=$consultaReciente['hil_fecha'];?></small>
                </h6>
            </li>

            <?php }?>
        </ul>	
    </div>
</div>
