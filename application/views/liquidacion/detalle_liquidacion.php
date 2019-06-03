<?php
/**
 * Created by Cesar Mejía.
 * User: Sistemas
 * Date: 17/2/2019 11:53 2019
 * FileName: liquidacion.php
 */
?>
<div class="row">
	<div class="col-sm-3 col-md-3 col-lg-3">
		<a  href="javascript:history.back()" class="btn btn-sm btn-primary">
			<i class="fa fa-arrow-left"></i> Volver
		</a>
	</div>
</div>
<br>
<section class="panel" id="printHTML">
	<div class="panel-body">
		<div class="invoice">
			<header class="clearfix">
				<div class="row">
					<div class="col-sm-6 mt-md">
						<h2 class="h2 mt-none mb-sm text-dark text-bold">LIQUIDACION</h2>
						<?php
						$fecha1 = '';$fecha2 = '';
						if(!$liq){
						}
						else{
							foreach ($liq as $key)
							{
								echo "				
									   <p class='text-dark text-semibold'>
									     Desde : ".date_format(new DateTime($key["FechaInicio"]),"Y-m-d H:i:s")." 
									   </p>
									   <p class='text-dark text-semibold'>
									     Hasta: ".date_format(new DateTime($key["FechaFinal"]), "Y-m-d H:i:s")."
									    </p>
									 ";
								if($key["Liquidado"] == "N"){
									echo'
										<p>
										    <i>No es posible imprimir este documento porque esta
											pendiente de liquidar</i>
										</p>
										';
								}
							}
						}
						?>
					</div>
					<div class="col-sm-6 text-right mt-md mb-md">
						<address class="ib mr-xlg">
							<?php
							echo '
									<p class="text-dark">Datos Liquidador</p>
									 '.$this->session->userdata('Name')." ". $this->session->userdata('Apelli').'
									<br/>
									 '.$this->session->userdata('Tel').'
									 <br/>
									 '.$this->session->userdata('Tel2').'
									<br/>
									 '.$this->session->userdata('mail').'';
							?>
						</address>
						<div class="ib">
							<img width="80px" src="<?php echo base_url()?>assets/img/LOGO.png" alt="dd" />
						</div>
					</div>
				</div>
			</header>
			<div class="bill-info">
				<div class="row">
					<div class="col-md-6">
						<div class="bill-to">
							<?php
							$ruta = ''; $vendedor = '';
							if(!$det){
							}else{
								foreach ($det as $item) {
									$ruta = $item["CODVENDEDOR"];
									$vendedor = $item["NOMBREVENDEDOR"];
								}
								echo '
										<span style="display:none;" id="idperiodo">'.$item["IDPERIODO"].'</span>
										<p class="h5 mb-xs text-dark text-semibold">Datos vendedor</p>
										<address>
										<p class="mb-none">
											<span class="text-dark">Ruta:</span>
											<span class="value" id="ruta">'.$ruta.'</span>
										</p>
										<p class="mb-none">
											<span class="text-dark">Nombre:</span>
											<span class="value">'.$vendedor.'</span>
										</p>
										<p class="mb-none">
											<span class="text-dark">Tipo Venta:</span>
											<span class="value" id="tipo">Venta</span>
										</p>
										</address>
									';
							}
							?>
						</div>
					</div>
					<div class="col-md-6">
						<div class="bill-data text-right">
							<p class="mb-none">
								<span class="text-dark text-semibold">Nota:</span>
								<span class="value"></span>
							</p>

							<?php
							if(!$liq){
							}else{
								foreach($liq as $key){
									if($key["Liquidado"] == "N"){
										echo '
												<p class="mb-none">
													<span class="text-danger text-semibold">Pendiente liquidar</span>
													<span class="value"></span>
												</p>
											';
									}else{
										echo '
												<p class="mb-none">
													<span class="text-primary text-semibold">Liquidado</span>
													<span class="value"></span>
												</p>
											';
									}
								}
							}
							?>

							<p class="mb-none">
								<span class="text-dark">Vista Previa</span>
								<span class="value"></span>
							</p>
						</div>
					</div>
				</div>
			</div>

			<div class="tabs tabs-danger">
				<ul class="nav nav-tabs tabs-primary">
					<li class="active">
						<a class="text-muted" href="#general" data-toggle="tab" aria-expanded="true">General</a>
					</li>
					<li class="">
						<a class="text-muted" href="#unidad" data-toggle="tab" aria-expanded="true">Por unidad</a>
					</li>
				</ul>
				<div class="tab-content">
					<div id="general" class="tab-pane active">
						<div class="">
							<table class="table table-condensed table-bordered table-striped" id="tblDetFactLiq">
								<thead>
								<tr class="text-dark">
									<th>Codigo</th>
									<th>Descripcion</th>
									<th>Peso <br> Gramos</th>
									<th>Precio</th>
									<th>Remision</th>
									<th>Devolucion</th>
									<th>UVendCred</th>
									<th>UVendCont</th>
									<th>Unid <br> Total</th>
									<th>SubTotal</th>
									<th>subCred</th>
									<th>Dto</th>
									<th>Dtcred</th>
									<th>ISC</th>
									<th>ISCCred</th>
									<th>IVA</th>
									<th>IVACred</th>
									<th>TotContado</th>
									<th>TotCredito</th>
									<th>Libras <br> Vendidas</th>
									<th>Merma</th>
									<th>% Merma</th>
								</tr>
								</thead>
								<tbody>
								<?php
								$devolucion = 0;
								$acumulado = 0;
								$i = 0;
								$bandera = false;
								//$codanterior = '';
								$codsiguiente ='';
								if(!$liqdet){
								}else{
									foreach ($liqdet as $item) {
										//$codanterior = $item["CODIGO"];
										/*	$devolucion = $item["REMISION"] - $item["UNIDTOTAL"];
                                            if ($i<count($det1)-1)
                                            {
                                                $codsiguiente = $det1[$i+1]["CODIGO"];
                                            }
                                            if ($bandera){
                                                $item["REMISION"] = $acumulado;
                                                $devolucion = $acumulado - $item["UNIDTOTAL"];
                                                $bandera = false;
                                            }
                                            if ($item["CODIGO"]==$codsiguiente){
                                                $acumulado = $devolucion;
                                                //$codsiguiente = $codsiguiente." entro";
                                                $bandera = true;
                                            }

                                            else{
                                                $acumulado = 0;
                                          }
                                            if ($acumulado<>0){
                                                //$devolucion	= $acumulado;
                                              $item["REMISION"] = $acumulado;
                                                $acumulado = 0;
                                          }*/

										echo "
								   	<tr style='font-size:12px;'>
								        <th>".$item["Codigo"]."</th>
										<th data-toggle='tooltip' title='".$item["Descripcion"]."' data-placement='top'>
										".substr($item["Descripcion"],0,15)."</th>
										<th>".$item["PesoGramos"]."</th>
										<th>".number_format($item["Precio"],2)."</th>
								        <th>".number_format($item["Carga"],2)."</th>
								        <th>".number_format($item["Devolucion"],2)."</th> 
								        <th>".number_format($item["UnidadesVenCredito"],2)."</th>
										<th>".number_format($item["UnidadesVenContado"],2)."</th>
										<th>".number_format($item["UnidadesVenTotal"],2)."</th>
										<th>".number_format($item["SubtotalContado"],2)."</th>
										<th>".number_format($item["SubtotalCredito"],2)."</th>
										<th>".number_format($item["DescContado"],2)."</th>
										<th>".number_format($item["DescCredito"],2)."</th>
										<th>".number_format($item["IscContado"],2)."</th>
										<th>".number_format($item["IscCredito"],2)."</th>
										<th>".number_format($item["IvaContado"],2)."</th>
										<th>".number_format($item["IvaCredito"],2)."</th>
										<th>".number_format($item["TotalContado"],2)."</th>
										<th>".number_format($item["TotalCredito"],2)."</th>
										<th>".number_format($item["LibrasVendidas"],2)."</th>
										<th>".number_format($item["Merma"],2)."</th>
										<th>".number_format($item["PorcentajeMerma"],2)."</th>
							      	</tr> 
							   ";
										$i++;
									}
								}
								?>
								</tbody>
								<tfoot>
								<tr class="bg-primary">
									<th>TOTAL</th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
								</tr>
								</tfoot>
							</table>
						</div>
						<?php
						if($this->session->userdata('IdRol') != 4){
							$ruta = '';
							if(!$det){
							}else {
								foreach ($det as $item) {
									$ruta = $item["IDPERIODO"];
								}
							}
							if(!$liq){
							}else{
								foreach($liq as $key){
									if($key["Liquidado"] == "Y"){
										echo '
												<div class="text-right mr-lg">
													<a target="_blank" href="'.base_url("index.php/ExcelLiquidacion/".$ruta."").'">
													  <img src="'.base_url().'assets/img/pdf.png" alt="printExcel" />
													</a>
												</div>
											';
									}
								}
							}
						}
						?>
					</div>
					<div id="unidad" class="tab-pane">
						<table class="table table-condensed table-bordered table-striped" id="Unidades">
							<thead>
							<tr class="text-dark">
								<th>CODIGO</th>
								<th>DESCRIPCION</th>
								<th>GRAMOS</th>
								<th>REMISION</th>
								<th>UNIDADES</th>
								<th>DEVOLUCION</th>
								<th>LIBRAS</th>
								<th>MERMA</th>
								<th>TOTAL</th>
							</tr>
							</thead>
							<tbody>
							<?php
							if(!$unidades){
							}else{
								foreach ($unidades as $key) {
									echo "
												<tr>
													<td style='width: 10px'>".$key["CODIGO"]."</td>	
													<td>".$key["DESCRIPCION"]."</td>	
													<td>".number_format($key["GRAMOS"],2)."</td>
													<td>".number_format($key["REMISION"],2)."</td>	
													<td>".number_format($key["UNIDADES"],2)."</td>	
													<td>".number_format($key["DEVOLUCION"],2)."</td>	
													<td>".number_format($key["LIBRAS"],2)."</td>
													<td>".number_format($key["MERMA"],2)."</td>	
													<td>".number_format($key["TOTAL"],2)."</td>	
												</tr>
											";
								}
							}
							?>
							</tbody>
							<tfoot>
							<tr class="bg-primary">
								<th>TOTAL</th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
							</tr>
							</tfoot>
						</table>
						<?php
						if($this->session->userdata('IdRol') != 4){
							$ruta = '';
							if(!$det){
							}else {
								foreach ($det as $item) {
									$ruta = $item["IDPERIODO"];
								}
							}
							if(!$liq){
							}else{
								foreach($liq as $key){
									if($key["Liquidado"] == "Y"){
										echo '
												<div class="text-right mr-lg">
													<a target="_blank" href="'.base_url("index.php/ExcelLiqUnid/".$ruta."").'">
													  <img src="'.base_url().'assets/img/pdf.png" alt="printPDF" />
													</a>
												</div>
											';
									}
								}
							}
						}
						?>
					</div>
				</div>
			</div>
			<br>
			<!--<div class="invoice-summary">
				<div class="row">
					<div class="col-sm-8">
					</div>
					<div class="col-sm-4 col-sm-offset-0">
						<table class="table h5 text-dark">
							<tbody>
							<?php
			/*$sumsubtotal = 0; $total = 0; $isc = 0; $iva = 0;
                echo '
                        <tr class="b-top-none">
                            <td colspan="1">SUBTOTAL</td>
                            <td class="text-left">C$ '.number_format($sumsubtotal,2).'</td>
                        </tr>
                        <tr>
                            <td colspan="1">ISC</td>
                            <td class="text-left">C$ '.number_format($isc,2).'</td>
                        </tr>
                        <tr>
                            <td colspan="1">IVA</td>
                            <td class="text-left">C$ '.number_format($iva,2).'</td>
                        </tr>
                        <tr class="h4">
                            <td colspan="1">Total</td>
                            <td class="">C$ '.number_format($total,2).'</td>
                        </tr>
                    ';*/
			?>
							</tbody>
						</table>
					</div>
				</div>
			</div>-->
		</div>

		<?php
		if($this->session->userdata('IdRol') != 4){
			$ruta = '';
			if(!$det){
			}else {
				foreach ($det as $item) {
					$ruta = $item["IDPERIODO"];
				}
			}

			if(!$liq){
			}else{
				foreach($liq as $key){
					if($key["Liquidado"] != "Y"){
						echo '
					<div class="text-right mr-lg">
						<a id="btnLiquidar" href="javascript:void(0)" class="btn btn-default"><i class="fa fa-gavel"></i> Liquidar vendedor</a>
					</div>';
					}
				}
			}
		}
		?>
	</div>
</section>

<div class="modal" id="loading" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered modal-sm" role="document">
		<div class="modal-content" style="background-color:transparent;box-shadow: none; border: none;">
			<div class="text-center">
				<img width="130px" src="<?php echo base_url()?>assets/img/loading.gif">
			</div>
		</div>
	</div>
</div>



