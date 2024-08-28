<?php
require_once("../class/componentes/componenteFiltro.php");
$grados = Grados::listarGrados(1);
$count = 0;
while ($grado = mysqli_fetch_array($grados, MYSQLI_BOTH)) {
    $filtroCurso[$count] = [
        componenteFiltro::COMPB_FILTRO_LISTA_ID => $grado['gra_id'],
        componenteFiltro::COMPB_FILTRO_LISTA_TEXTO => $grado['gra_nombre'],
        componenteFiltro::COMPB_FILTRO_LISTA_URL => $_SERVER['PHP_SELF'] . "?curso=" . base64_encode($grado['gra_id']) 
    ];
    $count++;
}
$filtroCurso[$count] = [
    componenteFiltro::COMPB_FILTRO_LISTA_TEXTO => 'VER TODOS',
    componenteFiltro::COMPB_FILTRO_LISTA_URL => $_SERVER['PHP_SELF'] . "?curso="
];
$filtros[0] = [
    componenteFiltro::COMPB_FILTRO_GET => 'curso',
    componenteFiltro::COMPB_FILTRO_TEXTO => 'Filtrar por curso',
    componenteFiltro::COMPB_FILTRO_LISTA => $filtroCurso,
    componenteFiltro::COMPB_FILTRO_TIPO => 'enlace',
];

for($i=1; $i<=$config['conf_periodos_maximos']; $i++){
    $filtroPeriodo[$i] = [
        componenteFiltro::COMPB_FILTRO_LISTA_ID => $i,
        componenteFiltro::COMPB_FILTRO_LISTA_TEXTO => 'Periodo '.$i,
        componenteFiltro::COMPB_FILTRO_LISTA_URL => $_SERVER['PHP_SELF'] . "?periodo=" . base64_encode($i) 
    ];
}
$filtros[1] = [
    componenteFiltro::COMPB_FILTRO_GET => 'periodo',
    componenteFiltro::COMPB_FILTRO_TEXTO => 'Filtrar por periodo',
    componenteFiltro::COMPB_FILTRO_LISTA => $filtroPeriodo,
    componenteFiltro::COMPB_FILTRO_TIPO => componenteFiltro::COMPB_FILTRO_TIPO_CHECK,
];

$opciones[0] = [
    componenteFiltro::COMPB_OPCIONES_TEXTO => 'Más opciones',
    componenteFiltro::COMPB_OPCIONES_URL => '',
    componenteFiltro::COMPB_OPCIONES_PERMISO => Modulos::validarPermisoEdicion() && Modulos::validarSubRol(['DT0035']),
    componenteFiltro::COMPB_OPCIONES_PAGINAS => $paginas = [
        [
            componenteFiltro::COMPB_OPCIONES_PAGINAS_TEXTO => 'Indicadores obligatorios',
            componenteFiltro::COMPB_OPCIONES_PAGINAS_URL => 'cargas-indicadores-obligatorios.php'
        ],
        [
            componenteFiltro::COMPB_OPCIONES_PAGINAS_TEXTO => 'Notas de Comportamiento',
            componenteFiltro::COMPB_OPCIONES_PAGINAS_URL => 'cargas-comportamiento-filtros.php'
        ],
        [
            componenteFiltro::COMPB_OPCIONES_PAGINAS_TEXTO => 'Transferir cargas',
            componenteFiltro::COMPB_OPCIONES_PAGINAS_URL => 'javascript:void(0);',
            componenteFiltro::COMPB_OPCIONES_PAGINAS_TARGET => '#modalTranferirCargas'
        ],
        [
            componenteFiltro::COMPB_OPCIONES_PAGINAS_TEXTO => 'Estilo de notas',
            componenteFiltro::COMPB_OPCIONES_PAGINAS_URL => 'cargas-estilo-notas.php'
        ]
    ]
];

$barraSuperior = new componenteFiltro('cargas', 'filter-cargas.php', 'cargas-tbody.php', $filtros, $opciones);
$barraSuperior->generarComponente();
$idModal = "modalTranferirCargas";
$contenido = "../directivo/cargas-transferir-modal.php";
include("../compartido/contenido-modal.php");
