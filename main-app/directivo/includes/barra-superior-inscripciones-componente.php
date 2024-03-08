<?php
$busqueda = '';
if (!empty($_GET['busqueda'])) {
  $busqueda = $_GET['busqueda'];
  $filtro .= " AND (
  mat_id LIKE '%" . $busqueda . "%' 
  OR mat_nombres LIKE '%" . $busqueda . "%' 
  OR mat_nombre2 LIKE '%" . $busqueda . "%' 
  OR mat_primer_apellido LIKE '%" . $busqueda . "%' 
  OR mat_segundo_apellido LIKE '%" . $busqueda . "%' 
  OR mat_documento LIKE '%" . $busqueda . "%' 
  OR mat_email LIKE '%" . $busqueda . "%'
  OR CONCAT(TRIM(mat_primer_apellido), ' ', TRIM(mat_segundo_apellido), ' ', TRIM(mat_nombres)) LIKE '%" . $busqueda . "%'
  OR CONCAT(TRIM(mat_primer_apellido), TRIM(mat_segundo_apellido), TRIM(mat_nombres)) LIKE '%" . $busqueda . "%'
  OR CONCAT(TRIM(mat_primer_apellido), ' ', TRIM(mat_nombres)) LIKE '%" . $busqueda . "%'
  OR CONCAT(TRIM(mat_primer_apellido), TRIM(mat_nombres)) LIKE '%" . $busqueda . "%'
  OR CONCAT(TRIM(mat_nombres), ' ', TRIM(mat_primer_apellido)) LIKE '%".$busqueda."%'
  OR CONCAT(TRIM(mat_nombres), '', TRIM(mat_primer_apellido)) LIKE '%".$busqueda."%'
  OR CONCAT(TRIM(mat_primer_apellido), '', TRIM(mat_nombres)) LIKE '%".$busqueda."%'
  OR CONCAT(TRIM(mat_nombres), ' ', TRIM(mat_nombre2)) LIKE '%".$busqueda."%'
  OR CONCAT(TRIM(mat_nombres), ' ', TRIM(mat_segundo_apellido)) LIKE '%".$busqueda."%'
  OR CONCAT(TRIM(mat_nombre2), ' ', TRIM(mat_nombres)) LIKE '%".$busqueda."%'
  OR CONCAT(TRIM(mat_segundo_apellido), ' ', TRIM(mat_nombres)) LIKE '%".$busqueda."%'
  OR CONCAT(TRIM(mat_segundo_apellido), ' ', TRIM(mat_nombre2)) LIKE '%".$busqueda."%'
  OR CONCAT(TRIM(mat_nombre2), ' ', TRIM(mat_segundo_apellido)) LIKE '%".$busqueda."%'
  OR gra_nombre LIKE '%" . $busqueda . "%'
  OR asp_email_acudiente LIKE '%" . $busqueda . "%'
  OR asp_nombre_acudiente LIKE '%" . $busqueda . "%'
  OR asp_nombre LIKE '%" . $busqueda . "%'
  OR asp_documento_acudiente LIKE '%" . $busqueda . "%'
  )";
}
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
    'url' => $_SERVER['PHP_SELF']."?estado=".base64_encode($estado)."&curso=".base64_encode($grado['gra_id'])."&busqueda=".$busqueda
  ];
  $count++;
}
$filtroCurso[$count] = [
  'texto' => 'VER TODOS',
  'url' => $_SERVER['PHP_SELF']
];
$count=0;
foreach($ordenReal as $clave) {
  $filtroEstado[$count] = [
    'texto' => $estadosSolicitud[$clave],
    'url' => $_SERVER['PHP_SELF']."?estado=".base64_encode($clave)."&curso=".base64_encode($curso)."&busqueda=".$busqueda
  ];
  $count++;
}
$filtroEstado[$count] = [
  'texto' => 'VER TODOS',
  'url' => $_SERVER['PHP_SELF']
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

