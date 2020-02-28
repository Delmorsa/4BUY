<div class="row">
	<div class='col-9 col-sm-9 col-md-9'>
		<h2 class="h2 mt-none mb-sm ">Nuevo Inventario</h2>
	</div>
	<div class="col-3 col-sm-3 col-md-3 pull-right">
	    <button id="btnSaveInv" class="mb-xs mt-xs mr-xs btn btn-primary pull-right" data-toggle="tooltip" data-placement="left" title="" data-original-title="">
					   Subir Inventario <i class="fa fa-download"></i>
		</button>
		</div>
</div>
<!-- start: page -->
<div class="row">
	<div class="col-12 col-sm-12 col-md-12">
		<section class="panel">
			<div class="panel-body">
				<div class="invoice">
					<br>

					<div class="row">
						<div class="form-group">
							<h5 class="titulosGen"><span><img id="printConsecutivosTodosExcel" src="<?php echo base_url()?>assets/img/excel.png" alt="printExcel" /></span> Cargar Excel Inventario</h5>
							<div class="col-md-6">
								<div class="fileupload fileupload-new" data-provides="fileupload"><input type="hidden">
								<div class="input-append">
									<div class="uneditable-input">
										<i class="fa fa-file fileupload-exists"></i>
										<span class="fileupload-preview"></span>
									</div>
									<span class="btn btn-primary btn-file">
										<span class="fileupload-exists">Cambiar</span>
										<span class="fileupload-new">Seleccionar archivo</span>
										<input type="file" id="fileUpload" name="fileSelect">
									</span>
									<a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">Remover</a>
								</div>
							</div>
						</div>
					</div>
					</div>
					<hr class="dotted">

					<div class="row"> <!--tblRemisiones-->
						<div class="col-12 col-sm-12 col-md-12">
							<div id="wrapper">
								<table id="tblInventario" class="table table-condensed table-bordered table-responsive table-striped mb-none table-sm">
								<thead>
									<tr>
										<th>CODIGO</th>
										<th>DESCRIPCION</th>
										<th>GRM</th>
										<th>UND/LBRS</th>
										<th>LIBRAS</th>
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
		</section>
	</div>
</div>

<div class="row">

</div>

<div class="row">

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
