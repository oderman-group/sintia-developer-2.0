<?php

class ComponenteModal
{
	private $urlHtml;
	private $id;
	private $titulo;
	private $data;
	private $timeOut;
	private $width;
	private $z_index;
	private $estatico;
	public function __construct($id, $titulo, $urlHtml, $data = null, $timeOut = null, $width = '1350px',$estatico =false)
	{
		$this->id = $id;
		$this->titulo = $titulo;
		$this->urlHtml = $urlHtml;
		$this->data = $data;
		$this->timeOut = $timeOut;
		$this->width = $width;
		$this->estatico = $estatico;

		self::crearfuncion();
		self::generarComponente();

	}
	public function generarComponente()
	{
     ?>
		<div class="modal fade" id="ComponeteModal-<?=$this->id?>" tabindex="-1" role="dialog" aria-labelledby="basicModal" <?=$this->estatico?'data-backdrop="static"':'' ?> style="z-index: <?=$this->z_index?> !important;" aria-hidden="true">
			<div class="modal-dialog" style="max-width: 1350px!important;">
				<div class="modal-content" style="border-radius: 20px;max-width: 1350px!important; ">

					<div class="modal-header panel-heading-purple">
						<h4 class="modal-title " id="ComponeteModalTitulo-<?=$this->id?>"><?=$this->titulo?></h4>
						<a href="#" data-dismiss="modal" data-bs-dismiss="modal" class="btn btn-danger" aria-label="Close" id="boton-cerrar-compra-modulo"><i class="fa fa-window-close"></i></a>
					</div>

					<div class="modal-body">
						<div id="ComponeteModalContenido-<?=$this->id?>"></div>
					</div>

				</div>
			</div>
		</div>
<?php
	}
	public static function ejecutar($script)
    {
        echo "<script type='text/javascript'>{$script}</script>";
    }
	public static function mostrarAlerta($mensaje)
    {
        self::ejecutar("alert('{$mensaje}');");
    }

	public  function crearfuncion()
    {

	   $id          = $this->id; 
       $urlHtml     = $this->urlHtml; 
       $data        = empty($this->data) ? 'null'   : json_encode($this->data); 
	   $width       = empty($this->data) ? '1350px' : $this->width;
	   $AutoCerrado = empty($this->timeOut) ? 'false' : 'true';
	   $timeOut     = empty($this->timeOut) ? ''    : $this->width;

        echo"<script type='text/javascript'> 
		async function abrirModal_$id() {
		const contenido = document.getElementById('ComponeteModalContenido-$id');
		var gifCarga = document.getElementById('gifCarga');
		if (gifCarga) {
			document.getElementById('gifCarga').style.display = 'block';
		}
		contenido.innerHTML = '';
		resultado = await metodoFetchAsync('$urlHtml', $data , 'html', false);
		resultData = resultado['data'];

        contenido.innerHTML = resultData;
		ejecutarScriptsCargados(contenido);

		
		
		$('#ComponeteModal-$id .modal-dialog').css('width','$width');

		if (gifCarga) {
			document.getElementById('gifCarga').style.display = 'none';
		}

		$('#ComponeteModal-$id').modal('show');

		if ($AutoCerrado) {
			setTimeout(function() {
				$('#ModalCentralizado').modal('hide'); // Cierra el modal
			}, $timeOut);
		}
		}
		</script>";
    }
	
	public  function getMetodoAbrirModal(): string
    {
		return "abrirModal_$this->id()";
    }
}
?>
<script type="application/javascript">
	

	//ejecutar los scripts del string
	function ejecutarScriptsCargados(elemento) {
		var scripts = elemento.getElementsByTagName('script');

		for (var i = 0; i < scripts.length; i++) {
			var script = document.createElement('script');
			// Si el script tiene un atributo src, cargamos el script externo
			if (scripts[i].src) {
				script.src = scripts[i].src;
				script.async = false; // Esto asegura que los scripts se ejecuten en el orden correcto
			} else {
				script.textContent = scripts[i].textContent;
			}

			// Agregamos el script al <head> para que se ejecute
			document.head.appendChild(script);
		}
	}
</script>