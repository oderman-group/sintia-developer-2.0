<?php
require_once(ROOT_PATH."/main-app/class/componentes/ComponenteFiltro.php");
$grados = Grados::listarGrados(1);
$count = 0;
while ($grado = mysqli_fetch_array($grados, MYSQLI_BOTH)) {
    $filtroCurso[$count] = [
        ComponenteFiltro::COMPB_FILTRO_LISTA_ID => $grado['gra_id'],
        ComponenteFiltro::COMPB_FILTRO_LISTA_TEXTO => $grado['gra_nombre'],
        ComponenteFiltro::COMPB_FILTRO_LISTA_URL => $_SERVER['PHP_SELF'] . "?curso=" . base64_encode($grado['gra_id']) 
    ];
    $count++;
}
$filtroCurso[$count] = [
    ComponenteFiltro::COMPB_FILTRO_LISTA_TEXTO => 'VER TODOS',
    ComponenteFiltro::COMPB_FILTRO_LISTA_URL => $_SERVER['PHP_SELF'] . "?curso="
];
$filtros[0] = [
    ComponenteFiltro::COMPB_FILTRO_GET => 'curso',
    ComponenteFiltro::COMPB_FILTRO_TEXTO => 'Filtrar por curso',
    ComponenteFiltro::COMPB_FILTRO_LISTA => $filtroCurso,
    ComponenteFiltro::COMPB_FILTRO_TIPO => 'enlace',
];

for($i=1; $i<=$config['conf_periodos_maximos']; $i++){
    $filtroPeriodo[$i] = [
        ComponenteFiltro::COMPB_FILTRO_LISTA_ID => $i,
        ComponenteFiltro::COMPB_FILTRO_LISTA_TEXTO => 'Periodo '.$i,
        ComponenteFiltro::COMPB_FILTRO_LISTA_URL => $_SERVER['PHP_SELF'] . "?periodo=" . base64_encode($i) 
    ];
}
$filtros[1] = [
    ComponenteFiltro::COMPB_FILTRO_GET => 'periodo',
    ComponenteFiltro::COMPB_FILTRO_TEXTO => 'Filtrar por periodo',
    ComponenteFiltro::COMPB_FILTRO_LISTA => $filtroPeriodo,
    ComponenteFiltro::COMPB_FILTRO_TIPO => ComponenteFiltro::COMPB_FILTRO_TIPO_CHECK,
];

$opciones[0] = [
    ComponenteFiltro::COMPB_OPCIONES_TEXTO => 'Más opciones',
    ComponenteFiltro::COMPB_OPCIONES_URL => '',
    ComponenteFiltro::COMPB_OPCIONES_PERMISO => Modulos::validarPermisoEdicion() && Modulos::validarSubRol(['DT0035']),
    ComponenteFiltro::COMPB_OPCIONES_PAGINAS => $paginas = [
        [
            ComponenteFiltro::COMPB_OPCIONES_PAGINAS_TEXTO => 'Indicadores obligatorios',
            ComponenteFiltro::COMPB_OPCIONES_PAGINAS_URL => 'cargas-indicadores-obligatorios.php'
        ],
        [
            ComponenteFiltro::COMPB_OPCIONES_PAGINAS_TEXTO => 'Notas de Comportamiento',
            ComponenteFiltro::COMPB_OPCIONES_PAGINAS_URL => 'cargas-comportamiento-filtros.php'
        ],
        [
            ComponenteFiltro::COMPB_OPCIONES_PAGINAS_TEXTO => 'Transferir cargas',
            ComponenteFiltro::COMPB_OPCIONES_PAGINAS_URL => 'javascript:void(0);',
            ComponenteFiltro::COMPB_OPCIONES_PAGINAS_TARGET => '#modalTranferirCargas'
        ],
        [
            ComponenteFiltro::COMPB_OPCIONES_PAGINAS_TEXTO => 'Estilo de notas',
            ComponenteFiltro::COMPB_OPCIONES_PAGINAS_URL => 'cargas-estilo-notas.php'
        ]
    ]
];

$barraSuperior = new ComponenteFiltro('cargas', 'filter-cargas.php', 'cargas-tbody.php', $filtros, $opciones);
$barraSuperior->generarComponente();
$idModal = "modalTranferirCargas";
$contenido = "../directivo/cargas-transferir-modal.php";
include("../compartido/contenido-modal.php");
