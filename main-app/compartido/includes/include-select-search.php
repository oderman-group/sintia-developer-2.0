<?php
class Select_component {
    // Propiedades (o atributos) de la clase
    public $id;
    public $texto;

    // Método constructor
    public function __construct($nombre, $edad) {
        $this->nombre = $nombre;
        $this->edad = $edad;
    }

    // Método de la clase
    public function saludar() {
        echo "Hola, mi nombre es " . $this->nombre . " y tengo " . $this->edad . " años.";
    }
}
if(!isset($selectId)){
	echo " Nose a definido identificacion Aun";
	exit();
}
$selectTexto = !isset($selectTexto) ? "Seleccione.." : $selectTexto;
$selectUrl = !isset($selectUrl) ? "url.php" : $selectUrl;
$selectAccion = !isset($selectAccion) ? "" : $selectAccion;
$mostarDato = !isset($selectAccion) ? false : true;

?>
<div class="input-group">
	<select id="<?= $selectId ?>" name="<?= $selectId ?>" class="form-control select2-multiple" onchange="selecionar_<?= $selectId ?>(this.value)" style="width: 80% !important">
		<option value="">Seleccione</option>
	</select>
	<div class="input-group-append">
		<!-- <div class="btn-group" role="group" > -->
		<button style="display: none;" id="btnEliminar<?= $selectId ?>" type="button" class="input-group-text btn btn-danger btn-sm" onclick="deselecionar_<?= $selectId ?>()"><i class="fa fa-trash"></i></button>
		<button <?= $mostarDato ? "" : "hiddend" ?> style="display: none;" id="btnAgregar<?= $selectId ?>" type="button" class="input-group-text btn btn-info btn-sm" onclick="mostrarDatos_<?= $selectId ?>()"><i class="fa fa-add"></i></button>
		<!-- </div> -->
	</div>
</div>
<script type="text/javascript">
	texto = '<?php echo $selectTexto ?>';
	url = '<?php echo $selectUrl ?>';
	acccion = '<?php echo $selectAccion ?>';
	mostarDato = '<?php echo $mostarDato ?>';

	function mostrarDatos_<?php echo $selectId ?>() {
		var seleccion = $('#<?php echo $selectId ?>').select2('data')[0];
		if (seleccion) {
			<?php echo $selectAccion ?>(seleccion);
		}

	};

	function selecionar_<?php echo $selectId ?>(data) {
		const btnEliminar = document.getElementById("btnEliminar<?php echo $selectId ?>");
		btnEliminar.style.display = "block";
		if (mostarDato) {
			const btnAgregar = document.getElementById("btnAgregar<?php echo $selectId ?>");
			btnAgregar.style.display = "block";
		}
	};

	function deselecionar_<?php echo $selectId ?>() {
		$('#<?php echo $selectId ?>').val(null).trigger('change');
		const btnEliminar = document.getElementById("btnEliminar<?php echo $selectId ?>");
		const btnAgregar = document.getElementById("btnAgregar<?php echo $selectId ?>");
		btnEliminar.style.display = "none";
		btnAgregar.style.display = "none";
	};

	$(document).ready(function() {
		$('#<?php echo $selectId ?>').select2({
			placeholder: texto,
			theme: "bootstrap",
			multiple: false,
			ajax: {
				type: 'GET',
				url: url,
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