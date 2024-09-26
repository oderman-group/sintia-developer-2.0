<table width="100%" cellspacing="0" cellpadding="0" rules="none" border="0" style="text-align:center; font-size:10px;">
	<tr>
		<td align="center" width="50%">
			<?php
			$nombreDirectorGrupo = UsuariosPadre::nombreCompletoDelUsuario($directorGrupo);
			if (!empty($directorGrupo["uss_firma"])) {
				echo '<img src="../files/fotos/' . $directorGrupo["uss_firma"] . '" width="15%"><br>';
			} else {
				echo '<p>&nbsp;</p>
                                            <p>&nbsp;</p>
                                            <p>&nbsp;</p>';
			}
			?>
			_________________________________<br>
			<p>&nbsp;</p>
			<?= $nombreDirectorGrupo ?><br>
			Director(a) de grupo
		</td>
		<td align="center" width="50%">
			<?php			
			$nombreRector = UsuariosPadre::nombreCompletoDelUsuario($rector);
			if (!empty($rector["uss_firma"])) {
				echo '<img src="../files/fotos/' . $rector["uss_firma"] . '" width="25%"><br>';
			} else {
				echo '<p>&nbsp;</p>
                      <p>&nbsp;</p>
                      <p>&nbsp;</p>';
			}
			?>
			_________________________________<br>
			<p>&nbsp;</p>
			<?= $nombreRector ?><br>
			Rector(a)
		</td>
	</tr>
</table>