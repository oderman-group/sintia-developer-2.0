<?php 
include("session.php");
$filtroPeriodo =is_null($_POST["periodo"])?"":"AND car_periodo = '".$_POST["periodo"]."' ";
$consultaCargas = mysqli_query($conexion, "SELECT * FROM academico_cargas
                                    INNER JOIN academico_materias ON mat_id=car_materia
                                    INNER JOIN usuarios ON uss_id=car_docente
                                    WHERE car_curso='".$_POST["grado"]."' AND car_grupo='".$_POST["grupo"]."' ".$filtroPeriodo."
                                    ORDER BY car_id");
while($datosCargas = mysqli_fetch_array($consultaCargas, MYSQLI_BOTH)){
    $nombre=$datosCargas['uss_nombre']." ".$datosCargas['uss_nombre2']." ".$datosCargas['uss_apellido1']." ".$datosCargas['uss_apellido1'];
    echo '<option value="'.$datosCargas['car_id'].'">['.$datosCargas['car_id'].'] - '.$datosCargas['mat_nombre'].' ('.strtoupper($nombre).')</option>';
}