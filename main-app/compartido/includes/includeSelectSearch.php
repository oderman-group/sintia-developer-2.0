<?php
class includeSelectSearch
{
    private $id;
    private $texto;
    private $url;
    private $metodo;


    public function __construct($id, $url, $texto = "Seleccione", $metodo = null)
    {
        $this->id = $id;
        $this->url = $url;
        $this->texto = $texto;
        $this->metodo = $metodo;
    }

    private $accion;


    public function generarComponente()
    {
        if (empty($this->id) || empty($this->texto)) {
            echo "<p>Parametros incompletos</p>";
        } else {
            $mostrarBoton = 'false';
            if (empty($this->metodo)) {
                $mostrarBoton = 'false';
            } else {
                $mostrarBoton = 'true';
            }
            $select =
                "<div class='input-group'>
        <select id='{$this->id}' class='form-control select2-multiple' name='{$this->id}' onchange='selecionar_{$this->id}(this.value)' style='width: 80% !important' >
            <option value=''>Seleccione un estudiante</option>
        </select>
        <div class='input-group-append'>
            <button style='display: none;margin-right:0px' id='btnEliminar_{$this->id}' type='button' class='input-group-text btn btn-danger btn-sm' onclick='limpiar_{$this->id}()'><i class='fa fa-trash'></i></button>
            <button  style='display: none;' id='btnAgregar_{$this->id}' type='button' class='input-group-text btn btn-info btn-sm' onclick='mostrar_{$this->id}()'><i class='fa fa-add'></i></button>
          
        </div>
    </div>
    <script type='text/javascript'>

    function mostrar_{$this->id}() {
        var seleccion = $('#{$this->id}').select2('data')[0];
        if (seleccion) {
            {$this->metodo}(seleccion);
            limpiar_{$this->id}();
        }
    }

    function selecionar_{$this->id}(data) {
        const btnEliminar = document.getElementById('btnEliminar_{$this->id}');
        btnEliminar.style.display = 'block';
        
        if({$mostrarBoton}){
            const btnAgregar = document.getElementById('btnAgregar_{$this->id}');
            btnAgregar.style.display = 'block';
        }
    };

    function limpiar_{$this->id}() {
        $('#{$this->id}').val(null).trigger('change');
        const btnEliminar = document.getElementById('btnEliminar_{$this->id}');
        btnEliminar.style.display = 'none';

        if({$mostrarBoton}){
            const btnAgregar = document.getElementById('btnAgregar_{$this->id}');
            btnAgregar.style.display = 'none';
        }
    };

    $(document).ready(function() {
        $('#{$this->id}').select2({
            placeholder: '{$this->texto}',
            theme: 'bootstrap',
            multiple: false,
            ajax: {
                type: 'GET',
                url: '{$this->url}',
                processResults: function(data) {
                    data = JSON.parse(data);
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.value,
                                text: item.label,
                                title: item.title
                            }
                        })
                    };
                }
            }
        });
    });
    </script>
    ";

            echo $select;
        }
    }
}
