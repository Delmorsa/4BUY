		<!-- start: page -->
		<section class="body-sign">
			<div class="center-sign">
				<a href="javascript:void(0)" class="logo pull-left">
					<img src="<?php echo base_url()?>assets/img/LOGOS_DELMOR.png" height="100" alt="DELMOR" />
				</a>

				<div class="panel panel-sign">
					<div class="panel-title-sign mt-xl text-right">
						<h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user mr-xs"></i> Iniciar Sesión</h2>
					</div>
					<div class="panel-body">
						<form action="<?php echo base_url('index.php/Login')?>" method="post">
							<div class="form-group mb-lg">
								<label>Nombre de Usuario</label>
								<div class="input-group input-group-icon">
									<input autocomplete="off" name="username" type="text" class="form-control input-lg" />
									<span class="input-group-addon">
										<span class="icon icon-lg">
											<i class="fa fa-user"></i>
										</span>
									</span>
								</div>
							</div>

							<div class="form-group mb-lg">
								<div class="clearfix">
									<label class="pull-left">Contraseña</label>
								</div>
								<div class="input-group input-group-icon">
									<input name="pwd" type="password" class="form-control input-lg" />
									<span class="input-group-addon">
										<span class="icon icon-lg">
											<i class="fa fa-lock"></i>
										</span>
									</span>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-12">
									<button type="submit" class="btn btn-primary btn-block btn-lg mt-lg">Acceder</button>
								</div>
							</div>
							<br>
							<!--<span class="mt-lg mb-lg line-thru text-center text-uppercase">
								<span>or</span>
							</span> -->
						</form>
					</div>
				</div>

				<p class="text-center text-semibold text-muted mt-md mb-md"> <!--DELMOR-->
					&copy; Copyright <?php echo date("Y")?> Todos los derechos reservados.</p>
			</div>
		</section>
		<!-- end: page -->
