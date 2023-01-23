<?php
$tiempo_final = microtime(true);
$tiempo = $tiempo_final - $tiempo_inicial;
$tiempoMostrar = round($tiempo,3);
//HISTORIAL DE ACCIONES
mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".seguridad_historial_acciones(hil_usuario, hil_url, hil_titulo, hil_ip, hil_so, hil_institucion, hil_pagina_anterior, hil_tiempo_carga)
VALUES('".$_SESSION["id"]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', '".$idPaginaInterna."', '".$_SERVER["REMOTE_ADDR"]."', '".$_SERVER['HTTP_USER_AGENT']."', '".$config['conf_id_institucion']."', '".$_SERVER["HTTP_REFERER"]."', '".$tiempoMostrar."')");