
<ul class="pagination pg-dark justify-content-center pb-5 pt-5 mb-0" style="float: none;">
    <li class="page-item">
<?php

    if($_REQUEST["nume"] == "1" ){
    $_REQUEST["nume"] == "0";
    echo  "";
    }else{
    if ($pagina>1)
    $ant = $_REQUEST["nume"] - 1;
    echo "<a class='page-link' aria-label='Previous' href='cargas.php?nume=1'><span aria-hidden='false'>Anterior</span><span class='sr-only'>Previous</span></a>"; 
    echo "<li class='page-item '><a class='page-link' href='cargas.php?nume=". ($pagina-1) ."' >".$ant."</a></li>"; }
    echo "<li class='page-item active'><a class='page-link' >".$_REQUEST["nume"]."</a></li>"; 
    $sigui = $_REQUEST["nume"] + 1;
    $ultima = $num_registros / $registros;
    if ($ultima == $_REQUEST["nume"] +1 ){
    $ultima == "";}
    if ($pagina<$paginas && $paginas>1)
    echo "<li class='page-item'><a class='page-link' href='cargas.php?nume=". ($pagina+1) ."'>".$sigui."</a></li>"; 
    if ($pagina<$paginas && $paginas>1)
    echo "
    <li class='page-item'><a class='page-link' aria-label='Next' href='cargas.php?nume=". ceil($ultima) ."'><span aria-hidden='false'>&raquo;</span><span class='sr-only'>Next</span></a>
    </li>";
    ?>
</ul>
