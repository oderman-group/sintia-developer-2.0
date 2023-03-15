<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #41c4c4;">
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="navbar-collapse" id="navbarSupportedContent">
						<ul class="navbar-nav mr-auto">
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#FFF;">
								    Menú movimiento financiero
									<span class="fa fa-angle-down"></span>
								</a>
								<div class="dropdown-menu" aria-labelledby="navbarDropdown">
									<a class="dropdown-item" href="movimientos-importar.php">Importar saldos</a>
								</div>
							</li>
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#FFF;">
								    Resúmen
									<span class="fa fa-angle-down"></span>
								</a>
								<div class="dropdown-menu" aria-labelledby="navbarDropdown">	
									<a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>?usuario=<?=$_GET["usuario"];?>&tipo=1">Ingresos</a>
									<a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>?usuario=<?=$_GET["usuario"];?>&tipo=2">Egresos</a>
									<a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>">Ver todos</a>
          
								</div>
							</li>
						</ul> 
					</div>
				</nav>