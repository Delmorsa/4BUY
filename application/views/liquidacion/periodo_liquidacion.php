<div class="row">
    <div class='col-12 col-sm-12 col-md-12'>
        <div class="pull-right">
            <button id="newperidobtn" data-toggle="modal" class="mb-xs mt-xs mr-xs btn btn-primary">
                Agregar <i class="fa fa-plus"></i>
            </button>
        </div>
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

                <h2 class="panel-title">Períodos de Liquidación</h2>
            </header>
            <div class="panel-body">
                <div class="tabs tabs-danger">
                    <ul class="nav nav-tabs tabs-primary">
                        <li class="active">
                            <a id="sinliquidar" class="text-muted" href="#SinLiquidar" data-toggle="tab"
                                aria-expanded="true">Sin Liquidar</a>
                        </li>
                        <li class="">
                            <a id="Liqpendientes" class="text-muted" href="#Pendientes" data-toggle="tab"
                                aria-expanded="true">Pendientes</a>
                        </li>
                        <li class="">
                            <a id="liquidados" class="text-muted" href="#Liquidados" data-toggle="tab"
                                aria-expanded="true">Liquidados</a>
                        </li>
                        <li class="">
                            <a id="liqanuladas" class="text-muted" href="#Anuladas" data-toggle="tab"
                                aria-expanded="true">Anuladas</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="SinLiquidar" class="tab-pane active">
                            <table class="table table-bordered table-striped mb-none table-sm table-condensed"
                                id="datatable">
                                <thead>
                                    <tr>
                                        <th>Inicia</th>
                                        <th>Finaliza</th>
                                        <th>Ruta</th>
                                        <th>Creado por</th>
                                        <th>Fecha registro</th>
                                        <th>Estado</th>
                                        <th>Liquidado</th>
                                        <th>Fecha liquidacion</th>
                                        <th>Liquidado por</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
									/*$estado = '';
									$liquidado = '';
									if (!$lista) {
									} else {
										foreach ($lista as $key) {
											$fechaIn = explode(" ", $key["FechaInicio"]);
											$fechaFin = explode(" ", $key["FechaFinal"]);
											$key["FechaLiquidacion"] = ($key["FechaLiquidacion"] == null) ? '' : date_format(new DateTime($key["FechaLiquidacion"]), "Y-m-d H:i:s");
											switch (strval($key["Activo"])) {
												case "Y":
													$estado = "<p class='text-success center'>Activo</p>";
													break;
												case "N":
													$estado = "<p class='text-danger center'>Cerrado</p>";
													break;
												case "C":
													$estado = "<p class='text-danger center'>Anulada</p>";
													break;
												default:
													$estado = "<p class='text-warning center'>Pendiente</p>";
													break;
											}
											switch (strval($key["Liquidado"])) {
												case "N":
													$liquidado = "<p class='text-default center'>Sin liquidar</p>";
													break;
												default:
													$liquidado = "<p class='text-success center'>Liquidado</p>";
													break;
											}
											echo "
										<tr>
										   <td>" . date_format(new DateTime($key["FechaInicio"]), "Y-m-d H:i") . "</td>
										   <td>" . date_format(new DateTime($key["FechaFinal"]), "Y-m-d H:i") . "</td>
										   <td>" . $key["IdRuta"] . "</td>
										   <td>" . $key["Nombre"] . "</td>
										   <td>" . date_format(new DateTime($key["FechaCrea"]), "Y-m-d H:i:s") . "</td>
										   <td>" . $estado . "</td>
										   <td>" . $liquidado . "</td>
										   <td>" . $key["FechaLiquidacion"] . "</td>
										   <td>" . $key["NomLiquidador"] . "</td>
										   <td class='center'>";
											if ($key["Activo"] == "Y" && $key["Liquidado"] == "N") {
												echo "
										     	<a href='Liquidacion/" . $key["IdPeriodo"] . "' data-toggle='tooltip' title='Liquidar' data-placement='top'
										     	 class='btn btn-xs btn-info'><i class='fa fa-gavel'></i></a> 
										     	 
										     	<a href='javascript:void(0)' data-toggle='tooltip' title='Editar' data-placement='top'
										     	 onclick='editar(" . '"' . $key["IdPeriodo"] . '","' . $key["IdRuta"] . '","' . $fechaIn[0] . '","' . $fechaFin[0] . '",
										     	 "' . date_format(new DateTime($fechaIn[1]), "H:i") . '","' . date_format(new DateTime($fechaFin[1]), "H:i") . '","' . $key["Activo"] . '"' . ")' 
										     	class='btn btn-xs btn-primary'><i class='fa fa-edit'></i></a>
										     	
										     	<a href='javascript:void(0)' onclick='AnularPeriodo(" . '"' . $key["IdPeriodo"] . '"' . ")' data-toggle='tooltip' title='Anular' data-placement='top'
										     	 class='btn btn-xs btn-danger'><i class='fa fa-trash-o'></i></a> 
										     	";
											} elseif ($key["Activo"] == "P" && $key["Liquidado"] == "N") {
												echo "
										     	<a href='Liquidacion/" . $key["IdPeriodo"] . "' data-toggle='tooltip' title='Liquidar' data-placement='top'
										     	 class='btn btn-xs btn-info'><i class='fa fa-gavel'></i></a> 
										     	 
										     	";
											} else {
												echo "
												<a href='Liquidacion/" . $key["IdPeriodo"] . "' data-toggle='tooltip' title='Detalles'
												 data-placement='top'
										     	 class='btn btn-xs btn-primary'><i class='fa fa-eye'></i></a>";
											}
											echo "</td>
										</tr>
									";
										}
									}*/
									?>
                                </tbody>
                            </table>
                        </div>
                        <div id="Pendientes" class="tab-pane">
                            <table class="table table-bordered table-striped mb-none table-sm table-condensed"
                                id="datatablePend">
                                <thead>
                                    <tr>
                                        <th>Inicia</th>
                                        <th>Finaliza</th>
                                        <th>Ruta</th>
                                        <th>Creado por</th>
                                        <th>Fecha registro</th>
                                        <th>Estado</th>
                                        <th>Liquidado</th>
                                        <th>Fecha liquidacion</th>
                                        <th>Liquidado por</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
									/*$estado = '';
									$liquidado = '';
									if (!$pend) {
									} else {
										foreach ($pend as $key) {
											$fechaIn = explode(" ", $key["FechaInicio"]);
											$fechaFin = explode(" ", $key["FechaFinal"]);
											$key["FechaLiquidacion"] = ($key["FechaLiquidacion"] == null) ? '' : date_format(new DateTime($key["FechaLiquidacion"]), "Y-m-d H:i:s");
											switch (strval($key["Activo"])) {
												case "Y":
													$estado = "<p class='text-success center'>Activo</p>";
													break;
												case "N":
													$estado = "<p class='text-danger center'>Cerrado</p>";
													break;
												case "C":
													$estado = "<p class='text-danger center'>Anulada</p>";
													break;
												default:
													$estado = "<p class='text-warning center'>Pendiente</p>";
													break;
											}
											switch (strval($key["Liquidado"])) {
												case "N":
													$liquidado = "<p class='text-default center'>Sin liquidar</p>";
													break;
												default:
													$liquidado = "<p class='text-success center'>Liquidado</p>";
													break;
											}
											echo "
										<tr>
										   <td>" . date_format(new DateTime($key["FechaInicio"]), "Y-m-d H:i") . "</td>
										   <td>" . date_format(new DateTime($key["FechaFinal"]), "Y-m-d H:i") . "</td>
										   <td>" . $key["IdRuta"] . "</td>
										   <td>" . $key["Nombre"] . "</td>
										   <td>" . date_format(new DateTime($key["FechaCrea"]), "Y-m-d H:i:s") . "</td>
										   <td>" . $estado . "</td>
										   <td>" . $liquidado . "</td>
										   <td>" . $key["FechaLiquidacion"] . "</td>
										   <td>" . $key["NomLiquidador"] . "</td>
										   <td class='center'>";
											if ($key["Activo"] == "Y" && $key["Liquidado"] == "N") {
												echo "
										     	<a href='Liquidacion/" . $key["IdPeriodo"] . "' data-toggle='tooltip' title='Liquidar' data-placement='top'
										     	 class='btn btn-xs btn-info'><i class='fa fa-gavel'></i></a> 
										     	 
										     	<a href='javascript:void(0)' data-toggle='tooltip' title='Editar' data-placement='top'
										     	 onclick='editar(" . '"' . $key["IdPeriodo"] . '","' . $key["IdRuta"] . '","' . $fechaIn[0] . '","' . $fechaFin[0] . '",
										     	 "' . date_format(new DateTime($fechaIn[1]), "H:i") . '","' . date_format(new DateTime($fechaFin[1]), "H:i") . '","' . $key["Activo"] . '"' . ")' 
										     	class='btn btn-xs btn-primary'><i class='fa fa-edit'></i></a>
										     	
										     	<a href='javascript:void(0)' onclick='AnularPeriodo(" . '"' . $key["IdPeriodo"] . '"' . ")' data-toggle='tooltip' title='Anular' data-placement='top'
										     	 class='btn btn-xs btn-danger'><i class='fa fa-trash-o'></i></a> 
										     	";
											} elseif ($key["Activo"] == "P" && $key["Liquidado"] == "N") {
												echo "
										     	<a href='Liquidacion/" . $key["IdPeriodo"] . "' data-toggle='tooltip' title='Liquidar' data-placement='top'
										     	 class='btn btn-xs btn-info'><i class='fa fa-gavel'></i></a> 
										     	 
										     	";
											} else {
												echo "
												<a href='Liquidacion/" . $key["IdPeriodo"] . "' data-toggle='tooltip' title='Detalles'
												 data-placement='top'
										     	 class='btn btn-xs btn-primary'><i class='fa fa-eye'></i></a>";
											}
											echo "</td>
										</tr>
									";
										}
									}*/
									?>
                                </tbody>
                            </table>
                        </div>
                        <div id="Liquidados" class="tab-pane">
                            <div class="row">
                                <div class="col-3 col-sm-3 col-md-3 col-lg-3 pull-right">
                                    <div class="form-group">
                                        <label class="col-md-6 control-label" for="rutasFilter">Rutas</label>
                                        <div class="input-group input-group-icon">
                                            <span class="input-group-addon">
                                                <span class="icon"></span>
                                            </span>
                                            <select id="rutasFilter" data-plugin-selectTwo
                                                class="form-control col12 col-md-12 col-sm-12 populate">
                                                <option selected></option>
                                                <?php
												if (!$rutas) {
												} else {
													foreach ($rutas as $ruta) {
														echo '
													<option value=' . $ruta["IdRuta"] . '>' . $ruta["Ruta"] . '</option>
												';
													}
												}

												?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <table class="table table-bordered table-striped mb-none table-sm table-condensed"
                                id="datatableLiq">
                                <thead>
                                    <tr>
                                        <th>Inicia</th>
                                        <th>Finaliza</th>
                                        <th>Ruta</th>
                                        <th>Creado por</th>
                                        <th>Fecha registro</th>
                                        <th>Estado</th>
                                        <th>Liquidado</th>
                                        <th>Fecha liquidacion</th>
                                        <th>Liquidado por</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
									/*$estado = '';
									$liquidado = '';
									if (!$liq) {
									} else {
										foreach ($liq as $key) {
											$fechaIn = explode(" ", $key["FechaInicio"]);
											$fechaFin = explode(" ", $key["FechaFinal"]);
											$key["FechaLiquidacion"] = ($key["FechaLiquidacion"] == null) ? '' : date_format(new DateTime($key["FechaLiquidacion"]), "Y-m-d H:i:s");
											switch (strval($key["Activo"])) {
												case "Y":
													$estado = "<p class='text-success center'>Activo</p>";
													break;
												case "N":
													$estado = "<p class='text-danger center'>Cerrado</p>";
													break;
												case "C":
													$estado = "<p class='text-danger center'>Anulada</p>";
													break;
												default:
													$estado = "<p class='text-warning center'>Pendiente</p>";
													break;
											}
											switch (strval($key["Liquidado"])) {
												case "N":
													$liquidado = "<p class='text-default center'>Sin liquidar</p>";
													break;
												default:
													$liquidado = "<p class='text-success center'>Liquidado</p>";
													break;
											}
											echo "
										<tr>
										   <td>" . date_format(new DateTime($key["FechaInicio"]), "Y-m-d H:i") . "</td>
										   <td>" . date_format(new DateTime($key["FechaFinal"]), "Y-m-d H:i") . "</td>
										   <td>" . $key["IdRuta"] . "</td>
										   <td>" . $key["Nombre"] . "</td>
										   <td>" . date_format(new DateTime($key["FechaCrea"]), "Y-m-d H:i:s") . "</td>
										   <td>" . $estado . "</td>
										   <td>" . $liquidado . "</td>
										   <td>" . $key["FechaLiquidacion"] . "</td>
										   <td>" . $key["NomLiquidador"] . "</td>
										   <td class='center'>";
											if ($key["Activo"] == "Y" && $key["Liquidado"] == "N") {
												echo "
										     	<a href='Liquidacion/" . $key["IdPeriodo"] . "' data-toggle='tooltip' title='Liquidar' data-placement='top'
										     	 class='btn btn-xs btn-info'><i class='fa fa-gavel'></i></a> 
										     	 
										     	<a href='javascript:void(0)' data-toggle='tooltip' title='Editar' data-placement='top'
										     	 onclick='editar(" . '"' . $key["IdPeriodo"] . '","' . $key["IdRuta"] . '","' . $fechaIn[0] . '","' . $fechaFin[0] . '",
										     	 "' . date_format(new DateTime($fechaIn[1]), "H:i") . '","' . date_format(new DateTime($fechaFin[1]), "H:i") . '","' . $key["Activo"] . '"' . ")' 
										     	class='btn btn-xs btn-primary'><i class='fa fa-edit'></i></a>
										     	
										     	<a href='javascript:void(0)' onclick='AnularPeriodo(" . '"' . $key["IdPeriodo"] . '"' . ")' data-toggle='tooltip' title='Anular' data-placement='top'
										     	 class='btn btn-xs btn-danger'><i class='fa fa-trash-o'></i></a> 
										     	";
											} elseif ($key["Activo"] == "P" && $key["Liquidado"] == "N") {
												echo "
										     	<a href='Detalleliquidacion/" . $key["IdPeriodo"] . "' data-toggle='tooltip' title='Liquidar' data-placement='top'
										     	 class='btn btn-xs btn-info'><i class='fa fa-gavel'></i></a> 
										     	 
										     	";
											} else {
												echo "
												<a href='Detalleliquidacion/" . $key["IdPeriodo"] . "' data-toggle='tooltip' title='Detalles'
												 data-placement='top'
										     	 class='btn btn-xs btn-primary'><i class='fa fa-eye'></i></a>";
											}
											echo "</td>
										</tr>
									";
										}
									}*/
									?>
                                </tbody>
                            </table>
                        </div>
                        <div id="Anuladas" class="tab-pane">
                            <table class="table table-bordered table-striped mb-none table-sm table-condensed"
                                id="datatableLAnul">
                                <thead>
                                    <tr>
                                        <th>Inicia</th>
                                        <th>Finaliza</th>
                                        <th>Ruta</th>
                                        <th>Creado por</th>
                                        <th>Fecha registro</th>
                                        <th>Estado</th>
                                        <th>Liquidado</th>
                                        <th>Fecha liquidacion</th>
                                        <th>Liquidado por</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
									/*$estado = '';
									$liquidado = '';
									if (!$anul) {
									} else {
										foreach ($anul as $key) {
											$fechaIn = explode(" ", $key["FechaInicio"]);
											$fechaFin = explode(" ", $key["FechaFinal"]);
											$key["FechaLiquidacion"] = ($key["FechaLiquidacion"] == null) ? '' : date_format(new DateTime($key["FechaLiquidacion"]), "Y-m-d H:i:s");
											switch (strval($key["Activo"])) {
												case "Y":
													$estado = "<p class='text-success center'>Activo</p>";
													break;
												case "N":
													$estado = "<p class='text-danger center'>Cerrado</p>";
													break;
												case "C":
													$estado = "<p class='text-danger center'>Anulada</p>";
													break;
												default:
													$estado = "<p class='text-warning center'>Pendiente</p>";
													break;
											}
											switch (strval($key["Liquidado"])) {
												case "N":
													$liquidado = "<p class='text-default center'>Sin liquidar</p>";
													break;
												default:
													$liquidado = "<p class='text-success center'>Liquidado</p>";
													break;
											}
											echo "
										<tr>
										   <td>" . date_format(new DateTime($key["FechaInicio"]), "Y-m-d H:i") . "</td>
										   <td>" . date_format(new DateTime($key["FechaFinal"]), "Y-m-d H:i") . "</td>
										   <td>" . $key["IdRuta"] . "</td>
										   <td>" . $key["Nombre"] . "</td>
										   <td>" . date_format(new DateTime($key["FechaCrea"]), "Y-m-d H:i:s") . "</td>
										   <td>" . $estado . "</td>
										   <td>" . $liquidado . "</td>
										   <td>" . $key["FechaLiquidacion"] . "</td>
										   <td>" . $key["NomLiquidador"] . "</td>
										   <td class='center'>";
											if ($key["Activo"] == "Y" && $key["Liquidado"] == "N") {
												echo "
										     	<a href='Liquidacion/" . $key["IdPeriodo"] . "' data-toggle='tooltip' title='Liquidar' data-placement='top'
										     	 class='btn btn-xs btn-info'><i class='fa fa-gavel'></i></a> 
										     	 
										     	<a href='javascript:void(0)' data-toggle='tooltip' title='Editar' data-placement='top'
										     	 onclick='editar(" . '"' . $key["IdPeriodo"] . '","' . $key["IdRuta"] . '","' . $fechaIn[0] . '","' . $fechaFin[0] . '",
										     	 "' . date_format(new DateTime($fechaIn[1]), "H:i") . '","' . date_format(new DateTime($fechaFin[1]), "H:i") . '","' . $key["Activo"] . '"' . ")' 
										     	class='btn btn-xs btn-primary'><i class='fa fa-edit'></i></a>
										     	
										     	<a href='javascript:void(0)' onclick='AnularPeriodo(" . '"' . $key["IdPeriodo"] . '"' . ")' data-toggle='tooltip' title='Anular' data-placement='top'
										     	 class='btn btn-xs btn-danger'><i class='fa fa-trash-o'></i></a> 
										     	";
											} elseif ($key["Activo"] == "P" && $key["Liquidado"] == "N") {
												echo "
										     	<a href='Detalleliquidacion/" . $key["IdPeriodo"] . "' data-toggle='tooltip' title='Liquidar' data-placement='top'
										     	 class='btn btn-xs btn-info'><i class='fa fa-gavel'></i></a> 
										     	 
										     	";
											} else {
												echo "
												<a href='Detalleliquidacion/" . $key["IdPeriodo"] . "' data-toggle='tooltip' title='Detalles'
												 data-placement='top'
										     	 class='btn btn-xs btn-primary'><i class='fa fa-eye'></i></a>";
											}
											echo "</td>
										</tr>
									";
										}
									}*/
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

