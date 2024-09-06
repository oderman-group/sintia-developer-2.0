<?php
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
  componenteFiltro::COMPB_OPCIONES_TEXTO   => 'Promedios estudiantiles',
  componenteFiltro::COMPB_OPCIONES_URL     => 'estudiantes-promedios.php',
  componenteFiltro::COMPB_OPCIONES_PERMISO => Modulos::validarSubRol(['DT0002'])
];
$cursoActual=GradoServicios::consultarCurso($curso);
$opciones[1] = [
  componenteFiltro::COMPB_OPCIONES_TEXTO   => 'Menú matriculas',
  componenteFiltro::COMPB_OPCIONES_PERMISO => Modulos::validarSubRol(['DT0077', 'DT0080', 'DT0075']),
  componenteFiltro::COMPB_OPCIONES_PAGINAS => $paginas = [
    [
      componenteFiltro::COMPB_OPCIONES_PAGINAS_TEXTO   => 'Importar matrículas desde Excel',
      componenteFiltro::COMPB_OPCIONES_PAGINAS_URL     => 'estudiantes-importar-excel.php',
      componenteFiltro::COMPB_OPCIONES_PAGINAS_PERMISO => Modulos::validarSubRol(['DT0077'])
    ],
    [
      componenteFiltro::COMPB_OPCIONES_PAGINAS_TEXTO   => 'Consolidado final',
      componenteFiltro::COMPB_OPCIONES_PAGINAS_URL     => 'estudiantes-consolidado-final.php',
      componenteFiltro::COMPB_OPCIONES_PAGINAS_PERMISO => Modulos::validarSubRol(['DT0080'])
    ],
    [
      componenteFiltro::COMPB_OPCIONES_PAGINAS_TEXTO   => 'Nivelaciones',
      componenteFiltro::COMPB_OPCIONES_PAGINAS_URL     => 'estudiantes-nivelaciones.php',
      componenteFiltro::COMPB_OPCIONES_PAGINAS_PERMISO => Modulos::validarSubRol(['DT0075'])
    ]
  ]
];
$opciones[3] = [
  componenteFiltro::COMPB_OPCIONES_TEXTO   => 'Más opciones',
  componenteFiltro::COMPB_OPCIONES_PERMISO => Modulos::validarPermisoEdicion() && Modulos::validarSubRol(['DT0212', 'DT0213', 'DT0214', 'DT0215', 'DT0175', 'DT0216', 'DT0149']),
  componenteFiltro::COMPB_OPCIONES_PAGINAS => $paginas = [
    [
      componenteFiltro::COMPB_OPCIONES_PAGINAS_TEXTO   => 'Matricular a todos',
      componenteFiltro::COMPB_OPCIONES_PAGINAS_URL     => 'javascript:void(0);',
      componenteFiltro::COMPB_OPCIONES_PAGINAS_PERMISO => Modulos::validarSubRol(['DT0212']),
      componenteFiltro::COMPB_OPCIONES_PAGINAS_ACTION  => 'sweetConfirmacion("Alerta!","Deseas ejecutar esta accion?","question","estudiantes-matricular-todos.php")'
    ],
    [
      componenteFiltro::COMPB_OPCIONES_PAGINAS_TEXTO   => 'Cancelar a todos',
      componenteFiltro::COMPB_OPCIONES_PAGINAS_URL     => 'javascript:void(0);',
      componenteFiltro::COMPB_OPCIONES_PAGINAS_PERMISO => Modulos::validarSubRol(['DT0213']),
      componenteFiltro::COMPB_OPCIONES_PAGINAS_ACTION  => 'sweetConfirmacion("Alerta!","Deseas ejecutar esta accion?","question","estudiantes-matriculas-cancelar.php")'
    ],
    [
      componenteFiltro::COMPB_OPCIONES_PAGINAS_TEXTO   => 'Asignar a todos al grupo A',
      componenteFiltro::COMPB_OPCIONES_PAGINAS_URL     => 'javascript:void(0);',
      componenteFiltro::COMPB_OPCIONES_PAGINAS_PERMISO => Modulos::validarSubRol(['DT0214']),
      componenteFiltro::COMPB_OPCIONES_PAGINAS_ACTION  => 'sweetConfirmacion("Alerta!","Deseas ejecutar esta accion?","question","estudiantes-grupoa-todos.php")',
      componenteFiltro::COMPB_OPCIONES_PAGINAS_DIVIDER => 'S'
    ],
    [
      componenteFiltro::COMPB_OPCIONES_PAGINAS_TEXTO   => 'Remover estudiantes Inactivos este año',
      componenteFiltro::COMPB_OPCIONES_PAGINAS_URL     => 'javascript:void(0);',
      componenteFiltro::COMPB_OPCIONES_PAGINAS_PERMISO => Modulos::validarSubRol(['DT0215']),
      componenteFiltro::COMPB_OPCIONES_PAGINAS_ACTION  => 'sweetConfirmacion("Alerta!","Esta opción removerá a todos lo estudiantes que no estén en estado Matriculado, desea continuar?","question","estudiantes-inactivos-remover.php")',
      componenteFiltro::COMPB_OPCIONES_PAGINAS_DIVIDER => 'S'
    ],
    [
      componenteFiltro::COMPB_OPCIONES_PAGINAS_TEXTO   => 'Colocar documento como usuario de acceso',
      componenteFiltro::COMPB_OPCIONES_PAGINAS_URL     => 'javascript:void(0);',
      componenteFiltro::COMPB_OPCIONES_PAGINAS_PERMISO => Modulos::validarSubRol(['DT0175']),
      componenteFiltro::COMPB_OPCIONES_PAGINAS_ACTION  => 'sweetConfirmacion("Alerta!","Deseas ejecutar esta accion?","question","estudiantes-documento-usuario-actualizar.php")'
    ],
    [
      componenteFiltro::COMPB_OPCIONES_PAGINAS_TEXTO   => 'Verificar y generar credenciales a estudiantes',
      componenteFiltro::COMPB_OPCIONES_PAGINAS_URL     => 'javascript:void(0);',
      componenteFiltro::COMPB_OPCIONES_PAGINAS_PERMISO => Modulos::validarSubRol(['DT0216']),
      componenteFiltro::COMPB_OPCIONES_PAGINAS_ACTION  => 'sweetConfirmacion("Alerta!","Deseas ejecutar esta accion?","question","estudiantes-crear-usuarios.php")'
    ],
    [
      componenteFiltro::COMPB_OPCIONES_PAGINAS_TEXTO   => 'Generar Folios',
      componenteFiltro::COMPB_OPCIONES_PAGINAS_URL     => 'filtro-general-folio.php',
      componenteFiltro::COMPB_OPCIONES_PAGINAS_PERMISO => Modulos::validarSubRol(['DT0149'])
    ]

  ]
];
$grados = Grados::listarGrados(1);
$count = 0;
while ($grado = mysqli_fetch_array($grados, MYSQLI_BOTH)) {
  $filtroCurso[$count] = [
    componenteFiltro::COMPB_FILTRO_LISTA_ID    => $grado['gra_id'],
    componenteFiltro::COMPB_FILTRO_LISTA_TEXTO => $grado['gra_nombre'],
    componenteFiltro::COMPB_FILTRO_LISTA_URL   => $_SERVER['PHP_SELF'] . "?estadoM=" . base64_encode($estadoM) . "&curso=" . base64_encode($grado['gra_id'])
  ];
  $count++;
}
$filtroCurso[$count] = [
  componenteFiltro::COMPB_FILTRO_LISTA_TEXTO => 'VER TODOS',
  componenteFiltro::COMPB_FILTRO_LISTA_URL   => $_SERVER['PHP_SELF']. "?estadoM=" . base64_encode($estadoM) . "&curso=",
  componenteFiltro::COMPB_FILTRO_LISTA_STYLE => 'font-weight: bold; text-align: center;'
];

