<?php
/**
 * Created by Cesar Mejía.
 * User: Sistemas
 * Date: 13/2/2019 16:19 2019
 * FileName: jsconsolidadoRem.php
 */
?>
<script type="text/javascript">
$(document).ready(function () {
	$("#Rubro").select2({
        placeholder: "Seleccione un rubro",
        allowClear: true,
        language: "es"
    });

    $("#Ruta").select2({
        placeholder: "Seleccione una ruta",
        allowClear: true,
        language: "es"
    });
});


$("#chkTodos").change(function () {
	if($(this).prop("checked") == true){
		$("#chkOrden").prop("checked",true);
		$("#chkPreventa").prop("checked",true);
		$("#chkRecargo").prop("checked",true);
		$("#chkAdelanto").prop("checked",true);
        $("#chkWalmart").prop("checked",true);
        $("#chkcasaMant").prop("checked",true);
        $("#chkindepend").prop("checked",true);

	}else{
		$("#chkOrden").prop("checked",false);
		$("#chkPreventa").prop("checked",false);
		$("#chkRecargo").prop("checked",false);
		$("#chkAdelanto").prop("checked",false);
        $("#chkWalmart").prop("checked",false);
        $("#chkcasaMant").prop("checked",false);
        $("#chkindepend").prop("checked",false);
	}
});

$("#btnConsolidadoRem").click(function () {
	$("#tblRemisionesCons tbody").html("");
	let array = new Array();
	let Ruta = $("#Ruta option:selected").val(), parametro = '';
	let sms = 'Generado correctamente', tiposms = 'success';
	$("input[name='chk']:checked").each(function () {
		array.push($(this).val());
	});

	if(Ruta == ''){
		parametro = "NULL";
	}else{
		parametro = Ruta;
	}

	let form_data = {
		fecha: $("#fechaEntrega").val(),
		fechaFin: $("#fechaFin").val(),
		rubro: $("#Rubro option:selected").val(),
		tipo: array,
		ruta: $("#Ruta option:selected").val()
	};

	$.ajax({
		url: "ConsolidadoRems",
		type: "POST",
		data: form_data,
		beforeSend: function(){
			if(array == ""){
				swal({
					text: "Debe seleccionar un tipo de remision",
					type: "error"
				});
				$.ajax.abort();
			}
		},
		success: function (data) {
			if(data){
				let obj = jQuery.parseJSON(data);
				$.each(obj, function (i, value){
					sms = value.mensaje;
					tiposms = value.tipomensaje;

					$("#tblRemisionesCons tbody").append(
						"<tr>" +
						"<td>"+value.FechaEntrega+"</td>" +
						"<td>"+value.Cantidad+"</td>" +
						"<td>"+value.CantidadLBS+"</td>" +
						"<td>"+value.RubroD+"</td>" +
						"<td>"+value.TipoD+"</td>" +
						'<td><div class="btn-group dropup"><button type="button" ' +
						'class="left btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" ' +
						'aria-expanded="false">Acciones <span class="caret"></span></button><ul ' +
						'class="dropdown-menu" style="width: 130px !important;" role="menu"><li><a ' +
						'href="javascript:void(0)"' +
'onclick="DetallesModal('+"'"+value.FechaEntrega+"','"+value.Cantidad+"','"+value.CantidadLBS+"','"+value.RubroD+"','"+value.TipoD+"',"+value.Rubro+","+value.Tipo+" ,"+value.IdUsuario+" ,'"+parametro+"' "+')" >' +
						'Detalles</a></li>' +
						'<li><a target="_blank" href="Consolidado/'+value.FechaEntrega+'/'+value.Rubro+'/'+value.Tipo+'/'+parametro+'">'+
						'Ver/ Imprimir</a></li>'+
						'</ul></div></td>' +
						"</tr>"
					);
				});
				swal({
					text: sms,
					type: tiposms
				});
			}
		}
	});
});


function DetallesModal(fecha,cant,cantlbs,rubro,tipo,codrubro,codtipo,coduser,codruta) {
	$("#txtRubro").text(rubro);
	$("#txtTipo").text("("+tipo+")");
	$("#FechaEntDt").val(fecha);
	$("#CantTotal").val(cant);
	$("#CantlbsTotal").val(cantlbs);
	$("#tblDetalles tbody").html("")
	$.ajax({
		url: "detConsolidadoAjax/"+fecha+"/"+codrubro+"/"+codtipo+"/"+coduser+"/"+codruta,
		type: "POST",
		async: true,
		success: function (data) {
			$.each(JSON.parse(data), function (i, item) {
				$("#tblDetalles tbody").append(
					"<tr>"+
					  "<td>"+item.IdRuta+"</td>"+
					  "<td>"+item.Vendedor+"</td>"+
					  "<td>"+item.Referencia+"</td>"+
					  "<td>"+item.CodigoProd+"</td>"+
					  "<td>"+item.DescripcionProd+"</td>"+
					  "<td>"+item.LBS+"</td>"+
					  "<td>"+item.Cantidad+"</td>"+
					  "<td>"+item.CantidadLBS+"</td>"+
				    "</tr>"
				);
			});
		}
	});

	$("#modalDetalles").modal("show");
}
</script>
