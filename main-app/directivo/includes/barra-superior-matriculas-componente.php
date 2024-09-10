<?php
require_once("../class/componentes/componenteFiltro.php");
$curso = '';
if (!empty($_GET['curso'])) {
  $curso = base64_decode($_GET['curso']);
  $filtro .= " AND mat_grado='" . $curso . "'";
}
$estadoM = '';
if (!empty($_GET['estadoM'])) {
  $estadoM = base64_decode($_GET['estadoM']);
  $filtro .= " AND mat_estado_matricula='" . $estadoM . "'";
}
$opciones[0] = [
  ComponenteFiltro::COMPB_OPCIONES_TEXTO   => 'Promedios estudiantiles',
  ComponenteFiltro::COMPB_OPCIONES_URL     => 'estudiantes-promedios.php',
  ComponenteFiltro::COMPB_OPCIONES_PERMISO => Modulos::validarSubRol(['DT0002'])
];
$cursoActual=GradoServicios::consultarCurso($curso);
$opciones[1] = [
  ComponenteFiltro::COMPB_OPCIONES_TEXTO   => 'Menú matriculas',
  ComponenteFiltro::COMPB_OPCIONES_PERMISO => Modulos::validarSubRol(['DT0077', 'DT0080', 'DT0075']),
  ComponenteFiltro::COMPB_OPCIONES_PAGINAS => $paginas = [
    [
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_TEXTO   => 'Importar matrículas desde Excel',
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_URL     => 'estudiantes-importar-excel.php',
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_PERMISO => Modulos::validarSubRol(['DT0077'])
    ],
    [
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_TEXTO   => 'Consolidado final',
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_URL     => 'estudiantes-consolidado-final.php',
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_PERMISO => Modulos::validarSubRol(['DT0080'])
    ],
    [
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_TEXTO   => 'Nivelaciones',
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_URL     => 'estudiantes-nivelaciones.php',
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_PERMISO => Modulos::validarSubRol(['DT0075'])
    ]
  ]
];
$opciones[3] = [
  ComponenteFiltro::COMPB_OPCIONES_TEXTO   => 'Más opciones',
  ComponenteFiltro::COMPB_OPCIONES_PERMISO => Modulos::validarPermisoEdicion() && Modulos::validarSubRol(['DT0212', 'DT0213', 'DT0214', 'DT0215', 'DT0175', 'DT0216', 'DT0149']),
  ComponenteFiltro::COMPB_OPCIONES_PAGINAS => $paginas = [
    [
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_TEXTO   => 'Matricular a todos',
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_URL     => 'javascript:void(0);',
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_PERMISO => Modulos::validarSubRol(['DT0212']),
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_ACTION  => 'sweetConfirmacion("Alerta!","Deseas ejecutar esta accion?","question","estudiantes-matricular-todos.php")'
    ],
    [
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_TEXTO   => 'Cancelar a todos',
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_URL     => 'javascript:void(0);',
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_PERMISO => Modulos::validarSubRol(['DT0213']),
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_ACTION  => 'sweetConfirmacion("Alerta!","Deseas ejecutar esta accion?","question","estudiantes-matriculas-cancelar.php")'
    ],
    [
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_TEXTO   => 'Asignar a todos al grupo A',
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_URL     => 'javascript:void(0);',
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_PERMISO => Modulos::validarSubRol(['DT0214']),
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_ACTION  => 'sweetConfirmacion("Alerta!","Deseas ejecutar esta accion?","question","estudiantes-grupoa-todos.php")',
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_DIVIDER => 'S'
    ],
    [
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_TEXTO   => 'Remover estudiantes Inactivos este año',
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_URL     => 'javascript:void(0);',
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_PERMISO => Modulos::validarSubRol(['DT0215']),
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_ACTION  => 'sweetConfirmacion("Alerta!","Esta opción removerá a todos lo estudiantes que no estén en estado Matriculado, desea continuar?","question","estudiantes-inactivos-remover.php")',
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_DIVIDER => 'S'
    ],
    [
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_TEXTO   => 'Colocar documento como usuario de acceso',
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_URL     => 'javascript:void(0);',
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_PERMISO => Modulos::validarSubRol(['DT0175']),
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_ACTION  => 'sweetConfirmacion("Alerta!","Deseas ejecutar esta accion?","question","estudiantes-documento-usuario-actualizar.php")'
    ],
    [
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_TEXTO   => 'Verificar y generar credenciales a estudiantes',
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_URL     => 'javascript:void(0);',
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_PERMISO => Modulos::validarSubRol(['DT0216']),
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_ACTION  => 'sweetConfirmacion("Alerta!","Deseas ejecutar esta accion?","question","estudiantes-crear-usuarios.php")'
    ],
    [
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_TEXTO   => 'Generar Folios',
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_URL     => 'filtro-general-folio.php',
      ComponenteFiltro::COMPB_OPCIONES_PAGINAS_PERMISO => Modulos::validarSubRol(['DT0149'])
    ]

  ]
];
$grados = Grados::listarGrados(1);
$count = 0;
while ($grado = mysqli_fetch_array($grados, MYSQLI_BOTH)) {
  $filtroCurso[$count] = [
    ComponenteFiltro::COMPB_FILTRO_LISTA_ID    => $grado['gra_id'],
    ComponenteFiltro::COMPB_FILTRO_LISTA_TEXTO => $grado['gra_nombre'],
    ComponenteFiltro::COMPB_FILTRO_LISTA_URL   => $_SERVER['PHP_SELF'] . "?estadoM=" . base64_encode($estadoM) . "&curso=" . base64_encode($grado['gra_id'])
  ];
  $count++;
}
$filtroCurso[$count] = [
  ComponenteFiltro::COMPB_FILTRO_LISTA_TEXTO => 'VER TODOS',
  ComponenteFiltro::COMPB_FILTRO_LISTA_URL   => $_SERVER['PHP_SELF']. "?estadoM=" . base64_encode($estadoM) . "&curso=",
  ComponenteFiltro::COMPB_FILTRO_LISTA_STYLE => 'font-weight: bold; text-align: center;'
];

