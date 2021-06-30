
<div class="row">
	<div class='col-9 col-sm-9 col-md-9'>
		<h2 class="h2 mt-none mb-sm ">Reporte Facturas de Empleado</h2>
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
								<button id="btnActualizar" class="mb-xs mt-xs mr-xs btn btn-primary">
									Actualizar <i class="fa fa-download"></i>
								</button>
							</div>
							<div class="form-group col-3 col-sm-3 col-md-3">								
								<div class="input-group input-group-icon">
								<span class="input-group-addon">
									<span class="icon"><i class=""></i></span>
                                </span>
									<select id="Ruta" placeholder="Ruta" data-plugin-selectTwo  class="form-control col12 col-md-12 col-sm-12 populate">
										<option selected></option>
                                        <?php 
                                                if(!$foraneos){
                                                }else{
                                                    foreach ($foraneos as $key ) {
                                                        echo "<option value='".$key["IdRuta"]."'>".$key["Ruta"]."</option>";
                                                    }
                                                }
                                        ?>
									</select>
								</div>
							</div>
							<div class="form-group col-3 col-sm-3 col-md-3 pull-left">
								<div class="input-group input-group-icon">
										<span class="input-group-addon">
											<span class="icon"><i class="fa fa-calendar"></i></span>
										</span>
									<input type="text" id="fechainicio" data-plugin-skin="primary"
										   data-plugin-datepicker="" class="form-control" placeholder="Fecha Inicio" autocomplete="off">
								</div>
							</div>
							<div class="form-group col-3 col-sm-3 col-md-3 pull-left">
								<div class="input-group input-group-icon">
									<span class="input-group-addon">
										<span class="icon"><i class="fa fa-calendar"></i></span>
									</span>
									<input type="text" id="fechafin" data-plugin-skin="primary"
										   data-plugin-datepicker="" class="form-control" placeholder="Fecha Fin" autocomplete="off">
								</div>
							</div>
						</div>
						</div>

						<br>

						<hr class="dotted">
						<div class="row">
							<div class="col-12 col-sm-12 col-md-12">
								<table id="tblrptVentas" class="table table-bordered table-striped mb-none table-sm table-condensed">
									<thead>
									<tr>
										<th>FECHA</th>
										<th>COD CLIENTE</th>
										<th>CLIENTE</th>
										<th>FACTURA</th>
										<th>COD VENDEDOR</th>
										<th>VENDEDOR</th>
										<th>TOTAL</th>
										<th>N° FACTURAS</th>
									</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
                            <!--<div class="row">
									<div class="text-right mr-lg">
										<?php
											echo '
												<div class="text-right mr-lg">
													<a href="javascript:void(0)">
														<img id="printRptVentasDep" src="'.base_url().'assets/img/pdf.png" alt="printPDF" />
													</a>
											</div>
											';
										?>
									</div>
							</div>-->
						</div>
					</div>
				</div>
		</section>
	</div>
</div>