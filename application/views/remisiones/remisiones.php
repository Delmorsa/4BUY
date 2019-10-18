<div class="row">
	<div class='col-9 col-sm-9 col-md-9'>
		<h2 class="h2 mt-none mb-sm ">Nueva Remision</h2>
	</div>
	<div class="col-3 col-sm-3 col-md-3 pull-right">
	    <button id="btnSaveRem" class="mb-xs mt-xs mr-xs btn btn-primary pull-right" data-toggle="tooltip" data-placement="left" title="" data-original-title="">
					   Guardar remision <i class="fa fa-download"></i>
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
							<label class="col-md-12 control-label" for="Consecutivo">Num Consecutivo</label>
							<div class="input-group input-group-icon">
								<span class="input-group-addon">
									<span class="icon"><i class="fa fa-list-ol"></i></span>
								</span>
								<input readonly type="text" id="Consecutivo"  class="form-control right"
									   placeholder="Consecutivo" autocomplete="off">
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
									<option selected></option>
									<?php
									if (!$rutas) {
									} else {
										foreach ($rutas as $ruta) {
											echo "
												<option value=".$ruta["IdRuta"].">".$ruta["Ruta"]."</option>
											";
										}
									}

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
                                	<option selected></option>
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
							<label class="col-md-6 control-label" for="fechaEntrega">Fecha Entrega</label>
							<div class="input-group input-group-icon">
								<span class="input-group-addon">
									<span class="icon"><i class="fa fa-calendar"></i></span>
								</span>
								<input type="text" id="fechaEntrega" data-plugin-skin="primary" data-plugin-datepicker="" class="form-control" placeholder="Fecha Entrega" autocomplete="off">
							</div>
						</div>
						<div class="form-group col-3 col-sm-3 col-md-3">
							<label class="col-md-12 control-label" for="fechaLiquida">Fecha Liquidación</label>
							<div class="input-group input-group-icon">
								<span class="input-group-addon">
									<span class="icon"><i class="fa fa-calendar"></i></span>
								</span>
								<input type="text" id="fechaLiquida" data-plugin-skin="primary" data-plugin-datepicker="" class="form-control" placeholder="Fecha Liquidación" autocomplete="off">
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
								<input type="text" disabled name="" id="vendedor" placeholder="Vendedor" class="form-control">
							</div>
						</div>
						<div class="form-group col-3 col-sm-3 col-md-3">
							<label class="col-md-3 control-label" for="Ref">Cliente</label>
							<div class="input-group input-group-icon">
								<span class="input-group-addon">
									<span class="icon"><i class="fa fa-group"></i></span>
                                </span>
								<input type="text" name="" id="Ref" placeholder="Cliente" class="form-control">
							</div>
						</div>
					</div>
                    <br>

                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12">
                         <div class="form-group col-2 col-sm-2 col-md-2">
                            <div class="radio-custom radio-primary">
                                <input type="radio" id="chkOrden" name="TipoOrden" checked>
                                <label for="chkOrden">Remision</label>
                            </div>
                        </div>
                        <div class="form-group col-2 col-sm-2 col-md-2">
                            <div class="radio-custom radio-primary">
                                <input type="radio" id="chkPreventa" name="TipoOrden">
                                <label for="chkPreventa">Preventa</label>
                            </div>
                        </div>
                        <div class="form-group col-2 col-sm-2 col-md-2">
                            <div class="radio-custom radio-primary">
                                <input type="radio" id="chkRecargo" name="TipoOrden">
                                <label for="chkRecargo">Recargo</label>
                            </div>
                        </div>
                        <div class="form-group col-2 col-sm-2 col-md-2">
                            <div class="radio-custom radio-primary">
                                <input type="radio" id="chkWalmart" name="TipoOrden">
                                <label for="chkWalmart">Walmart</label>
                            </div>
                        </div>
                        <div class="form-group col-2 col-sm-2 col-md-2">
                            <div class="radio-custom radio-primary">
                                <input type="radio" id="chkCsaMantica" name="TipoOrden">
                                <label for="chkCsaMantica">Casa Mántica</label>
                            </div>
                        </div>
                        <div class="form-group col-2 col-sm-2 col-md-2">
                            <div class="radio-custom radio-primary">
                                <input type="radio" id="chkIndependiente" name="TipoOrden">
                                <label for="chkIndependiente">Independiente</label>
                            </div>
                        </div>
                        <div class="form-group col-2 col-sm-2 col-md-2">
                            <div class="radio-custom radio-primary">
                                <input type="radio" id="chkAdelanto" name="TipoOrden">
                                <label for="chkAdelanto">Adelanto</label>
                            </div>
                        </div>
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
						<!-- mostrar carga y hacer aparecer los botones -->
						<div style="display: none" id="loaderButtons" class="col-1 col-sm-1 col-md-1">
							<img width="50" src="<?php echo base_url()?>assets/img/loading.gif">
						</div>
						<div style="display: none" id="buttonsRem" class="form-group col-2 col-sm-2 col-md-2">
							<button id="btnAgregar" class=" btn btn-primary" data-toggle="tooltip" data-placement="left" title="" data-original-title="">
							<i class="fa fa-plus"></i>
							</button>
							<button id="btnDelete" class="btn btn-danger" data-toggle="tooltip" data-placement="left" title="" data-original-title="">
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
						<div class="form-group">
							<h5 class="titulosGen"><span><img id="printConsecutivosTodosExcel" src="<?php echo base_url()?>assets/img/excel.png" alt="printExcel" /></span> Cargar Excel Remisiones</h5>
							<div class="col-md-6">
								<div class="fileupload fileupload-new" data-provides="fileupload"><input type="hidden">
								<div class="input-append">
									<div class="uneditable-input">
										<i class="fa fa-file fileupload-exists"></i>
										<span class="fileupload-preview"></span>
									</div>
									<span class="btn btn-primary btn-file">
										<span class="fileupload-exists">Cambiar</span>
										<span class="fileupload-new">Seleccionar archivo</span>
										<input type="file" id="fileUpload" name="fileSelect">
									</span>
									<a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">Remover</a>
								</div>
							</div>
						</div>
					</div>
					</div>
					<hr class="dotted">

					<div class="row"> <!--tblRemisiones-->
						<div class="col-12 col-sm-12 col-md-12">
							<div id="wrapper">
								<table id="tblRemisiones" class="table table-condensed table-bordered table-responsive table-striped mb-none table-sm">
								<thead>
									<tr>
										<th>Codigo</th>
										<th>Descripción</th>
										<th>GR</th>
										<th>Cantidad</th>
										<th>Cantidad LBS</th>
										<th>Precio</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
							</div>
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
                                <textarea placeholder="Escribe un comentario" class="form-control" rows="3" id="Comentario"></textarea>
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
					<div class="form-group col-4 col-sm-4 col-md-4">
						<div class="radio-custom radio-primary">
							<input type="radio" id="chkTitular" name="TipoVendedor" checked>
							<label for="chkTitular">Vendedor Titular</label>
						</div>
					</div>
					<div class="form-group col-6 col-sm-6 col-md-6">
						<div class="radio-custom radio-primary">
							<input type="radio" id="chkRotador" name="TipoVendedor" checked>
							<label for="chkRotador">Vendedor Rotador</label>
						</div>
					</div>
				</div>
				<div class="row" id="textRotador" style="display: none">
					<br>
					<div class="col-12 col-sm-12 col-md-12 col-lg-12">
						<div class="form-group">
							<label class="col-md-3 control-label" for="RotadorNom"></label>
							<div class="col-12 col-sm-12 col-md-12">
								<textarea placeholder="Escribe un nombre" class="form-control" rows="3" id="RotadorNom"></textarea>
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
