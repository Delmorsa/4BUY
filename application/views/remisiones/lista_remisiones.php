<?php
/**
 * Created by Cesar Calderón.
 * User: Sistemas
 * Date: 28/12/2018
 * Time: 15:26
 */
?>
<div class="row">
	<div class='col-9 col-sm-9 col-md-9'>
		<h2 class="h2 mt-none mb-sm ">Lista de Remisiones</h2>
	</div>
</div>
<div class="row">
	<div class=" col-12 col-sm-12 col-md-12 col-lg-12">
		<div class="tabs tabs-danger">
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#ordenes" class="text-muted" data-toggle="tab">Ordenes</a>
				</li>
				<li>
					<a href="#preventas" class="text-muted" data-toggle="tab">Preventas</a>
				</li>
				<li>
					<a href="#recargos" class="text-muted" data-toggle="tab">Recargos</a>
				</li>
				<li>
					<a href="#adelantos" class="text-muted" data-toggle="tab">Adelantos</a>
				</li>
                <li>
                    <a href="#walmart" class="text-muted" data-toggle="tab">Walmart</a>
                </li>
                <li>
                <a href="#casamant" class="text-muted" data-toggle="tab">Casa Mantica</a>
                </li>
                <li>
                <a href="#independiente" class="text-muted" data-toggle="tab">Independiente</a>
                </li>
			</ul>
			<div class="tab-content">
				<div id="ordenes" class="tab-pane active">
					<table class="table compact table-responsive table-bordered table-striped mb-none table-sm" id="tblOrdenesList">
						<thead>
						<tr>
							<th>Cod Rem.</th>
							<th>Fec. Entrega</th>
							<th>Cantidad</th>
							<th>Cant. LBS</th>
							<th>Fec. Liquida.</th>
							<th>Fec. Registro</th>
							<th>Fec. Edicion</th>
							<th>Fec. Baja</th>
							<th>Estado</th>
							<th>Detalles</th>
						</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
				<div id="preventas" class="tab-pane">
					<table class="table compact table-responsive table-bordered table-striped mb-none table-sm" id="tblPreventasList">
						<thead>
						<tr>
							<th>Cod Rem.</th>
							<th>Fec. Entrega</th>
							<th>Cantidad</th>
							<th>Cant. LBS</th>
							<th>Fec. Liq.</th>
							<th>Fec. Registro</th>
							<th>Fec. Edicion</th>
							<th>Fec. Baja</th>
							<th>Estado</th>
							<th>Detalles</th>
						</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
				<div id="recargos" class="tab-pane">
					<table class="table compact table-responsive table-bordered table-striped mb-none table-sm" id="tblRecargosList">
						<thead>
						<tr>
							<th>Cod Rem.</th>
							<th>Fec. Entrega</th>
							<th>Cantidad</th>
							<th>Cant. LBS</th>
							<th>Fec. Liq.</th>
							<th>Fec. Registro</th>
							<th>Fec. Edicion</th>
							<th>Fec. Baja</th>
							<th>Estado</th>
							<th>Detalles</th>
						</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
				<div id="adelantos" class="tab-pane">
					<table class="table compact table-responsive table-bordered table-striped mb-none table-sm" id="tblAdelantosList">
						<thead>
						<tr>
							<th>Cod Rem.</th>
							<th>Fec. Entrega</th>
							<th>Cantidad</th>
							<th>Cant. LBS</th>
							<th>Fec. Liq.</th>
							<th>Fec. Registro</th>
							<th>Fec. Edicion</th>
							<th>Fec. Baja</th>
							<th>Estado</th>
							<th>Detalles</th>
						</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
                <div id="walmart" class="tab-pane">
                    <table class="table compact table-responsive table-bordered table-striped mb-none table-sm" id="tblwalmartList">
                        <thead>
                        <tr>
                            <th>Cod Rem.</th>
                            <th>Fec. Entrega</th>
                            <th>Cantidad</th>
                            <th>Cant. LBS</th>
                            <th>Fec. Liq.</th>
                            <th>Fec. Registro</th>
                            <th>Fec. Edicion</th>
                            <th>Fec. Baja</th>
                            <th>Estado</th>
                            <th>Detalles</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div id="casamant" class="tab-pane">
                    <table class="table compact table-responsive table-bordered table-striped mb-none table-sm" id="tblmanticaList">
                        <thead>
                        <tr>
                            <th>Cod Rem.</th>
                            <th>Fec. Entrega</th>
                            <th>Cantidad</th>
                            <th>Cant. LBS</th>
                            <th>Fec. Liq.</th>
                            <th>Fec. Registro</th>
                            <th>Fec. Edicion</th>
                            <th>Fec. Baja</th>
                            <th>Estado</th>
                            <th>Detalles</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div id="independiente" class="tab-pane">
                    <table class="table compact table-responsive table-bordered table-striped mb-none table-sm" id="tblindepenList">
                        <thead>
                        <tr>
                            <th>Cod Rem.</th>
                            <th>Fec. Entrega</th>
                            <th>Cantidad</th>
                            <th>Cant. LBS</th>
                            <th>Fec. Liq.</th>
                            <th>Fec. Registro</th>
                            <th>Fec. Edicion</th>
                            <th>Fec. Baja</th>
                            <th>Estado</th>
                            <th>Detalles</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
			</div>
		</div>
	</div>
</div>
