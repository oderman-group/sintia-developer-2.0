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
    COMPB_FILTRO_LISTA_ID    => $grado['gra_id'],
    COMPB_FILTRO_LISTA_TEXTO => $grado['gra_nombre'],
    COMPB_FILTRO_LISTA_URL   => $_SERVER['PHP_SELF']."?estado=".base64_encode($estado)."&curso=".base64_encode($grado['gra_id'])
  ];
  $count++;
}
$filtroCurso[$count] = [
  COMPB_FILTRO_LISTA_TEXTO => 'VER TODOS',
  COMPB_FILTRO_LISTA_URL   => $_SERVER['PHP_SELF']."?estado=".base64_encode($estado)."&curso=",
  COMPB_FILTRO_LISTA_STYLE => 'font-weight: bold; text-align: center;'
];
$count=0;
foreach($ordenReal as $clave) {
  $filtroEstado[$count] = [
    COMPB_FILTRO_LISTA_ID    => $clave,
    COMPB_FILTRO_LISTA_TEXTO => $estadosSolicitud[$clave],
    COMPB_FILTRO_LISTA_URL   => $_SERVER['PHP_SELF']."?estado=".base64_encode($clave)."&curso=".base64_encode($curso)
  ];
  $count++;
}
$filtroEstado[$count] = [
  COMPB_FILTRO_LISTA_TEXTO => 'VER TODOS',
  COMPB_FILTRO_LISTA_URL   => $_SERVER['PHP_SELF']."?estado=&curso=".base64_encode($curso),
  COMPB_FILTRO_LISTA_STYLE => 'font-weight: bold; text-align: center;'
];

$filtros[0] = [
  COMPB_FILTRO_GET   => 'curso',
  COMPB_FILTRO_TEXTO => 'Filtrar por curso',
  COMPB_FILTRO_LISTA => $filtroCurso,
];
$filtros[1] = [
  COMPB_FILTRO_GET   => 'estado',
  COMPB_FILTRO_TEXTO => 'Filtrar por estado',
  COMPB_FILTRO_LISTA => $filtroEstado,
];


$barraSuperior = new componenteFiltro('inscripciones', 'filter-inscripciones.php', 'inscripciones-tbody.php',$filtros,null,'crearDatos');
$barraSuperior->generarComponente();
?>

