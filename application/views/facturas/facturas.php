<div class="row">
	<div class='col-12 col-sm-12 col-md-12'>
		<!--<div class="pull-right">
			<button id="btnActualizarFacturas" class="mb-xs mt-xs mr-xs btn btn-primary">
				Actualizar <i class="fa fa-download"></i>
			</button>
		</div> -->
	</div>
</div>
<!-- start: page -->
<div class="row">
	<div class="col-12 col-sm-12 col-md-12">
		<section class="panel col-12 col-sm-12 col-md-12">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="#" class="fa fa-caret-down"></a>
				</div>

				<h2 class="panel-title">Listado de Facturas</h2>
			</header>
			<div class="panel-body">
				<div class="col-sm-12 col-12 col-md-12 col-lg-12">
					<div class="pull-right">
						<button id="btnActualizarFacturas" class="mb-xs mt-xs mr-xs btn btn-primary">
							Actualizar <i class="fa fa-download"></i>
						</button>
					</div>
					<select  name="ruta_regex" id="searchSelect_regex" class="">
						<option></option>
						<?php
						if(!$rutas){
						}else{
							foreach ($rutas as $ruta) {
								echo "<option value=".$ruta["CODVENDEDOR"].">".$ruta["CODVENDEDOR"]."</option>";
							}
						}
						?>
					</select>

					<select  name="ruta_regex" id="searchSelect_regexTipo" class="">
						<option></option>
						<option value="-1">Contado</option>
						<option value="3">Credito</option>
					</select>

					<div class="form-group col-3 col-sm-3 col-md-3 pull-left">
						<div class="input-group input-group-icon">
								<span class="input-group-addon">
									<span class="icon"><i class="fa fa-calendar"></i></span>
								</span>
							<input type="text" id="fechaFac1" data-plugin-skin="primary"
								   data-plugin-datepicker="" class="form-control" placeholder="Fecha Inicio" autocomplete="off">
						</div>
					</div>

					<div class="form-group col-3 col-sm-3 col-md-3 pull-left">
						<div class="input-group input-group-icon">
								<span class="input-group-addon">
									<span class="icon"><i class="fa fa-calendar"></i></span>
								</span>
							<input type="text" id="fechaFac2" data-plugin-skin="primary"
								   data-plugin-datepicker="" class="form-control" placeholder="Fecha Fin" autocomplete="off">
						</div>
					</div>
				</div>
				<div>
					<div class="col-12 col-sm-12 col-md-12 col-lg-12">
						<table class="table table-bordered table-stripedtable-condensed" id="dtFacturas">
							<thead>
							<tr>
								<th width="110px">Fecha</th>
								<th style="display: none">estado1</th>
								<th>Estado</th>
								<th>Serie</th>
								<th>Consec.</th>
								<th>Cod Cliente</th>
								<th>Cliente</th>
								<th>Ruta</th>
								<th>SubTotal</th>
								<th>Desc</th>
								<th>ISC</th>
								<th>IVA</th>
								<th>Total</th>
								<th>Detalles</th>
							</tr>
							</thead>
							<tbody style="font-size: 11.5px;">
							</tbody>
							<tfoot style="font-size: 11.5px; font-weight: bolder;">
							<tr class="bg-primary">
								<th>Totales</th>
								<th style="display: none"></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th id="SubTotal"></th>
								<th id="Desc"></th>
								<th id="ISC"></th>
								<th id="IVA"></th>
								<th id="Total"></th>
								<th></th>
							</tr>
							</tfoot>
						</table>
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
