<!-- end: page -->
<aside id="sidebar-right" class="sidebar-right">
    <div class="nano">
        <div class="nano-content">
            <a href="#" class="mobile-close visible-xs">
                Ocultar <i class="fa fa-chevron-right"></i>
            </a>

            <div class="sidebar-right-wrapper">

                <div class="sidebar-widget widget-calendar">
                    <h6>Upcoming Tasks</h6>
                    <div data-plugin-datepicker data-plugin-skin="dark"></div>

                    <ul>
                        <li>
                            <time datetime="2014-04-19T00:00+00:00">04/19/2014</time>
                            <span>Company Meeting</span>
                        </li>
                    </ul>
                </div>

                <div class="sidebar-widget widget-friends">
                    <h6>Friends</h6>
                    <ul>
                        <li class="status-online">
                            <figure class="profile-picture">
                                <img width="30px" src="<?php echo base_url()?>assets/img/user2.png" alt="Joseph Doe Junior" class="img-circle">
                            </figure>
                            <div class="profile-info">
                                <span class="name">Joseph Doe Junior</span>
                                <span class="title">Hey, how are you?</span>
                            </div>
                        </li>
                        <li class="status-online">
                            <figure class="profile-picture">
                                <img width="30px" src="<?php echo base_url()?>assets/img/user2.png" alt="Joseph Doe Junior" class="img-circle">
                            </figure>
                            <div class="profile-info">
                                <span class="name">Joseph Doe Junior</span>
                                <span class="title">Hey, how are you?</span>
                            </div>
                        </li>
                        <li class="status-offline">
                            <figure class="profile-picture">
                                <img width="30px" src="<?php echo base_url()?>assets/img/user2.png" alt="Joseph Doe Junior" class="img-circle">
                            </figure>
                            <div class="profile-info">
                                <span class="name">Joseph Doe Junior</span>
                                <span class="title">Hey, how are you?</span>
                            </div>
                        </li>
                        <li class="status-offline">
                            <figure class="profile-picture">
                                <img width="30px" src="<?php echo base_url()?>assets/img/user2.png" alt="Joseph Doe Junior" class="img-circle">
                            </figure>
                            <div class="profile-info">
                                <span class="name">Joseph Doe Junior</span>
                                <span class="title">Hey, how are you?</span>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</aside>
</section>
</section>


<script src="<?php echo base_url()?>assets/js/jquery.js"></script>
<script src="<?php echo base_url()?>assets/js/xlsx.full.min.js"></script>
<script src="<?php echo base_url()?>assets/js/bootstrap.js"></script>
<script src="<?php echo base_url()?>assets/js/sweetalert2.min.js"></script>
<script src="<?php echo base_url()?>assets/js/nanoscroller.js"></script>
<script src="<?php echo base_url()?>assets/js/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url()?>assets/js/bootstrap-datepicker.es.js"></script>
<script src="<?php echo base_url()?>assets/js/bootstrap-timepicker.js"></script>
<script src="<?php echo base_url()?>assets/js/select2.js"></script>
<script src="<?php echo base_url()?>assets/js/jquery.numeric.js"></script>

<script src="<?php echo base_url()?>assets/js/jquery.dataTables.js"></script>
<script src="<?php echo base_url()?>assets/js/datatables.js"></script>	
<script src="<?php echo base_url()?>assets/js/dataTables.fixedColumns.js"></script>  
<script src="<?php echo base_url()?>assets/js/autosize.js"></script>
<script src="<?php echo base_url()?>assets/js/pnotify.custom.js"></script>



<script src="<?php echo base_url()?>assets/js/jstree.js"></script>

<script src="<?php echo base_url()?>assets/js/bootstrap-fileupload.min.js"></script>
		<!-- Theme Base, Components and Settings -->
<script src="<?php echo base_url()?>assets/js/theme.js"></script>
		
        <!-- Theme Custom -->
<script src="<?php echo base_url()?>assets/js/theme.custom.js"></script>
		
		<!-- Theme Initialization Files -->
