<?php 
include("session.php");
try{
    $consultaCargas = mysqli_query($conexion, "SELECT * FROM academico_cargas
    INNER JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$_SESSION["bd"]}
    INNER JOIN ".BD_GENERAL.".usuarios uss ON uss_id=car_docente AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
    WHERE car_curso='".$_POST["grado"]."' AND car_grupo='".$_POST["grupo"]."'
    ORDER BY car_id");
} catch (Exception $e) {
    include("../compartido/error-catch-to-report.php");
}
while($datosCargas = mysqli_fetch_array($consultaCargas, MYSQLI_BOTH)){
    $nombre=$datosCargas['uss_nombre']." ".$datosCargas['uss_nombre2']." ".$datosCargas['uss_apellido1']." ".$datosCargas['uss_apellido1'];
    echo '<option value="'.$datosCargas['car_id'].'">['.$datosCargas['car_id'].'] - '.$datosCargas['mat_nombre'].' ('.strtoupper($nombre).')</option>';
}