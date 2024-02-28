<?php
    $filtro = '';
    $busqueda = '';
    if (!empty($_GET['busqueda'])) {
        $busqueda = $_GET['busqueda'];
        $filtro .= " AND (
            uss_id LIKE '%".$busqueda."%' 
            OR uss_nombre LIKE '%".$busqueda."%' 
            OR uss_nombre2 LIKE '%".$busqueda."%' 
            OR uss_apellido1 LIKE '%".$busqueda."%' 
            OR uss_apellido2 LIKE '%".$busqueda."%' 
            OR uss_usuario LIKE '%".$busqueda."%' 
            OR uss_email LIKE '%".$busqueda."%'
            OR CONCAT(TRIM(uss_nombre), ' ',TRIM(uss_apellido1), ' ', TRIM(uss_apellido2)) LIKE '%".$busqueda."%'
            OR CONCAT(TRIM(uss_nombre), TRIM(uss_apellido1), TRIM(uss_apellido2)) LIKE '%".$busqueda."%'
            OR CONCAT(TRIM(uss_nombre), ' ', TRIM(uss_apellido1)) LIKE '%".$busqueda."%'
            OR CONCAT(TRIM(uss_nombre), TRIM(uss_apellido1)) LIKE '%".$busqueda."%'
            OR fcu_detalle LIKE '%".$busqueda."%' 
            OR fcu_observaciones LIKE '%".$busqueda."%'
            OR fcu_id LIKE '%".$busqueda."%'
        )";
    }
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
    require_once("../compartido/includes/barra-superior.php");
$opciones[0] = [
    'texto' => 'Menú movimiento financiero',
    'url' => 'movimientos-importar.php',
    'permiso' => Modulos::validarPermisoEdicion() && Modulos::validarSubRol(['DT0105']),
    'paginas' => $paginas = [
        [
            'texto' => 'Importar saldos',
            'url' => 'movimientos-importar.php'
        ]
    ]
];
$filtroTipo = [
    [
        'texto' => 'Fact. Venta',
        'url' => $_SERVER['PHP_SELF'] . "?estadoFil=" . base64_encode($estadoFil) . "&usuario=" . base64_encode($usuario) . "&desde=" . $desde . "&hasta=" . $hasta . "&tipo=" . base64_encode(1) . "&busqueda=" . $busqueda . "&estadoM=" . base64_encode($estadoM) . "&fecha=" . base64_encode($fecha)
    ],
    [
        'texto' => 'Fact. Compra',
        'url' => $_SERVER['PHP_SELF'] . "?estadoFil=" . base64_encode($estadoFil) . "&usuario=" . base64_encode($usuario) . "&desde=" . $desde . "&hasta=" . $hasta . "&tipo=" . base64_encode(2) . "&busqueda=" . $busqueda . "&estadoM=" . base64_encode($estadoM) . "&fecha=" . base64_encode($fecha)
    ],
    [
        'texto' => 'Ver Todos',
        'url' => $_SERVER['PHP_SELF']
    ]

];
$filtroEstado = [
    [
        'texto' => 'Por Cobrar',
        'url' => $_SERVER['PHP_SELF'] . "?estadoFil=" . base64_encode(POR_COBRAR) . "&usuario=" . base64_encode($usuario) . "&desde=" . $desde . "&hasta=" . $hasta . "&tipo=" . base64_encode(1) . "&busqueda=" . $busqueda . "&estadoM=" . base64_encode($estadoM) . "&fecha=" . base64_encode($fecha)
    ],
    [
        'texto' => 'Cobradas',
        'url' => $_SERVER['PHP_SELF'] . "?estadoFil=" . base64_encode(COBRADA) . "&usuario=" . base64_encode($usuario) . "&desde=" . $desde . "&hasta=" . $hasta . "&tipo=" . base64_encode(2) . "&busqueda=" . $busqueda . "&estadoM=" . base64_encode($estadoM) . "&fecha=" . base64_encode($fecha)
    ],
    [
        'texto' => 'Ver Todos',
        'url' => $_SERVER['PHP_SELF']
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
    'get' => 'tipo',
    'texto' => 'Filtrar por tipo',
    'opciones' => $filtroTipo,
];
$filtros[1] = [
    'get' => 'estadoFil',
    'texto' => 'Filtrar por estado',
    'opciones' => $filtroEstado,
];

$filtros[2] = [
    'texto' => 'Filtrar por Fecha',
    'html' => $html,
];
$queryString = $_SERVER['QUERY_STRING'];// Parsear la cadena de consulta y almacenar los parámetros en un array
parse_str($queryString, $parametros);// Convertir el array a JSON
$filtros_get = json_encode($parametros);

$barraSuperior = new componenteFiltro('movimientos', 'filter-movimientos.php', 'saludar', $opciones, $filtros,$filtros_get);
$barraSuperior->generarComponente();
?>