<?php
    $filtro = '';
    $busqueda='';
    $usuario = '';
    if (!empty($_GET['usuario'])) {
        $usuario = base64_decode($_GET['usuario']);
        $filtro .= " AND fcu_usuario='".$usuario."'";
    }
    $tipo = '';
    if (!empty($_GET['tipo'])) {
        $tipo = base64_decode($_GET['tipo']);
        $filtro .= " AND fcu_tipo='".$tipo."'";
    }
    $estadoFil = '';
    if (!empty($_GET['estadoFil'])) {
        $estadoFil = base64_decode($_GET['estadoFil']);
        $filtro .= " AND fcu_status='".$estadoFil."'";
    }
    $estadoM = '';
    if (!empty($_GET['estadoM'])) {
        $estadoM = base64_decode($_GET['estadoM']);
        $filtro .= " AND mat_estado_matricula='".$estadoM."'";
    }
    $fecha = '';
    if (!empty($_GET['fecha'])) {
        $fecha = base64_decode($_GET['fecha']);
        $filtro .= " AND fcu_fecha='".$fecha."'";
    }
    $desde='';
    $hasta='';
    if (!empty($_GET["fFecha"]) || (!empty($_GET["desde"]) || !empty($_GET["hasta"]))) {
        $desde=$_GET["desde"];
        $hasta=$_GET["hasta"];
        $filtro .= " AND (fcu_fecha BETWEEN '" . $_GET["desde"] . "' AND '" . $_GET["hasta"] . "' OR fcu_fecha LIKE '%" . $_GET["hasta"] . "%')";
    }

    $estiloResaltadoFV = '';
    if (isset($_GET['tipo']) && $_GET['tipo'] == base64_encode(1)) $estiloResaltadoFV = 'style="color: '.$Plataforma->colorUno.';"';
    $estiloResaltadoFC = '';
    if (isset($_GET['tipo']) && $_GET['tipo'] == base64_encode(2)) $estiloResaltadoFC = 'style="color: '.$Plataforma->colorUno.';"';

    $estiloResaltadoCobrado = '';
    if (isset($_GET['estadoFil']) && $_GET['estadoFil'] == base64_encode(COBRADA)) $estiloResaltadoCobrado = 'style="color: '.$Plataforma->colorUno.';"';
    $estiloResaltadoPorCobrar = '';
    if (isset($_GET['estadoFil']) && $_GET['estadoFil'] == base64_encode(POR_COBRAR)) $estiloResaltadoPorCobrar = 'style="color: '.$Plataforma->colorUno.';"';
    require_once("../class/componentes/barra-superior.php");
