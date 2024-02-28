<?php

class componenteFiltro
{

    /**
     * @var string El ID del componente.
     */
    private $id;
    /**
     * @var array  un array donde se insertaran los datos pra realizar los filtros de la consulta.
     */
    private $filtros;
    /**
     * @var array  un array donde se insertaran las opciones las culaes necesariamente tendran que utilizar las etiquetas 
     * [texto,permiso,pagina] y dentro de paginas debe ir un array con las siguiente estrucutra ['texto','url'].
     */
    private $opciones;
    /**
     * @var int la url donde sera ejecutado el fetch .
     */
    private $url;
    /**
     * @var int metodo por el cual se enviara el resultado obtenido del fetch. este debe estar en la direccion:
     * /app-sintia/main-app/compartido/componentes/filter/
     */
    private $metodo;
     /**
     * @var string Son los filtros pasados por $_GET.
     */
    private $filtros_get;
    public function __construct($id, $url, $metodo, $opciones = array(), $filtros = array() ,$filtros_get='')
    {
        $protocolo = '';
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            $protocolo = 'https://';
        } else {
            $protocolo = 'http://';
        }
        if (!empty($opciones)) {
            foreach ($opciones as $indice => $opcion) {
                if (!array_key_exists("texto", $opcion)) {
                    echo "En la opcion (" . $indice . ") del componente (" . $id . ")  no está la etiqueta texto en el array.";
                    exit();
                }
                if (!array_key_exists("permiso", $opcion)) {
                    echo "En la opcion (" . $indice . ") del componente (" . $id . ")  no está la etiqueta permiso en el array";
                    exit();
                }
                if (array_key_exists("paginas", $opcion)) {
                    if (is_array($opcion["paginas"])) {
                        foreach ($opcion["paginas"] as $indice2 => $page) {
                            if (!array_key_exists("texto", $page)) {
                                echo "En la opcion (" . $indice . ") del componente (" . $id . ")  en la pagina({$indice2}) no está la etiqueta texto en el array";
                                exit();
                            }
                            if (!array_key_exists("url", $page)) {
                                echo "En la opcion (" . $indice . ") del componente (" . $id . ")  en la pagina({$indice2}) no está la etiqueta url en el array";
                                exit();
                            }
                        }
                    } else {
                        echo "En la opcion (" . $indice . ") del componente (" . $id . ")  paginas debe ser un  array.";
                        exit();
                    }
                }
            }
        }
        $this->opciones = $opciones;
        $this->id = $id;
        $this->url = $protocolo . $_SERVER['HTTP_HOST'] . '/app-sintia/main-app/compartido/componentes/filter/' . $url;
        $this->metodo = $metodo;
        $this->filtros = $filtros;
        $this->filtros_get = $filtros_get;
    }

    private $accion;


    public function generarComponente()
    {
        global $frases, $datosUsuarioActual, $Plataforma;
        // Generar HTML del componente
        $html = "
        <nav class='navbar navbar-expand-lg navbar-dark' style='background-color: #41c4c4;'>
            <ul class='navbar-nav mr-auto'>";
        foreach ($this->opciones as $opcion) {
            if ($opcion)
                $html .= "
                <li class='nav-item dropdown'>
                    <a class='nav-link dropdown-toggle' href='javascript:void(0);' id='navbarDropdown' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' style='color:{$Plataforma->colorUno};'>
                        {$opcion["texto"]}
                        <span class='fa fa-angle-down'></span>
                    </a>";
            if (!empty($opcion["paginas"])) {
                $html .= "<div class='dropdown-menu' aria-labelledby='navbarDropdown'>";
                foreach ($opcion["paginas"] as $pagina) {
                    $html .= "
                    <a class='dropdown-item' href='{$pagina["url"]}'>{$pagina["texto"]}</a>
                    ";
                }
                $html .= "</div>";
            }
            $html .= "
                </li>
                ";
           
        }
        if (!empty($this->filtros)) {
            $html .= " <li class='nav-item'> <a class='nav-link' href='javascript:void(0);' style='color:#FFF;'>|</a></li>";
            foreach ($this->filtros as $filtro) {
                $html .= " <li class='nav-item dropdown'>
                <a class='nav-link dropdown-toggle' href='javascript:void(0);' id='navbarDropdown' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' style='color:#FFF;'>
                {$filtro["texto"]}  ";
                if (!empty($filtro["get"])){
                    if(!empty($_GET[$filtro["get"]])){
                        $decode=base64_decode($_GET[$filtro["get"]]);
                        $html.=": {$decode} ";
                    }                   
                }

                $html.="
					<span class='fa fa-angle-down'></span>
				</a>
                ";
                if(!empty($filtro["opciones"])){
                    $html .= "<div class='dropdown-menu' aria-labelledby='navbarDropdown'>";
                    foreach ($filtro["opciones"] as $opcion) {
                        $html .= "<a class='dropdown-item' href='{$opcion["url"]}' >{$opcion["texto"]}</a>";
                    }
                    $html .= "</div>";
                }
                if(!empty($filtro["html"])){
                    $html .= "<div class='dropdown-menu' aria-labelledby='navbarDropdown'>";
                    $html .= $filtro["html"];
                    $html .= "</div>";
                }
                

                $html .= "</li>";
            }
        }

        $html .= "   </ul> 
            <div class='form-inline my-2 my-lg-0'>
                <input id='input_{$this->id}' class='form-control mr-sm-2' type='search' placeholder='{$frases[386][$datosUsuarioActual['uss_idioma']]}..' aria-label='Search' name='busqueda' >
                <button id='btn_{$this->id}' onclick='buscar()' class='btn deepPink-bgcolor my-2 my-sm-0' type='buttom'>{$frases[8][$datosUsuarioActual['uss_idioma']]}</button>
            </div>
        </nav>
        <script type='text/javascript'>
        input_{$this->id}.addEventListener('keyup', function(event) {
            buscar()
          });
        function buscar(){
            var valor = document.getElementById('input_{$this->id}').value;
            var url = '{$this->url}';  
            if(valor.length > 3){
                var data = {'valor': (valor)};	
                data.filtros =JSON.parse('{$this->filtros_get}');
                ejecutarFetch(url,data);
            }else if(valor.length == 0){
                var data = {'valor': ''};
                data.filtros =JSON.parse('{$this->filtros_get}');
                ejecutarFetch(url,data);
            }
            
           
        }
        function ejecutarFetch(url,data){
            fetch(url, {
                method: 'POST', // or 'PUT'
                body: JSON.stringify(data), // data can be `string` or {object}!
                headers: {
                    'Content-Type': 'application/json'
                },
            })
            .then((res) => res.json())
            .catch((error) => console.error('Error:', error))
            .then(
                function(response) {
                       {$this->metodo}(response);
                });
        }
        </script>
        ";

        // Retornar el HTML generado
        echo $html;
    }
}
