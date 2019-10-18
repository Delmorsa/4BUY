<?php
/**
 * Created by PhpStorm.
 * User: Sistemas
 * Date: 28/12/2018
 * Time: 16:53
 */
?>
<script type="text/javascript">
$(document).ready(function () {
	cargarRemOrdenes();
	cargarRemPreventas();
	cargarRemRecargos();
	cargarRemAdelantos();
    cargarRemWalmart();
    cargarRemcasaMant();
    cargarRemIndepenientes();

	setInterval(function () {
		cargarRemOrdenes();
		cargarRemPreventas();
		cargarRemRecargos();
		cargarRemAdelantos();
		cargarRemWalmart();
		cargarRemcasaMant();
		cargarRemIndepenientes();
	},3000000);
});

function cargarRemOrdenes(){
	let table = $("#tblOrdenesList").DataTable({
		"ajax": {
			"url": "ListOrdensAjax",
			"type": "POST"
		},
		"processing": true,
		"serverSide": true,
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
			{"data" : "IdRemision"},
			{"data" : "FechaEntrega"},
			{"data" : "CantTotal"},
			{"data" : "TotalLbs"},
			{"data" : "FechaLiq"},
			{"data" : "FechaCrea"},
			{"data" : "FechaEdita"},
			{"data" : "FechaBaja"},
			{"data" : "Estado"},
			{"data" : "Detalles"}
		]
	});
}

function cargarRemPreventas(){
	let table = $("#tblPreventasList").DataTable({
		"ajax": {
			"url": "ListPreventasAjax",
			"type": "POST"
		},
		"processing": true,
		"serverSide": true,
		"info": true,
		"sort": true,
		"destroy": true,
		"lengthMenu": [
			[10,20,50,100, -1],
			[10,20,50,100, "Todo"]
		],
		"order": [
			[0, "asc"]
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
			{"data" : "IdRemision"},
			{"data" : "FechaEntrega"},
			{"data" : "CantTotal"},
			{"data" : "TotalLbs"},
			{"data" : "FechaLiq"},
			{"data" : "FechaCrea"},
			{"data" : "FechaEdita"},
			{"data" : "FechaBaja"},
			{"data" : "Estado"},
			{"data" : "Detalles"}
		]
	});
}

function cargarRemRecargos(){
	let table = $("#tblRecargosList").DataTable({
		"ajax": {
			"url": "ListRecargosAjax",
			"type": "POST"
		},
		"processing": true,
		"serverSide": true,
		"info": true,
		"sort": true,
		"destroy": true,
		"lengthMenu": [
			[10,20,50,100, -1],
			[10,20,50,100, "Todo"]
		],
		"order": [
			[0, "asc"]
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
			{"data" : "IdRemision"},
			{"data" : "FechaEntrega"},
			{"data" : "CantTotal"},
			{"data" : "TotalLbs"},
			{"data" : "FechaLiq"},
			{"data" : "FechaCrea"},
			{"data" : "FechaEdita"},
			{"data" : "FechaBaja"},
			{"data" : "Estado"},
			{"data" : "Detalles"}
		]
	});
}
function cargarRemWalmart(){
    let table = $("#tblwalmartList").DataTable({
        "ajax": {
            "url": "listaWalmartAjax",
            "type": "POST"
        },
        "processing": true,
        "serverSide": true,
        "info": true,
        "sort": true,
        "destroy": true,
        "lengthMenu": [
            [10,20,50,100, -1],
            [10,20,50,100, "Todo"]
        ],
        "order": [
            [0, "asc"]
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
            {"data" : "IdRemision"},
            {"data" : "FechaEntrega"},
            {"data" : "CantTotal"},
            {"data" : "TotalLbs"},
            {"data" : "FechaLiq"},
            {"data" : "FechaCrea"},
            {"data" : "FechaEdita"},
            {"data" : "FechaBaja"},
            {"data" : "Estado"},
            {"data" : "Detalles"}
        ]
    });
}

function cargarRemcasaMant(){
    let table = $("#tblmanticaList").DataTable({
        "ajax": {
            "url": "listaCasaMantAjax",
            "type": "POST"
        },
        "processing": true,
        "serverSide": true,
        "info": true,
        "sort": true,
        "destroy": true,
        "lengthMenu": [
            [10,20,50,100, -1],
            [10,20,50,100, "Todo"]
        ],
        "order": [
            [0, "asc"]
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
            {"data" : "IdRemision"},
            {"data" : "FechaEntrega"},
            {"data" : "CantTotal"},
            {"data" : "TotalLbs"},
            {"data" : "FechaLiq"},
            {"data" : "FechaCrea"},
            {"data" : "FechaEdita"},
            {"data" : "FechaBaja"},
            {"data" : "Estado"},
            {"data" : "Detalles"}
        ]
    });
}

