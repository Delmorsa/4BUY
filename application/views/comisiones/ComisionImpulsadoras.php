<style type="text/css">
	.font-weight-bold{font-weight: bold!important;}
</style>

<!-- start: page -->
<div class="row">
	<div class="col-12 col-sm-12 col-md-12">
		<section class="panel col-12 col-sm-12 col-md-12">
			<div class="row">
				<div class="col-12 text-center">
					<h3 class="text-danger">Pantalla para configurar la cuota fija de las impulsadoras (adelanto de 1era quincena)</h3>
				</div>
			</div>
			<div class="panel-body">
				<div class="tabs tabs-danger">
					<ul class="nav nav-tabs tabs-primary">
						<li class="active">
							<a class="text-muted" href="#Inventario" data-toggle="tab" aria-expanded="true">Lista de Impulsadoras</a>
						</li>
					</ul>
					<div class="tab-content">
						<div id="Inventario" class="tab-pane active">
							<table class="table table-bordered table-striped mb-none table-sm table-condensed" id="datatable">
								<thead>
									<tr>										
										<th>Usaurio</th>
										<th>Nombre</th>
										<th>Fecha Apellido</th>
										<th>Última actualización</th>
										<th>Opción</th>
									</tr>
								</thead>
								<tbody>
									<?php
									if(!$impulsadoras){
									}else{
										foreach ($impulsadoras as $key) {
											echo "<tr>
												<td>".$key["Nombre_Usuario"]."</td>
												<td>".$key["Nombre"]."</td>
												<td>".$key["Apellidos"]."</td>
												<td></td>";
											echo"<td class='center'>";
													echo"<a href='javascript:void(0)' style='margin-right:4px;' onclick='editarAdelanto(".'"'.$key["IdUsuario"].'","'.$key["Nombre"].'","'.$key["monto_adelanto"].'"'.")'
														   class='btn btn-xs btn-primary'><i class='fa fa-edit'></i></a>";
												if ($key["Estado"]) {
													echo "<a href='javascript:void(0)' onclick='darBaja(".'"'.$key["IdUsuario"].'","'.$key["Nombre"].'","'.$key["Estado"].'"'.")'
														   class='btn btn-xs btn-danger'><i class='fa fa-trash-o'></i></a>";
												}else
												{
													echo "<a href='javascript:void(0)' onclick='darBaja(".'"'.$key["IdUsuario"].'","'.$key["Nombre"].'","'.$key["Estado"].'"'.")'
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

<!-- Modal editar-->
<div class="modal fade" id="modalEditar" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title font-weight-bold"  id="exampleModalLabel">Asignar comision a impulsadora</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<h3 class="text-center" id="nameAdelanto">asd</h3>
				<label class="col-md-3 control-label" for="thisid">Valor adelanto:</label>
				<div class="input-group input-group-icon">
					<input type='number' id="inputAdelanto" step='0.01' value='0.00' placeholder='0.00' class="form-control" />
				</div><br>
			</div>
			<div class="modal-footer">
				<button type="button" id="cancelarEditar" class="btn btn-secondary">Cancelar</button>
				<button type="button" id="guardarEditarAdelanto" class="btn btn-primary">Guardar</button>
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