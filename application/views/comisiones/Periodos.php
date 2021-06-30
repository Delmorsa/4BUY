<style type="text/css">
	.font-weight-bold{font-weight: bold!important;}
</style>
<div class="row">
	<div class='col-12 col-sm-12 col-md-12'>
		
	</div><br><br>
	<div class='col-2 col-sm-12 col-md-2 text-center'>
		<div class="pull-center">
			<button id="btnFiltrar" data-toggle="modal" data-target="#modalNuevo" class="mb-xs mt-xs mr-xs btn btn-primary" >
				Nuevo periodo <i class="fa fa-truck"></i>
			</button>
		</div>
	</div>
	<div class='col-2 col-sm-12 col-md-2 text-center'>
		<div class="pull-center">
			<button id="btnCopiar" data-toggle="modal" data-target="#modalCopiar" class="mb-xs mt-xs mr-xs btn btn-primary" >
				Copiar periodos <i class="fa fa-clipboard"></i>
			</button>
		</div>
	</div>
</div>
<!-- start: page -->
<div class="row">
	<div class="col-12 col-sm-12 col-md-12">
		<section class="panel col-12 col-sm-12 col-md-12">
			<div class="panel-body">
				<div class="tabs tabs-danger">
					<ul class="nav nav-tabs tabs-primary">
						<li class="active">
							<a class="text-muted" href="#Inventario" data-toggle="tab" aria-expanded="true">Lista de Periodos</a>
						</li>
					</ul>
					<div class="tab-content">
						<div id="Inventario" class="tab-pane active">
							<table class="table table-bordered table-striped mb-none table-sm table-condensed" id="datatable">
								<thead>
									<tr>
										<th>Tipo</th>
										<th>Fecha Inicial</th>
										<th>Fecha Final</th>
										<th>Mes</th>
										<th>Año</th>
										<th>Asignado a:</th>
										<th>Estado</th>
										<th>Opción</th>
									</tr>
								</thead>
								<tbody>
									<?php
									if(!$sup){
									}else{
										foreach ($sup as $key) {
											$tipo = 'Impulsadores';
											$ruta = 'EditarPeriodo';
											if ($key["Tipo"] == 1) {
												$tipo = 'Vendedores';
											}
											if ($key["Tipo"] == 2) {
												$tipo = 'Supervisores';
											}
											if ($key["Tipo"] == 3) {
												$tipo = 'Impulsadoras';
												$ruta = 'EditarPeriodoImpulsadora';
											}
											if ($key["Tipo"] == 4) {
												$tipo = 'Impulsadoras especiales';
												$ruta = 'EditarPeriodoImpulsadora';
											}
											if ($key["Tipo"] == 5) {
												$tipo = 'Jefe Impulsadoras';
												$ruta = 'EditarPeriodoJefeImpulsadora';
											}
											echo "<tr>
													<td>".$tipo."</td>
													<td>".$key["FechaInicial"]."</td>
													<td>".$key["FechaFinal"]."</td>
													<td>".$key["Mes"]."</td>
													<td>".$key["Anio"]."</td>";
												if ($key["IdUsuario"] != null) {
													echo "<td>".$key["Nombre_Usuario"]."</td>";
												}else{
													echo "<td>Todos</td>";
												}

												if ($key["Estado"]) {
													echo "<td class='text-center text-bold text-success'>ACTIVO</td>";
													echo "<td class='center'>
													<a href='".base_url("index.php/".$ruta."/".$key["IdPeriodo"]."")."' style='margin-right:4px;' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i></a>
													<a href='#' onclick='ActualizarEstado(".'"'.$key["IdPeriodo"].'","'.$key["Estado"].'"'.")' class='btn btn-xs btn-danger '><i class='fa fa-trash-o'></i></a> 
													</td>";													
												}else{
													echo "<td class='text-center text-bold text-danger'>INACTIVO</td>";
													echo "
														<td class='left'>
															<a href='#' onclick='ActualizarEstado(".'"'.$key["IdPeriodo"].'","'.$key["Estado"].'"'.")' 
															  class='btn btn-xs btn-danger col-md-offset-6'><i class='fa fa-rotate-left'></i></a> 
														</td>";
												}
										  	echo "</tr>";
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
<!-- Modal -->
<div class="modal fade" id="modalNuevo" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title font-weight-bold"  id="exampleModalLabel">Nuevo Periodo</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">				
				<div class="form-group">					
					<div class="form-group col-6 col-sm-6 col-md-6">
						<label for="selectVendedores">Desde:</label>
						<div class="input-group input-group-icon">
							<span class="input-group-addon">
								<span class="icon"><i class="fa fa-calendar"></i></span>
							</span>
							<input type="text" id="desde" data-plugin-skin="primary"
							data-plugin-datepicker="" class="form-control" placeholder="Fecha Inicio" autocomplete="off">
						</div>
					</div>					
					<div class="form-group col-6 col-sm-6 col-md-6">
						<label for="selectVendedores">Hasta:</label>
						<div class="input-group input-group-icon">
							<span class="input-group-addon">
								<span class="icon"><i class="fa fa-calendar"></i></span>
							</span>
							<input type="text" id="hasta" data-plugin-skin="primary"
							data-plugin-datepicker="" class="form-control" placeholder="Fecha Inicio" autocomplete="off">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label>Tipo de comision:</label>
					<select id="selectTipo" data-plugin-selectTwo class="form-control populate">
						<option selected value="1">Vendedores</option>
						<option value="2">Supervisores</option>
						<option value="3">Impulsadoras</option>
						<option value="4">Impulsadora especial</option>
						<option value="5">Jefe Impulsadoras</option>
					</select>
				</div>
				<div class="form-group">
				<label for="selectVendedores">Trabajador asignado:</label>
				<input type="hidden" id="selectVendedores"  class="form-control col-12 col-md-12 col-sm-12" />
			</div>
			<div class="form-group">
				<label for="selectEstado">Estado:</label>
				<select id="selectEstado" data-plugin-selectTwo class="form-control populate">
					<option selected value="1">Activo</option>
					<option disabled="" value="0">Inactivo</option>
				</select>
			</div>			
			</div>
			<div class="modal-footer">
				<button type="button" id="cancelarNuevo" class="btn btn-secondary">Cancelar</button>
				<button type="button" id="guardarNuevo" class="btn btn-primary">Guardar</button>
			</div>
		</div>
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
							<label class="font-weight-bold" for="mesOrigen">Tipo:</label>
							<select id="selectCopiarTipo" data-plugin-selectTwo class="form-control populate">
								<option selected value="1">Vendedores</option>
								<option value="2">Supervisores</option>
								<option value="3">Impulsadores</option>
							</select>
						</div>
					</div>
					<div class="form-group col-12 col-sm-12 col-md-12" style="border-top: 2px solid #00000038">
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
									echo '<option value="'.gmdate(date("m")-1).'">'.gmdate(date("m")-1).'</option>';
									echo '<option value="'.gmdate(date("m")).'" selected>'.gmdate(date("m")).'</option>';
								?>
							</select>
						</div>					
						<div class="form-group col-6 col-sm-6 col-md-6">
							<label for="anioDestino">Año actual:</label>
							<select id="anioDestino" data-plugin-selectTwo class="form-control populate">
								<?php
									echo '<option value="'.gmdate(date("Y")).'" selected>'.gmdate(date("Y")).'</option>';
									echo '<option value="'.gmdate(date("Y")+1).'">'.gmdate(date("Y")+1).'</option>';
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