function cargarRemIndepenientes(){
    let table = $("#tblindepenList").DataTable({
        "ajax": {
            "url": "listaIndependienteAjax",
            "type": "POST"
        },
        "processing": true,
        "serverSide": true,
        "info": true,
        "sort": true,
        "destroy": true,
        "lengthMenu": [
            [10,20,50,100, -1],
            [10,20,50,100, "Todo"]
        ],
        "order": [
            [0, "asc"]
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
            {"data" : "IdRemision"},
            {"data" : "FechaEntrega"},
            {"data" : "CantTotal"},
            {"data" : "TotalLbs"},
            {"data" : "FechaLiq"},
            {"data" : "FechaCrea"},
            {"data" : "FechaEdita"},
            {"data" : "FechaBaja"},
            {"data" : "Estado"},
            {"data" : "Detalles"}
        ]
    });
}

function cargarRemAdelantos(){
	let table = $("#tblAdelantosList").DataTable({
		"ajax": {
			"url": "ListaAdelantosAjax",
			"type": "POST"
		},
		"processing": true,
		"serverSide": true,
		"info": true,
		"sort": true,
		"destroy": true,
		"lengthMenu": [
			[10,20,50,100, -1],
			[10,20,50,100, "Todo"]
		],
		"order": [
			[0, "asc"]
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
			{"data" : "IdRemision"},
			{"data" : "FechaEntrega"},
			{"data" : "CantTotal"},
			{"data" : "TotalLbs"},
			{"data" : "FechaLiq"},
			{"data" : "FechaCrea"},
			{"data" : "FechaEdita"},
			{"data" : "FechaBaja"},
			{"data" : "Estado"},
			{"data" : "Detalles"}
		]
	});
}

$("#tblOrdenesList").on("click", ".expand",function () {
	let table = $("#tblOrdenesList").DataTable();
	let tr = $(this).closest('tr');
	let row = table.row(tr);
	let data = table.row($(this).parents('tr')).data();
	if(row.child.isShown()){
		row.child.hide();
		tr.removeClass("shown");
		$("#Rem"+data.IdRemision).css("color","");
	}else{
		$("#Rem"+data.IdRemision).css("color","red");
		detalleRemision(row.child,data.IdRemision);
		tr.addClass("shown");
	}

});

$("#tblPreventasList").on("click", ".expand",function () {
	let table = $("#tblPreventasList").DataTable();
	let tr = $(this).closest('tr');
	let row = table.row(tr);
	let data = table.row($(this).parents('tr')).data();
	if(row.child.isShown()){
		row.child.hide();
		tr.removeClass("shown");
		$("#Rem"+data.IdRemision).css("color","");
	}else{
		$("#Rem"+data.IdRemision).css("color","red");
		detalleRemision(row.child,data.IdRemision);
		tr.addClass("shown");
	}

});

$("#tblRecargosList").on("click", ".expand",function () {
	let table = $("#tblRecargosList").DataTable();
	let tr = $(this).closest('tr');
	let row = table.row(tr);
	let data = table.row($(this).parents('tr')).data();
	if(row.child.isShown()){
		row.child.hide();
		tr.removeClass("shown");
		$("#Rem"+data.IdRemision).css("color","");
	}else{
		$("#Rem"+data.IdRemision).css("color","red");
		detalleRemision(row.child,data.IdRemision);
		tr.addClass("shown");
	}

});

$("#tblAdelantosList").on("click", ".expand",function () {
	let table = $("#tblAdelantosList").DataTable();
	let tr = $(this).closest('tr');
	let row = table.row(tr);
	let data = table.row($(this).parents('tr')).data();
	if(row.child.isShown()){
		row.child.hide();
		tr.removeClass("shown");
		$("#Rem"+data.IdRemision).css("color","");
	}else{
		$("#Rem"+data.IdRemision).css("color","red");
		detalleRemision(row.child,data.IdRemision);
		tr.addClass("shown");
	}

});


$("#tblwalmartList").on("click", ".expand",function () {
    let table = $("#tblwalmartList").DataTable();
    let tr = $(this).closest('tr');
    let row = table.row(tr);
    let data = table.row($(this).parents('tr')).data();
    if(row.child.isShown()){
        row.child.hide();
        tr.removeClass("shown");
        $("#Rem"+data.IdRemision).css("color","");
    }else{
        $("#Rem"+data.IdRemision).css("color","red");
        detalleRemision(row.child,data.IdRemision);
        tr.addClass("shown");
    }

});

$("#tblmanticaList").on("click", ".expand",function () {
    let table = $("#tblmanticaList").DataTable();
    let tr = $(this).closest('tr');
    let row = table.row(tr);
    let data = table.row($(this).parents('tr')).data();
    if(row.child.isShown()){
        row.child.hide();
        tr.removeClass("shown");
        $("#Rem"+data.IdRemision).css("color","");
    }else{
        $("#Rem"+data.IdRemision).css("color","red");
        detalleRemision(row.child,data.IdRemision);
        tr.addClass("shown");
    }

});


