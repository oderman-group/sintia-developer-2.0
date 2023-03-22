<head>
	<link href="../../config-general/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
</head>

<div class="container" style="margin-top:10px !important;">
	<div class="row">
		<div class="col-sm-2"></div>
		<div class="col-sm-2"> <img class="img-thumbnail" src="../files/images/logo/<?= $informacion_inst["info_logo"] ?>" height="200" width="200"><br><br></div>
		<div class="col-sm-3" style="padding:0px">
			<div class="col-sm-12"> <b><?= $informacion_inst["info_nombre"] ?></b></div>
			<div class="col-sm-12"><b>NIT: </b><?= $informacion_inst["info_nit"] ?></div>
			<div class="col-sm-12"><b>DIR: </b><?= $informacion_inst["info_direccion"] ?></div>
			<div class="col-sm-12"><b>TEL: </b><?= $informacion_inst["info_telefono"] ?></div>
		</div>
		<div class="col-sm-4">
			<div class="col-sm-12" style="margin:10px;"></div>
			<div class="col-sm-12" align="center" style="margin-bottom:20px;font-weight:bold;color:<?=$Plataforma->colorUno;?>"><b><?= $nombre_informe ?></br></div>
			<div class="col-sm-12" style="margin:10px;"></div>
		</div>
		<div class="col-sm-2"></div>

	</div>
</div>