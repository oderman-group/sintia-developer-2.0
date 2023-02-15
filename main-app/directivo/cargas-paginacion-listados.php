
<?php include("../directivo/cargas.php");?>
<?php

$elementos_por_pagina = 10;

$lista = $resultado;
$consulta = count($lista);
$total_paginas = ceil($consulta / $elementos_por_pagina);

if (empty($_GET['pagina'])) {
    $pagina_actual = 1;
} else {
    $pagina_actual = $_GET['pagina'];
}

if ($pagina_actual < 1) {
    $pagina_actual = 1;
} elseif ($pagina_actual > $total_paginas) {
    $pagina_actual = $total_paginas;
}

$inicio = ($pagina_actual - 1) * $elementos_por_pagina;
$pagina_actuale = array_slice($lista, $inicio, $elementos_por_pagina);

foreach ($elementos_actuales as $elemento) {
    echo $elemento . "<br>";
}

echo "<br>";
echo "Página " . $pagina_actual . " de " . $total_paginas;
echo "<br>";

if ($pagina_actual > 1) {
    echo '<a href="?pagina=' . ($pagina_actual - 1) . '">Anterior</a> ';
}

if ($pagina_actual < $total_paginas) {
    echo '<a href="?pagina=' . ($pagina_actual + 1) . '">Siguiente</a>';
}
?>