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
require_once("../class/componentes/componente-filtro.php");
$grados = Grados::listarGrados(1);
$count=0;
while ($grado = mysqli_fetch_array($grados, MYSQLI_BOTH)) {
  $filtroCurso[$count] = [
    componenteFiltro::COMPB_FILTRO_LISTA_ID    => $grado['gra_id'],
    componenteFiltro::COMPB_FILTRO_LISTA_TEXTO => $grado['gra_nombre'],
    componenteFiltro::COMPB_FILTRO_LISTA_URL   => $_SERVER['PHP_SELF']."?estado=".base64_encode($estado)."&curso=".base64_encode($grado['gra_id'])
  ];
  $count++;
}
$filtroCurso[$count] = [
  componenteFiltro::COMPB_FILTRO_LISTA_TEXTO => 'VER TODOS',
  componenteFiltro::COMPB_FILTRO_LISTA_URL   => $_SERVER['PHP_SELF']."?estado=".base64_encode($estado)."&curso=",
  componenteFiltro::COMPB_FILTRO_LISTA_STYLE => 'font-weight: bold; text-align: center;'
];
$count=0;
foreach($ordenReal as $clave) {
  $filtroEstado[$count] = [
    componenteFiltro::COMPB_FILTRO_LISTA_ID    => $clave,
    componenteFiltro::COMPB_FILTRO_LISTA_TEXTO => $estadosSolicitud[$clave],
    componenteFiltro::COMPB_FILTRO_LISTA_URL   => $_SERVER['PHP_SELF']."?estado=".base64_encode($clave)."&curso=".base64_encode($curso)
  ];
  $count++;
}
$filtroEstado[$count] = [
  componenteFiltro::COMPB_FILTRO_LISTA_TEXTO => 'VER TODOS',
  componenteFiltro::COMPB_FILTRO_LISTA_URL   => $_SERVER['PHP_SELF']."?estado=&curso=".base64_encode($curso),
  componenteFiltro::COMPB_FILTRO_LISTA_STYLE => 'font-weight: bold; text-align: center;'
];

$filtros[0] = [
  componenteFiltro::COMPB_FILTRO_GET   => 'curso',
  componenteFiltro::COMPB_FILTRO_TEXTO => 'Filtrar por curso',
  componenteFiltro::COMPB_FILTRO_LISTA => $filtroCurso,
];
$filtros[1] = [
  componenteFiltro::COMPB_FILTRO_GET   => 'estado',
  componenteFiltro::COMPB_FILTRO_TEXTO => 'Filtrar por estado',
  componenteFiltro::COMPB_FILTRO_LISTA => $filtroEstado,
];


$barraSuperior = new componenteFiltro('inscripciones', 'filter-inscripciones.php', 'inscripciones-tbody.php',$filtros,null,'crearDatos');
$barraSuperior->generarComponente();
?>

