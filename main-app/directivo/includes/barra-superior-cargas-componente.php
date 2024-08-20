<?php
require_once("../class/componentes/barra-superior.php");
$grados = Grados::listarGrados(1);
$count = 0;
while ($grado = mysqli_fetch_array($grados, MYSQLI_BOTH)) {
    $filtroCurso[$count] = [
        COMPB_FILTRO_LISTA_ID => $grado['gra_id'],
        COMPB_FILTRO_LISTA_TEXTO => $grado['gra_nombre'],
        COMPB_FILTRO_LISTA_URL => $_SERVER['PHP_SELF'] . "?curso=" . base64_encode($grado['gra_id']) 
    ];
    $count++;
}
$filtroCurso[$count] = [
    COMPB_FILTRO_LISTA_TEXTO => 'VER TODOS',
    COMPB_FILTRO_LISTA_URL => $_SERVER['PHP_SELF'] . "?curso="
];
$filtros[0] = [
    COMPB_FILTRO_GET => 'curso',
    COMPB_FILTRO_TEXTO => 'Filtrar por curso',
    COMPB_FILTRO_LISTA => $filtroCurso,
    COMPB_FILTRO_TIPO => 'enlace',
];

for($i=1; $i<=$config['conf_periodos_maximos']; $i++){
    $filtroPeriodo[$i] = [
        COMPB_FILTRO_LISTA_ID => $i,
        COMPB_FILTRO_LISTA_TEXTO => 'Periodo '.$i,
        COMPB_FILTRO_LISTA_URL => $_SERVER['PHP_SELF'] . "?periodo=" . base64_encode($i) 
    ];
}
$filtros[1] = [
    COMPB_FILTRO_GET => 'periodo',
    COMPB_FILTRO_TEXTO => 'Filtrar por periodo',
    COMPB_FILTRO_LISTA => $filtroPeriodo,
    COMPB_FILTRO_TIPO => COMPB_FILTRO_TIPO_CHECK,
];

$opciones[0] = [
    COMPB_OPCIONES_TEXTO => 'Más opciones',
    COMPB_OPCIONES_URL => '',
    COMPB_OPCIONES_PERMISO => Modulos::validarPermisoEdicion() && Modulos::validarSubRol(['DT0035']),
    COMPB_OPCIONES_PAGINAS => $paginas = [
        [
            COMPB_OPCIONES_PAGINAS_TEXTO => 'Indicadores obligatorios',
            COMPB_OPCIONES_PAGINAS_URL => 'cargas-indicadores-obligatorios.php'
        ],
        [
            COMPB_OPCIONES_PAGINAS_TEXTO => 'Notas de Comportamiento',
            COMPB_OPCIONES_PAGINAS_URL => 'cargas-comportamiento-filtros.php'
        ],
        [
            COMPB_OPCIONES_PAGINAS_TEXTO => 'Transferir cargas',
            COMPB_OPCIONES_PAGINAS_URL => 'javascript:void(0);',
            COMPB_OPCIONES_PAGINAS_TARGET => '#modalTranferirCargas'
        ],
        [
            COMPB_OPCIONES_PAGINAS_TEXTO => 'Estilo de notas',
            COMPB_OPCIONES_PAGINAS_URL => 'cargas-estilo-notas.php'
        ]
    ]
];

$barraSuperior = new componenteFiltro('cargas', 'filter-cargas.php', 'cargas-tbody.php', $filtros, $opciones);
$barraSuperior->generarComponente();
$idModal = "modalTranferirCargas";
$contenido = "../directivo/cargas-transferir-modal.php";
include("../compartido/contenido-modal.php");
