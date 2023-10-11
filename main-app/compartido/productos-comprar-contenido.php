<?php
$id = '';
if (!empty($_GET["id"])) {
    $id = base64_decode($_GET["id"]);
}

$producto = mysqli_fetch_array(mysqli_query($conexion, "SELECT * FROM ".$baseDatosMarketPlace.".productos
INNER JOIN ".$baseDatosMarketPlace.".categorias_productos ON catp_id=prod_categoria
INNER JOIN ".$baseDatosMarketPlace.".empresas ON emp_id=prod_empresa
WHERE prod_id='".$id."'
"), MYSQLI_BOTH);
?>
<div class="row">
                        <div class="col-sm-9">
                            <div class="card card-box">
                                <div class="card-head">
                                    <header>Confirme los datos</header>
                                </div>
                                <div class="card-body " id="bar-parent6">
                                    <form class="form-horizontal" id="formularioCompraMP" action="../pagos-online/index.php" method="post" enctype="multipart/form-data" target="_target">
										<input type="hidden" name="monto" value="<?=$producto['prod_precio'];?>">
                                        <input type="hidden" name="idProducto" value="<?=$producto['prod_id'];?>">
                                        <input type="hidden" name="idUsuario" value="<?=$datosUsuarioActual['uss_id'];?>">
                                        <input type="hidden" name="idInstitucion" value="<?=$config['conf_id_institucion'];?>">
                                        
										<div class="form-group row">
                                            <label class="col-sm-2 control-label">Producto</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="nombre" class="form-control" value="<?=$producto['prod_nombre'];?>" readonly>
                                            </div>
                                        </div>
										
										<div class="form-group row">
                                            <label class="col-sm-2 control-label">Descripción</label>
                                            <div class="col-sm-10">
                                                <textarea id="editor1" class="form-control" rows="5" style="margin-top: 0px; margin-bottom: 0px; height: 100px; resize: none;" readonly><?=$producto['prod_descripcion'];?></textarea>
                                            </div>
                                        </div>
										
										<div class="form-group row">
                                            <label class="col-sm-2 control-label">Precio</label>
                                            <div class="col-sm-4">
                                                <input class="form-control" value="<?=number_format($producto['prod_precio'],0,".",".");?>" readonly>
                                            </div>
                                        </div>
                                        <input type="hidden" value="<?=$producto['prod_precio'];?>" id="precio">

                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label">Stock</label>
                                            <div class="col-sm-2">
                                                <input class="form-control" type="text" value="<?=$producto['prod_existencias'];?>" name="stock" id="stock" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label">Cantidad</label>
                                            <div class="col-sm-2">
                                                <input class="form-control" type="number" value="1" name="cantidad" id="cantidad" required onChange="calcularTotal()">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label">Total</label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="total" id="total" readonly>
                                            </div>
                                        </div>

										<div class="form-group row">
                                            <label class="col-sm-2 control-label">Documento de quien recibe (*)</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="documentoUsuario" class="form-control" value="<?=$datosUsuarioActual['uss_documento'];?>" required>
                                            </div>
                                        </div>
										
										<div class="form-group row">
                                            <label class="col-sm-2 control-label">Nombre de quien recibe (*)</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="nombreUsuario" class="form-control" value="<?=UsuariosPadre::nombreCompletoDelUsuario($datosUsuarioActual);?>" required>
                                            </div>
                                        </div>
										
										<div class="form-group row">
                                            <label class="col-sm-2 control-label">Email (*)</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="emailUsuario" class="form-control" value="<?=$datosUsuarioActual['uss_email'];?>" required>
                                            </div>
                                        </div>
										
										<div class="form-group row">
                                            <label class="col-sm-2 control-label">Teléfono (*)</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="celularUsuario" class="form-control" value="<?=$datosUsuarioActual['uss_celular'];?>" required>
                                            </div>
                                        </div>
										
										<div class="form-group row">
                                            <label class="col-sm-2 control-label">Dirección de envío (*)</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="direccion" class="form-control" value="<?=$datosUsuarioActual['uss_direccion'];?>" required>
                                            </div>
                                        </div>
										
										<p align="right" style="color: navy;">
											<b>SU COMPRA ESTÁ PROTEGIDA POR SINTIA MARKETPLACE</b><br>
											Puede comprar tranquilo. Reciba lo que esperaba o le devolvemos su dinero.
										</p>
										
										<div align="right">
											<!-- <input type="submit" class="btn btn-primary" value="Continuar al pago">&nbsp; -->
                                            <button type="button" onClick="enviarFormulario()" id="continuarPago" class="btn btn-primary"><i class="fa fa-credit-card" aria-hidden="true"></i>Continuar al pago</button>
										</div>
										

                                    </form>
                                </div>
                            </div>
                        </div>
						
                        <div class="col-sm-3">
                            <?php include("../compartido/publicidad-lateral.php");?>
                        </div>
						
                    </div>
                    <script src="../ckeditor/ckeditor.js"></script>

                    <script>
                        // Replace the <textarea id="editor1"> with a CKEditor 4
                        // instance, using default configuration.
                        CKEDITOR.replace( 'editor1' );
                        /**
                         * Esta función calcula el precio total a pagar al momento
                         * de comprar un producto en merkaplace
                         */
                        function calcularTotal() {

                            var precio        = document.getElementById("precio").value;
                            var total         = document.getElementById("total");
                            var cantidad      = document.getElementById("cantidad");
                            var stock         = document.getElementById("stock");
                            var continuarPago = document.getElementById("continuarPago");

                            if(parseInt(stock.value) <= 0) {
                                continuarPago.disabled = true;
                                cantidad.value = 0;
                                Swal.fire('Este producto no tiene stock');

                                const formulario = document.getElementById('formularioCompraMP');
                                const inputs = formulario.getElementsByTagName('input');
                                for (let i = 0; i < inputs.length; i++) {
                                    inputs[i].disabled = true;
                                }

                                return false;
                            }
                            
                            minimoUno(cantidad);

                            if(parseInt(cantidad.value) > parseInt(stock.value)) {
                                Swal.fire('El stock de este producto es de '+stock.value);
                                cantidad.value = stock.value;
                            }
                            
                            var calculo = parseInt(precio) * parseInt(cantidad.value);

                            continuarPago.disabled = false;
                            if(calculo <= 0 || isNaN(calculo)) {
                                Swal.fire('AVISPATE!', 'La cantidad minima para comprar debe ser 1', 'info');
                                continuarPago.disabled = true;
                                total.value = 0;
                                return false;
                            }

                            
                            const formatoDinero = calculo.toLocaleString('es-ES', { style: 'currency', currency: 'COP' });
                            
                            total.value = formatoDinero;

                            return parseInt(calculo);

                        }

                        document.addEventListener("DOMContentLoaded", function() {
                            calcularTotal();
                        });

                        function enviarFormulario() {
                            var calculo = calcularTotal();
                            // Obtén una referencia al formulario por su ID
                            const formularioEnviar = document.getElementById('formularioCompraMP');

                            var valorMinimo = 10000;
                            if( parseInt(calculo) > 0 && parseInt(calculo) < parseInt(valorMinimo) ) {
                                Swal.fire({
                                    title: 'El valor minimo para comprar debe ser de $10.000 COP.',
                                    backdrop: `
                                        rgba(220,173,2,0.4)
                                        url("https://media.giphy.com/media/LdOyjZ7io5Msw/giphy.gif")
                                        left top
                                        no-repeat
                                    `
                                });
                                continuarPago.disabled = true;
                                return false;
                            }

                            // Llama al método submit() del formulario para enviarlo
                            formularioEnviar.submit();
                        }
                    </script>