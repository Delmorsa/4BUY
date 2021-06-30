<style type="text/css">
	.font-weight-bold{font-weight: bold!important;}
</style>
<div class="row">
	<div class='col-12 col-sm-12 col-md-12'>
		
	</div><br><br>
	<div class='col-2 col-sm-12 col-md-2 text-center'>
		<div class="pull-center">
			<button id="btnFiltrar" data-toggle="modal" class="mb-xs mt-xs mr-xs btn btn-primary" >
				Actualizar <i class="fa fa-refresh"></i>
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
							<a class="text-muted" href="#Inventario" data-toggle="tab" aria-expanded="true">Lista de Artículos</a>
						</li>
					</ul>
					<div class="tab-content">
						<div id="Inventario" class="tab-pane active">
							<table class="table table-bordered table-striped mb-none table-sm table-condensed" id="datatable">
								<thead>
									<tr class="text-bold">
										<th>Código</th>
										<th>Nombre</th>
										<th>Cod Grupo</th>
										<th>Grupo</th>
										<th>Cod Categoria</th>
										<th>Categoria</th>
										<th>Fecha Creación</th>
										<th>Ultima Actualización</th>
									</tr>
								</thead>
								<tbody>
									<?php
									if(!$lista){
									}else{
										foreach ($lista as $key) {
											echo "<tr style='color:black;'>
												<td class='text-bold'>".$key["IdProducto"]."</td>
												<td>".$key["Nombre"]."</td>
												<td>".$key["IdGrupo"]."</td>
												<td class='text-bold'>".$key["Grupo"]."</td>
												<td>".$key["IdCategoria"]."</td>
												<td class='text-bold'>".$key["Categoria"]."</td>
												<td>".$key["FechaCrea"]."</td>
												<td>".$key["FechaEdita"]."</td>
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




<div class="modal" id="loading" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered modal-sm" role="document">
		<div class="modal-content" style="background-color:transparent;box-shadow: none; border: none;">
			<div class="text-center">
				<img width="130px" src="<?php echo base_url()?>assets/img/loading.gif">
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="Modaladvertencia" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-warning text-center" id="exampleModalLabel">Advertencia</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-black">
        <p>	- Todas las acciónes que realice serán registradas en un log. <br><br>
        	- Las existencias mostradas son en tiempo real (al momento de dar click en "Filtrar") cualquier cambio posterior no será responsabilidad de sistema. <br><br>
        	- El inventario físico puede variar con los datos aqui mostrados. <br><br>
        	- Se limitará el No de registro a <strong>200</strong> por motivos de seguridad y rendimiento.
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Estoy de acuerdo</button>
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


<!-- Modal -->
<div class="modal fade" id="modalCopiar" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-bold" style="color:red!important;" id="exampleModalLabel">¿Esta seguro?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-warning">
        <p>
        	- Se creará una nueva congelación con los mismos datos. <br>
        	- La fecha de creación de este reporte será la fecha actual. <br>
        	- Se registrará su usuario como el responsable de esta copia.
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" onclick="$('#loading').modal('show')" class="btn btn-primary">Aceptar</button>
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