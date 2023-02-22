<?php
$registros_por_pagina = 10;
$limite_inferior = 0;
// Calcular el número total de registros en la tabla
$sql = "SELECT COUNT(*) AS total FROM academico_cargas";
$resultado = $conn->query($sql);
$fila = $resultado->fetch_assoc();
$total_registros = $fila['total'];

// Calcular el número total de páginas
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Determinar la página actual
if (isset($_GET['pagina']) && is_numeric($_GET['pagina'])) {
    $pagina_actual = (int) $_GET['pagina'];
} else {
    $pagina_actual = 1;
}

// Verificar que la página actual no sea mayor al total de páginas
if ($pagina_actual > $total_paginas) {
    $pagina_actual = $total_paginas;
}

// Calcular el límite para la consulta
$limite_inferior = ($pagina_actual - 1) * $registros_por_pagina;
if ($pagina_actual == 1) {
  $limite_inferior = 0;}

// Consultar los datos de la tabla
$sql = "SELECT * FROM academico_cargas LIMIT $limite_inferior, $registros_por_pagina";
$resultado = $conn->query($sql);

// Mostrar los enlaces de paginación
echo "<div class='paginacion'>";
if ($pagina_actual > 1) {
    echo "<a href='?pagina=".($pagina_actual - 1)."'>Anterior</a>";
}
for ($i = 1; $i <= $total_paginas; $i++) {
    if ($i == 1 || ($i > ($pagina_actual - 2) && $i < ($pagina_actual + 2)) || $i == $total_paginas) {
    if ($i == $pagina_actual) {
        echo "<span>$i</span>";
    } elseif ($i <= 3 || $i >= $total_paginas - 2 || ($i >= $pagina_actual - 2 && $i <= $pagina_actual + 2)) {
        echo "<a href='?pagina=$i'>$i</a>";
    } elseif ($i == 4 && $pagina_actual > 6) {
        echo "<span>...</span>";
    } elseif ($i == $total_paginas - 3 && $pagina_actual < $total_paginas - 5) {
        echo "<span>...</span>";
    }
}
}
if ($pagina_actual < ($total_paginas - 1)) {
    echo "<a href='?pagina=".($pagina_actual + 1)."'>Siguiente</a>";
}
echo "</div>";?>
