<?php
/**
 * Created by Cesar Mejía.
 * User: Sistemas
 * Date: 13/2/2019 15:26 2019
 * FileName: consolidado_remisiones.php
 */
?>
<div class="row">
	<div class='col-9 col-sm-9 col-md-9'>
		<h2 class="h2 mt-none mb-sm ">Consolidado Remisión</h2>
	</div>
	<div class="col-3 col-sm-3 col-md-3 pull-right">
		<button id="btnConsolidadoRem" class="mb-xs mt-xs mr-xs btn btn-primary pull-right" data-toggle="tooltip"
				data-placement="left" title="" data-original-title="">
			Ver Consolidados <i class="fa fa-filter"></i>
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
					<div class="row">
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
							<label class="col-md-3 control-label" for="Ruta">Ruta</label>
							<div class="input-group input-group-icon">
								<span class="input-group-addon">
									<span class="icon"><i class=""></i></span>
                                </span>
								<select id="Ruta" data-plugin-selectTwo  class="form-control col12 col-md-12 col-sm-12 populate">
									<option selected></option>
									<?php
										if(!$rutas){
										}else{
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
							<label class="col-md-6 control-label" for="fechaEntrega">Desde:</label>
							<div class="input-group input-group-icon">
								<span class="input-group-addon">
									<span class="icon"><i class="fa fa-calendar"></i></span>
								</span>
								<input type="text" id="fechaEntrega" data-plugin-skin="primary" data-plugin-datepicker="" class="form-control" placeholder="Desde" autocomplete="off">
							</div>
						</div>
						<div class="form-group col-3 col-sm-3 col-md-3">
							<label class="col-md-6 control-label" for="fechaFin">Hasta:</label>
							<div class="input-group input-group-icon">
								<span class="input-group-addon">
									<span class="icon"><i class="fa fa-calendar"></i></span>
								</span>
								<input type="text" id="fechaFin" data-plugin-skin="primary" data-plugin-datepicker="" class="form-control" placeholder="Hasta" autocomplete="off">
							</div>
						</div>
					</div>
						<br>
						<div class="row">
							<div class="form-group col-12 col-sm-12 col-md-12">
								<div class="checkbox-custom checkbox-primary col-1 col-sm-1 col-md-1">
									<input type="checkbox" id="chkOrden" name="chk" value="1">
									<label for="chkOrden">Remision</label>
								</div>
								<div class="checkbox-custom checkbox-primary col-1 col-sm-1 col-md-1">
									<input type="checkbox" id="chkPreventa" name="chk" value="2">
									<label for="chkPreventa">Preventa</label>
								</div>
								<div class="checkbox-custom checkbox-primary col-1 col-sm-1 col-md-1">
									<input type="checkbox" id="chkRecargo" name="chk" value="3">
									<label for="chkRecargo">Recargo</label>
								</div>
                                <div class="checkbox-custom checkbox-primary col-1 col-sm-1 col-md-1">
                                    <input type="checkbox" id="chkWalmart" name="chk" value="5">
                                    <label for="chkWalmart">Walmart</label>
                                </div>
                                <div class="checkbox-custom checkbox-primary col-2 col-sm-2 col-md-2">
                                    <input type="checkbox" id="chkcasaMant" name="chk" value="6">
                                    <label for="chkcasaMant">Casa Mantica</label>
                                </div>
                                <div class="checkbox-custom checkbox-primary col-1 col-sm-1 col-md-2">
                                    <input type="checkbox" id="chkindepend" name="chk" value="7">
                                    <label for="chkindepend">Independiente</label>
                                </div>
								<div class="checkbox-custom checkbox-primary col-1 col-sm-1 col-md-1">
									<input type="checkbox" id="chkAdelanto" name="chk" value="4">
									<label for="chkAdelanto">Adelanto</label>
								</div>
								<div class="checkbox-custom checkbox-danger col-1 col-sm-1 col-md-1">
									<input type="checkbox" id="chkTodos">
									<label for="chkTodos">Todos</label>
								</div>
							</div>
						</div>

					<hr class="dotted">

					<div class="row">
						<div class="col-12 col-sm-12 col-md-12">
							<table id="tblRemisionesCons" class="table table-condensed table-bordered table-responsive table-striped mb-none table-sm">
								<thead>
								<tr>
									<th>Fecha Entrega</th>
									<th>Cantidad</th>
									<th>Cantidad LBS</th>
									<th>Rubro</th>
									<th>Tipo</th>
									<th>Detalles</th>
								</tr>
								</thead>
								<tbody>
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


<div class="modal" id="modalDetalles" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
	 aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document" style="width: 1100px">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title">
					<i class="fa fa-info-circle">
						<span class="text-semibold">
							Detalle Consolidado
							<span id="txtRubro">Rubro</span>
							<span id="txtTipo">(Tipo)</span>
						</span>
					</i>
				</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-2 col-sm-2 col-md-2 col-lg-2 form-group">
						Fecha Entrega: <input type="text" class="form-control" readonly id="FechaEntDt">
					</div>
					<div class="col-2 col-sm-2 col-md-2 col-lg-2 form-group">
						Cantidad Total: <input type="text" class="form-control" readonly id="CantTotal">
					</div>
					<div class="col-2 col-sm-2 col-md-2 col-lg-2 form-group">
						CantidadLBS Total: <input type="text" class="form-control" readonly id="CantlbsTotal">
					</div>
				</div>
				<hr class="dotted">
				<div class="row">
					<div class="col-12 col-sm-12 col-md-12">
						<table class='table table-condensed table-striped table-bordered' id="tblDetalles">
							<thead>
							<tr>
								<th>Ruta</th>
								<th>Vendedor</th>
								<th>Cliente</th>
								<th>Cod. Producto</th>
								<th>Descripcion</th>
								<th>LBS</th>
								<th>Cantidad</th>
								<th>CantidadLBS</th>
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
</div>



