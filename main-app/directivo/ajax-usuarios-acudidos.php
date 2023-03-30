<?php 
include("session.php");
require_once("../class/Estudiantes.php");

$opcionesConsulta = Estudiantes::listarEstudiantes(0,'','LIMIT 0, 100');
// $jsonData['acudidos'] = array();
$i=0;
while($opcionesDatos = mysqli_fetch_array($opcionesConsulta, MYSQLI_BOTH)){
    $consultaUsuarioAcudiente=mysqli_query($conexion, "SELECT * FROM usuarios_por_estudiantes WHERE upe_id_usuario='".$_REQUEST['idA']."' AND upe_id_estudiante='".$opcionesDatos['mat_id']."'");
    $num = mysqli_num_rows($consultaUsuarioAcudiente);
    $nombre = Estudiantes::NombreCompletoDelEstudiante($opcionesDatos);
    $selected = " ";
    if($opcionesDatos['mat_acudiente']==$_REQUEST['idA'] AND $num>0){ $selected = 'selected';}
    $jsonData[$i]['value'] = $opcionesDatos['mat_id'];
    $jsonData[$i]['nombre'] = $nombre;
    $jsonData[$i]['select'] = $selected;
    $i++;
}
echo json_encode($jsonData);
?>