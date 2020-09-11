<section class="body">

	<!-- start: header -->
	<header class="header">
		<div class="logo-container">
			<a href="#" class="logo">
				<img src="<?php echo base_url()?>assets/img/LOGOS_DELMOR.png" style="width:100px; vertical-align:middle; margin-top:-2vh;" alt="4BUY" />
			</a>

			<div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html"
			 data-fire-event="sidebar-left-opened">
				<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
			</div>
		</div>

		<!-- start: search & user box -->
		<div class="header-left alternative-font">
			<h2>SISTEMA DE VENTAS</h2>
		</div>
		<div class="header-right">

			<!--<form action="pages-search-results.html" class="search nav-form">
						<div class="input-group input-search">
							<input type="text" class="form-control" name="q" id="q" placeholder="Search...">
							<span class="input-group-btn">
								<button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
							</span>
						</div>
			</form> -->

			<span class="separator"></span>

			<ul class="notifications">
				<!--<li>
					<a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
						<i class="fa fa-tasks"></i>
						<span class="badge">12</span>
					</a>

					<div class="dropdown-menu notification-menu large">
						<div class="notification-title">
							<span class="pull-right label label-default">3</span>
							Tasks
						</div>

						<div class="content">
							<ul>
								<li>
									<p class="clearfix mb-xs">
										<span class="message pull-left">Generating Sales Report</span>
										<span class="message pull-right text-dark">60%</span>
									</p>
									<div class="progress progress-xs light">
										<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;"></div>
									</div>
								</li>

								<li>
									<p class="clearfix mb-xs">
										<span class="message pull-left">Importing Contacts</span>
										<span class="message pull-right text-dark">98%</span>
									</p>
									<div class="progress progress-xs light">
										<div class="progress-bar" role="progressbar" aria-valuenow="98" aria-valuemin="0" aria-valuemax="100" style="width: 98%;"></div>
									</div>
								</li>

								<li>
									<p class="clearfix mb-xs">
										<span class="message pull-left">Uploading something big</span>
										<span class="message pull-right text-dark">33%</span>
									</p>
									<div class="progress progress-xs light mb-xs">
										<div class="progress-bar" role="progressbar" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100" style="width: 33%;"></div>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</li> -->
				<!-- <li>
					<a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
						<i class="fa fa-envelope"></i>
						<span class="badge">4</span>
					</a>

					<div class="dropdown-menu notification-menu">
						<div class="notification-title">
							<span class="pull-right label label-default">230</span>
							Messages
						</div>

						<div class="content">
							<ul>
								<li>
									<a href="#" class="clearfix">
										<figure class="image">
											<img src="" alt="Joseph Doe Junior" class="img-circle" />
										</figure>
										<span class="title">Joseph Doe</span>
										<span class="message">Lorem ipsum dolor sit.</span>
									</a>
								</li>
								<li>
									<a href="#" class="clearfix">
										<figure class="image">
											<img src="" alt="Joseph Junior" class="img-circle" />
										</figure>
										<span class="title">Joseph Junior</span>
										<span class="message truncate">Truncated message. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
											Donec sit amet lacinia orci. Proin vestibulum eget risus non luctus. Nunc cursus lacinia lacinia. Nulla
											molestie malesuada est ac tincidunt. Quisque eget convallis diam, nec venenatis risus. Vestibulum blandit
											faucibus est et malesuada. Sed interdum cursus dui nec venenatis. Pellentesque non nisi lobortis, rutrum
											eros ut, convallis nisi. Sed tellus turpis, dignissim sit amet tristique quis, pretium id est. Sed aliquam
											diam diam, sit amet faucibus tellus ultricies eu. Aliquam lacinia nibh a metus bibendum, eu commodo eros
											commodo. Sed commodo molestie elit, a molestie lacus porttitor id. Donec facilisis varius sapien, ac
											fringilla velit porttitor et. Nam tincidunt gravida dui, sed pharetra odio pharetra nec. Duis consectetur
											venenatis pharetra. Vestibulum egestas nisi quis elementum elementum.</span>
									</a>
								</li>
								<li>
									<a href="#" class="clearfix">
										<figure class="image">
											<img src="" alt="Joe Junior" class="img-circle" />
										</figure>
										<span class="title">Joe Junior</span>
										<span class="message">Lorem ipsum dolor sit.</span>
									</a>
								</li>
								<li>
									<a href="#" class="clearfix">
										<figure class="image">
											<img src="" alt="Joseph Junior" class="img-circle" />
										</figure>
										<span class="title">Joseph Junior</span>
										<span class="message">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sit amet lacinia orci.
											Proin vestibulum eget risus non luctus. Nunc cursus lacinia lacinia. Nulla molestie malesuada est ac
											tincidunt. Quisque eget convallis diam.</span>
									</a>
								</li>
							</ul>

							<hr />

							<div class="text-right">
								<a href="#" class="view-more">Ver todos</a>
							</div>
						</div>
					</div>
				</li> -->
				<li>
					<a href="#" id="notBell" class="dropdown-toggle notification-icon" data-toggle="dropdown">
						<i class="fa fa-bell"></i>
						<span class="badge" id="notificaciones"></span>
					</a>

					<div class="dropdown-menu notification-menu">
						<div class="notification-title">
							<span class="pull-right label label-default" id="contadorNotif"></span>
							Notificaciones
						</div>

						<div class="content">
							<ul>
								<li style="display: none;" id="liFacturas">
									<a href="<?php echo base_url("index.php/Facturas")?>" class="clearfix">
										<div class="image">
											<i class="fa fa-gears bg-primary"></i>
										</div>
										<span class="title" id="encFac"></span>
										<span class="message" id="messageFact"></span>
									</a>
								</li>
								<li style="display: none;" id="lianulaFacturas">
									<a href="<?php echo base_url("index.php/Anulaciones")?>" class="clearfix">
										<div class="image">
											<i class="fa fa-trash-o bg-danger"></i>
										</div>
										<span class="title" id="encAnulFact"></span>
										<span class="message" id="messageAnulFact"></span>
									</a>
								</li>
								<!-- <li>
									<a href="#" class="clearfix">
										<div class="image">
											<i class="fa fa-signal bg-success"></i>
										</div>
										<span class="title">Connection Restaured</span>
										<span class="message">10/10/2014</span>
									</a>
								</li> -->
							</ul>

							<!--<hr />

							<div class="text-right">
								<a href="#" class="view-more">Ver todos</a>
							</div>-->
						</div>
					</div>
				</li>
			</ul>

			<span class="separator"></span>

			<div id="userbox" class="userbox">
				<a href="#" data-toggle="dropdown">
					<figure class="profile-picture">
						<?php
						$img = '';
						if ($this->session->userdata("Sexo") == 1) {
							$img = 'user2.png';
						}else{
							$img = 'female.jpg';
						}
						 echo "<img src='".base_url()."/assets/img/".$img."' alt='".$this->session->userdata('User')."'
						  class='img-circle' data-lock-picture='".base_url()."/assets/img/".$img."'/>";
					?>
					</figure>
					<div class="profile-info" data-lock-name="John Doe" data-lock-email="johndoe@JSOFT.com">
						<span class="name"><?php echo $this->session->userdata("User")?></span>
						<span class="role"><?php echo $this->session->userdata("Rol")?></span>
					</div>

					<i class="fa custom-caret"></i>
				</a>

				<div class="dropdown-menu">
					<ul class="list-unstyled">
						<li class="divider"></li>
						<li>
							<a role="menuitem" tabindex="-1" href="<?php echo base_url("index.php/Perfil")?>"><i class="fa fa-user"></i> Mi Perfil</a>
						</li>
						<!-- <li>
							<a role="menuitem" tabindex="-1" href="#" data-lock-screen="true"><i class="fa fa-lock"></i> Lock Screen</a>
						</li> -->
						<li>
							<a role="menuitem" tabindex="-1" href="<?php echo base_url("index.php/LogOut")?>">
							<i class="fa fa-power-off"></i> Finalizar Sesión</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- end: search & user box -->
	</header>
	<!-- end: header -->

	<div class="inner-wrapper">
		<!-- start: sidebar -->
		<aside id="sidebar-left" class="sidebar-left">

			<div class="sidebar-header">
				<div class="sidebar-title" style="color:white;">
					Menú
				</div>
				<div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
					<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
				</div>
			</div>

			<div class="nano">
				<div class="nano-content">
					<nav id="menu" class="nav-main" role="navigation">
						<ul class="nav nav-main">
							<!--<li class="nav-parent1">
								<a href="index.html">
									<i class="fa fa-home" aria-hidden="true"></i>
									<span>Dashboard</span>
								</a>
							</li>-->
							<?php
								if ($this->session->userdata("IdRol") == 1 || $this->session->userdata("IdRol") == 2) {
								   echo '
									<li class="nav-parent1">
										<a href="'.base_url("index.php/LogsView").'">
											<!--<span class="pull-right label label-primary">182</span>-->
											<i class="fa fa-book" aria-hidden="true"></i>
											<span>Ver Logs</span>
										</a>
									</li>
								   ';
								}
							?>
							<li class="nav-parent">
								<a>
									<i class="fa fa-key" aria-hidden="true"></i>
									<span>Autorizaciones</span>
								</a>
								<ul class="nav nav-children">
									<li>
                                       <a href="<?php echo base_url("index.php/Autorizaciones")?>">
                                           Crear permisos
                                        </a>
										<a href="<?php echo base_url("index.php/Asignar_Permiso")?>">
											Asignar permisos
                                        </a>
									</li>
								</ul>
							</li>
							<li class="nav-parent">
								<a>
									<i class="fa fa-user" aria-hidden="true"></i>
									<span>Usuarios</span>
								</a>
								<ul class="nav nav-children">
									<li>
                                       <a href="<?php echo base_url("index.php/Perfil")?>">
                                           Mi Perfil
                                        </a>
										<a href="<?php echo base_url("index.php/Usuarios")?>">
											Crear y Administrar Usuarios
                                        </a>
                                        <a href="<?php echo base_url("index.php/Roles")?>">
                                            Roles
                                        </a>
									</li>
								</ul>
							</li>
							<li class="nav-parent">
								<a>
									<i class="fa fa-cubes" aria-hidden="true"></i>
									<span>Inventario</span>
								</a>
								<ul class="nav nav-children">
									<li>
										<a href="<?php echo base_url("index.php/Inventario")?>">
											Inventario Diario
										</a>
									</li>
								</ul>
							</li>
							<li class="nav-parent">
								<a>
									<i class="fa fa-truck" aria-hidden="true"></i>
									<span>Ventas</span>
								</a>
								<ul class="nav nav-children">
									<li>
										<a href="<?php echo base_url("index.php/vendedores")?>">
											Asignacion Vendedores
										</a>
									</li>
									<li class="nav-parent">
										<a>Remisiones</a>
										<ul class="nav nav-children">
											<li>
												<a href="<?php echo base_url("index.php/Inventario")?>">Inventario</a>
											</li>
											<li>
												<a href="<?php echo base_url("index.php/Remisiones")?>">Nueva Remisión</a>
											</li>
											<li>
												<a href="<?php echo base_url("index.php/ListaRemision")?>">Lista de Remisiones</a>
											</li>
										</ul>
									</li>
									<li>
										<a href="<?php echo base_url("index.php/Facturas")?>">
											Facturas
										</a>
									</li>
									<li>
										<a href="<?php echo base_url("index.php/Anulaciones")?>">
											Solicitud Anulación
										</a>
									</li>
									<li>
										<a href="<?php echo base_url("index.php/Traslados")?>">
											Traslados
										</a>
									</li>
								</ul>
							</li>
							<li class="nav-parent">
								<a>
									<i class="fa fa-database" aria-hidden="true"></i>
									<span>Integracion</span>
								</a>
								<ul class="nav nav-children">
									<li>
										<a href="<?php echo base_url('index.php/Integracion')?>">
											Integrar facturas
										</a>
									</li>
								</ul>
							</li>
							<li class="nav-parent">
								<a>
									<i class="fa fa-gavel" aria-hidden="true"></i>
									<span>Liquidacion</span>
								</a>
								<ul class="nav nav-children">
									<li>
										<a href="<?php echo base_url("index.php/PeriodoLiq")?>">
											Periodo de Liquidacion
										</a>
									</li>
								</ul>
							</li>
							<li class="nav-parent">
								<a>
									<i class="fa fa-flag-checkered" aria-hidden="true"></i>
									<span>Cuotas</span>
								</a>
								<ul class="nav nav-children">
									<li>
										<a href="<?php echo base_url("index.php/Cuotas")?>">
											Cuotas actual
										</a>
									</li>
									<li>
										<a href="<?php echo base_url("index.php/ListaCuotas")?>">
											Lista de Cuotas
										</a>
									</li> 
								</ul>
							</li>
							<li class="nav-parent">
								<a>
									<i class="fa fa-bar-chart-o" aria-hidden="true"></i>
									<span>Reportes</span>
								</a>
								<ul class="nav nav-children">
									<li>
										<a href="<?php echo base_url("index.php/ConsolidadoRem")?>">
											Consolidado Remisiones
										</a>
									</li>
									<li>
										<a href="<?php echo base_url("index.php/Reporte_Remisiones")?>">
											Reporte Remisiones
										</a>
									</li>
									<li>
										<a href="<?php echo base_url("index.php/rptventas")?>">
											Reporte de Ventas
										</a>
									</li>
									<li>
										<a href="<?php echo base_url("index.php/VentasDepositos")?>">
											Reporte de Ventas para depósito
										</a>
									</li>
									<li>
										<a href="<?php echo base_url("index.php/rptDevolucionesRuta")?>">
											Reporte de Devoluciones por ruta
										</a>
									</li>
									<li>
										<a href="<?php echo base_url("index.php/rptconsecutivos")?>">
											Reporte de Consecutivos
										</a>
									</li>
									<li>
										<a href="<?php echo base_url("index.php/Reporte_Merma")?>">
											Reporte de Mermas
										</a>
									</li>
								</ul>
							</li>

							<br>
							<li>
								<!--<img src="<?php echo base_url()?>assets/img/LOGOS_DELMOR.png" style="text-align: center;width:100%; vertical-align:middle; margin-top:-4vh;"
								alt="4BUY" />-->
							</li>
						</ul>
						<br><br>
						<!--<div class="center">

						</div>-->
					</nav>

		</aside>
		<!-- end: sidebar -->
		<section role="main" class="content-body">
			<header class="page-header">
				<h2>
					<?php echo $this->uri->segment(1)?>
				</h2>

				<div class="right-wrapper pull-right">
					<ol class="breadcrumbs">
						<li>
							<a href="index.html">
								<i class="fa fa-home"></i>
							</a>
						</li>
						<li><span>
								<?php echo $this->uri->segment(1)?></span></li>
					</ol>

					<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
				</div>
			</header>
