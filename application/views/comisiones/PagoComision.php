<style type="text/css">
	.font-weight-bold{font-weight: bold!important;}
</style>
<div class="row">
	<div class='col-12 col-sm-12 col-md-12'>
		
	</div><br><br>	
</div>
<!-- start: page -->
<div class="row">
	<div class="col-12 col-sm-12 col-md-12">
		<section class="panel col-12 col-sm-12 col-md-12">
			<div class="panel-body">
				<div class="col-sm-12 col-12 col-md-12 col-lg-12">
					<div class="form-group col-3 col-sm-3 col-md-3">
						<select name="ruta_regex" id="selectTipo" style="width: 100%!important;">							
							<option value="1">Vendedor</option>
							<option value="2">Supervisor</option>
							<option value="3">Gerente de ventas</option>
							<option value="4">Impulsadora</option>
							<option value="5">Impulsadora especial</option>
							<option value="6">Jefe impulsadoras</option>
						</select>
					</div>
					<div class="form-group col-3 col-sm-3 col-md-3">
						<div class="input-group input-group-icon">
							<span class="input-group-addon">
								<span class="icon"><i class=""></i></span>
							</span>
							<input type="hidden" id="selectFiltro"  class="form-control col12 col-md-12 col-sm-12" />
						</div>
					</div>
					<div class="form-group col-2 col-sm-2 col-md-2">
						<div class="input-group input-group-icon">
								<span class="input-group-addon">
									<span class="icon"><i class="fa fa-calendar"></i></span>
								</span>
							<input type="text" value="2021-04-01" id="desde" data-plugin-skin="primary" data-plugin-datepicker="" class="form-control" placeholder="Fecha Inicio" autocomplete="off">
						</div>
					</div>
					<div class="form-group col-2 col-sm-2 col-md-2">
						<div class="input-group input-group-icon">
							<span class="input-group-addon">
								<span class="icon"><i class="fa fa-calendar"></i></span>
							</span>
							<input type="text" value="2021-04-30" id="hasta" data-plugin-skin="primary" data-plugin-datepicker="" class="form-control" placeholder="Fecha Fin" autocomplete="off">
						</div>
					</div>
					<div class="pull-right">
						<button id="btnGenerar" class="mb-xs mt-xs mr-xs btn btn-primary">
							Generar <i class="fa fa-download"></i>
						</button>
					</div>
					<div class="pull-right">
						<button id="printRptVentasDep" class="mb-xs mt-xs mr-xs btn btn-primary">
							Detallado <i class="fa fa-print"></i>
						</button>
						<button id="printRptVentasDepConsolidado" class="mb-xs mt-xs mr-xs btn btn-primary">
							Consolidado <i class="fa fa-file"></i> <i class="fa fa-print"></i>
						</button>
					</div>
					<br>

				</div>
				<div class="tabs tabs-danger">
					<ul class="nav nav-tabs tabs-primary">
						<li class="active">
							<a class="text-muted" href="#Inventario" data-toggle="tab" aria-expanded="true">Pago de comisiones</a>
						</li>
					</ul>
					<div class="tab-content">
						<div id="Inventario" class="tab-pane active">
							<h3>Ventas</h3>
							<table class="table table-bordered table-striped mb-none table-sm table-condensed" id="datatable">
								<thead>
									<tr>
										<th>Nombre</th>
										<th>Grupo</th>
										<th>Categoria</th>
										<th>Libras</th>
										<th>Lbs Devolución</th>
										<th>Total libras</th>
										<th>Comisión</th>
										<th>Total</th>
									</tr>
								</thead>
								<tbody>
									
								</tbody>
							</table>
							<hr>
							<h3>Devoluciónes</h3>
							<table class="table table-bordered table-striped mb-none table-sm table-condensed" id="datatableDevoluciones">
								<thead>
									<tr>
										<th>Nombre</th>
										<th>Grupo</th>
										<th>Categoria</th>
										<th>Total Lbs</th>										
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


