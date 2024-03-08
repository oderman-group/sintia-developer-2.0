<?php
$filtro = '';
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
  'texto' => 'Promedios estudiantiles',
  'url' => 'estudiantes-promedios.php',
  'permiso' => Modulos::validarSubRol(['DT0002'])
];
$opciones[1] = [
  'texto' => 'Menú matriculas',
  'permiso' => Modulos::validarSubRol(['DT0077', 'DT0080', 'DT0075']),
  'paginas' => $paginas = [
    [
      'texto' => 'Importar matrículas desde Excel',
      'url' => 'estudiantes-importar-excel.php',
      'permiso' => Modulos::validarSubRol(['DT0077'])
    ],
    [
      'texto' => 'Consolidado final',
      'url' => 'estudiantes-consolidado-final.php',
      'permiso' => Modulos::validarSubRol(['DT0080'])
    ],
    [
      'texto' => 'Nivelaciones',
      'url' => 'estudiantes-nivelaciones.php',
      'permiso' => Modulos::validarSubRol(['DT0075'])
    ]
  ]
];
$opciones[3] = [
  'texto' => 'Más opciones',
  'permiso' => Modulos::validarPermisoEdicion() && Modulos::validarSubRol(['DT0212', 'DT0213', 'DT0214', 'DT0215', 'DT0175', 'DT0216', 'DT0149']),
  'paginas' => $paginas = [
    [
      'texto' => 'Matricular a todos',
      'url' => 'javascript:void(0);',
      'permiso' => Modulos::validarSubRol(['DT0212']),
      'action' => 'sweetConfirmacion("Alerta!","Deseas ejecutar esta accion?","question","estudiantes-matricular-todos.php")'
    ],
    [
      'texto' => 'Cancelar a todos',
      'url' => 'javascript:void(0);',
      'permiso' => Modulos::validarSubRol(['DT0213']),
      'action' => 'sweetConfirmacion("Alerta!","Deseas ejecutar esta accion?","question","estudiantes-matriculas-cancelar.php")'
    ],
    [
      'texto' => 'Asignar a todos al grupo A',
      'url' => 'javascript:void(0);',
      'permiso' => Modulos::validarSubRol(['DT0214']),
      'action' => 'sweetConfirmacion("Alerta!","Deseas ejecutar esta accion?","question","estudiantes-grupoa-todos.php")',
      'divider' => 'S'
    ],
    [
      'texto' => 'Remover estudiantes Inactivos este año',
      'url' => 'javascript:void(0);',
      'permiso' => Modulos::validarSubRol(['DT0215']),
      'action' => 'sweetConfirmacion("Alerta!","Esta opción removerá a todos lo estudiantes que no estén en estado Matriculado, desea continuar?","question","estudiantes-inactivos-remover.php")',
      'divider' => 'S'
    ],
    [
      'texto' => 'Colocar documento como usuario de acceso',
      'url' => 'javascript:void(0);',
      'permiso' => Modulos::validarSubRol(['DT0175']),
      'action' => 'sweetConfirmacion("Alerta!","Deseas ejecutar esta accion?","question","estudiantes-documento-usuario-actualizar.php")'
    ],
    [
      'texto' => 'Verificar y generar credenciales a estudiantes',
      'url' => 'javascript:void(0);',
      'permiso' => Modulos::validarSubRol(['DT0216']),
      'action' => 'sweetConfirmacion("Alerta!","Deseas ejecutar esta accion?","question","estudiantes-crear-usuarios.php")'
    ],
    [
      'texto' => 'Generar Folios',
      'url' => 'filtro-general-folio.php',
      'permiso' => Modulos::validarSubRol(['DT0149'])
    ]

  ]
];
$grados = Grados::listarGrados(1);
$count = 0;
while ($grado = mysqli_fetch_array($grados, MYSQLI_BOTH)) {
  $filtroCurso[$count] = [
    'texto' => $grado['gra_nombre'],
    'url' => $_SERVER['PHP_SELF'] . "?estadoM=" . base64_encode($estadoM) . "&curso=" . base64_encode($grado['gra_id'])
  ];
  $count++;
}
$filtroCurso[$count] = [
  'texto' => 'VER TODOS',
  'url' => $_SERVER['PHP_SELF'],
  'style' => 'font-weight: bold; text-align: center;'
];
$filtros[0] = [
  'get' => 'curso',
  'texto' => 'Filtrar por curso',
  'opciones' => $filtroCurso,
];
$count = 0;
foreach ($estadosMatriculasEstudiantes as $clave => $valor) {
  $listaEstado[$count] = [
    'texto' => $valor,
    'url' => $_SERVER['PHP_SELF'] . "?estadoM=" . base64_encode($clave) . "&curso=" . base64_encode($curso)
  ];
  $count++;
}
$listaEstado[$count] = [
  'texto' => 'VER TODOS',
  'url' => $_SERVER['PHP_SELF'],
  'style' => 'font-weight: bold; text-align: center;'
];
$filtros[1] = [
  'get' => 'curso',
  'texto' => 'Filtrar por estados',
  'opciones' => $listaEstado,
];

require_once("../class/componentes/barra-superior.php");
$barraSuperior = new componenteFiltro('matriculas', 'filter-matriculas.php', 'matriculas-tbody.php', $filtros, $opciones);
$barraSuperior->generarComponente();
