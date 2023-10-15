<?php
class Utilidades {
    public static  function getToString($valor)
    {   
        // validammos que las variables no sean null 
        if (isset($valor)) {
            return  $valor;
        }else{
            return "";
        }
    
    }

    /**
     * Comprueba si un archivo existe en la ruta especificada.
     *
     * Esta función verifica si el archivo especificado por la ruta existe en el sistema de archivos.
     *
     * @param string $ruta La ruta completa al archivo que se va a comprobar.
     *
     * @return bool Devuelve true si el archivo existe, o false en caso contrario.
     */
    public static  function ArchivoExiste($ruta)
    {   
        if ( file_exists($ruta) ) {
            return  true;
        }

        return false;
    
    }
}