<?php
/**
 * Created by Cesar Mejía.
 * User: Sistemas
 * Date: 28/3/2019 14:23 2019
 * FileName: jsCuotasLista.php
 */
?>

<script type="text/javascript">
let Gnombre;
let Gcuotamensual;
let Glibrasvendidas;
let Gcuotaallevar;
let Ggap;
let Gfaltavender;
let Gidruta;

	$(document).ready(function () {
		$("#ddlRutas").select2({
			placeholder: "Seleccione una ruta",
			allowClear: true,
			language: "es"
		});

		$("#Mes").select2({
			placeholder: "Seleccione un mes",
			allowClear: true,
			language: "es"
		});
	});

	
	$("#btnActualizar2").click(function(){
	$.ajax({
		        url: <?php echo "'".base_url('index.php/librasXdia')."'"?>,
		        type: 'post',
		        dataType: 'json',
		        data: {
		        		fecha1: $("#fecha1").val(),
		        		fecha2: $("#fecha2").val(),
		        		idruta: Gidruta
		        		},
		        success: function (msg) {
				paramNombres = [];
				paramDatos = [];
				bgColor = [];
				bgBorder = [];
				for (var i=0; i<=6; i++) {
							console.log(i);
							var r = Math.random() * 255;
							r = Math.round(r);

							var g = Math.random() * 255;
							g = Math.round(g);

							var b = Math.random() * 255;
							b = Math.round(b);
							bgColor.push('rgba('+r+','+g+','+b+', 0.7)');
							bgBorder.push('rgba('+r+','+g+','+b+', 1)');
						}
				$.each(msg, function(i,item){						
					paramNombres.push(item["NOMBREVENDEDOR"]);
					paramDatos.push(item["LUNES"]);
					paramDatos.push(item["MARTES"]);
					paramDatos.push(item["MIERCOLES"]);
					paramDatos.push(item["JUEVES"]);
					paramDatos.push(item["VIERNES"]);
					paramDatos.push(item["SABADO"]);
					paramDatos.push(item["DOMINGO"]);
					bgColor.push('rgba('+r+','+g+','+b+', 0.8)');
					bgBorder.push('rgba('+r+','+g+','+b+', 1)');					
				});
				var ctx2 = $("#myChart2");
				    var myChart = new Chart(ctx2, {
					    type: 'line',
					    data: {
					        labels: ['Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo'],
					        datasets: [{
					            label: Gnombre,
					            data: paramDatos,
					            backgroundColor: bgColor,
					            borderColor: bgBorder,
					            borderWidth: 1,
					            pointRadius: 10,
					            pointHoverRadius: 10
					        }]
					    },
					    options: {
					        scales: {
					            yAxes: [{
					                ticks: {
					                    beginAtZero:true
					                }
					            }]
					        }
					    }
					});
		        }
		    });
	});

	function grafica(nombre,cuotamensual,librasvendidas,cuotaallevar,gap,faltavender,idruta){
		Gnombre = nombre;
		Gcuotamensual = cuotamensual;
		Glibrasvendidas = librasvendidas;
		Gcuotaallevar = cuotaallevar
		Ggap = gap;
		Gfaltavender = Gfaltavender;
		Gidruta = idruta;
		$('#myChart2').remove();
		$('#modal-chart').append("<canvas id='myChart2' width='400' height='100'></canvas>");
		/*$('#myChart').remove();
		$('#modal-chart').append("<canvas id='myChart' width='400' height='100'></canvas>");*/
		let url = window.location.pathname;
	    let params = url.split("ListaCuotas");
		let loc = window.location;
		let pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 4);
	    let ruta1 = loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));    
	    url = ruta1+"../remisionOrdenSCliente"+params[1];
	    url = ruta1.replace("ListaCuotas","grafica");
		paramNombres = [];
		paramDatos = [];
		bgColor = [];
		bgBorder = [];
		for (var i=0; i<=4; i++) {
			console.log(i);
			var r = Math.random() * 255;
			r = Math.round(r);

			var g = Math.random() * 255;
			g = Math.round(g);

			var b = Math.random() * 255;
			b = Math.round(b);
			bgColor.push('rgba('+r+','+g+','+b+', 0.7)');
			bgBorder.push('rgba('+r+','+g+','+b+', 1)');
		}		
			paramNombres.push(nombre);
			//paramDatos.push(item["LIBRAS_VENDIDAS"]);		
		var ctx = $("#myChart");
			var myChart = new Chart(ctx, {
			    type: 'bar',
			    data: {
			        labels: ['Cuota Mensual','Libras Vendidas','Cuota a Llevar','GAP Lbs','Falta Vender'],
			        datasets: [{
			            label: nombre,
			            data: [cuotamensual,librasvendidas,cuotaallevar,gap,faltavender],
			            backgroundColor: bgColor,
			            borderColor: bgBorder,
			            borderWidth: 1
			        }]
			    },
			    options: {
			        scales: {
			            yAxes: [{
			                ticks: {
			                    beginAtZero:true
			                }
			            }]
			        }
			    }
			});
			//alert(idruta);
			$.ajax({
		        url: <?php echo "'".base_url('index.php/librasXdia')."'"?>,
		        type: 'post',
		        dataType: 'json',
		        data: {
		        		fecha1: $("#fechaFac1").val(),
		        		fecha2: $("#fechaFac2").val(),
		        		idruta: idruta
		        		},
		        success: function (msg) {
				paramNombres = [];
				paramDatos = [];
				bgColor = [];
				bgBorder = [];
				for (var i=0; i<=6; i++) {
							console.log(i);
							var r = Math.random() * 255;
							r = Math.round(r);

							var g = Math.random() * 255;
							g = Math.round(g);

							var b = Math.random() * 255;
							b = Math.round(b);
							bgColor.push('rgba('+r+','+g+','+b+', 0.7)');
							bgBorder.push('rgba('+r+','+g+','+b+', 1)');
						}
				$.each(msg, function(i,item){						
					paramNombres.push(item["NOMBREVENDEDOR"]);
					paramDatos.push(item["LUNES"]);
					paramDatos.push(item["MARTES"]);
					paramDatos.push(item["MIERCOLES"]);
					paramDatos.push(item["JUEVES"]);
					paramDatos.push(item["VIERNES"]);
					paramDatos.push(item["SABADO"]);
					paramDatos.push(item["DOMINGO"]);
					bgColor.push('rgba('+r+','+g+','+b+', 0.8)');
					bgBorder.push('rgba('+r+','+g+','+b+', 1)');					
				});
				var ctx2 = $("#myChart2");
				    var myChart = new Chart(ctx2, {
					    type: 'line',
					    data: {
					        labels: ['Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo'],
					        datasets: [{
					            label: nombre,
					            data: paramDatos,
					            backgroundColor: bgColor,
					            borderColor: bgBorder,
					            borderWidth: 1,
					            pointRadius: 10,
					            pointHoverRadius: 10
					        }]
					    },
					    options: {
					        scales: {
					            yAxes: [{
					                ticks: {
					                    beginAtZero:true
					                }
					            }]
					        }
					    }
					});
		        }
		    });
			$('#modalGrafica').modal('show');
	}






	$("#btnActualizar").click(function () {
		cargarCuotas();
	});

	function cargarCuotas(){
		if(true == false /*$("#fechaFac1").val() == "" || $("#fechaFac2").val() == ""*/){
			swal({
				text: "Debe ingresar una fecha inicio y una fecha final",
				type: "error",
				allowOutsideClick: false
			});
		}else{
			let table = $("#dtCuotas").DataTable({
				"ajax": {
					"url": "ReporteCuotas",
					"type": "POST",
					"data": function ( d ) {
						d.fecha1 = $("#fechaFac1").val();
						d.fecha2 = $("#fechaFac2").val();
						d.ruta = $("#ddlRutas option:selected").text();
						d.mes = $("#Mes option:selected").val();						
					}
				},
				"processing": true,
				"scrollX": true,
				"orderMulti": false,
				"info": true,
				"sort": true,
				"destroy": true,
				"lengthMenu": [
					[10,20,50,100, -1],
					[10,20,50,100, "Todo"]
				],
				"order": [
					[1, "asc"]
				],
				"language": {
					"info": "Registro _START_ a _END_ de _TOTAL_ entradas",
					"infoEmpty": "Registro 0 a 0 de 0 entradas",
					"zeroRecords": "No se encontro coincidencia",
					"infoFiltered": "(filtrado de _MAX_ registros en total)",
					"emptyTable": "NO HAY DATOS DISPONIBLES",
					"lengthMenu": '_MENU_ ',
					"search": '<i class="fa fa-search"></i>',
					"loadingRecords": "",
					"processing": "Procesando datos  <i class='fa fa-spin fa-refresh'></i>",
					"paginate": {
						"first": "Primera",
						"last": "Última ",
						"next": "Siguiente",
						"previous": "Anterior"
					}
				},
				"columns": [
					{"data" : "grafica","class":'text-center'},
					{"data" : "IdRuta"},
					{"data" : "Descripcion"},
					{"data" : "Nombre"},
					{"data" : "CUOTAMENSUAL"},
					{"data" : "LIBRAS_VENDIDAS"},
					{"data" : "CUOTA_A_LLEVAR"},
					{"data" : "GAP_LIBRAS"},
					{"data" : "FALTA_VENDER"},
					{"data" : "AVANCE_VENTAS"},
					{"data" : "CUMPLIMIENTO"},
					{"data" : "PROMEDIO_DIARIO"},
					{"data" : "DIAS_EFECTIVOS"},
					{"data" : "PRIMER_DIA"},
					{"data" : "DIAS_TRANSCURRIDOS"}
				],
				"columnDefs": [
					{
						"targets": [1],
						"visible": false,
						"orderable": false
					}
				]
			});
		}
		if($("#ddlRutas option:selected").val() == ""){
			$("#exportarMetas").attr('href','ExportarCuota/'+$("#fechaFac1").val()+"/"+$("#fechaFac2").val()+"/"+'null'+"/"+$("#Mes option:selected").val());
		}else{
			$("#exportarMetas").attr('href','ExportarCuota/'+$("#fechaFac1").val()+"/"+$("#fechaFac2").val()+"/"+$("#ddlRutas option:selected").text()+"/"+$("#Mes option:selected").val());
		}
	}
</script>
