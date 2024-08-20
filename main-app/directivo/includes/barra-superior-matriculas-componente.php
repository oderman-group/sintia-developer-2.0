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
  COMPB_OPCIONES_TEXTO   => 'Promedios estudiantiles',
  COMPB_OPCIONES_URL     => 'estudiantes-promedios.php',
  COMPB_OPCIONES_PERMISO => Modulos::validarSubRol(['DT0002'])
];
$cursoActual=GradoServicios::consultarCurso($curso);
$opciones[1] = [
  COMPB_OPCIONES_TEXTO   => 'Menú matriculas',
  COMPB_OPCIONES_PERMISO => Modulos::validarSubRol(['DT0077', 'DT0080', 'DT0075']),
  COMPB_OPCIONES_PAGINAS => $paginas = [
    [
      COMPB_OPCIONES_PAGINAS_TEXTO   => 'Importar matrículas desde Excel',
      COMPB_OPCIONES_PAGINAS_URL     => 'estudiantes-importar-excel.php',
      COMPB_OPCIONES_PAGINAS_PERMISO => Modulos::validarSubRol(['DT0077'])
    ],
    [
      COMPB_OPCIONES_PAGINAS_TEXTO   => 'Consolidado final',
      COMPB_OPCIONES_PAGINAS_URL     => 'estudiantes-consolidado-final.php',
      COMPB_OPCIONES_PAGINAS_PERMISO => Modulos::validarSubRol(['DT0080'])
    ],
    [
      COMPB_OPCIONES_PAGINAS_TEXTO   => 'Nivelaciones',
      COMPB_OPCIONES_PAGINAS_URL     => 'estudiantes-nivelaciones.php',
      COMPB_OPCIONES_PAGINAS_PERMISO => Modulos::validarSubRol(['DT0075'])
    ]
  ]
];
$opciones[3] = [
  COMPB_OPCIONES_TEXTO   => 'Más opciones',
  COMPB_OPCIONES_PERMISO => Modulos::validarPermisoEdicion() && Modulos::validarSubRol(['DT0212', 'DT0213', 'DT0214', 'DT0215', 'DT0175', 'DT0216', 'DT0149']),
  COMPB_OPCIONES_PAGINAS => $paginas = [
    [
      COMPB_OPCIONES_PAGINAS_TEXTO   => 'Matricular a todos',
      COMPB_OPCIONES_PAGINAS_URL     => 'javascript:void(0);',
      COMPB_OPCIONES_PAGINAS_PERMISO => Modulos::validarSubRol(['DT0212']),
      COMPB_OPCIONES_PAGINAS_ACTION  => 'sweetConfirmacion("Alerta!","Deseas ejecutar esta accion?","question","estudiantes-matricular-todos.php")'
    ],
    [
      COMPB_OPCIONES_PAGINAS_TEXTO   => 'Cancelar a todos',
      COMPB_OPCIONES_PAGINAS_URL     => 'javascript:void(0);',
      COMPB_OPCIONES_PAGINAS_PERMISO => Modulos::validarSubRol(['DT0213']),
      COMPB_OPCIONES_PAGINAS_ACTION  => 'sweetConfirmacion("Alerta!","Deseas ejecutar esta accion?","question","estudiantes-matriculas-cancelar.php")'
    ],
    [
      COMPB_OPCIONES_PAGINAS_TEXTO   => 'Asignar a todos al grupo A',
      COMPB_OPCIONES_PAGINAS_URL     => 'javascript:void(0);',
      COMPB_OPCIONES_PAGINAS_PERMISO => Modulos::validarSubRol(['DT0214']),
      COMPB_OPCIONES_PAGINAS_ACTION  => 'sweetConfirmacion("Alerta!","Deseas ejecutar esta accion?","question","estudiantes-grupoa-todos.php")',
      COMPB_OPCIONES_PAGINAS_DIVIDER => 'S'
    ],
    [
      COMPB_OPCIONES_PAGINAS_TEXTO   => 'Remover estudiantes Inactivos este año',
      COMPB_OPCIONES_PAGINAS_URL     => 'javascript:void(0);',
      COMPB_OPCIONES_PAGINAS_PERMISO => Modulos::validarSubRol(['DT0215']),
      COMPB_OPCIONES_PAGINAS_ACTION  => 'sweetConfirmacion("Alerta!","Esta opción removerá a todos lo estudiantes que no estén en estado Matriculado, desea continuar?","question","estudiantes-inactivos-remover.php")',
      COMPB_OPCIONES_PAGINAS_DIVIDER => 'S'
    ],
    [
      COMPB_OPCIONES_PAGINAS_TEXTO   => 'Colocar documento como usuario de acceso',
      COMPB_OPCIONES_PAGINAS_URL     => 'javascript:void(0);',
      COMPB_OPCIONES_PAGINAS_PERMISO => Modulos::validarSubRol(['DT0175']),
      COMPB_OPCIONES_PAGINAS_ACTION  => 'sweetConfirmacion("Alerta!","Deseas ejecutar esta accion?","question","estudiantes-documento-usuario-actualizar.php")'
    ],
    [
      COMPB_OPCIONES_PAGINAS_TEXTO   => 'Verificar y generar credenciales a estudiantes',
      COMPB_OPCIONES_PAGINAS_URL     => 'javascript:void(0);',
      COMPB_OPCIONES_PAGINAS_PERMISO => Modulos::validarSubRol(['DT0216']),
      COMPB_OPCIONES_PAGINAS_ACTION  => 'sweetConfirmacion("Alerta!","Deseas ejecutar esta accion?","question","estudiantes-crear-usuarios.php")'
    ],
    [
      COMPB_OPCIONES_PAGINAS_TEXTO   => 'Generar Folios',
      COMPB_OPCIONES_PAGINAS_URL     => 'filtro-general-folio.php',
      COMPB_OPCIONES_PAGINAS_PERMISO => Modulos::validarSubRol(['DT0149'])
    ]

  ]
];
$grados = Grados::listarGrados(1);
$count = 0;
while ($grado = mysqli_fetch_array($grados, MYSQLI_BOTH)) {
  $filtroCurso[$count] = [
    COMPB_FILTRO_LISTA_ID    => $grado['gra_id'],
    COMPB_FILTRO_LISTA_TEXTO => $grado['gra_nombre'],
    COMPB_FILTRO_LISTA_URL   => $_SERVER['PHP_SELF'] . "?estadoM=" . base64_encode($estadoM) . "&curso=" . base64_encode($grado['gra_id'])
  ];
  $count++;
}
$filtroCurso[$count] = [
  COMPB_FILTRO_LISTA_TEXTO => 'VER TODOS',
  COMPB_FILTRO_LISTA_URL   => $_SERVER['PHP_SELF']. "?estadoM=" . base64_encode($estadoM) . "&curso=",
  COMPB_FILTRO_LISTA_STYLE => 'font-weight: bold; text-align: center;'
];

