					<div class="row">
                        <div class="col-md-12">
                            <div class="row">
								<?php
								$resumen = mysql_fetch_array(mysql_query("SELECT
								(SELECT sum(fcu_valor) FROM finanzas_cuentas WHERE fcu_usuario='".$_SESSION["id"]."' AND fcu_anulado=0 AND fcu_tipo=1),
								(SELECT sum(fcu_valor) FROM finanzas_cuentas WHERE fcu_usuario='".$_SESSION["id"]."' AND fcu_anulado=0 AND fcu_tipo=2),
								(SELECT sum(fcu_valor) FROM finanzas_cuentas WHERE fcu_usuario='".$_SESSION["id"]."' AND fcu_anulado=0 AND fcu_tipo=3),
								(SELECT sum(fcu_valor) FROM finanzas_cuentas WHERE fcu_usuario='".$_SESSION["id"]."' AND fcu_anulado=0 AND fcu_tipo=4)
								",$conexion));
								$saldo = ($resumen[0] - $resumen[2]);
								$mensajeSaldo='Tienes las cuentas al día. Excelente!';
								if($saldo>0){$mensajeSaldo='Tienes saldo a favor.';}
								if($saldo<0){$mensajeSaldo='Tienes un saldo pendiente por pagar. Trata de ponerte al día lo antes posible.';}
								?>
								<div class="col-md-4 col-lg-3">
									
									<div class="panel">
										<header class="panel-heading panel-heading-purple">Resumen </header>
                                        <div class="panel-body">
											<p><b>COBROS:</b> $<?=number_format($resumen[2],0,",",".");?></p>
											<p><b>PAGOS:</b> $<?=number_format($resumen[0],0,",",".");?></p>
											<hr>
											<p><b>SALDO:</b> $<?=number_format($saldo,0,",",".");?></p>
											<p style="color: blueviolet;"><?=$mensajeSaldo;?></p>
										</div>
									</div>

									<div align="center">
										<p><mark>Recuerde que su código para hacer el pago es el siguiente: <b><?=$datosEstudianteActual['mat_codigo_tesoreria'];?></b></mark></p>

										<p><a href="https://www.pagosvirtualesavvillas.com.co/personal/pagos/22" class="btn btn-info" target="_blank">LINK DE PAGO</a></p>

										<p><a href="http://sion.icolven.edu.co/Services/ServiceIcolven.svc/GenerarEstadoCuenta/<?=$datosEstudianteActual['mat_codigo_tesoreria'];?>/<?=date('Y');?>" class="btn btn-success" target="_blank">ESTADO DE CUENTA</a></p>
									</div>

								</div>
									
								<div class="col-md-4 col-lg-6">
                                    <div class="card card-topline-purple">
                                        <div class="card-head">
                                            <header><?=$frases[104][$datosUsuarioActual['uss_idioma']];?></header>
                                            <div class="tools">
                                                <a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>
			                                    <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
			                                    <a class="t-close btn-color fa fa-times" href="javascript:;"></a>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                        <div class="table-scrollable">
                                    		<table id="example1" class="display" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
														<th><?=$frases[51][$datosUsuarioActual[8]];?></th>
														<th><?=$frases[162][$datosUsuarioActual[8]];?></th>
														<th>Tipo</th>
														<th><?=$frases[52][$datosUsuarioActual[8]];?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
													<?php
													 $tiposArray = array("","ABONO","PAGO REALIZADO A TI","COBRO","POR PAGARTE");
													 $consulta = mysql_query("SELECT * FROM finanzas_cuentas 
													 WHERE fcu_usuario='".$_SESSION["id"]."' AND fcu_anulado=0",$conexion);
													 $contReg = 1;
													 while($resultado = mysql_fetch_array($consulta)){
														 $colorValor = 'black';
														 if($resultado['fcu_tipo']==3) $colorValor = 'red';
													 ?>
													<tr>
                                                        <td><?=$contReg;?></td>
														<td><?=$resultado['fcu_fecha'];?></td>
														<td><?=$resultado['fcu_detalle'];?></td>
														<td><?=$tiposArray[$resultado['fcu_tipo']];?></td>
														<td style="color:<?=$colorValor;?>;">$<?=number_format($resultado['fcu_valor'],0,",",".");?></td>
                                                    </tr>
													<?php 
														 $contReg++;
													  }
													  ?>
                                                </tbody>
                                            </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								
								<div class="col-md-4 col-lg-3">
									<?php include("../compartido/publicidad-lateral.php");?>
								</div>
								
							
                            </div>
                        </div>
                    </div>