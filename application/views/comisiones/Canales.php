<style type="text/css">
	.font-weight-bold{font-weight: bold!important;}
</style>
<div class="row">
	<div class='col-12 col-sm-12 col-md-12'>
		
	</div><br><br>
	<div class='col-2 col-sm-12 col-md-2 text-center'>
		<div class="pull-center">
			<button id="btnFiltrar" data-toggle="modal" data-target="#modalNuevo" class="mb-xs mt-xs mr-xs btn btn-primary" >
				Nuevo Canal <i class="fa fa-truck"></i>
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
							<a class="text-muted" href="#Inventario" data-toggle="tab" aria-expanded="true">Lista de Canales</a>
						</li>
					</ul>
					<div class="tab-content">
						<div id="Inventario" class="tab-pane active">
							<table class="table table-bordered table-striped mb-none table-sm table-condensed" id="datatable">
								<thead>
									<tr>										
										<th>Cod Canal</th>
										<th>Descripción</th>
										<th>Fecha Creación</th>
										<th>Última actualización</th>
										<th>Estado</th>
										<th>Opción</th>
									</tr>
								</thead>
								<tbody>
									<?php
									if(!$lista){
									}else{
										foreach ($lista as $key) {
											echo "<tr>
												<td class='text-center'>".$key["IdCanal"]."</td>
												<td>".$key["Nombre"]."</td>
												<td>".$key["FechaCrea"]."</td>
												<td>".$key["FechaEdita"]."</td>";
												if ($key["Estado"]) {
													echo "<td class='text-center text-bold text-success'>ACTIVO</td>";
												}else{													
													echo "<td class='text-center text-bold text-danger'>INACTIVO</td>";
												}
												echo "
												<td class='center'>
													<a href='javascript:void(0)' style='margin-right:4px;' onclick='editar(".'"'.$key["IdCanal"].'","'.$key["Nombre"].'"'.")'
														   class='btn btn-xs btn-primary'><i class='fa fa-edit'></i></a>";
												if ($key["Estado"]) {
													echo "<a href='javascript:void(0)' onclick='darBaja(".'"'.$key["IdCanal"].'","'.$key["Nombre"].'","'.$key["Estado"].'"'.")'
														   class='btn btn-xs btn-danger'><i class='fa fa-trash-o'></i></a>";
												}else
												{
													echo "<a href='javascript:void(0)' onclick='darBaja(".'"'.$key["IdCanal"].'","'.$key["Nombre"].'","'.$key["Estado"].'"'.")'
														   class='btn btn-xs btn-success'><i class='fa fa-check'></i></a>";
												}
										  	echo "</td>
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
<!-- Modal -->
<div class="modal fade" id="modalNuevo" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title font-weight-bold"  id="exampleModalLabel">Nuevo Canal</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="inputNuevoCanal">Nombre del canal:</label>
					<input type="text" class="form-control" id="inputNuevoCanal" placeholder="nombre">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" id="cancelarNuevo" class="btn btn-secondary">Cancelar</button>
				<button type="button" id="guardarNuevo" class="btn btn-primary">Aceptar</button>
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
				<div class="form-group">
					<label for="inputEditarCanal">Nombre del canal:</label>
					<input type="text" class="form-control" id="inputEditarCanal" placeholder="nombre">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" id="cancelarEditar" class="btn btn-secondary">Cancelar</button>
				<button type="button" id="guardarEditar" class="btn btn-primary">Aceptar</button>
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


