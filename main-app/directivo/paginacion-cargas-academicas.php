<?php
$registros_por_pagina = 10;
$limite_inferior = 0;

try{
    $sql = "SELECT COUNT(*) AS total FROM academico_cargas
    INNER JOIN academico_grados ON gra_id=car_curso
    INNER JOIN ".BD_ACADEMICA.".academico_grupos gru ON gru.gru_id=car_grupo AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$_SESSION["bd"]}
    INNER JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$_SESSION["bd"]}
    INNER JOIN usuarios ON uss_id=car_docente
    WHERE car_id=car_id";
    $resultado = $conexion->query($sql);
} catch (Exception $e) {
    include("../compartido/error-catch-to-report.php");
}
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
try{
    $sql = "SELECT * FROM academico_cargas
    INNER JOIN academico_grados ON gra_id=car_curso
    INNER JOIN ".BD_ACADEMICA.".academico_grupos gru ON gru.gru_id=car_grupo AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$_SESSION["bd"]}
    INNER JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$_SESSION["bd"]}
    INNER JOIN usuarios ON uss_id=car_docente
    WHERE car_id=car_id LIMIT $limite_inferior, $registros_por_pagina";
    $resultado = $conexion->query($sql);
} catch (Exception $e) {
    include("../compartido/error-catch-to-report.php");
}

// Mostrar los enlaces de paginación
$primer_registro = $limite_inferior + 1;
$ultimo_registro = min($limite_inferior + $registros_por_pagina, $total_registros);

// Mostrar el mensaje con la información de los registros
echo "<p>Mostrando " . $primer_registro . " a " . $ultimo_registro . " de " . $total_registros . " resultados totales</p>";
echo "<div class='paginacion'>";
if ($pagina_actual > 1) {
    echo "<a href='?pagina=".($pagina_actual - 1)."'class='paginacion-enlace'>Anterior</a>";
}
for ($i = 1; $i <= $total_paginas; $i++) {
    if ($i == 1 || ($i > ($pagina_actual - 2) && $i < ($pagina_actual + 2)) || $i == $total_paginas) {
    if ($i == $pagina_actual) {
        echo "<span class='paginacion-actual'>$i</span>";
    } elseif ($i <= 3 || $i >= $total_paginas - 2 || ($i >= $pagina_actual - 2 && $i <= $pagina_actual + 2)) {
        echo "<a  class='paginacion-enlace' href='?pagina=$i'>$i</a>";
    } elseif ($i == 4 && $pagina_actual > 6) {
        echo "<span class='paginacion-enlace'>...</span>";
    } elseif ($i == $total_paginas - 3 && $pagina_actual < $total_paginas - 5) {
        echo "<span class='paginacion-enlace'>...</span>";
    }
}
}
if ($pagina_actual < ($total_paginas - 0)) {
    echo "<a href='?pagina=".($pagina_actual + 1)."' class='paginacion-enlace'>Siguiente</a>"; 
}
    ?>