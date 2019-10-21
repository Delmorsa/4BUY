
<div class="row">
	<div class='col-8 col-sm-8 col-md-8'>
		<h2 class="h2 mt-none mb-sm ">Editar Remision</h2>
	</div>
	<div class="col-4 col-sm-4 col-md-4 pull-right">
		<button onclick="javascript:history.back()"  class="mb-xs mt-xs mr-xs btn btn-danger pull-right" data-toggle="tooltip" data-placement="left" title="" data-original-title="">
			Volver <i class="fa fa-undo"></i>
		</button>
		<button  id="btnSaveRem" class="mb-xs mt-xs mr-xs btn btn-primary pull-right" data-toggle="tooltip" data-placement="left" title="" data-original-title="">
			Guardar cambios <i class="fa fa-download"></i>
		</button>
	</div>
</div>
<!-- start: page -->
<div class="row">
	<div class="col-12 col-sm-12 col-md-12">
		<section class="panel">
			<div class="panel-body">
				<div class="invoice">
					<div class="row">
						<div class="form-group col-3 col-sm-3 col-md-3">
							<label class="col-md-12 control-label" for="IdRem">Cod. Remisión</label>
							<div class="input-group input-group-icon">
								<span class="input-group-addon">
									<span class="icon"><i class="fa fa-bookmark"></i></span>
								</span>
								<?php
									if(!$enc){}else{
										foreach ($enc as $item) {
											echo '
												<input type="text" disabled id="IdRem" value="'.$item["IdRemision"].'"  class="form-control right"
									   			placeholder="Consecutivo" autocomplete="off">
											';
										}
									}
								?>
							</div>
						</div>
						<div class="form-group col-3 col-sm-3 col-md-3">
							<label class="col-md-12 control-label" for="Consecutivo">Num Consecutivo</label>
							<div class="input-group input-group-icon">
								<span class="input-group-addon">
									<span class="icon"><i class="fa fa-list-ol"></i></span>
								</span>
								<?php
								if(!$enc){}else{
									foreach ($enc as $item) {
										echo '
												<input type="text" readonly id="Consecutivo" value="'.$item["Consecutivo"].'"  class="form-control right"
									   			placeholder="Consecutivo" autocomplete="off">
											';
									}
								}
								?>
							</div>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="form-group col-3 col-sm-3 col-md-3">
							<label class="col-md-3 control-label" for="ddlRutas">Ruta</label>
							<div class="input-group input-group-icon">
								<span class="input-group-addon">
									<span class="icon"><i class=""></i></span>
                                </span>
								<select id="ddlRutas" data-plugin-selectTwo  class="form-control col12 col-md-12 col-sm-12 populate">
									<?php
									if(!$enc){
									}else{
										foreach ($enc as $item) {
											echo "
												<option value=".$item["IdRuta"].">".$item["Ruta"]."</option>
											";
											if(!$rutas){
											}else{
												foreach ($rutas as $ruta) {
													echo "
														<option value=".$ruta["IdRuta"].">".$ruta["Ruta"]."</option>
													";
												}
									  }
									}
								}

								/*	if (!$rutas) {
									} else {
										foreach ($rutas as $ruta) {
											echo "
												<option value=".$ruta["IdRuta"].">".$ruta["Ruta"]."</option>
											";
										}
									}*/

									?>
								</select>
							</div>
						</div>
						<div class="form-group col-3 col-sm-3 col-md-3">
							<label class="col-md-3 control-label" for="Rubro">Rubro</label>
							<div class="input-group input-group-icon">
								<span class="input-group-addon">
									<span class="icon"><i class=""></i></span>
                                </span>
								<select id="Rubro" data-plugin-selectTwo  class="form-control col12 col-md-12 col-sm-12 populate">
									<?php
									  if(!$enc){
									  }else{
										  foreach ($enc as $item) {
											  echo '<option selected readonly value="'.$item["Rubro"].'">'.$item["Rubros"].'</option>';
									  	}
									  }
									?>
										<option value="1">Detalle</option>
                    <option value="2">Supermercado</option>
                    <option value="3">Hoteles y/o Restaurantes</option>
									  <option value="4">Foráneo</option>
										<option value="5">Venta Local</option>
   									<option value="6">Supermercado Foráneo</option>
								</select>
							</div>
						</div>
						<div class="form-group col-3 col-sm-3 col-md-3">
							<label class="col-md-8 control-label" for="fechaEntrega">Fecha Entrega</label>
							<div class="input-group input-group-icon">
								<span class="input-group-addon">
									<span class="icon"><i class="fa fa-calendar"></i></span>
								</span>
								<?php
								  if(!$enc){
								  }else{
									  foreach ($enc as $item) {
										  echo '<input type="text" id="fechaEntrega" data-plugin-skin="primary"
										   data-plugin-datepicker="" class="form-control" 
										  value="'.date_format(new DateTime($item["FechaEntrega"]),"Y-m-d").'" autocomplete="off">';
								  	}
								  }
								?>
							</div>
						</div>
						<div class="form-group col-3 col-sm-3 col-md-3">
							<label class="col-md-12 control-label" for="fechaLiquida">Fecha Liquidación</label>
							<div class="input-group input-group-icon">
								<span class="input-group-addon">
									<span class="icon"><i class="fa fa-calendar"></i></span>
								</span>
								<?php
								if(!$enc){
								}else{
									foreach ($enc as $item) {
										echo '<input type="text" id="fechaLiquida" data-plugin-skin="primary"
										 data-plugin-datepicker="" class="form-control"
										 value="'.date_format(new DateTime($item["FechaLiq"]),"Y-m-d").'" autocomplete="off">';
									}
								}
								?>
							</div>
						</div>
					</div>
					<div class="row">
						<!--<div class="form-group col-5 col-sm-5 col-md-5">
							<label class="col-md-3 control-label" for="ddlClientes">Cliente</label>
							<div class="input-group input-group-icon">
								<span class="input-group-addon">
									<span class="icon"><i class=""></i></span>
                                </span>
                                <input type="hidden" id=""  class="form-control col12 col-md-12 col-sm-12" />
							</div>
						</div>-->
						<div class="form-group col-4 col-sm-4 col-md-4">
							<label class="col-md-4 control-label" for="vendedor">Vendedor</label>
							<div class="input-group input-group-icon">
								<span class="input-group-addon">
									<span class="icon"><i class="fa fa-user"></i></span>
                                </span>
								<?php
								if(!$enc){
								}else{
									foreach ($enc as $item) {
										echo '<input type="text" disabled name="" id="vendedor" value="'.$item["Vendedor"].'" class="form-control">';
									}
								}
								?>
							</div>
						</div>
						<div class="form-group col-3 col-sm-3 col-md-3">
							<label class="col-md-3 control-label" for="Ref">Cliente</label>
							<div class="input-group input-group-icon">
								<span class="input-group-addon">
									<span class="icon"><i class="fa fa-group"></i></span>
                                </span>
								<?php
								if(!$enc){
								}else{
									foreach ($enc as $item) {
										echo '<input type="text" name="" id="Ref" value="'.$item["Referencia"].'" class="form-control">';
									}
								}
								?>
							</div>
						</div>
						<br>
					</div>

                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12">
                            <?php
                            $attr = '';
                            $attr1 = '';
                            $attr2 = '';
                            $attr3 = '';
                            $attr4 = '';
                            $attr5 = '';
                            $attr6 = '';
                            if(!$enc){
                            }else{
                                foreach ($enc as $item) {
                                    switch ($item["Tipo"])
                                    {
                                        case 1:
                                            $attr = "checked";
                                            break;
                                        case 2:
                                            $attr1 = "checked";
                                            break;
                                        case 3 :
                                            $attr2 = "checked";
                                            break;
                                        case 4:
                                            $attr6 = "checked";
                                            break;
                                        case 5:
                                            $attr3 = "checked";
                                            break;
                                        case 6:
                                            $attr4 = "checked";
                                            break;
                                        case 7:
                                            $attr5 = "checked";
                                            break;
                                    }
                                    echo '
										<div class="form-group col-1 col-sm-1 col-md-1">
											<div class="radio-custom radio-primary">
												<input type="radio" id="chkOrden" name="TipoOrden" '.$attr.'>
												<label for="chkOrden">Remision</label>
											</div>
										</div>
										<div class="form-group col-1 col-sm-1 col-md-1">
											<div class="radio-custom radio-primary">
												<input type="radio" id="chkPreventa" name="TipoOrden" '.$attr1.'>
												<label for="chkPreventa">Preventa</label>
											</div>
										</div>
										<div class="form-group col-1 col-sm-1 col-md-1">
											<div class="radio-custom radio-primary">
												<input type="radio" id="chkRecargo" name="TipoOrden" '.$attr2.'>
												<label for="chkRecargo">Recargo</label>
											</div>
										</div>
										<div class="form-group col-2 col-sm-2 col-md-2">
                                            <div class="radio-custom radio-primary">
                                                <input type="radio" id="chkWalmart" name="TipoOrden" '.$attr3.'>
                                                <label for="chkWalmart">Walmart</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-2 col-sm-2 col-md-2">
                                            <div class="radio-custom radio-primary">
                                                <input type="radio" id="chkCsaMantica" name="TipoOrden" '.$attr4.'>
                                                <label for="chkCsaMantica">Casa Mántica</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-2 col-sm-2 col-md-2">
                                            <div class="radio-custom radio-primary">
                                                <input type="radio" id="chkIndependiente" name="TipoOrden" '.$attr5.'>
                                                <label for="chkIndependiente">Independiente</label>
                                            </div>
                                        </div>
										<div class="form-group col-1 col-sm-1 col-md-1">
											<div class="radio-custom radio-primary">
												<input type="radio" id="chkAdelanto" name="TipoOrden" '.$attr6.'>
												<label for="chkAdelanto">Adelanto</label>
											</div>
										</div>
									';
                                }
                            }
                            ?>
                        </div>
                        <br>
                    </div>

					<hr class="dotted">

					<div class="row">
						<div class="form-group col-5 col-sm-5 col-md-5">
							<label class="col-md-3 control-label" for="ddlProductos">Productos</label>
							<div class="input-group input-group-icon">
								<span class="input-group-addon">
									<span class="icon"><i class=""></i></span>
                                </span>
								<input type="hidden" id="thisid"  class="form-control col12 col-md-12 col-sm-12" />
							</div>
						</div>
						<div class="form-group col-2 col-sm-2 col-md-2">
							<label class="col-md-3 control-label" for="txtstockProd">Stock</label>
							<div class="input-group input-group-icon">
									<span class="input-group-addon">
										<span class="icon"><i class="fa  fa-sort-numeric-asc"></i></span>
									</span>
								<input type="text" readonly name="" placeholder="Stock" id="txtstockProd" class="form-control">
								<input type="hidden" readonly name="" placeholder="Bodega" id="txtbodegaProd" class="form-control">
							</div>
						</div>
						<div class="form-group col-2 col-sm-2 col-md-2">
							<label class="col-md-3 control-label" for="cantidad">Cantidad</label>
							<div class="input-group input-group-icon">
									<span class="input-group-addon">
										<span class="icon"><i class="fa  fa-sort-numeric-asc"></i></span>
									</span>
								<input type="text" name="" id="cantidad" placeholder="Cantidad" class="form-control">
							</div>
						</div><br>
						<div style="display: none" id="loaderButtons" class="col-1 col-sm-1 col-md-1">
							<img width="50" src="<?php echo base_url()?>assets/img/loading.gif">
						</div>
						<div  style="display: none" id="buttonsRem" class="form-group col-2 col-sm-2 col-md-2">
							<button id="btnAgregar" class=" btn btn-primary" data-toggle="tooltip" data-placement="left" title="" 		              data-original-title="">
								<i class="fa fa-plus"></i>
							</button>

							<button id="btnDelete" class="btn btn-danger" data-toggle="tooltip" data-placement="left" title="" 		              data-original-title="">
								<i class="fa fa-trash-o"></i>
							</button>
							<div id="campo">
							</div>
						</div>
						<div class="form-group col-3 col-sm-3 col-md-3">
							<p class="text-bold">T. Cantidad: <span id="SumCant"></span></p>
							<p class="text-bold">T. Libras: <span id="SumLbs"></span></p>
						</div>
					</div>
					<hr class="dotted">
					<div class="row">
						<div class="col-12 col-sm-12 col-md-12">
							<table id="tblRemisionesDet" class="table table-condensed table-bordered table-responsive table-striped mb-none table-sm">
								<thead>
								<tr>
									<th>Codigo</th>
									<th>Descripción</th>
									<th>GR</th>
									<th>Cantidad</th>
									<th>Cantidad LBS</th>
								</tr>
								</thead>
								<tbody>
								  <?php
								  	if(!$det){
									}else{
										foreach ($det as $item) {
											echo
											"<tr>
											   <td>".$item["CodigoProd"]."</td>
											   <td>".$item["DescripcionProd"]."</td>
											   <td>".$item["LBS"]."</td>
											   <td>".$item["Cantidad"]."</td>
											   <td>".$item["CantidadLBS"]."</td>	  												
											</tr>";
								  		}
									}
								  ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>

