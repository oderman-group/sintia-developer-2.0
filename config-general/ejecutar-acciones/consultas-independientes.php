<?php
function conversor($n, $b){
    if($n<$b)
        echo $n;
    else
        echo $n % $b;
}

conversor(14, 2);
?>