$count = 0;
$estadoSelect=null;
foreach ($estadosMatriculasEstudiantes as $clave => $valor) {
  $listaEstado[$count] = [
    ComponenteFiltro::COMPB_FILTRO_LISTA_ID    => $clave,
    ComponenteFiltro::COMPB_FILTRO_LISTA_TEXTO => $valor,
    ComponenteFiltro::COMPB_FILTRO_LISTA_URL   => $_SERVER['PHP_SELF'] . "?estadoM=" . base64_encode($clave) . "&curso=" . base64_encode($curso)    
  ];
  if(!empty($estadoM) && $estadoM==$clave){
    $estadoSelect=$count;
  };
  $count++;
}
$listaEstado[$count] = [
  ComponenteFiltro::COMPB_FILTRO_LISTA_TEXTO => 'VER TODOS',
  ComponenteFiltro::COMPB_FILTRO_LISTA_URL   => $_SERVER['PHP_SELF']. "?estadoM=&curso=" . base64_encode($curso),
  ComponenteFiltro::COMPB_FILTRO_LISTA_STYLE => 'font-weight: bold; text-align: center;'
];


$filtros[0] = [
  ComponenteFiltro::COMPB_FILTRO_GET   => 'curso',
  ComponenteFiltro::COMPB_FILTRO_TEXTO => 'Filtrar por curso',
  ComponenteFiltro::COMPB_FILTRO_LISTA => $filtroCurso,
];

$filtros[1] = [
  ComponenteFiltro::COMPB_FILTRO_GET   => 'estadoM',
  ComponenteFiltro::COMPB_FILTRO_TEXTO => 'Filtrar por estados',
  ComponenteFiltro::COMPB_FILTRO_LISTA => $listaEstado,
  ComponenteFiltro::COMPB_FILTRO_SELECT=> $estadoSelect,
];

$barraSuperior = new ComponenteFiltro('matriculas', 'filter-matriculas.php', 'matriculas-tbody.php', $filtros, $opciones);
$barraSuperior->generarComponente();
