<?php
require_once("../class/componentes/barra-superior.php");
$grados = Grados::listarGrados(1);
$count = 0;
while ($grado = mysqli_fetch_array($grados, MYSQLI_BOTH)) {
    $filtroCurso[$count] = [
        'texto' => $grado['gra_nombre'],
        'url' => $_SERVER['PHP_SELF'] . "?curso=" . base64_encode($grado['gra_id']) 
    ];
    $count++;
}
$filtroCurso[$count] = [
    'texto' => 'VER TODOS',
    'url' => $_SERVER['PHP_SELF']
];
$filtros[0] = [
    'get' => 'curso',
    'texto' => 'Filtrar por curso',
    'opciones' => $filtroCurso,
];

$opciones[0] = [
    'texto' => 'Más opciones',
    'url' => '',
    'permiso' => Modulos::validarPermisoEdicion() && Modulos::validarSubRol(['DT0035']),
    'paginas' => $paginas = [
        [
            'texto' => 'Indicadores obligatorios',
            'url' => 'cargas-indicadores-obligatorios.php'
        ],
        [
            'texto' => 'Notas de Comportamiento',
            'url' => 'cargas-comportamiento-filtros.php'
        ],
        [
            'texto' => 'Indicadores obligatorios',
            'url' => 'javascript:void(0);',
            'data-target' => '#modalTranferirCargas'
        ],
        [
            'texto' => 'Transferir cargas',
            'url' => 'cargas-estilo-notas.php'
        ]
    ]
];

$barraSuperior = new componenteFiltro('cargas', 'filter-cargas.php', 'cargas-tbody.php', $filtros, $opciones);
$barraSuperior->generarComponente();
$idModal = "modalTranferirCargas";
$contenido = "../directivo/cargas-transferir-modal.php";
include("../compartido/contenido-modal.php");
