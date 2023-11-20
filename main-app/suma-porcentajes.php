<?php
$consultaSpcd=mysqli_query($conexion, "SELECT sum(act_valor) FROM ".BD_ACADEMICA.".academico_actividades WHERE act_id_carga='".$cargaSP."' AND act_estado=1 AND act_periodo='".$periodoSP."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
$spcd = mysqli_fetch_array($consultaSpcd, MYSQLI_BOTH);
$consultaSpcr=mysqli_query($conexion, "SELECT sum(act_valor) FROM ".BD_ACADEMICA.".academico_actividades WHERE act_id_carga='".$cargaSP."' AND act_estado=1 AND act_periodo='".$periodoSP."' AND act_registrada=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
$spcr = mysqli_fetch_array($consultaSpcr, MYSQLI_BOTH);
?>