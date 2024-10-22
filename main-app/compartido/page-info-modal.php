<?php if (empty($_SESSION["id"])) {
	include("session-compartida.php");
	$input = json_decode(file_get_contents("php://input"), true);
	if (!empty($input)) {
		$_GET = $input;
	}
}
include("../../config-general/page-info-contenido.php");
?>
<div class="panel">
	<header class="panel-heading panel-heading-<?= $color; ?>"><?= $titulo; ?></header>
	<div class="panel-body">
		<p><?= $texto; ?></p>
		<?php if (!empty($lottie)) { ?>
			<p>
				<lottie-player src="<?= $lottie; ?>" background="transparent"
					speed="1" style="width: 500px; height: 500px;" loop autoplay></lottie-player>
			</p>
		<?php } ?>
	</div>
</div>
<!-- Core theme JS-->
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>