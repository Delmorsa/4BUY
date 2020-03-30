
<div class="row">
	<div class='col-9 col-sm-9 col-md-9'>
		<h2 class="h2 mt-none mb-sm ">Reporte  de Devoluciones por ruta</h2>
	</div>	
</div>
<!-- start: page -->
<div class="row">
	<div class="col-12 col-sm-12 col-md-12">
		<section class="panel">
			<div class="panel-body">
				<div class="invoice">
					<div class="row">						
						<div class="col-sm-12 col-12 col-md-12 col-lg-12">
							<div class="pull-right">
								<button id="btnGenerarRpt" class="mb-xs mt-xs mr-xs btn btn-primary">
									Generar reporte <i class="fa fa-download"></i>
								</button>
							</div>

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
						</div>

						<br>

						<hr class="dotted">
						<div class="row">
							<div class="col-12 col-sm-12 col-md-12 col-xs-12">
								<table id="tblDevoluciones" class="table table-bordered table-striped mb-none table-xl">
									<thead>
									<tr>
										<th>Codigo</th>
										<th>Descripcion</th>
										<th>ruta1</th>
									    <th>ruta2</th>
									    <th>ruta3</th>
									    <th>ruta4</th>
									    <th>ruta5</th>
									    <th>ruta6</th>
									    <th>ruta7</th>
									    <th>ruta8</th>
									    <th>ruta9</th>
									    <th>ruta10</th>
									    <th>ruta11</th>
									    <th>ruta12</th>
									    <th>ruta13</th>
									    <th>ruta14</th>
									    <th>ruta15</th>
									    <th>ruta16</th>
									    <th>ruta17</th>
									    <th>ruta18</th>
									    <th>ruta19</th>
									    <th>ruta21</th>
									    <th>ruta22</th>
									    <th>ruta23</th>
									    <th>ruta24</th>
									    <th>ruta25</th>
									    <th>ruta26</th>
									    <th>ruta27</th>
									    <th>ruta28</th>
									    <th>ruta30</th>
									    <th>ruta31</th>
										<th>ruta32</th>
										<th>ruta33</th>
										<th>ruta34</th>
									</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
						<div class="row">
							<div class="text-right mr-lg">
								<?php
									echo '
									<div class="text-right mr-lg">	
										<a href="javascript:void(0)">									
											<img id="printDevoluciones" src="'.base_url().'assets/img/pdf.png" alt="printDevoluciones" />
										</a>
									</div>
									';
								?>
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