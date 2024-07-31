<?php
require_once("../class/componentes/barra-superior.php");
$grados = Grados::listarGrados(1);
$count = 0;
while ($grado = mysqli_fetch_array($grados, MYSQLI_BOTH)) {
    $filtroCurso[$count] = [
        'ID' => $grado['gra_id'],
        'texto' => $grado['gra_nombre'],
        'url' => $_SERVER['PHP_SELF'] . "?curso=" . base64_encode($grado['gra_id']) 
    ];
    $count++;
}
$filtroCurso[$count] = [
    'ID' => NULL,
    'texto' => 'VER TODOS',
    'url' => $_SERVER['PHP_SELF']
];
$filtros[0] = [
    'get' => 'curso',
    'texto' => 'Filtrar por curso',
    'opciones' => $filtroCurso,
    'tipo' => 'enlace',
];

for($i=1; $i<=$config['conf_periodos_maximos']; $i++){
    $filtroPeriodo[$i] = [
        'ID' => $i,
        'texto' => 'Periodo '.$i,
        'url' => $_SERVER['PHP_SELF'] . "?periodo=" . base64_encode($i) 
    ];
}
$filtros[1] = [
    'get' => 'periodo',
    'texto' => 'Filtrar por periodo',
    'opciones' => $filtroPeriodo,
    'tipo' => 'check',
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
            'texto' => 'Transferir cargas',
            'url' => 'javascript:void(0);',
            'data-target' => '#modalTranferirCargas'
        ],
        [
            'texto' => 'Estilo de notas',
            'url' => 'cargas-estilo-notas.php'
        ]
    ]
];

$barraSuperior = new componenteFiltro('cargas', 'filter-cargas.php', 'cargas-tbody.php', $filtros, $opciones);
$barraSuperior->generarComponente();
$idModal = "modalTranferirCargas";
$contenido = "../directivo/cargas-transferir-modal.php";
include("../compartido/contenido-modal.php");