<div class="modal fade" id="modalCopiar" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title font-weight-bold"  id="exampleModalLabel">Copiar Periodos</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">				
				<div class="form-group">
					<div class="form-group col-12 col-sm-12 col-md-12">
						<div class="form-group col-6 col-sm-6 col-md-6">
							<label for="mesOrigen">Mes:</label>
							<select id="mesOrigen" data-plugin-selectTwo class="form-control populate">
								<?php 
									if (!$meses) {
									} else {
										foreach ($meses as $key) {
											echo '
												<option value="'.$key['Mes'].'">'.$key['Mes'].'</option>
											';
										}
									}
								?>
							</select>
						</div>					
						<div class="form-group col-6 col-sm-6 col-md-6">
							<label for="anioOrigen">Año:</label>
							<select id="anioOrigen" data-plugin-selectTwo class="form-control populate">
								<?php 
									if (!$meses) {
									} else {
										foreach ($meses as $key) {
											echo '
												<option value="'.$key['Anio'].'">'.$key['Anio'].'</option>
											';
										}
									}
								?>
							</select>
						</div>
					</div>
					<div class="form-group col-12 col-sm-12 col-md-12" style="border-top: 2px solid #00000038"><br>
						<div class="form-group col-6 col-sm-6 col-md-6">
							<label for="mesDestino">Mes actual:</label>
							<select id="mesDestino" data-plugin-selectTwo class="form-control populate">
								<?php
									echo '<option value="'.gmdate(date("m")).'">'.gmdate(date("m")).'</option>';				
								?>
							</select>
						</div>
						<div class="form-group col-6 col-sm-6 col-md-6">
							<label for="anioDestino">Año actual:</label>
							<select id="anioDestino" data-plugin-selectTwo class="form-control populate">
								<?php
									echo '<option value="'.gmdate(date("Y")).'">'.gmdate(date("Y")).'</option>';				
								?>
							</select>
						</div>
					</div>
				</div>				
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-secondary">Cancelar</button>
				<button type="button" id="guardarCopia" class="btn btn-primary">Copiar</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal editar-->
<div class="modal fade" id="modalEditar" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title font-weight-bold"  id="exampleModalLabel">Editar Canal</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">				
				<label class="col-md-3 control-label" for="thisid">Rutas:</label>
				<div class="input-group input-group-icon">
					<span class="input-group-addon">
						<span class="icon"><i class=""></i></span>
					</span>
					<input type="hidden" id="thisid"  class="form-control col-8 col-md-8 col-sm-12" />
				</div><br>
				<button type="button" id="btnAddRuta" class="btn btn-primary">Agregar Ruta a Canal</button>
				<h4>Rutas Asignadas</h4>
				<table class="table table-bordered table-striped mb-none table-sm table-condensed" id="datatableRutasCanales">
					<thead>
						<tr>
							<th>Cod vendedor</th>
							<th>Nombre</th>
							<th>Opción</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" id="cancelarEditar" class="btn btn-secondary">Cancelar</button>
				<button type="button" id="guardarEditar" class="btn btn-primary">Guardar</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal baja-->
<div class="modal fade" id="modalBaja" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title font-weight-bold"  id="exampleModalLabel">Editar Canal</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body text-center">
				<h3 class="text-danger" id="mensajeBaja">¿Esta segur@?</h3>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-secondary">Cancelar</button>
				<button type="button" id="guardarBaja" class="btn btn-primary">Aceptar</button>
			</div>
		</div>
	</div>
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


<div class="modal" id="loading" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered modal-sm" role="document">
		<div class="modal-content" style="background-color:transparent;box-shadow: none; border: none;margin-top: 26vh;">
			<div class="text-center">
				<img width="130px" src="<?php echo base_url()?>assets/img/loading.gif">
			</div>
		</div>
	</div>
</div>