<?php
/**
 * Created by Cesar Mejía.
 * User: Sistemas
 * Date: 13/2/2019 17:54 2019
 * FileName: Reportes_model.php
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	//region funciones para Consolidado de Remisiones
	public function consolidadoRem($fechaEntrega,  $fechaFin, $codRubri, $codTipo, $ruta)
	{
		$mensaje = array(); $json = array(); $i = 0;
		$query = '';
		$queryRuta = '';
		if($ruta){
			$queryRuta = "and IdRuta = '".$ruta."' ";
		}

		if($this->session->userdata('IdRol') == 1){
			if($queryRuta == ''){
					$query = $this->db->query("SELECT FechaEntrega,Rubro,Tipo,SUM(Cantidad) Cantidad,SUM(CantidadLBS) CantidadLBS,
                                                RubroD,TipoD,Estado,IdUsuario
  												FROM cm_Consolidado_Remisiones
  												where FechaEntrega between '".$fechaEntrega."' 
											 	and '".$fechaFin."' and Rubro = '".$codRubri."' 
											 	and Tipo in ("."'".implode("','",$codTipo)."'".") 
 												group by FechaEntrega,Rubro,Tipo,RubroD,TipoD,Estado,IdUsuario");
			}else{
				$query = $this->db->query("select * from cm_Consolidado_Remisiones where FechaEntrega between '".$fechaEntrega."'
			 and '".$fechaFin."' and Rubro = '".$codRubri."' and Tipo in ("."'".implode("','",$codTipo)."'".") ".$queryRuta." ");
			}
		}else{
			if($queryRuta == ''){
					$query = $this->db->query("SELECT FechaEntrega,Rubro,Tipo,SUM(Cantidad) Cantidad,SUM(CantidadLBS) CantidadLBS,
                                                RubroD,TipoD,Estado,IdUsuario
  												FROM cm_Consolidado_Remisiones
  												where FechaEntrega between '".$fechaEntrega."' 
											 	and '".$fechaFin."' and Rubro = '".$codRubri."' 
											 	and Tipo in ("."'".implode("','",$codTipo)."'".") 
						 						and IdUsuario = '".$this->session->userdata('id')."'
 												group by FechaEntrega,Rubro,Tipo,RubroD,TipoD,Estado,IdUsuario");
			}else{
				$query = $this->db->query("SELECT * from cm_Consolidado_Remisiones where FechaEntrega between '".$fechaEntrega."' 
						 and '".$fechaFin."' and Rubro = '".$codRubri."' and Tipo in ("."'".implode("','",$codTipo)."'".") 
						 and IdUsuario = '".$this->session->userdata('id')."' ".$queryRuta." ");
			}
		}
		if($query->num_rows() > 0){
			/*$mensaje[0]["tipomensaje"] = "success";
			$mensaje[0]["mensaje"] = "Generado correctamente";*/
			foreach ($query->result_array() as $key){
				$json[$i]["FechaEntrega"] = $key["FechaEntrega"];
				$json[$i]["Cantidad"] = $key["Cantidad"];
				$json[$i]["CantidadLBS"] = $key["CantidadLBS"];
				$json[$i]["Rubro"] = $key["Rubro"];
				$json[$i]["Tipo"] = $key["Tipo"];
				$json[$i]["RubroD"] = $key["RubroD"];
				$json[$i]["TipoD"] = $key["TipoD"];
				$json[$i]["IdUsuario"] = $key["IdUsuario"];
				$i++;
			}
			//echo json_encode($mensaje, JSON_FORCE_OBJECT);
			echo json_encode($json);
		}else{
			$mensaje[0]["tipomensaje"] = "error";
			$mensaje[0]["mensaje"] = "Lo sentimos, no fue posible generar 
			                          el consolidado con los datos proporcionados";
			echo json_encode($mensaje);
		}
	}

	public function detConsolidadoAjax($fechaEntrega, $codRubro, $codTipo, $IdUsuario,$codRuta){
		$json = array(); $i = 0;
		$query2 = '';
		if($codRuta != "NULL"){
			$query2 = "and IdRuta = '".$codRuta."' ";
		}
		$query = $this->db->query("select * from view_detalle_consolidado
									where CodRubro = '".$codRubro."' and IdUsuario = '".$IdUsuario."'
									and CodTipo = '".$codTipo."' and FechaEntrega = '".$fechaEntrega."' ".$query2." ");

		foreach ($query->result_array() as $item) {
			$json[$i]["IdRuta"] = $item["IdRuta"];
			if($item["CodTipo"] != 4){
				$json[$i]["Vendedor"] = $item["Vendedor"];
			}
			if($item["CodTipo"] == 4 && $item["NombreRotador"] != null){
				$json[$i]["Vendedor"] = $item["NombreRotador"]." (Rotador)";
			}else{
				$json[$i]["Vendedor"] = $item["Vendedor"];
			}
			$json[$i]["Consecutivo"] = $item["Consecutivo"];
			$json[$i]["CodigoProd"] = $item["CodigoProd"];
			$json[$i]["DescripcionProd"] = $item["DescripcionProd"];
			$json[$i]["LBS"] = $item["LBS"];
			$json[$i]["Cantidad"] = $item["Cantidad"];
			$json[$i]["CantidadLBS"] = $item["CantidadLBS"];
			$json[$i]["IdUsuario"] = $item["IdUsuario"];
			$json[$i]["Referencia"] = $item["Referencia"] = ($item["Referencia"] == "")? "Sin referencia": $item["Referencia"];
			$i++;
		}
		echo json_encode($json);
	}

	public function reportesRemision($fechaEntrega, $fechaFin, $codRubro, $codTipo, $ruta){
		$mensaje = array(); $json = array(); $i = 0;
		$query = '';
		$queryRuta = '';
		if($ruta){
			$queryRuta = "and IdRuta = '".$ruta."' ";
		}

		if($this->session->userdata('IdRol') == 1){
			$query = $this->db->query("select * from cm_Lista_Remisiones where CAST(FechaEntrega AS DATE)
			 between '".$fechaEntrega."' and '".$fechaFin."' and CodRubro = '".$codRubro."' 
			 and CodTipo in ("."'".implode("','",$codTipo)."'".") 
			 ".$queryRuta." ");
		}else{
			$query =  $this->db->query("select * from cm_Lista_Remisiones where  CAST(FechaEntrega AS DATE) between '".$fechaEntrega."' 
			 and '".$fechaFin."' and CodRubro = '".$codRubro."' and CodTipo in ("."'".implode("','",$codTipo)."'".") 
			 and IdUsuario = '".$this->session->userdata('id')."' ".$queryRuta." ");
		}
		if($query->num_rows() > 0){
			/*$mensaje[0]["tipomensaje"] = "success";
			$mensaje[0]["mensaje"] = "Generado correctamente";*/
			foreach ($query->result_array() as $key){
				$json["data"][$i]["IdRuta"] = $key["IdRuta"];
				$json["data"][$i]["FechaEntrega"] = date_format(new DateTime($key["FechaEntrega"]),"Y-m-d");
				$json["data"][$i]["CantTotal"] = $key["CantTotal"];
				$json["data"][$i]["TotalLbs"] = $key["TotalLbs"];
				$json["data"][$i]["Rubro"] = $key["Rubro"];
				$json["data"][$i]["Tipo"] = $key["Tipo"];
				$json["data"][$i]["FechaLiq"] = date_format(new DateTime($key["FechaLiq"]), "Y-m-d");
				$json["data"][$i]["FechaEdita"] =date_format(new DateTime( $key["FechaEdita"]), "Y-m-d");
				$json["data"][$i]["FechaBaja"] = date_format(new DateTime($key["FechaBaja"]), "Y-m-d");
				$json["data"][$i]["Estado"] = $key["Estado"];
				$json["data"][$i]["Detalles"] = "<p style='text-align:center;' class='expand text-primary'>
			<a onclick='showDetails(".'"'.$key["IdRemision"].'","'.$key["Rubro"].'","'.$key["Tipo"].'","'. date_format(new DateTime($key["FechaEntrega"]),"Y-m-d").'","'.$key["CantTotal"].'","'.$key["TotalLbs"].'"'.")'
			 href='javascript:void(0)'><i class='center fa fa-expand'></i></a></p>";;
				$i++;
			}
			//echo json_encode($mensaje, JSON_FORCE_OBJECT);
			echo json_encode($json);
		}else{
			$mensaje[0]["tipomensaje"] = "error";
			$mensaje[0]["mensaje"] = "Lo sentimos, no fue posible generar 
			                          el consolidado con los datos proporcionados";
			echo json_encode($mensaje);
		}
	}

	public function detallesReportesRemision($idRemision){
		$json = array(); $i = 0;
		$query = $this->db->query("SELECT  t1.*,t2.*
								  FROM [4BUY].[dbo].[DetalleRemision] t1
								  inner join cm_Detalles_Lista_Remisiones t2 on t1.IdRemision = t2.IdRemision
								  where t1.IdRemision = '".$idRemision."' ");
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $item) {
				$json["data"][$i]["CodigoProd"] = $item["CodigoProd"];
				$json["data"][$i]["DescripcionProd"] = $item["DescripcionProd"];
				$json["data"][$i]["LBS"] = $item["LBS"];
				$json["data"][$i]["Cantidad"] = $item["Cantidad"];
				$json["data"][$i]["CantidadLBS"] = $item["CantidadLBS"];
				$json["data"][$i]["IdRuta"] = $item["IdRuta"];
				if($item["CodTipo"] != 4){
					$json["data"][$i]["Vendedor"] = $item["Vendedor"];
				}
				if($item["CodTipo"] == 4 && $item["NombreRotador"] != null){
					$json["data"][$i]["Vendedor"] = $item["NombreRotador"]." (Rotador)";
				}else{
					$json["data"][$i]["Vendedor"] = $item["Vendedor"];
				}
				$json["data"][$i]["Referencia"] = $item["Referencia"] = ($item["Referencia"] == "")? "Sin referencia": $item["Referencia"];
				$i++;
			}
		}
		echo json_encode($json);
	}
	//endregion

	public function filtrarrptventas($fecha1,$fecha2,$ruta)
	{
		$json = array();
		$i = 0;
		$query = $this->db->query("SELECT  t1.*,t2.*
								  FROM [4BUY].[dbo].[DetalleRemision] t1
								  inner join cm_Detalles_Lista_Remisiones t2 on t1.IdRemision = t2.IdRemision
								  where t1.IdRemision = '".$idRemision."' ");
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $item) {
				$json["data"][$i]["CodigoProd"] = $item["CodigoProd"];
				$json["data"][$i]["DescripcionProd"] = $item["DescripcionProd"];
				$json["data"][$i]["LBS"] = $item["LBS"];
				$json["data"][$i]["Cantidad"] = $item["Cantidad"];
				$json["data"][$i]["CantidadLBS"] = $item["CantidadLBS"];
				$json["data"][$i]["IdRuta"] = $item["IdRuta"];				
				$i++;
			}
		}
		echo json_encode($json);
	}
	public function reportesConsecutivos($fecha1, $fecha2,$bandera)
	{
		$i = 0;
		$query = $this->db->query("SELECT LEFT(IDFACTURA,CHARINDEX('-', IDFACTURA)-1) SERIE,
									MIN(RIGHT(IDFACTURA,LEN(IDFACTURA)-CHARINDEX('-', IDFACTURA))) MINIMO, 
									MAX(RIGHT(IDFACTURA,LEN(IDFACTURA)-CHARINDEX('-', IDFACTURA))) MAXIMO,
									CAST(MAX(RIGHT(IDFACTURA,LEN(IDFACTURA)-CHARINDEX('-', IDFACTURA)))AS INT)
									- 
									CAST(MIN(RIGHT(IDFACTURA,LEN(IDFACTURA)-CHARINDEX('-', IDFACTURA)))AS INT)+1 NUMERO
									FROM Facturas 
									WHERE CAST(FECHA AS DATE) >= '".$fecha1."' AND CAST(FECHA AS DATE) <= '".$fecha2."' 
									GROUP BY LEFT(IDFACTURA,CHARINDEX('-', IDFACTURA)-1)
									ORDER BY LEFT(IDFACTURA,CHARINDEX('-', IDFACTURA)-1) ASC");
		if ($query->num_rows()>0) {
			foreach ($query->result_array() as $key){
				$json["data"][$i]["SERIE"] = $key["SERIE"];
				$json["data"][$i]["MINIMO"] = $key["MINIMO"];
				$json["data"][$i]["MAXIMO"] = $key["MAXIMO"];
				$json["data"][$i]["NUMERO"] = $key["NUMERO"];			
				$i++;
			}
			if ($bandera) {
				echo json_encode($json);
				return;
			}
			return $query->result_array();			
		}
		return 0;
	}


	public function reporteConsecutivosDetallado($fecha1,$fecha2,$bandera){
		$i = 0; $json = array();
		$query = $this->db->query("SELECT LEFT(t0.IDFACTURA,CHARINDEX('-', t0.IDFACTURA)-1) SERIE,
									RIGHT(t0.IDFACTURA,LEN(t0.IDFACTURA)-CHARINDEX('-', t0.IDFACTURA)) Consecutivo, 
									t0.CODVENDEDOR,t1.Nombre,t1.Apellidos
									FROM Facturas t0 
									inner join Usuarios t1 on t1.IdRuta = t0.CODVENDEDOR
									WHERE CAST(FECHA AS DATE) >= '".$fecha1."' AND CAST(FECHA AS DATE) <= '".$fecha2."' 
									GROUP BY LEFT(t0.IDFACTURA,CHARINDEX('-', t0.IDFACTURA)-1),
									RIGHT(t0.IDFACTURA,LEN(t0.IDFACTURA)-CHARINDEX('-', t0.IDFACTURA)),t0.CODVENDEDOR,t1.Nombre,t1.Apellidos
									ORDER BY LEFT(t0.IDFACTURA,CHARINDEX('-', t0.IDFACTURA)-1) ASC");
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key) {
				$json["data"][$i]["SERIE"] = $key["SERIE"];
				$json["data"][$i]["Consecutivo"] = $key["Consecutivo"];
				$json["data"][$i]["CODVENDEDOR"] = $key["CODVENDEDOR"];
				$json["data"][$i]["Nombre"] = $key["Nombre"]." ".$key["Apellidos"];
				$i++;
			}
			if($bandera){
				echo json_encode($json);
				return;
			}
			return $query->result_array();
		}
		return 0;
	}

	public function devolucionesPorRutas($fecha1,$fecha2,$bandera){
		$json = array();
		$i = 0;
		$query = $this->db->query("WITH TABLA AS (
					SELECT T0.IdLiquidacion,T3.FechaFinal FechaCrea /*T0.FechaCrea*/, T0.IdRuta,T2.Ruta,T2.Nombre+' '+T2.Apellidos 'NOMBRE',  
					T1.Codigo,t1.Descripcion,SUM(UnidadesVenTotal) 'VENDIDO',
					MAX(T1.Carga)-SUM(T1.UnidadesVenTotal) 'DEVOLUCION',
					SUM(t1.LibrasVendidas) 'LIBRASVENDIDAS',
					(T1.PesoGramos*(MAX(T1.Carga)-SUM(T1.UnidadesVenTotal)))/454 'LIBRASDEVOLUCION'
					FROM Liquidacion t0
					INNER JOIN Liquidacion_Detalle T1 ON T1.IdLiquidacion = T0.IdLiquidacion
					INNER JOIN Usuarios T2 ON T2.IdRuta = T0.IdRuta
					INNER JOIN Periodos T3 ON T3.IdPeriodo = T0.IdPeriodo
					---where t0.IdLiquidacion = 1807 --cast (FechaCrea as date ) >='20190622' AND  cast (FechaCrea as date ) <='20190622'
					GROUP BY T0.IdLiquidacion,T3.FechaFinal /*T0.FechaCrea*/, T0.IdRuta,T2.Ruta,T2.Nombre+' '+T2.Apellidos, T1.Codigo,t1.Descripcion,T1.PesoGramos
					 )
					 SELECT Codigo,Descripcion,
					 sum(ruta1) ruta1,sum(ruta2) ruta2,sum(ruta3) ruta3,sum(ruta4) ruta4,sum(ruta5) ruta5,
					 sum(ruta6) ruta6,sum(ruta7) ruta7,sum(ruta8) ruta8,sum(ruta9) ruta9,sum(ruta10) ruta10,
					 sum(ruta11) ruta11,sum(ruta12) ruta12,sum(ruta13) ruta13,sum(ruta14) ruta14,sum(ruta15) ruta15,
					 sum(ruta16) ruta16,sum(ruta17) ruta17,sum(ruta18) ruta18,sum(ruta19) ruta19,sum(ruta21) ruta21,sum(ruta22) ruta22,
					 sum(ruta23) ruta23,sum(ruta24) ruta24,sum(ruta25) ruta25,sum(ruta26) ruta26,sum(ruta27) ruta27,sum(ruta28) ruta28,
					 sum(ruta30) ruta30,sum(ruta31) ruta31,SUM(DEVOLUCIONES) Total FROM
					 (select Ruta,NOMBRE,Codigo,Descripcion,DEVOLUCIONES,
					 isnull([1],0) ruta1,isnull([2],0) ruta2,isnull([3],0) ruta3,isnull([4],0) ruta4,isnull([5],0) ruta5,isnull([6],0) ruta6,
					 isnull([7],0) ruta7,isnull([8],0) ruta8,isnull([9],0) ruta9,isnull([10],0) ruta10,isnull([11],0) ruta11,
					 isnull([12],0) ruta12,isnull([13],0) ruta13,isnull([14],0) ruta14,isnull([15],0) ruta15,isnull([16],0) ruta16,
					 isnull([17],0) ruta17,isnull([18],0) ruta18,isnull([19],0) ruta19,isnull([21],0) ruta21,
					 isnull([22],0) ruta22,isnull([23],0) ruta23,isnull([24],0) ruta24,isnull([25],0) ruta25,isnull([26],0) ruta26,
					 isnull([27],0) ruta27,isnull([28],0) ruta28,isnull([78],0) ruta30,isnull([79],0) ruta31
					 from(
					 SELECT IdRuta,Ruta,NOMBRE,Codigo,Descripcion,SUM(DEVOLUCION) DEVOLUCION, SUM(DEVOLUCION) DEVOLUCIONES FROM TABLA
					 WHERE cast (FechaCrea as date ) >='".$fecha1."' AND  cast (FechaCrea as date ) <='".$fecha2."'
					 and DEVOLUCION > 0
					 GROUP BY IdRuta,Ruta,NOMBRE,Codigo,Descripcion
					 
					 ) as tabla
					 pivot 
					 (
					   sum(DEVOLUCION)
					   for IdRuta in ([1],[2],[3],[4],[5],[6],[7],[8],[9],[10],[11],
									  [12],[13],[14],[15],[16],[17],[18],[19],[21],
									  [22],[23],[24],[25],[26],[27],[28],[78],[79])
					 ) as pvt
					 )AS TABLA2
					 group by  Codigo,Descripcion
					 ORDER BY Codigo
                      ");
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key) {
					$json["data"][$i]["Codigo"] = $key["Codigo"];
					$json["data"][$i]["Descripcion"] = $key["Descripcion"];
					$json["data"][$i]["ruta1"] = number_format($key["ruta1"],2);
					$json["data"][$i]["ruta2"] = number_format($key["ruta2"],2);
					$json["data"][$i]["ruta3"] = number_format($key["ruta3"],2);
					$json["data"][$i]["ruta4"] = number_format($key["ruta4"],2);
					$json["data"][$i]["ruta5"] = number_format($key["ruta5"],2);
					$json["data"][$i]["ruta6"] = number_format($key["ruta6"],2);
					$json["data"][$i]["ruta7"] = number_format($key["ruta7"],2);
					$json["data"][$i]["ruta8"] = number_format($key["ruta8"],2);
					$json["data"][$i]["ruta9"] = number_format($key["ruta9"],2);
					$json["data"][$i]["ruta10"] = number_format($key["ruta10"],2);
					$json["data"][$i]["ruta11"] = number_format($key["ruta11"],2);
					$json["data"][$i]["ruta12"] = number_format($key["ruta12"],2);
					$json["data"][$i]["ruta13"] = number_format($key["ruta13"],2);
					$json["data"][$i]["ruta14"] = number_format($key["ruta14"],2);
					$json["data"][$i]["ruta15"] = number_format($key["ruta15"],2);
					$json["data"][$i]["ruta16"] = number_format($key["ruta16"],2);
					$json["data"][$i]["ruta17"] = number_format($key["ruta17"],2);
					$json["data"][$i]["ruta18"] = number_format($key["ruta18"],2);
					$json["data"][$i]["ruta19"] = number_format($key["ruta19"],2);
					$json["data"][$i]["ruta21"] = number_format($key["ruta21"],2);
					$json["data"][$i]["ruta22"] = number_format($key["ruta22"],2);
					$json["data"][$i]["ruta23"] = number_format($key["ruta23"],2);
					$json["data"][$i]["ruta24"] = number_format($key["ruta24"],2);
					$json["data"][$i]["ruta25"] = number_format($key["ruta25"],2);
					$json["data"][$i]["ruta26"] = number_format($key["ruta26"],2);
					$json["data"][$i]["ruta27"] = number_format($key["ruta27"],2);
					$json["data"][$i]["ruta28"] = number_format($key["ruta28"],2);
					$json["data"][$i]["ruta30"] = number_format($key["ruta30"],2);
					$json["data"][$i]["ruta31"] = number_format($key["ruta31"],2);
					$i++;
			}
			if ($bandera) {
				echo json_encode($json);
				return;
			}
			return $query->result_array();
		}
		return 0;
	}

	public function reporteDeVentas($fechainicio,$fechafinal,$codruta,$bandera){
		$json = array(); $i = 0; $queryRuta= '';
		if($codruta){
			$queryRuta = "AND CODVENDEDOR = '".$codruta."'";
		}

		$query = $this->db->query("SELECT CODVENDEDOR,t1.Nombre as NOMBREVENDEDOR,COUNT(DISTINCT IDFACTURA) NOFACTURA,SUM(UVENCREDITO)UVENCREDITO,SUM(UVENCONTADO)UVENCONTADO,SUM(SUBTOTALCREDITO)SUBTOTALCREDITO,
			SUM(SUBTOTALCONTADO)SUBTOTALCONTADO,SUM(DESCCREDITO)DESCCREDITO,SUM(DESCCONTADO)DESCCONTADO,SUM(ISCCREDITO)ISCCREDITO,
			SUM(ISCCONTADO)ISCCONTADO,SUM(IVACREDITO)IVACREDITO,
			SUM(IVACONTADO)IVACONTADO,SUM(TOTALCREDITO)+SUM(TOTALCONTADO) TOTAL,SUM(LIBRAS) LIBRAS
			FROM 
			View_Facturas_Liquidaciones t0
			inner join Usuarios t1 on t0.CODVENDEDOR = t1.IdRuta
			WHERE FECHA>='".$fechainicio."' AND FECHA <='".$fechafinal."' ".$queryRuta."
			GROUP BY CODVENDEDOR,t1.Nombre
			ORDER  BY CODVENDEDOR
			");
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key) {
				$json["data"][$i]["CODVENDEDOR"] = $key["CODVENDEDOR"];
				$json["data"][$i]["NOMBREVENDEDOR"] = $key["NOMBREVENDEDOR"];
				$json["data"][$i]["NOFACTURA"] = $key["NOFACTURA"];
				$json["data"][$i]["LIBRAS"] = number_format($key["LIBRAS"],2);
				$json["data"][$i]["UVENCREDITO"] = number_format($key["UVENCREDITO"],2);
				$json["data"][$i]["UVENCONTADO"] = number_format($key["UVENCONTADO"],2);
				$json["data"][$i]["SUBTOTALCREDITO"] = number_format($key["SUBTOTALCREDITO"],2);
				$json["data"][$i]["SUBTOTALCONTADO"] = number_format($key["SUBTOTALCONTADO"],2);
				$json["data"][$i]["DESCCREDITO"] = number_format($key["DESCCREDITO"],2);
				$json["data"][$i]["DESCCONTADO"] = number_format($key["DESCCONTADO"],2);
				$json["data"][$i]["ISCCREDITO"] = number_format($key["ISCCREDITO"],2);
				$json["data"][$i]["ISCCONTADO"] = number_format($key["ISCCONTADO"],2);
				$json["data"][$i]["IVACREDITO"] = number_format($key["IVACREDITO"],2);
				$json["data"][$i]["IVACONTADO"] = number_format($key["IVACONTADO"],2);
				$json["data"][$i]["TOTAL"] = number_format($key["TOTAL"],2);
				$i++;
			}
			if($bandera){
				echo json_encode($json);	
				return;
			}
			return $query->result_array();
		}	
		return 0;
	}
	
	public function reporteMermas($rango1,$rango2,$bandera){
		$json = array();
		$i = 0;
		$query = $this->db->query("SP_Mermas '".$rango1."','".$rango2."'");
		$encabezado = $this->encabezadoMerma($rango1,$rango2,FALSE);
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $key) {
				$json[$i]["codigo"] = $key["Codigo"];
				$json[$i]["Descripcion"] = $key["Descripcion"];
				$json[$i]["Total"] = number_format($key["Total"],2);
				$json[$i]["NombreMes"] = $key["NombreMes"];
				foreach ($encabezado as $key1) {
					for($var = 1; $var <= count($key1); $var++){
						$json[$i]["DIA".$key1["Dias"].""] = number_format($key["DIA".$key1["Dias"].""],2);
					}
				}
				$i++;
			}
			if($bandera){
				echo json_encode($json);	
				return;
			}
			return $query->result_array();
		}
		return 0;
	}

	public function encabezadoMerma($rango1,$rango2,$bandera){
		$json = array();
		$i = 0;
		$query = $this->db->query("DECLARE @FechaDesde date = '".$rango1."', @FechaHasta date = '".$rango2."';
		WITH DateSequence(Fecha) AS
		(   SELECT @FechaDesde as Fecha
			UNION ALL 
			SELECT DATEADD(DAY, 1, Fecha)
			FROM DateSequence
			WHERE Fecha < @FechaHasta
		)
	
		--select final para obtener la secuencia
		SELECT DATEPART(DAY,Fecha)  AS Dias
		FROM DateSequence where DATEPART(dw,Fecha) <> 1 
		OPTION (MAXRECURSION 3000)
		");
		if($query->num_rows()>0){
			foreach ($query->result_array() as $key) {
				for($var = 1; $var <= count($key); $var ++){
					$json[$i]["Dias"] = $key["Dias"];
				}
				$i++;
			}
			if($bandera){
				echo json_encode($json);	
				return;
			}
			return $query->result_array();
		}
		return 0;
	}


	public function encabezadoMerma2($rango1,$rango2,$bandera){
		$json = array();
		$i = 0;
		$query = $this->db->query("DECLARE @FechaDesde date = '".$rango1."', @FechaHasta date = '".$rango2."';
		WITH DateSequence(Fecha) AS
		(   SELECT @FechaDesde as Fecha
			UNION ALL 
			SELECT DATEADD(DAY, 1, Fecha)
			FROM DateSequence
			WHERE Fecha < @FechaHasta
		)
	
		--select final para obtener la secuencia
		SELECT 'DIA'+CAST(DATEPART(DAY,Fecha) AS VARCHAR) as Dias
		FROM DateSequence where DATEPART(dw,Fecha) <> 1 
		OPTION (MAXRECURSION 3000)
		");
		if($query->num_rows()>0){
			foreach ($query->result_array() as $key) {
				for($var = 1; $var <= count($key); $var ++){
					$json[$i]["Dias"] = $key["Dias"];
				}
				$i++;
			}
			if($bandera){
				echo json_encode($json);	
				return;
			}
			return $query->result_array();
		}
		return 0;
	}

	public function mostrarVendForaneos(){
		$query = $this->db->query("SELECT t1.ID,t1.DESCRIPCION,t2.IdRuta,t2.Ruta
														FROM CategoriasRutas t1
													inner join Usuarios t2 on t1.ID = t2.IdCatRuta
													where t1.ID = 4");
		if($query->num_rows()  >0){
			return $query->result_array();
		}
		return 0;
	}

	public function reporteDeVentasDeposito($fechaInicio,$fechaFin,$ruta,$bandera){
		$json = array(); $i = 0; $queryRuta = '';
		if($ruta){
			$queryRuta = "AND T0.CODVENDEDOR = '".$ruta."' ";
		}else if($ruta == 0){
			$queryRuta = "AND T0.CODVENDEDOR in ( SELECT t2.IdRuta
			FROM CategoriasRutas t1
			inner join Usuarios t2 on t1.ID = t2.IdCatRuta
			where t1.ID = 4) ";
		}else{
			$queryRuta = "AND T0.CODVENDEDOR in ( SELECT t2.IdRuta
			FROM CategoriasRutas t1
			inner join Usuarios t2 on t1.ID = t2.IdCatRuta
			where t1.ID = 4) ";
		}

		$query = $this->db->query("SELECT T2.IdCatRuta,T3.DESCRIPCION, T0.CODVENDEDOR, T2.Ruta,  
			SUM(CASE WHEN T0.CODCONDPAGO = '-1' THEN 1 ELSE 0 END) NOFACTURASCONTADO,
			SUM(CASE WHEN T0.CODCONDPAGO = '-1' THEN T0.TOTAL ELSE 0 END) TOTALCONTADO,
			SUM(CASE WHEN T0.CODCONDPAGO <> '-1' THEN 1 ELSE 0 END) NOFACTURASCREDITO,
			SUM(CASE WHEN T0.CODCONDPAGO <> '-1' THEN T0.TOTAL ELSE 0 END) TOTALCREDITO
			FROM Facturas T0
			INNER JOIN Usuarios T2 ON T2.IdRuta = T0.CODVENDEDOR
			INNER JOIN CategoriasRutas T3 ON T3.ID = T2.IdCatRuta
			WHERE CAST(T0.FECHA AS DATE) >='".$fechaInicio."' AND CAST(T0.FECHA AS DATE) <= '".$fechaFin."' ".$queryRuta."
			AND T0.ESTADOAPP <> 4  
			GROUP BY  T2.IdCatRuta,T3.DESCRIPCION,T0.CODVENDEDOR, T2.Ruta
			ORDER BY T0.CODVENDEDOR
			");
		 
		 if($query->num_rows() > 0){
			foreach ($query->result_array() as $key) {
				$json["data"][$i]["CODVENDEDOR"] = $key["CODVENDEDOR"];
				$json["data"][$i]["RUTA"] = $key["Ruta"];
				$json["data"][$i]["NOFACTURASCONTADO"] = $key["NOFACTURASCONTADO"];
				$json["data"][$i]["TOTALCONTADO"] = number_format($key["TOTALCONTADO"],2);
				$json["data"][$i]["NOFACTURASCREDITO"] = $key["NOFACTURASCREDITO"];
				$json["data"][$i]["TOTALCREDITO"] = number_format($key["TOTALCREDITO"],2);
				$i++;
			}

			if ($bandera) {
				echo json_encode($json);
				return;
			}
			return $query->result_array();
		 }
		 return 0;
	}	
} 

/* End of file Reportes_model.php */
