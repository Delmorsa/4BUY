<script type="text/javascript">
  $(document).ready(function() {

  });

  $("#fileUpload").change(function(e){
    let table = $("#tblInventario").DataTable();
    table.destroy();
    if($(this).val() != ""){

      let reader = new FileReader();
      reader.readAsArrayBuffer(e.target.files[0]);
      reader.onload = function(e){

        let data = new Uint8Array(reader.result);
        let wb = XLSX.read(data, {type: 'array'});
        let htmlstr = "<td>"+XLSX.write(wb, {sheet:"", type:"binary", bookType:"html"})+"</td>";
        $("#wrapper table tbody")[0].innerHTML += htmlstr;

        let cuerpo = $("#wrapper>table>tbody>tr>td>table>tbody").html();
		    $("#wrapper").html("");

        $("#wrapper").append('<table id="tblInventario" class="display table table-condensed table-bordered table-responsive table-striped mb-none table-sm"" style="width:100%">' +
                        ' <thead>' +
                        '  <tr>' +
                        '  <th>CODIGO</th>' +
                        '  <th>DESCRIPCION</th>' +
                        '  <th>GRM</th>' +
        								'  <th>UND/LBRS</th>' +
        								'  <th>LIBRAS</th>' +
                        ' </tr>' +
                        '</thead>' +
                        ' <tbody>'+cuerpo+
                        '</tbody>');
        $("#tblInventario").DataTable({
      				"autoWidth": false,
      				"info": false,
      				"sort": false,
      				"processing": true,
      				"destroy":true,
      				"paging": false,
      				"ordering": false,
      				"searching":false,
      				"order": [
      					[0, "asc"] 
      				],
      				/*"dom": 'T<"clear">lfrtip',
                       "tableTools": {
                           "sSwfPath": "< echo base_url(); ?>assets/data/swf/copy_csv_xls_pdf.swf",
                       },*/
      				"pagingType": "full_numbers",
      				"lengthMenu": [
      					[10, 20, 100, -1],
      					[10, 20, 100, "Todo"]
      				],
      				"language": {
      					"info": "Registro _START_ a _END_ de _TOTAL_ deshueses",
      					"infoEmpty": "Registro 0 a 0 de 0 deshueses",
      					"zeroRecords": "No se encontro coincidencia",
      					"infoFiltered": "(filtrado de _MAX_ registros en total)",
      					"emptyTable": "NO HAY DATOS DISPONIBLES",
      					"lengthMenu": '_MENU_ ',
      					"search": '<i class=" material-icons">search</i>',
      					"loadingRecords": "Cargando...",
      					"paginate": {
      						"first": "Primera",
      						"last": "Última ",
      						"next": "Siguiente",
      						"previous": "Anterior"
      					}
      				}
      		});
      }
    }else{
      $("#wrapper").html('<table id="tblInventario" class="display table table-condensed table-bordered table-responsive table-striped mb-none table-sm"" style="width:100%">' +
                '        <thead>' +
                '        <tr>' +
                '         <th>CODIGO</th>' +
                '         <th>DESCRIPCION</th>' +
                '         <th>GRM</th>' +
                '         <th>UND/LBRS</th>' +
                '         <th>LIBRAS</th>' +
                '        </tr>' +
                '        </thead>' +
                '        <tbody>'+
                '</tbody>');
      $("#tblInventario").DataTable({
              				"autoWidth": false,
              				"info": false,
              				"sort": false,
              				"processing": true,
              				"destroy":true,
              				"paging": false,
              				"ordering": true,
              				"searching":false,
              				"order": [
              					[0, "asc"]
              				],
              				/*"dom": 'T<"clear">lfrtip',
                               "tableTools": {
                                   "sSwfPath": "< echo base_url(); ?>assets/data/swf/copy_csv_xls_pdf.swf",
                               },*/
              				"pagingType": "full_numbers",
              				"lengthMenu": [
              					[10, 20, 100, -1],
              					[10, 20, 100, "Todo"]
              				],
              				"language": {
              					"info": "Registro _START_ a _END_ de _TOTAL_ deshueses",
              					"infoEmpty": "Registro 0 a 0 de 0 deshueses",
              					"zeroRecords": "No se encontro coincidencia",
              					"infoFiltered": "(filtrado de _MAX_ registros en total)",
              					"emptyTable": "NO HAY DATOS DISPONIBLES",
              					"lengthMenu": '_MENU_ ',
              					"search": '<i class=" material-icons">search</i>',
              					"loadingRecords": "Cargando...",
              					"paginate": {
              						"first": "Primera",
              						"last": "Última ",
              						"next": "Siguiente",
              						"previous": "Anterior"
              					}
              				}
              		});
    }
  });
</script>