$count = 0;
$estadoSelect=null;
foreach ($estadosMatriculasEstudiantes as $clave => $valor) {
  $listaEstado[$count] = [
    COMPB_FILTRO_LISTA_ID    => $clave,
    COMPB_FILTRO_LISTA_TEXTO => $valor,
    COMPB_FILTRO_LISTA_URL   => $_SERVER['PHP_SELF'] . "?estadoM=" . base64_encode($clave) . "&curso=" . base64_encode($curso)    
  ];
  if(!empty($estadoM) && $estadoM==$clave){
    $estadoSelect=$count;
  };
  $count++;
}
$listaEstado[$count] = [
  COMPB_FILTRO_LISTA_TEXTO => 'VER TODOS',
  COMPB_FILTRO_LISTA_URL   => $_SERVER['PHP_SELF']. "?estadoM=&curso=" . base64_encode($curso),
  COMPB_FILTRO_LISTA_STYLE => 'font-weight: bold; text-align: center;'
];


$filtros[0] = [
  COMPB_FILTRO_GET   => 'curso',
  COMPB_FILTRO_TEXTO => 'Filtrar por curso',
  COMPB_FILTRO_LISTA => $filtroCurso,
];

$filtros[1] = [
  COMPB_FILTRO_GET   => 'estadoM',
  COMPB_FILTRO_TEXTO => 'Filtrar por estados',
  COMPB_FILTRO_LISTA => $listaEstado,
  COMPB_FILTRO_SELECT=> $estadoSelect,
];

require_once("../class/componentes/barra-superior.php");
$barraSuperior = new componenteFiltro('matriculas', 'filter-matriculas.php', 'matriculas-tbody.php', $filtros, $opciones);
$barraSuperior->generarComponente();
