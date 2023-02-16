<html>
<body>
    
 <?php
 $elementos_por_pagina = 10;
 $lista = $cargaSP;
 $total_elementos = count($lista);
 $total_paginas = ceil($cargaSP / $elementos_por_pagina);

 if (!isset($_GET['pagina'])) {
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
 $elementos_actuales = array_slice($lista, $inicio, $elementos_por_pagina);

 foreach ($elementos_actuales as $elemento) {
    echo $elemento . "<br>";
 }

 ?>
 <div class="paginacion">
    <ul>
        <?php
           echo "<br>";
           echo "Total " . $pagina_actual . " de " . $total_paginas;
           echo "<br>";

          if ($pagina_actual > 1) {
              echo '<a href="?pagina=' . ($pagina_actual - 1) . '">Anterior</a> ';
            }
             for ($i=1; $i <= $total_paginas; $i++){
             if ($i == $pagina) {
                echo '<li class="pageSelected">'.$i.'</li>';
            } else{
                 echo '<li><a "href=?pagina='.$i.'">'.$i.'</a></li>';
                }   
            }

            if ($pagina_actual < $total_paginas) {
            echo '<a href="?pagina=' . ($pagina_actual + 1) . '">Siguiente</a>';
           }
        ?>
    </ul>
 </div>
 <script src="../../config-general/assets/css/theme/light/style.css ></script>
</body>
</html>
