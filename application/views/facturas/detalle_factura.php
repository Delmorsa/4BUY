<!-- <div class="row">
	<div class="col-sm-3 col-md-3 col-lg-3">
		<a  href="javascript:history.back()" class="btn btn-sm btn-primary">
			<i class="fa fa-arrow-left"></i> Volver
		</a>
	</div>
</div>-->
<br>
<section class="panel">
	<div class="panel-body">
		<div class="invoice">
			<header class="clearfix">
				<div class="row">
					<div class="col-sm-6 mt-md">
						<h2 class="h2 mt-none mb-sm text-dark text-bold">FACTURA</h2>
						<?php
						$consecutivo = ''; $estadofact = ''; $condpago = "";
							if(!$detalles){
							}else{
								foreach ($detalles as $key){
									$consecutivo = $key["IDFACTURA"];
									$estadofact = $key["ESTADOFACT"];
									if($key["CODCONDPAGO"] == "-1"){
										$condpago = 'Contado';
									}else{
										$condpago = 'Credito';
									}
								}
								echo "
										<h4 class='h4 m-none text-dark text-bold'># <span id='consecutivo'>".$consecutivo."</span></h4>
										<p>Estado : ".$estadofact."</p>
										<p>Tipo:".$condpago."</p>
									";
							}
						?>
						<p>Documento no válido como factura oficial.</p>
					</div>
					<div class="col-sm-6 text-right mt-md mb-md">
						<address class="ib mr-xlg">
							<?php
								$codvend =''; $vendedor = ''; $tel1 = ''; $tel2 = '';
								if(!$detalles){
								}else{
									foreach ($detalles as $key) {
										$codvend = $key["CODVENDEDOR"];
										$vendedor = $key["NOMBREVENDEDOR"];
										$tel1 = $key["Telefono1"];
										$tel2 = $key["Telefono2"];
									}
									echo '
									'.$vendedor.'
									<br/>
									 CODIGO '.$codvend.'
									<br/>
									Telefono1: (+505) '.$tel1.'
									<br/>
									Telefono2: (+505) '.$tel2.'';
								}
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
							$cliente = ''; $codcli = ''; $nomcomerc = ''; $ruc= ''; $listprecio = '';
								if(!$detalles){
								}else{
									foreach ($detalles as $key) {
										$cliente = $key["NOMBRE"];
										$codcli = $key["CODCLIENTE"];
										$nomcomerc = $key["NOMBRECOMERCIAL"];
										$ruc = $key["RUC"];
										$listprecio = $key["LISTAPRECIO"];
									}
									echo '
										<p class="h5 mb-xs text-dark text-semibold">Cliente: '.$cliente.'</p>
										<address>
											Codigo: '.$codcli.'
											<br/>
											Nombre Comercial: '.$nomcomerc.'
											<br/>
											RUC: '.$ruc.'
											<br/>
											Lista de precios: '.$listprecio.'
										</address>
									';
								}
							?>
						</div>
					</div>
					<div class="col-md-6">
						<div class="bill-data text-right">
							<?php
							$fechafact = ''; $fechavence = ''; $tiempo = '00:00:00';
								if(!$detalles){
								}else{
									foreach ($detalles as $key){
										$fechafact = $key["FECHA"];
										$fechavence = $key["FECHAVENCE"];
										$tiempo = $key["TIEMPO"];
									}
									echo '
										<p class="mb-none">
											<span class="text-dark">Fecha Factura:</span>
											<span class="value">'.date_format (new DateTime($fechafact), 'd-m-Y').'</span>
										</p>
										<p class="mb-none">
											<span class="text-dark">Fecha Vence:</span>
											<span class="value">'.date_format(new DateTime($fechavence), "d-m-Y").'</span>
										</p>
										<p class="mb-none">
											<span class="text-dark">Duracion Facturacion:</span>
											<span class="value">'.$tiempo.'</span>
										</p>
									';
								}
							?>
						</div>
					</div>
				</div>
			</div>

			<div class="">
				<table class="table invoice-items" id="tblDetFact">
					<thead>
					<tr class="h4 text-dark">
						<th id="cell-id"     class="text-semibold">codigo</th>
						<th id="cell-desc"   class="text-semibold">producto</th>
						<th id="cell-item"   class="text-semibold">Precio</th>
						<th id="cell-price"  class="text-center text-semibold">Cantidad</th>
						<th id="cell-qty"    class="text-center text-semibold">Desc</th>
						<th id="cell-qty"    class="text-center text-semibold">Subtotal</th>
						<th id="cell-qty"  class="text-center text-semibold">ISC</th>
						<th id="cell-qty"  class="text-center text-semibold">IVA</th>
						<th id="cell-total"  class="text-center text-semibold">Total</th>
						<!--<th class="text-right text-semibold"><i style="font-size:10px;" class="fa fa-expand"></th> -->
					</tr>
					</thead>
					<tbody>
					<?php
					$link = '';
						if(!$detalles){
						}else{
							foreach ($detalles as $key) {
								$subtotal = $key["TOTALDET"]- $key["IVADET"] - $key["ISCDET"];
								/*if($key["ESTADOAPP"] == 1){
									$link = '<a href="javascript:void(0)" data-toggle="tooltip" title="Editar Cantidad" data-placement="top"
										   onclick="Editar('."'".$key["IDDETALLE"]."',
										   '".$key["CODIGO"]."'
										   ,'".$key["DESCRIPCION"]."',
										   '".number_format($key["PRECIO"],2)."',
										   '".number_format($key["CANTIDAD"],2)."'
										   ,'".number_format($subtotal,2)."',
										   '".number_format($key["ISCDET"],2)."'
										   ,'".number_format($key["IVADET"],2)."'
										   ,'".number_format($key["TOTALDET"],2)."'".')">
										   <i style="font-size:10px;" class="fa fa-expand">
										   </i>
										   </a>';
								}else{
									$link = '<i style="font-size:10px;" class="fa fa-expand btn disabled"></i>';
								}*/
								echo '
									<tr>
										<td>'.$key["CODIGO"].'</td>
										<td>'.$key["DESCRIPCION"].'</td>
										<td>'.number_format($key["PRECIO"],2).'</td>
										<td class="text-center">'.number_format($key["CANTIDAD"],2).'</td>
										<td class="text-center">'.number_format($key["DESCUENTO"],2).'</td>
										<td class="text-center">'.number_format($subtotal,2).'</td>
										<td class="text-center">'.number_format($key["ISCDET"],2).'</td>
										<td class="text-center">'.number_format($key["IVADET"],2).'</td>
										<td class="text-center">'.number_format($key["TOTALDET"],2).'</td>
										<td class="text-right">
										  '.$link.'
										</td>
									</tr>
								';
							}
						}
					?>
					</tbody>
				</table>
			</div>
			<br>
			<div class="invoice-summary">
				<div class="row">
					<p class="align-left">Comentario</p>
					<div class="col-sm-8">

							<?php
							 $comentario = '';
							if(!$detalles){
								$comentario = '';
							}else{
								foreach ($detalles as $key){
									if($key["COMENTARIO"] != ""){
										$comentario = $key["COMENTARIO"];
									}else{
										$comentario = 'Sin comentarios';
									}
								}
								echo '<textarea disabled class="form-control" style="resize: none; width: 50%; text-align:start" name="Text1" cols="" rows="3">'.ltrim($comentario).'</textarea>';
							}
							?>
						<br>
						<p class="align-left">Comentario anulación</p>
				    <div class="form-group has-error">
							<?php
							$comentario = '';
							if(!$detalles){
								$comentario = '';
							}else{
								foreach ($detalles as $key){
									if($key["COMENTARIOANULACION"] != ""){
										$comentario = $key["COMENTARIOANULACION"];
									}else{
										$comentario = 'Sin comentarios';
									}
									if($key["ESTADOAPP"] == 4){
											$disabled = 'disabled';
									}
								}
								echo '<textarea disabled class="form-control" style="resize: none; width: 50%; text-align: left;" name="Text2" col="" rows="3">'.ltrim($comentario).'</textarea>';
							}
							?>
					</div>
					</div>

					<div class="col-sm-4 col-sm-offset-0">
						<table class="table h5 text-dark">
							<tbody>
							<?php
							$sumsubtotal = 0; $total = 0; $isc = 0; $iva = 0; $desc = 0;
								if(!$detalles){
								}else{
									foreach ($detalles as $key){
										$subtotal = $key["TOTALDET"]- $key["IVADET"] - $key["ISCDET"];
										$desc += $key["DESCUENTO"];
										$sumsubtotal += $subtotal;
										$isc += $key["ISCDET"];
										$iva += $key["IVADET"];
										$total += $key["TOTALDET"];
									}
									echo '
									<tr class="b-top-none">
											<td colspan="1">DESCUENTO</td>
											<td class="text-left">C$ '.number_format($desc,2).'</td>
										</tr>
										<tr class="">
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
											<td class="">C$ '.number_format($total-$desc,2).'</td>
										</tr>
									';
								}
							?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<?php
			if($this->session->userdata('IdRol') != 4){
				if(!$detalles){
				}else{
					$estado = '';
					foreach ($detalles as $key){
						$estado =  $key["ESTADOAPP"];
					}
					if($estado != 1){
						echo '
						<div class="text-right mr-lg">
							<a href="'.base_url("index.php/ImprimirFactura")."/".$this->uri->segment(2).'" target="_blank" class="btn btn-primary ml-sm"><i class="fa fa-print"></i> Imprimir</a>
						</div>';
					}else{
						echo '
						<div class="text-right mr-lg">
							<a href="'.base_url("index.php/ImprimirFactura")."/".$this->uri->segment(2).'" target="_blank" class="btn btn-primary ml-sm"><i class="fa fa-print"></i> Imprimir</a>
							<a href="javascript:void(0)" onclick="modalComent()" class="btn btn-danger ml-sm"><i class="fa fa-trash-o"></i> Anular</a>
						</div>';
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

<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title"><i class="fa fa-pencil"> Editar</i></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="form-group col-3 col-sm-3 col-md-3">
						<div class="">
							<label for="codigo" class="control-label">Codigo</label>
							<input type="text" disabled class="form-control" id="codigo">
							<input type="hidden" class="form-control" id="IdEnc">
						</div>
					</div>
					<div class="form-group col-9 col-sm-9 col-md-9">
						<div class="">
							<label for="descripcion" class="control-label">Descripcion</label>
							<input type="text" disabled class="form-control" id="descripcion">
						</div>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="form-group col-4 col-sm-4 col-md-4">
						<div class="">
							<label for="Precio" class="control-label">Precio</label>
							<input type="text" disabled class="form-control" id="Precio">
						</div>
					</div>
					<div class="form-group col-4 col-sm-4 col-md-4">
						<div class="">
							<label for="Cantidad" class="control-label">Cantidad</label>
							<input type="text"  class="form-control" id="txtCantidad">
						</div>
					</div>
					<div class="form-group col-4 col-sm-4 col-md-4">
						<div class="">
							<label for="Subtotal" class="control-label">Subtotal</label>
							<input type="text" disabled class="form-control" id="Subtotal">
						</div>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="form-group col-4 col-sm-4 col-md-4">
						<div class="">
							<label for="ISC" class="control-label">ISC</label>
							<input type="text" disabled class="form-control" id="ISC">
						</div>
					</div>
					<div class="form-group col-4 col-sm-4 col-md-4">
						<div class="">
							<label for="IVA" class="control-label">IVA</label>
							<input type="text" disabled class="form-control" id="IVA">
						</div>
					</div>
					<div class="form-group col-4 col-sm-4 col-md-4">
						<div class="">
							<label for="Total" class="control-label">Total</label>
							<input type="text" disabled class="form-control" id="Total">
						</div>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-12 col-sm-12 col-md-12 pull-right">
						<button type="button"  class="btn btn-primary" id="btnUpdItem">Actualizar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="modalComment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title"><i class="fa fa-pencil"> Comentario de anulación</i></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-12 col-sm-12 col-md-12 col-lg-12">
						<div class="form-group">
							<label class="col-md-3 control-label" for="Comentario"></label>
							<div class="col-12 col-sm-12 col-md-12">
								<textarea id="txtcomentAnul" class="form-control" rows="3"></textarea>
							</div>
						</div>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-12 col-sm-12 col-md-12 pull-right">
						<button id="btnAnulFactModal" type="button" onclick="anular()" class="btn btn-danger" data-dismiss="modal">Anular</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
