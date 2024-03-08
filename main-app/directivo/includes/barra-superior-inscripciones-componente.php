<?php
$curso = '';
if (!empty($_GET['curso'])) {
  $curso = base64_decode($_GET['curso']);
  $filtro .= " AND asp_grado='".$curso."'";
}
$estado = '';
if (!empty($_GET['estado'])) {
  $estado = base64_decode($_GET['estado']);
  $filtro .= " AND asp_estado_solicitud='".$estado."'";
}
require_once("../class/componentes/barra-superior.php");
$grados = Grados::listarGrados(1);
$count=0;
while ($grado = mysqli_fetch_array($grados, MYSQLI_BOTH)) {
  $filtroCurso[$count] = [
    'texto' => $grado['gra_nombre'],
    'url' => $_SERVER['PHP_SELF']."?estado=".base64_encode($estado)."&curso=".base64_encode($grado['gra_id'])
  ];
  $count++;
}
$filtroCurso[$count] = [
  'texto' => 'VER TODOS',
  'url' => $_SERVER['PHP_SELF'],
  'style' => 'font-weight: bold; text-align: center;'
];
$count=0;
foreach($ordenReal as $clave) {
  $filtroEstado[$count] = [
    'texto' => $estadosSolicitud[$clave],
    'url' => $_SERVER['PHP_SELF']."?estado=".base64_encode($clave)."&curso=".base64_encode($curso)
  ];
  $count++;
}
$filtroEstado[$count] = [
  'texto' => 'VER TODOS',
  'url' => $_SERVER['PHP_SELF'],
  'style' => 'font-weight: bold; text-align: center;'
];
$filtros[0] = [
  'get' => 'curso',
  'texto' => 'Filtrar por curso',
  'opciones' => $filtroCurso,
];
$filtros[1] = [
  'get' => 'estado',
  'texto' => 'Filtrar por estado',
  'opciones' => $filtroEstado,
];


$barraSuperior = new componenteFiltro('inscripciones', 'filter-inscripciones.php', 'inscripciones-tbody.php',$filtros,null,'crearDatos');
$barraSuperior->generarComponente();
?>

