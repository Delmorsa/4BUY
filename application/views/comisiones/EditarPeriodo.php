<style type="text/css">
	.font-weight-bold{font-weight: bold!important;}
</style>

<input type="hidden"  id="IdPeriodo" value="<?php echo $encabezadoPeriodo[0]["IdPeriodo"]?>" name="">
<div class="row" style="padding-left: 15px; padding-right: 15px;">	
	<div class="form-group col-2 col-sm-2 col-md-2">
		<label class="col-md-6 control-label" for="fechaInicio">Tipo</label>
		<div class="input-group input-group-icon">
			<span class="input-group-addon">
				<span class="icon"><i class="fa fa-calendar"></i></span>
			</span>
			<input type="text" disabled value="<?php
			$tipo = 'Impulsadores';
			if ($encabezadoPeriodo[0]["Tipo"] == 1) {
				$tipo = 'Vendedores';
			}
			if ($encabezadoPeriodo[0]["Tipo"] == 2) {
				$tipo = 'Supervisores';
			}
			 echo $tipo; 
			 ?>" data-plugin-skin="primary" class="form-control text-bold" autocomplete="off">
		</div>
	</div>
	<div class="form-group col-2 col-sm-2 col-md-2">
		<label class="col-md-6 control-label" for="fechaInicio">Estado</label>
		<div class="input-group input-group-icon">
			<span class="input-group-addon">
				<span class="icon"><i class="fa fa-calendar"></i></span>
			</span>
			<input type="text" disabled value="<?php if($encabezadoPeriodo[0]["Estado"] == 1){ echo "Activo";} else {echo "Inactivo";}?>" data-plugin-skin="primary" class="form-control text-bold" autocomplete="off">
		</div>
	</div>
	<div class="form-group col-2 col-sm-2 col-md-2">
		<label class="col-md-6 control-label" for="fechaInicio">Desde</label>
		<div class="input-group input-group-icon">
			<span class="input-group-addon">
				<span class="icon"><i class="fa fa-calendar"></i></span>
			</span>
			<input type="text" disabled value="<?php echo $encabezadoPeriodo[0]["FechaInicial"] ?>" data-plugin-skin="primary" class="form-control text-bold" autocomplete="off">
		</div>
	</div>
	<div class="form-group col-2 col-sm-2 col-md-2">
		<label class="col-md-6 control-label" for="fechaInicio">Hasta</label>
		<div class="input-group input-group-icon">
			<span class="input-group-addon">
				<span class="icon"><i class="fa fa-calendar"></i></span>
			</span>
			<input type="text" disabled value="<?php echo $encabezadoPeriodo[0]["FechaFinal"] ?>" data-plugin-skin="primary" class="form-control text-bold" autocomplete="off">
		</div>
	</div>
	<div class="form-group col-2 col-sm-2 col-md-2">
		<label class="col-md-6 control-label" for="fechaInicio">Mes</label>
		<div class="input-group input-group-icon">
			<span class="input-group-addon">
				<span class="icon"><i class="fa fa-calendar"></i></span>
			</span>
			<input type="text" disabled value="<?php echo $encabezadoPeriodo[0]["Mes"] ?>" data-plugin-skin="primary" class="form-control text-bold" autocomplete="off">
		</div>
	</div>
	<div class="form-group col-2 col-sm-2 col-md-2">
		<label class="col-md-6 control-label" for="fechaInicio">Año</label>
		<div class="input-group input-group-icon">
			<span class="input-group-addon">
				<span class="icon"><i class="fa fa-calendar"></i></span>
			</span>
			<input type="text" disabled value="<?php echo $encabezadoPeriodo[0]["Anio"] ?>" data-plugin-skin="primary" class="form-control text-bold" autocomplete="off">
		</div>
	</div>
	<div class="form-group col-2 col-sm-2 col-md-2">
		<label class="col-md-6 control-label" for="fechaInicio">Usuario crea</label>
		<div class="input-group input-group-icon">
			<span class="input-group-addon">
				<span class="icon"><i class="fa fa-calendar"></i></span>
			</span>
			<input type="text" disabled value="<?php echo $encabezadoPeriodo[0]["UsuarioCrea"] ?>" data-plugin-skin="primary" class="form-control text-bold" autocomplete="off">
		</div>
	</div>
	<div class="form-group col-2 col-sm-2 col-md-2">
		<label class="col-md-6 control-label" for="fechaInicio">Usuario edita</label>
		<div class="input-group input-group-icon">
			<span class="input-group-addon">
				<span class="icon"><i class="fa fa-calendar"></i></span>
			</span>
			<input type="text" disabled value="<?php echo $encabezadoPeriodo[0]["UsuarioEdita"] ?>" data-plugin-skin="primary" class="form-control text-bold" autocomplete="off">
		</div>
	</div>
	<div class="form-group col-2 col-sm-2 col-md-2">
		<label class="col-md-6 control-label" for="fechaInicio">Fecha Crea</label>
		<div class="input-group input-group-icon">
			<span class="input-group-addon">
				<span class="icon"><i class="fa fa-calendar"></i></span>
			</span>
			<input type="text" disabled value="<?php echo date_format (new DateTime($encabezadoPeriodo[0]["FechaCrea"]), 'Y-m-d H:m:s') ?>" data-plugin-skin="primary" class="form-control text-bold" autocomplete="off">
		</div>
	</div>	
	<div class="form-group col-2 col-sm-2 col-md-2">
		<?php
			if ($encabezadoPeriodo[0]["Pagado"]) {
				echo "<p>Estado: <h2 class='text-success'>Pagado</h2></p>";
			}
			else{
				echo "<p>Estado: <h2 class='text-danger'>No Pagado</h2></p>";
			}
		 ?>
	</div>
	<div class='col-2 col-sm-12 col-md-2 text-center'><br>
		<div class="pull-center">
			<button id="btnGuardar" class="mb-xs mt-xs mr-xs btn btn-primary" >
				Guardar <i class="fa fa-save"></i>
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
							<a class="text-muted" href="#Inventario" data-toggle="tab" aria-expanded="true">Datos del Periodo</a>
						</li>
					</ul>
					<div class="tab-content">
						<div style="width: 100%; overflow-x: scroll;" id="Inventario" class="tab-pane active">
							<table  class="table table-bordered table-striped mb-none table-sm table-condensed" id="datatable">
								<thead>
									<?php
									if ($tieneDetalle > 0) {
									//if (isset($detallePeriodo[0]["IdPeriodo"])) {
										echo "
										<tr class='text-bold'>
											<th colspan='2' width='15%'></th>";
										foreach ($canales as $key) {
											echo "<th class='text-bold'>".$key["Nombre"]."</th>";
											//echo "<th class='text-bold'>".$key["IdCanal"]." ".$key["Nombre"]."</th>";
										}
										echo "</tr>";
									}else{
										echo "
										<tr class='text-bold'>
											<th colspan='2' width='15%'></th>";
										foreach ($canales as $key) {
											echo "<th class='text-bold'>".$key["Nombre"]."</th>";
											//echo "<th class='text-bold'>".$key["IdCanal"]." ".$key["Nombre"]."</th>";
										}
										echo "</tr>";
									}
									?>
								</thead>
								<tbody>
									<?php
									if ($tieneDetalle > 0) {
									//if (isset($detallePeriodo[0]["IdPeriodo"])) {
										foreach ($categorias as $keyGrup) {
											/*echo "<tr>
												<td width='6%' class='text-bold'>".$keyGrup["Categoria"]."</td>
												<td class='text-bold'>".$keyGrup["Nombre"]."</td>";*/
											echo "<tr>												
												<td class='text-bold'>".$keyGrup["Nombre"]."</td>
												<td width='6%' class='text-bold'>".$keyGrup["Categoria"]."</td>";
												foreach ($canales as $keyCanales) {
													$bandera = true;
													//echo "<td contenteditable='true'>".$keyCanales["IdCanal"]." ".$keyGrup["IdGrupo"]." ".$keyGrup["Categoria"]."</td>";
													foreach ($detallePeriodo as $keyDetalle) {
														/*echo "( ".$keyDetalle["IdCanal"]."==".$keyCanales["IdCanal"]." && " 
															.$keyDetalle["IdGrupo"]."==".$keyGrup["IdGrupo"] ." && "
															.$keyDetalle["Categoria"]."==".$keyGrup["Categoria"]." )"."<br><br>";*/

															/*echo $keyDetalle["IdCanal"]. " ==" .$keyCanales["IdCanal"] .
															" && ".$keyDetalle["IdGrupo"]. " ==" .$keyGrup["IdGrupo"] .
															" && " .$keyDetalle["Categoria"]. " ==" .$keyGrup["Categoria"]."<br>";*/ 
															//return;
														if ($keyDetalle["IdCanal"] == $keyCanales["IdCanal"] 
															&& $keyDetalle["IdGrupo"] == $keyGrup["IdGrupo"] 
															&& $keyDetalle["Categoria"] == $keyGrup["Categoria"]) {
															
															/*echo "<td id='td-".$keyGrup["IdCategoria"]."-".$keyGrup["IdGrupo"]."-".$keyCanales["IdCanal"]."' contenteditable='true'>".$keyDetalle["ValorVendedor"]."</td>";*/
															echo "<td id='td-".$keyGrup["IdCategoria"]."-".$keyGrup["IdGrupo"]."-".$keyCanales["IdCanal"]."' contenteditable='true'>".number_format($keyDetalle["ValorVendedor"],2)."</td>";
															$bandera = false; 
															//break;
														} 
													}
													if ($bandera) {
														echo "<td id='td-".$keyGrup["IdCategoria"]."-".$keyGrup["IdGrupo"]."-".$keyCanales["IdCanal"]."' contenteditable='true'>0</td>";
														$bandera = false;
													}

												}
											echo "</tr>";//end grupos
										}//end grupos
									}else{
										foreach ($categorias as $keyGrup) {
											echo "<tr>
												<td class='text-bold'>".$keyGrup["Nombre"]."</td>
												<td width='6%' class='text-bold'>".$keyGrup["Categoria"]."</td>";
												foreach ($canales as $keyCanales) {
													echo "<td id='td-".$keyGrup["IdCategoria"]."-".$keyGrup["IdGrupo"]."-".$keyCanales["IdCanal"]."' contenteditable='true'>0</td>";
												}
											echo "</tr>";//end grupos
										}//end grupos
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