<script src="<?php echo base_url()?>assets/js/theme.init.js"></script>
<script src="<?php echo base_url()?>assets/js/jquery-ui-1.10.4.custom.js"></script>
<script src="<?php echo base_url()?>assets/js/store.js"></script>
<script src="<?php echo base_url()?>assets/js/notifications.js"></script>
<script src="<?php echo base_url()?>assets/js/spinner.js"></script>
<script src="<?php echo base_url()?>assets/js/sum.js"></script>
<script>
  $(document).ready(function() {
    $("#ddlSupervisores").select2({
        placeholder: "Seleccione un supervisor",
        allowClear: true,
        language: "es"
    });
	  setInterval(function () {
		 showNotifications();
	  },120000);//

	  $("#notBell").click(function () {
		  $("#notificaciones").html("")
	  });

       var hrefurl=$(location).attr("href");
        var miUrlpAGOS=hrefurl.substr(hrefurl.lastIndexOf('/') + 1)
        

          if (miUrlpAGOS != 'pagoProveedores') {
              $.ajax({
                  url: "<?php echo base_url("index.php/VerificarNotificacionAntiguedad")?>",
                  type: "POST",
                  async: true,
                    success: function (data) {                            
                        console.log(data);
                        if (data == '-1') {
                            $('#avisoProveedores').modal('show');
                        }
                        
                    }
              });
          }
  });

   /* $(".my_select_box").chosen({
    no_results_text: "Sin resultados",
    width: "100%",
    allow_single_deselect: true
  });*/


   function notificar(texto,icono,tipo)
   {
	   new PNotify({
		   title: "Notificaciones",
		   text: texto,
		   addclass: ""+tipo+" stack-left", //notification-primary
		   icon: icono,
		   hide: true,
		   delay: 4000,
		   buttons: {
			   sticker: false
		   }
	   });
   }

  function showNotifications(){
  	$("#notificaciones").html("");
  	$("#liFacturas").hide();
  	let num = new Array(), anul = new Array();
  	let cant = new Array(), cant1 = new Array(), sum = 0, notific = "";
	  $.ajax({
		  url: "<?php echo base_url("index.php/Notificaciones")?>",
		  type: "POST",
		  async: true,
		  success: function (data) {
			  if(data){
				  $.each(JSON.parse(data), function (i, item) {
					  if(item["facturas"] != null){
						  num[i] = item["facturas"];
					  }
					  if(item["anulaciones"] != null){
						  for (let j = 0; j < item["anulaciones"].length ; j++) {
							  anul[j] = item["anulaciones"][j];
						  }
					  }
					  cant = num;
					  cant1 = anul;
				  });


				  if(cant != null){
					  if(num[0] > 0){
						  notificar(
							  "Tienes nuevas facturas pendientes de integrar.",
							  "fa fa-gears",
							  "notification-primary");
						  $("#liFacturas").show();
						  $("#encFac").html("Facturas pendientes");
						  $("#messageFact").html("tiene "+num+" factura(s) sin integrarse");
					  }
				  }
				  if(cant1 != 0){
				  	sum = (cant1.length+1)-cant1.length;
					  if(anul[0].CANTIDAD > 0){
						  for (let i = 0; i < anul.length; i++) {
							  notificar(
								  "El vendedor "+anul[i].NOMBREUSUARIOSOLICITA+" ha solicitado anular la factura "+anul[i].IDFACTURA+".",
								  "fa fa-trash-o",
								  "notification-danger");
						  }
						  $("#lianulaFacturas").show();
						  $("#encAnulFact").html("Solicitud de anulacion");
						  $("#messageAnulFact").html("tiene "+anul[0].CANTIDAD+" solicitud de anulacion");
					  }
				  }

				  if(cant.length+ sum == 0){
						notific = "";
					  $("title").html("4BUY");
				  }else{
					notific = cant.length+ sum;
					  $("title").html("4BUY "+"("+notific+")"+"");
				  }

				  $("#notificaciones").html(notific);
				  $("#contadorNotif").html(notific);
			  }
		  }
	  });
  }

</script>
</body>
</html>