<div class="row">

</div>

<div class="row">

</div>

<div class="modal" id="loading" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered modal-sm" role="document">
		<div class="modal-content" style="background-color:transparent;box-shadow: none; border: none;">
			<div class="text-center">
				<img width="130px" src="<?php echo base_url()?>assets/img/loading.gif">
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
				<h4 class="modal-title"><i class="fa fa-pencil"> Comentario</i></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-12 col-sm-12 col-md-12 col-lg-12">
						<div class="form-group">
							<label class="col-md-3 control-label" for="Comentario"></label>
							<div class="col-12 col-sm-12 col-md-12">
                                <textarea class="form-control" rows="3" id="Comentario">
									<?php
										if(!$enc){
										}else{
											foreach ($enc as $item) {
												echo $item["Comentario"];
											}
										}
									?>
                                </textarea>
							</div>
						</div>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-12 col-sm-12 col-md-12 pull-right">
						<button type="button"  class="btn btn-primary" data-dismiss="modal">Aceptar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modalVendedor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title"><i class="fa fa-pencil"> Tipo Vendedor</i></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<?php
					$attr = "";
					$attr1 = "";
					if(!$enc){
					}else{
						foreach ($enc as $item) {
							if ($item["NombreRotador"] == null) {
								$attr = "checked";
							}else{
								$attr1 = "checked";
							}
							echo '
							  <div class="form-group col-4 col-sm-4 col-md-4">
								<div class="radio-custom radio-primary">
									<input type="radio" id="chkTitular" name="TipoVendedor" '.$attr.'>
									<label for="chkTitular">Vendedor Titular</label>
								</div>
							</div>
							<div class="form-group col-6 col-sm-6 col-md-6">
								<div class="radio-custom radio-primary">
									<input type="radio" id="chkRotador" name="TipoVendedor" '.$attr1.'>
									<label for="chkRotador">Vendedor Rotador</label>
								</div>
							</div>
							';
						}
					}
					?>
				</div>
				<div class="row" id="textRotador" style="display: none">
					<br>
					<?php
						if(!$enc){
						}else{
							foreach ($enc as $item) {
								echo '<div class="col-12 col-sm-12 col-md-12 col-lg-12">
										<div class="form-group">
											<label class="col-md-3 control-label" for="RotadorNom"></label>
											<div class="col-12 col-sm-12 col-md-12">
												<textarea placeholder="Escribe un nombre" class="form-control" rows="3" id="RotadorNom">'.$item["NombreRotador"].'</textarea>
											</div>
										</div>
									</div>';
							}
						}
					?>
				</div>
				<br>
				<div class="row">
					<div class="col-12 col-sm-12 col-md-12 pull-right">
						<button type="button"  class="btn btn-primary" data-dismiss="modal">Aceptar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