$opciones[0] = [
    COMPB_OPCIONES_TEXTO   => 'Menú movimiento financiero',
    COMPB_OPCIONES_URL     => 'movimientos-importar.php',
    COMPB_OPCIONES_PERMISO => Modulos::validarPermisoEdicion() && Modulos::validarSubRol(['DT0105']),
    COMPB_OPCIONES_PAGINAS => $paginas = [
        [
            COMPB_OPCIONES_PAGINAS_TEXTO => 'Importar saldos',
            COMPB_OPCIONES_PAGINAS_URL   => 'movimientos-importar.php'
        ]
    ]
];
$filtroTipo = [
    [
        COMPB_FILTRO_LISTA_ID    => 1,
        COMPB_FILTRO_LISTA_TEXTO => 'Fact. Venta',
        COMPB_FILTRO_LISTA_URL   => $_SERVER['PHP_SELF'] . "?estadoFil=" . base64_encode($estadoFil) . "&usuario=" . base64_encode($usuario) . "&desde=" . $desde . "&hasta=" . $hasta . "&tipo=" . base64_encode(1) . "&estadoM=" . base64_encode($estadoM) . "&fecha=" . base64_encode($fecha)
    ],
    [
        COMPB_FILTRO_LISTA_ID    => 2,
        COMPB_FILTRO_LISTA_TEXTO => 'Fact. Compra',
        COMPB_FILTRO_LISTA_URL   => $_SERVER['PHP_SELF'] . "?estadoFil=" . base64_encode($estadoFil) . "&usuario=" . base64_encode($usuario) . "&desde=" . $desde . "&hasta=" . $hasta . "&tipo=" . base64_encode(2) . "&estadoM=" . base64_encode($estadoM) . "&fecha=" . base64_encode($fecha)
    ],
    [
        COMPB_FILTRO_LISTA_TEXTO => 'Ver Todos',
        COMPB_FILTRO_LISTA_URL   => $_SERVER['PHP_SELF']. "?estadoFil=" . base64_encode($estadoFil) ."=&usuario=" . base64_encode($usuario) . "&desde=" . $desde . "&hasta=" . $hasta . "&tipo=&estadoM=" . base64_encode($estadoM) . "&fecha=" . base64_encode($fecha),
        COMPB_FILTRO_LISTA_STYLE => 'font-weight: bold; text-align: center;'
    ]

];
$filtroEstado = [
    [
        COMPB_FILTRO_LISTA_ID    => POR_COBRAR,
        COMPB_FILTRO_LISTA_TEXTO => 'Por Cobrar',
        COMPB_FILTRO_LISTA_URL   => $_SERVER['PHP_SELF'] . "?estadoFil=" . base64_encode(POR_COBRAR) . "&usuario=" . base64_encode($usuario) . "&desde=" . $desde . "&hasta=" . $hasta . "&tipo=" . base64_encode($tipo) . "&estadoM=" . base64_encode($estadoM) . "&fecha=" . base64_encode($fecha)
    ],
    [
        COMPB_FILTRO_LISTA_ID    => COBRADA,
        COMPB_FILTRO_LISTA_TEXTO => 'Cobradas',
        COMPB_FILTRO_LISTA_URL   => $_SERVER['PHP_SELF'] . "?estadoFil=" . base64_encode(COBRADA) . "&usuario=" . base64_encode($usuario) . "&desde=" . $desde . "&hasta=" . $hasta . "&tipo=" . base64_encode($tipo) . "&estadoM=" . base64_encode($estadoM) . "&fecha=" . base64_encode($fecha)
    ],
    [
        COMPB_FILTRO_LISTA_TEXTO => 'Ver Todos',
        COMPB_FILTRO_LISTA_URL   => $_SERVER['PHP_SELF']. "?estadoFil=&usuario=" . base64_encode($usuario) . "&desde=" . $desde . "&hasta=" . $hasta  . "&tipo=" . base64_encode($tipo) .  "&estadoM=" . base64_encode($estadoM) . "&fecha=" . base64_encode($fecha),
        COMPB_FILTRO_LISTA_STYLE => 'font-weight: bold; text-align: center;'
    ]

];
$html = "
<form class='dropdown-item' method='get' action='".$_SERVER['PHP_SELF']."'>
                        <input type='hidden' name='tipo' value='".base64_encode($tipo)."' />
                        <input type='hidden' name='busqueda' value='".$busqueda."' />
                        <input type='hidden' name='usuario' value='".base64_encode($usuario)."' />
                        <input type='hidden' name='estadoM' value='".base64_encode($estadoM)."' />
                        <input type='hidden' name='fecha' value='".base64_encode($fecha)."' />
                        <input type='hidden' name='estadoFil' value='".base64_encode($estadoFil)."' />
                        <label>Fecha Desde:</label>
                        <input type='date' class='form-control' placeholder='desde' name='desde' value='".$desde."' />

                        <label>Hasta</label>
                        <input type='date' class='form-control' placeholder='hasta' name='hasta' value='".$hasta."' />

                        <input type='submit' class='btn deepPink-bgcolor' name='fFecha' value='Filtrar' style='margin: 5px;'>
                    </form>
                    <a class='dropdown-item' href='".$_SERVER['PHP_SELF']."' style='font-weight: bold; text-align: center;'>VER TODO</a>
";
$filtros[0] = [
    COMPB_FILTRO_GET    => 'tipo',
    COMPB_FILTRO_TEXTO  => 'Filtrar por tipo',
    COMPB_FILTRO_LISTA  => $filtroTipo,
];
$filtros[1] = [
    COMPB_FILTRO_GET    => 'estadoFil',
    COMPB_FILTRO_TEXTO  => 'Filtrar por estado',
    COMPB_FILTRO_LISTA  => $filtroEstado,
];

$filtros[2] = [
    COMPB_FILTRO_GET    => '',
    COMPB_FILTRO_TEXTO  => 'Filtrar por Fecha',
    COMPB_FILTRO_TIPO   => COMPB_FILTRO_TIPO_HTML,
    COMPB_FILTRO_TIPO_HTML_CONTENIDO=> $html ,
];


$barraSuperior = new componenteFiltro('movimientos', 'filter-movimientos.php','movimientos-tbody.php', $filtros, $opciones,'mostrarResultado');
$barraSuperior->generarComponente();
?>