$("#tblindepenList").on("click", ".expand",function () {
    let table = $("#tblindepenList").DataTable();
    let tr = $(this).closest('tr');
    let row = table.row(tr);
    let data = table.row($(this).parents('tr')).data();
    if(row.child.isShown()){
        row.child.hide();
        tr.removeClass("shown");
        $("#Rem"+data.IdRemision).css("color","");
    }else{
        $("#Rem"+data.IdRemision).css("color","red");
        detalleRemision(row.child,data.IdRemision);
        tr.addClass("shown");
    }

});

function detalleRemision(callback,idRemision){
	let opcion = '';
	let edit='';
	$.ajax({
		url: "detallesRemisionesAjax/"+idRemision,
		async: true,
		success: function (response) {
			let thead = '', tbody = '';
			if(response != "false"){
				let obj = $.parseJSON(response);
				let temp = obj.length, cantRows = 0;
				thead += "<tr class='primary'><th class='center'>Cod Rem.</th>";
				thead += "<th class='center'>Consecutivo</th>";
				thead += "<th class='center'>Ruta</th>";
				thead += "<th class='center'>Vendedor</th>";
				thead += "<th class='center'>Rubro</th>";
				thead += "<th class='center'>Tipo</th>";
				thead += "<th class='center'>Ref.</th>";
				thead += "<th class='center'>Coment.</th>";
				thead += "<th class='center'>Acciones</th></tr>";

				$.each(JSON.parse(response), function (i, item) {
					if(item["Estado"] == 2){
						opcion = "";
						edit = '';
					}else{
						opcion= "Cerrar";
						edit = "<li><a href='EditarRem/"+item["IdRemision"]+"'>Editar</a></li>";
					}
					tbody += "<tr>"+
						"<td>"+item["IdRemision"]+"</td>"+
						"<td>"+item["Consecutivo"]+"</td>"+
						"<td>"+item["IdRuta"]+"</td>"+
						"<td>"+item["Vendedor"]+"</td>"+
						"<td>"+item["Rubro"]+"</td>"+
						"<td>"+item["Tipo"]+"</td>"+
						"<td>"+item["Referencia"]+"</td>"+
						"<td>"+item["Comentario"]+"</td>"+
						"<td class=''><div class=''>" +
						"<div class='btn-group dropup'>" +
						"<button type='button' class='left btn btn-sm btn-default dropdown-toggle' data-toggle='dropdown' aria-expanded='false'>Acciones <span class='caret'></span></button>" +
						"<ul class='dropdown-menu' style='width: 130px !important;' role='menu'>" +
						 edit+
						"<li><a onclick='activar_cerrar("+item["IdRemision"]+","+item["Estado"]+")' href='#'>"+opcion+"</a></li>" +
						"<li><a target='_blank' href='PrintRemision/"+item["IdRemision"]+"'>Ver/ Imprimir</a></li>"+
						"</ul>" +
						"</div>" +
						"</div></td>"+
						"</tr>";
				});
				callback($("<table id='detOrden' class='table table-condensed table-striped table-bordered'>"+ thead + tbody + "</table>")).show();
			}else{
				thead += "<tr><th>Consecutivo</th>";
				thead += "<th>Ruta</th>";
				thead += "<th>Vendedor</th>";
				thead += "<th>Rubro</th>";
				thead += "<th>Tipo</th>";
				thead += "<th>Ref.</th>";
				thead += "<th>Coment.</th></tr>";

				tbody += "<tr>"+
					"<td></td>"+
					"<td></td>"+
					"<td></td>"+
					"<td></td>"+
					"<td></td>"+
					"<td></td>"+
					"<td></td>"+
					"</tr>";
				callback($("<table id='detOrden' class='table striped'>"+ thead + tbody + "</table>")).show();
			}
		}
	});
}

function activar_cerrar(idremision,estado){
	let mensaje = '';
	if(estado == 2){
		mensaje = "¿Esta seguro(a) que deseas activar esta remision?";
	}else{
		mensaje = "¿Esta seguro(a) que deseas cerrar esta remision?";
	}
	swal({
		text: mensaje,
		type: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Aceptar',
		cancelButtonText: "Cancelar",
		allowOutsideClick: false
	}).then(result => {
		if(result.value){
			$.ajax({
				url: "DarBajaRemision/"+idremision+"/"+estado,
				type: "POST",
				async: true,
				success: function(){
					swal({
						text: "Datos actualizados correctamente",
						type: "success",
						allowOutsideClick: false
					}).then(result => {
						location.reload();
					});
				}
			});
		}
	});
}

</script>
