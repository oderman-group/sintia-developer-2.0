<?php
function validarPaginaActual(array $paginas = []): bool
{
	global $idPaginaInterna;
	foreach ($paginas as $idPagina) {
		if ($idPaginaInterna === $idPagina) {
			return true;
		}
	}
	return false;
};
function agregarClass(string $tipoMenu, array $paginas = [])
{
	switch ($tipoMenu) {
		case MENU:
			if (validarPaginaActual($paginas)) {
				echo 'class="active nav-item"';
			}
			break;
		case MENU_PADRE:
			if (validarPaginaActual($paginas)) {
				echo 'class="nav-item open"';
			} else {
				echo 'class="nav-item"';
			}
			break;
		case SUB_MENU:
			if (validarPaginaActual($paginas)) {
				echo 'style="display: block;"';
			}
			break;
	}
};
?>
