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
    private $urlFilter;
    /**
     * @var int la url donde sera estara el resultado en html .
     */
    private $urlHtml;
    private $urlBase;

    /**
     * @var int metodo por el cual se enviara el resultado obtenido del fetch. este debe estar en la direccion:
     * /app-sintia/main-app/class/componentes/filter/
     */
    private $metodo;
    /**
     * @var string Son los filtros pasados por $_GET.
     */
    private $filtrosGet;
    public function __construct($id, $urlFilter, $urlHtml = '', $filtros = array(), $opciones = array(), $metodo = '')
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
        $this->urlFilter = 'filter/' . $urlFilter;
        $this->urlHtml =  'result/' . $urlHtml;
        $this->urlBase = $protocolo . $_SERVER['HTTP_HOST'] . '/app-sintia/main-app/class/componentes/barra-builder.php';
        $this->metodo = $metodo;
        $this->filtros = $filtros;
        $queryString = $_SERVER['QUERY_STRING']; // Parsear la cadena de consulta y almacenar los parámetros en un array
        parse_str($queryString, $parametros); // Convertir el array a JSON
        $filtrosGet = json_encode($parametros);
        $this->filtrosGet = $filtrosGet;
    }

    /**
     * Construye un array a partir de los resultados de una consulta SQL.
     *
     * @param mysqli_result $resultSql El resultado de la consulta SQL.
     * @return array El array construido a partir de los resultados de la consulta.
     */
    public function  builderArray($resultSql)
    {
        $index = 0;
        $arraysDatos = array();
        while ($fila = $resultSql->fetch_assoc()) {
            $arraysDatos[$index] = $fila;
            $index++;
        }
        $lista = $arraysDatos;
        $data["data"] = $lista;
        return $data;
    }

    public function generarComponente()
    {
        global $frases, $datosUsuarioActual, $Plataforma;
        // Generar HTML del componente
        $html = "
        <nav class='navbar navbar-expand-lg navbar-dark' style='background-color: #41c4c4;'>
            <ul class='navbar-nav mr-auto'>";
        if (!empty($this->opciones)) {
            foreach ($this->opciones as $opcion) {
                if ($opcion) {

                    if (!empty($opcion["paginas"])) {
                        $html .= "
                        <li class='nav-item dropdown'>
                        <a class='nav-link dropdown-toggle' href='javascript:void(0);' id='navbarDropdown' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' style='color:{$Plataforma->colorUno};'>
                            {$opcion["texto"]}
                            <span class='fa fa-angle-down'></span>
                        </a>";
                        $html .= "<div class='dropdown-menu' aria-labelledby='navbarDropdown'>";
                        foreach ($opcion["paginas"] as $pagina) {
                            $dataModal = !empty($pagina["data-target"]) ? "data-toggle='modal'  data-target='{$pagina["data-target"]}' " : "";
                            $action = !empty($pagina["action"]) ? "onClick='{$pagina["action"]}' " : "";
                            $permiso = !empty($pagina["permiso"]) ? $pagina["permiso"] : true;
                            if ($permiso) {
                                $html .= "<a class='dropdown-item' href='{$pagina["url"]}' {$dataModal} {$action} >{$pagina["texto"]}</a>";
                            }
                            $divider = !empty($pagina["divider"]) ? true : false;
                            if ($divider) {
                                $html .= "<div class='dropdown-divider'></div>  ";
                            }
                        }
                        $html .= "</div>";
                    } else {
                        $html .= "
                        <li class='nav-item'>
                        <a class='nav-link' href='{$opcion["url"]}' style='color:{$Plataforma->colorUno};'>
                        {$opcion["texto"]}
                        </a>";
                    }
                    $html .= "
                    </li>
                    ";
                }
            }
        }
        if (!empty($this->filtros) &&  !empty($this->opciones)) {
            $html .= " <li class='nav-item'> <a class='nav-link' href='javascript:void(0);' style='color:#FFF;'>|</a></li>";
        }
        if (!empty($this->filtros)) {

            foreach ($this->filtros as $filtro) {
                $html .= " <li class='nav-item dropdown'>
                <a class='nav-link dropdown-toggle' href='javascript:void(0);' id='navbarDropdown' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' style='color:#FFF;'>
                {$filtro["texto"]}  ";
                $decode = NULL;
                if (!empty($filtro["get"])) {
                    if (!empty($_GET[$filtro["get"]])) {
                        $decode = base64_decode($_GET[$filtro["get"]]);
                        $html .= ": {$decode} ";
                    }
                }

                $parametros = "";
                if (isset($_GET) ) {
                    foreach ($_GET as $key => $value) {
                        if ($key != $filtro["get"]) {
                            $parametros .= "&{$key}={$value}";
                        }    
                    }
                }

                $html .= "
					<span class='fa fa-angle-down'></span>
				</a>
                ";
                if (!empty($filtro["opciones"])) {
                    $html .= "<div class='dropdown-menu' aria-labelledby='navbarDropdown'>";
                    $html .= "<form class='px-2 py-2' id='filtroForm'>";
                    foreach ($filtro["opciones"] as $opcion) {
                        if(!empty($filtro["tipo"]) && $filtro["tipo"] == 'check'){
                            $html .= "<div class='form-check'>";
                            $html .= "<input class='form-check-input {$filtro["get"]}-checkbox' type='checkbox' name='{$filtro["get"]}[]' value='{$opcion["ID"]}' id='{$filtro["get"]}{$opcion["ID"]}'>";
                            $html .= "<label class='form-check-label' for='{$filtro["get"]}{$opcion["ID"]}'>{$opcion["texto"]}</label>";
                            $html .= "</div>";
                        }else{
                            $style = !empty($opcion["style"]) ? "style='{$opcion["style"]}'" : "";
                            $styleSelect = !empty($opcion["ID"]) && $opcion["ID"] == $decode ? "style='color: orange;'" : "";
                            $html .= "<a class='dropdown-item' href='{$opcion["url"]}{$parametros}'  {$style} {$styleSelect}>{$opcion["texto"]}</a>";
                        }
                    }
                    $html .= "</form>";
                    $html .= "</div>";
                }
                if (!empty($filtro["html"])) {
                    $html .= "<div class='dropdown-menu' aria-labelledby='navbarDropdown'>";
                    $html .= $filtro["html"];
                    $html .= "</div>";
                }

                $html .= "<script>
                                var {$filtro["get"]}Seleccionados = [];
                            </script>";

                if(!empty($filtro["tipo"]) && $filtro["tipo"] == 'check'){
                    $html .= "
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                // Obtener todos los checkboxes del filtro
                                var {$filtro["get"]}Checkboxes = document.querySelectorAll('.{$filtro["get"]}-checkbox');

                                // Escuchar el evento de cambio en los checkboxes
                                {$filtro["get"]}Checkboxes.forEach(function (checkbox) {
                                    checkbox.addEventListener('change', function () {
                                        // Obtener los valores seleccionados
                                            {$filtro["get"]}Seleccionados = Array.from({$filtro["get"]}Checkboxes)
                                            .filter(function (checkbox) {
                                                return checkbox.checked;
                                            })
                                            .map(function (checkbox) {
                                                return checkbox.value;
                                            });

                                        // Realizar la acción de filtrado con los checkboxes seleccionados
                                        filtrarPor{$filtro["get"]}({$filtro["get"]}Seleccionados);
                                    });
                                });

                                function filtrarPor{$filtro["get"]}({$filtro["get"]}Seleccionados) {
                                    console.log('Checkboxes seleccionados:', {$filtro["get"]}Seleccionados);
                                    {$this->id}_buscar();
                                }
                            });
                        </script>
                    ";
                }

                $html .= "</li>";
            }
        }
        if (!empty($_GET)) {
            $html .= "<li class='nav-item'> <a class='nav-link' href='javascript:void(0);' style='color:{$Plataforma->colorUno}'>|</a></li>
  
            <li class='nav-item'> <a class='nav-link' href='{$_SERVER['PHP_SELF']}' style='color:{$Plataforma->colorUno}'>Quitar filtros</a></li>";
        }

        $html .= "   </ul> 
            <div class='form-inline my-2 my-lg-0'>
                <input id='input_{$this->id}' class='form-control mr-sm-2' type='search' placeholder='{$frases[386][$datosUsuarioActual['uss_idioma']]}..' aria-label='Search' name='busqueda' >
                <button id='btn_{$this->id}' onclick='{$this->id}_buscar(true)' class='btn deepPink-bgcolor my-2 my-sm-0' type='buttom'>{$frases[8][$datosUsuarioActual['uss_idioma']]}</button>
            </div>
        </nav>
        <script type='text/javascript'>
        input_{$this->id}.addEventListener('keyup', function(event) {
            {$this->id}_buscar()
          });
        function {$this->id}_buscar(buscar){
            var valor = document.getElementById('input_{$this->id}').value;
            var filtro2 = {'{$filtro["get"]}Seleccionados' : {$filtro["get"]}Seleccionados};
            var urlbase = '{$this->urlBase}'; 
            if(valor.length > 2){
                var data = {
                    'valor': (valor),
                    'filtro2': (filtro2),
                    'url': '{$this->urlFilter}'
                };
                data.filtros =JSON.parse('{$this->filtrosGet}');
                {$this->id}_ejecutarFetch(urlbase,data);
            }else if(valor.length == 0){
                var data = {
                    'valor': (valor),
                    'filtro2': (filtro2),
                    'url': '{$this->urlFilter}'
                };
                data.filtros =JSON.parse('{$this->filtrosGet}');
                {$this->id}_ejecutarFetch(urlbase,data);
            }else if(buscar){
                var data = {
                    'valor': (valor),
                    'filtro2': (filtro2),
                    'url': '{$this->urlFilter}'
                };
                data.filtros =JSON.parse('{$this->filtrosGet}');
                {$this->id}_ejecutarFetch(urlbase,data);

            }
            
           
        }
        function {$this->id}_ejecutarFetch(url,data){
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
                    {$this->id}_responseHtml(response);
                });
        }
        function {$this->id}_responseHtml(dato){
            var tbody = document.getElementById('{$this->id}_result');
            document.getElementById('gifCarga').style.display = 'block';
            tbody.innerHTML = ''; 
            var data = {
                'data': (dato),
                'url': '{$this->urlHtml}'
            };
            data.filtros =JSON.parse('{$this->filtrosGet}');
            var url = '{$this->urlBase}'; 
            fetch(url, {
                method: 'POST', // or 'PUT'
                body: JSON.stringify(data), // data can be `string` or {object}!
                headers: {
                    'Content-Type': 'text/html'
                },
            })
            .then((res) => res.text()).catch((error) => console.error('Error:', error))
            .catch((error) => console.error('Error:', error))
            .then(
                function(response) {
                       tbody.innerHTML = response;
                       document.getElementById('gifCarga').style.display = 'none';";

        if (!empty($this->metodo)) {
            $html .= "{$this->metodo}(response)";
        }
        $html .= "});
        }
        </script>
        ";

        // Retornar el HTML generado
        echo $html;
    }
}