$count = 0;
$estadoSelect=null;
foreach ($estadosMatriculasEstudiantes as $clave => $valor) {
  $listaEstado[$count] = [
    componenteFiltro::COMPB_FILTRO_LISTA_ID    => $clave,
    componenteFiltro::COMPB_FILTRO_LISTA_TEXTO => $valor,
    componenteFiltro::COMPB_FILTRO_LISTA_URL   => $_SERVER['PHP_SELF'] . "?estadoM=" . base64_encode($clave) . "&curso=" . base64_encode($curso)    
  ];
  if(!empty($estadoM) && $estadoM==$clave){
    $estadoSelect=$count;
  };
  $count++;
}
$listaEstado[$count] = [
  componenteFiltro::COMPB_FILTRO_LISTA_TEXTO => 'VER TODOS',
  componenteFiltro::COMPB_FILTRO_LISTA_URL   => $_SERVER['PHP_SELF']. "?estadoM=&curso=" . base64_encode($curso),
  componenteFiltro::COMPB_FILTRO_LISTA_STYLE => 'font-weight: bold; text-align: center;'
];


$filtros[0] = [
  componenteFiltro::COMPB_FILTRO_GET   => 'curso',
  componenteFiltro::COMPB_FILTRO_TEXTO => 'Filtrar por curso',
  componenteFiltro::COMPB_FILTRO_LISTA => $filtroCurso,
];

$filtros[1] = [
  componenteFiltro::COMPB_FILTRO_GET   => 'estadoM',
  componenteFiltro::COMPB_FILTRO_TEXTO => 'Filtrar por estados',
  componenteFiltro::COMPB_FILTRO_LISTA => $listaEstado,
  componenteFiltro::COMPB_FILTRO_SELECT=> $estadoSelect,
];

require_once("../class/componentes/componenteFiltro.php");
$barraSuperior = new componenteFiltro('matriculas', 'filter-matriculas.php', 'matriculas-tbody.php', $filtros, $opciones);
$barraSuperior->generarComponente();
