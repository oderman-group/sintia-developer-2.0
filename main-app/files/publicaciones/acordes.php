<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
</head>

<body>
	<?php
	if(isset($_POST["enviar"])){
		$verso = trim($_POST["verso"]);
		$longitud = strlen($verso);
		$palabras = str_word_count($verso,0);
		$division = explode(" ", $verso);
		$logArray = count($division);
		$logArray = $logArray - 1;
		$i=0;
		while($i<=$logArray){
		?>
			<input name="acorde" style="width: 45px; text-align: center;">
			<input name="acorde" style="width: 45px; text-align: center;">
			<input name="acorde" style="width: 45px; text-align: center;">
			-
		<?php
			$i++;	
		}
		echo "<br><br>";
		$i=0;
		while($i<=$logArray){
		?>
			<input name="verso" value="<?=$division[$i];?>" style="width: 150px; text-align: center; text-transform: uppercase;" readonly> -
		<?php
			$i++;	
		}
	}
	?>
	<p>&nbsp;</p>
	<form action="acordes.php" method="post">
		<textarea name="verso" cols="100" rows="20" style="width: 300px; text-transform: uppercase;"><?=$_POST["verso"];?></textarea><br>
		<input type="submit" name="enviar" value="Enviar">
	</form>
</body>
</html>