<div class="row">

</div>

<div class="row">

</div>

<div class="modal fade" id="pLiquidacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
    data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-gavel"> <span id="tituloModal"></span></i></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label class="col-md-6 control-label" for="Rutas">Rutas</label>
                            <div class="input-group input-group-icon">
                                <span class="input-group-addon">
                                    <span class="icon"></span>
                                </span>
                                <select id="Rutas" data-plugin-selectTwo
                                    class="form-control col12 col-md-12 col-sm-12 populate">
                                    <option selected></option>
                                    <?php
									if (!$rutas) {
									} else {
										foreach ($rutas as $ruta) {
											echo '
													<option value=' . $ruta["IdRuta"] . '>' . $ruta["Ruta"] . '</option>
												';
										}
									}

									?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-3 col-sm-3 col-md-3">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="fechaIn">Fecha Inicio</label>
                            <div class="input-group input-group-icon">
                                <span class="input-group-addon">
                                    <span class="icon"><i class="fa fa-calendar"></i></span>
                                </span>
                                <input autocomplete="off" type="text" data-plugin-datepicker=""
                                    data-plugin-skin="primary" id="fechaIn" class="form-control datepicker">
                                <input type="hidden" id="idperiod" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-3 col-sm-3 col-md-3">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="HoraIn">Hora Inicio</label>
                            <div class="input-group input-group-icon">
                                <span class="input-group-addon">
                                    <span class="icon"><i class="fa fa-clock-o"></i></span>
                                </span>
                                <input autocomplete="off" id="HoraIn" type="text" data-plugin-timepicker
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-3 col-sm-3 col-md-3">
                        <div class="form-group">
                            <label class="col-md-12 control-label" for="fechaFin">Fecha Finalizacion</label>
                            <div class="input-group input-group-icon">
                                <span class="input-group-addon">
                                    <span class="icon"><i class="fa fa-calendar"></i></span>
                                </span>
                                <input type="text" id="fechaFin" data-plugin-skin="primary" data-plugin-datepicker=""
                                    class="form-control datepicker">
                            </div>
                        </div>
                    </div>
                    <div class="col-3 col-sm-3 col-md-3">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="HoraFin">Hora Finalizacion</label>
                            <div class="input-group input-group-icon">
                                <span class="input-group-addon">
                                    <span class="icon"><i class="fa fa-clock-o"></i></span>
                                </span>
                                <input autocomplete="off" type="text" id="HoraFin" data-plugin-timepicker
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="display: none" id="rowEnEspera">
                    <br>
                    <div class="col-6 col-sm-6 col-md-6 pull-left">
                        <div class="form-group">
                            <div class="checkbox-custom checkbox-primary">
                                <input type="checkbox" id="EnEspera">
                                <label for="EnEspera">Poner periodo en espera</label>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 pull-right">
                        <button type="button" id="btnGuardar" class="btn btn-primary">Guardar</button>
                        <button type="button" id="btnActualizar" style="display: none"
                            class="btn btn-primary">Actualizar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="loading" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true"
    data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content" style="background-color:transparent;box-shadow: none; border: none;">
            <div class="text-center">
                <img width="130px" src="<?php echo base_url() ?>assets/img/loading.gif">
            </div>
        </div>
    </div>
</div>