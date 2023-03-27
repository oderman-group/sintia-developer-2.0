<?php
function getToString($valor)
{
    // validammos que las variables no sean null 
    if (isset($valor)) {
        return  $valor;
    }else{
        return "";
    }
    
}
