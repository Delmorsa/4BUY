<?php
/**
 * Created by Pinky Mejía.
 * User: Sistemas
 * Date: 27/3/2019 11:08 2019
 * FileName: Cuotas_model.php
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Cuotas_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		//Do your magic here
	}

	public function getRutas(){
		$query = $this->db->get("CategoriasRutas");
		if($query->num_rows() > 0){
			return $query->result_array();
		}
		return 0;
	}

	public function getCuotas()
	{
		date_default_timezone_set("America/Managua");
		$query = $this->db->where("MES", date("n"))
			->where("ANIO",date("Y"))
			->get("Cuotas");
		if ($query->num_rows() > 0){
	    	return $query->result_array();
     	}
	    return 0;
	}

	public function guardarCuota($mes, $anio, $ruta, $numruta, $cuotamens, $dias){
		$mensaje = array(); $string = '';
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1034");
		if($permiso){
			$string = explode("Vendedor",$numruta);

			$validar = $this->db->where("MES",$mes)->where("IDRUTA",$ruta)->where("ANIO",$anio)->get("Cuotas");

			if($validar->num_rows() > 0){
				$mensaje[0]["tipo"] = "error";
				$mensaje[0]["mensaje"] = "Ya existe una cuota para la ".strtolower( ucfirst($string[1]))." 
			con mes ".$mes.'/'.$anio." ";
				echo json_encode($mensaje);
			}else{
				$query = $this->db->query("SELECT ISNULL(MAX(IdCuota),0)+1 AS IdCuota FROM Cuotas");
				$save = array(
					"IDCUOTA" => $query->result_array()[0]["IdCuota"],
					"MES" => $mes,
					"ANIO" => $anio,
					"IDRUTA" => $ruta,
					"NUMRUTA" => strtolower( ucfirst($string[1])),
					"CUOTAMENSUAL" => $cuotamens,
					"DIAS_EFECTIVOS" => $dias,
					"FECHACREA" => gmdate(date("Y-m-d h:i:s")),
					"ESTADO" =>	1
				);
				$retorno = $this->db->insert("Cuotas",$save);
				if($retorno){
					$mensaje[0]["tipo"] = "success";
					$mensaje[0]["mensaje"] = "Cuota creada exitosamente";
					echo json_encode($mensaje);
				}else{
					$mensaje[0]["tipo"] = "error";
					$mensaje[0]["mensaje"] = "Ocurrio unn error inesperado en el servidor, contáctece con el administrador";
					echo json_encode($mensaje);
				}
			}
		}else{
			$mensaje[0]["tipo"] = "error";
			$mensaje[0]["mensaje"] = "No tienes permiso para realizar esta operación";
			echo json_encode($mensaje);
		}
	}

	public function actualizarCuota($id, $cuotamens, $dias){
		$mensaje = array();
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1035");
		if($permiso){
			$this->db->where("IDCUOTA",$id);
			$upd = array(
				"CUOTAMENSUAL" => $cuotamens,
				"DIAS_EFECTIVOS" => $dias,
				"FECHAEDITA" => gmdate(date("Y-m-d H:i:s"))
			);
			$retorno = $this->db->update("Cuotas",$upd);
			if($retorno){
				$mensaje[0]["tipo"] = "success";
				$mensaje[0]["mensaje"] = "Cuota actualizada exitosamente";
				echo json_encode($mensaje);
			}else{
				$mensaje[0]["tipo"] = "error";
				$mensaje[0]["mensaje"] = "Ocurrio unn error inesperado en el servidor, contáctece con el administrador";
				echo json_encode($mensaje);
			}
		}else{
			$mensaje[0]["tipo"] = "error";
			$mensaje[0]["mensaje"] = "No tienes permiso para realizar esta operación";
			echo json_encode($mensaje);
		}
	}

	public function ReporteCuotas($fechaInicio, $fechaFin, $IdRuta, $mes){
		$queryRuta = ''; $json = array(); $i = 0;
		if($IdRuta){
			$queryRuta = "WHERE Descripcion = '".$IdRuta."' ";
		}
		if($mes){
			$queryRuta = "WHERE MES = '".$mes."' ";
		}
		if($IdRuta && $mes){
			$queryRuta = "WHERE Descripcion = '".$IdRuta."' and MES = '".$mes."' ";
		}



		$query = $this->db->query("
		    WITH TABLA AS
			(
			SELECT t1.IdRuta, t1.Nombre, t1.Apellidos, t3.CUOTAMENSUAL, 
			SUM(t0.LibrasVendidas) LIBRAS_VENDIDAS,T3.DIAS_EFECTIVOS,
			CAST('01-'+CAST(T3.MES AS CHAR)+'-'+CAST(T3.ANIO AS CHAR) AS DATE) AS PRIMER_DIA,
			datediff(dd, '".$fechaInicio."', '".$fechaFin."') - (datediff(wk, '".$fechaInicio."', '".$fechaFin."')) 
			-((DATEDIFF(day,'".$fechaInicio."', '".$fechaFin."')-DATEPART(dw,'".$fechaFin."')+7)/7)*0.5 DIAS_TRANSCURRIDOS,
			T1.IDCatRuta,T4.Descripcion,
			t2.liquidado as Liquidado,
			t3.MES
			FROM [dbo].[Liquidacion] t0
			
			inner join [dbo].[Usuarios] t1 on t0.IdRuta=t1.IdRuta
			inner join periodos t2 on t2.IdPeriodo= t0.IdPeriodo
			inner join Cuotas t3 on t1.IdRuta = t3.idruta
			inner join CategoriasRutas t4 on T1.IDCatRuta = t4.ID
			WHERE t2.liquidado='Y' AND  
			(CAST (t2.FechaInicio AS date)>= '".$fechaInicio."' and 
			CAST(t2.FechaInicio AS date)<= '".$fechaFin."') or (CAST (t2.FechaFinal AS date)>= '".$fechaInicio."' and 
			CAST(t2.FechaFinal AS date)<= '".$fechaFin."')   and t1.ESTADO ='1'
			GROUP BY  t1.IdRuta, t1.Nombre, t1.Apellidos, t3.CUOTAMENSUAL,T3.DIAS_EFECTIVOS,T3.MES,T3.ANIO,t2.liquidado,T1.IDCatRuta, T4.Descripcion,t3.MES
			) 
			SELECT IdRuta,Descripcion,Nombre,Apellidos,CUOTAMENSUAL,cast (LIBRAS_VENDIDAS as decimal (18,2)) as LIBRAS_VENDIDAS,
			cast((CUOTAMENSUAL/DIAS_EFECTIVOS)*DIAS_TRANSCURRIDOS as decimal(18,2)) AS CUOTA_A_LLEVAR,
			cast(LIBRAS_VENDIDAS-(CUOTAMENSUAL/DIAS_EFECTIVOS)*DIAS_TRANSCURRIDOS as decimal (18,2)) AS GAP_LIBRAS,
			cast (CUOTAMENSUAL-LIBRAS_VENDIDAS as decimal (18,2)) AS FALTA_VENDER,
			cast(((LIBRAS_VENDIDAS/CUOTAMENSUAL)*100) as decimal (18,2))AS AVANCE_VENTAS,
			cast ((LIBRAS_VENDIDAS/((CUOTAMENSUAL/DIAS_EFECTIVOS)*DIAS_TRANSCURRIDOS)*100)as decimal (18,2)) AS CUMPLIMIENTO,
			cast((LIBRAS_VENDIDAS/DIAS_TRANSCURRIDOS) as decimal (18,2)) AS PROMEDIO_DIARIO,
			DIAS_EFECTIVOS,PRIMER_DIA,DIAS_TRANSCURRIDOS,Liquidado,MES FROM TABLA
			".$queryRuta." ");

         if($query->num_rows() > 0){
			 foreach ($query->result_array() as $key) {
			 	 $json["data"][$i]["grafica"] = '<a href="#" onclick="grafica('."'"."".$key["Nombre"]." ".$key["Apellidos"].""."'".','.$key["CUOTAMENSUAL"].','.$key["LIBRAS_VENDIDAS"].','.$key["CUOTA_A_LLEVAR"].','.$key["GAP_LIBRAS"].','.$key["FALTA_VENDER"].','.$key["IdRuta"].')" ><i class="fa fa-bar-chart-o"></i></a>';
				 $json["data"][$i]["IdRuta"] = $key["IdRuta"];
				 $json["data"][$i]["Descripcion"] = $key["Descripcion"];
				 $json["data"][$i]["Nombre"] = $key["Nombre"]." ".$key["Apellidos"];
				 $json["data"][$i]["CUOTAMENSUAL"] = $key["CUOTAMENSUAL"];
				 $json["data"][$i]["LIBRAS_VENDIDAS"] = $key["LIBRAS_VENDIDAS"];
				 $json["data"][$i]["CUOTA_A_LLEVAR"] = $key["CUOTA_A_LLEVAR"];
				 $json["data"][$i]["GAP_LIBRAS"] = $key["GAP_LIBRAS"];
				 $json["data"][$i]["FALTA_VENDER"] = $key["FALTA_VENDER"];
				 $json["data"][$i]["AVANCE_VENTAS"] = $key["AVANCE_VENTAS"];
				 $json["data"][$i]["CUMPLIMIENTO"] = $key["CUMPLIMIENTO"];
				 $json["data"][$i]["PROMEDIO_DIARIO"] = $key["PROMEDIO_DIARIO"];
				 $json["data"][$i]["DIAS_EFECTIVOS"] = $key["DIAS_EFECTIVOS"];
				 $json["data"][$i]["PRIMER_DIA"] = $key["PRIMER_DIA"];
				 $json["data"][$i]["DIAS_TRANSCURRIDOS"] = $key["DIAS_TRANSCURRIDOS"];
				 $json["data"][$i]["Liquidado"] = $key["Liquidado"];
				 $i++;
         	}
		 }
         echo json_encode($json);
	}

	public function ExportarReporteCuotas($fechaInicio, $fechaFin, $IdRuta, $mes){
		$queryRuta = '';
		if($IdRuta && $mes){
			$queryRuta = "WHERE Descripcion = '".$IdRuta."' and MES = '".$mes."' ";
		}
		if($IdRuta == 'null' && $mes){
			$queryRuta = "WHERE Descripcion not in('null') and MES = '".$mes."' ";
		}
		$query = $this->db->query("
		    WITH TABLA AS
			(
			SELECT t1.IdRuta, t1.Nombre, t1.Apellidos, t3.CUOTAMENSUAL, 
			SUM(t0.LibrasVendidas) LIBRAS_VENDIDAS,T3.DIAS_EFECTIVOS,
			CAST('01-'+CAST(T3.MES AS CHAR)+'-'+CAST(T3.ANIO AS CHAR) AS DATE) AS PRIMER_DIA,
			datediff(dd, '".$fechaInicio."', '".$fechaFin."') - (datediff(wk, '".$fechaInicio."', '".$fechaFin."')) 
			-((DATEDIFF(day,'".$fechaInicio."', '".$fechaFin."')-DATEPART(dw,'".$fechaFin."')+7)/7)*0.5 DIAS_TRANSCURRIDOS,
			T1.IDCatRuta,T4.Descripcion,
			t2.liquidado as Liquidado,
			t3.MES
			FROM [dbo].[Liquidacion] t0
			
			inner join [dbo].[Usuarios] t1 on t0.IdRuta=t1.IdRuta
			inner join periodos t2 on t2.IdPeriodo= t0.IdPeriodo
			inner join Cuotas t3 on t1.IdRuta = t3.idruta
			inner join CategoriasRutas t4 on T1.IDCatRuta = t4.ID
			WHERE t2.liquidado='Y' AND  
			(CAST (t2.FechaInicio AS date)>= '".$fechaInicio."' and 
			CAST(t2.FechaInicio AS date)<= '".$fechaFin."') or (CAST (t2.FechaFinal AS date)>= '".$fechaInicio."' and 
			CAST(t2.FechaFinal AS date)<= '".$fechaFin."')   and t1.ESTADO ='1'
			GROUP BY  t1.IdRuta, t1.Nombre, t1.Apellidos, t3.CUOTAMENSUAL,T3.DIAS_EFECTIVOS,T3.MES,T3.ANIO,t2.liquidado,T1.IDCatRuta, T4.Descripcion,t3.MES
			) 
			SELECT IdRuta,Descripcion,Nombre,Apellidos,CUOTAMENSUAL,cast (LIBRAS_VENDIDAS as decimal (18,2)) as LIBRAS_VENDIDAS,
			cast((CUOTAMENSUAL/DIAS_EFECTIVOS)*DIAS_TRANSCURRIDOS as decimal(18,2)) AS CUOTA_A_LLEVAR,
			cast(LIBRAS_VENDIDAS-(CUOTAMENSUAL/DIAS_EFECTIVOS)*DIAS_TRANSCURRIDOS as decimal (18,2)) AS GAP_LIBRAS,
			cast (CUOTAMENSUAL-LIBRAS_VENDIDAS as decimal (18,2)) AS FALTA_VENDER,
			cast(((LIBRAS_VENDIDAS/CUOTAMENSUAL)*100) as decimal (18,2))AS AVANCE_VENTAS,
			cast ((LIBRAS_VENDIDAS/((CUOTAMENSUAL/DIAS_EFECTIVOS)*DIAS_TRANSCURRIDOS)*100)as decimal (18,2)) AS CUMPLIMIENTO,
			cast((LIBRAS_VENDIDAS/DIAS_TRANSCURRIDOS) as decimal (18,2)) AS PROMEDIO_DIARIO,
			DIAS_EFECTIVOS,PRIMER_DIA,DIAS_TRANSCURRIDOS,Liquidado,MES FROM TABLA
			".$queryRuta."
			 ORDER BY IdRuta");

		if($query->num_rows() > 0){
			return $query->result_array();
		}
		return 0;
	}

	public function getRubros($descripcion){
		if($descripcion != "null"){
			$this->db->like('DESCRIPCION',substr($descripcion,0,4));
		}
		$this->db->order_by('DESCRIPCION', 'ASC');
		$query = $this->db->get("CategoriasRutas");
		if($query->num_rows() > 0){
			return $query->result_array();
		}
		return 0;
	}
	public function ExportarReporteCuotas2($fechaInicio, $fechaFin, $IdRuta, $mes){
		$array = array();
		$i = 0;
		$queryRuta = '';
		if($IdRuta && $mes){
			$queryRuta = "WHERE Descripcion = '".$IdRuta."' and MES = '".$mes."' ";
		}
		if($IdRuta == 'null' && $mes){
			$queryRuta = "WHERE Descripcion not in('null') and MES = '".$mes."' ";
		}
		$query = $this->db->query("
		    WITH TABLA AS
			(
			SELECT t1.IdRuta, t1.Nombre, t1.Apellidos, t3.CUOTAMENSUAL, 
			SUM(t0.LibrasVendidas) LIBRAS_VENDIDAS,T3.DIAS_EFECTIVOS,
			CAST('01-'+CAST(T3.MES AS CHAR)+'-'+CAST(T3.ANIO AS CHAR) AS DATE) AS PRIMER_DIA,
			datediff(dd, '".$fechaInicio."', '".$fechaFin."') - (datediff(wk, '".$fechaInicio."', '".$fechaFin."')) 
			-((DATEDIFF(day,'".$fechaInicio."', '".$fechaFin."')-DATEPART(dw,'".$fechaFin."')+7)/7)*0.5 DIAS_TRANSCURRIDOS,
			T1.IDCatRuta,T4.Descripcion,
			t2.liquidado as Liquidado,
			t3.MES
			FROM [dbo].[Liquidacion] t0
			
			inner join [dbo].[Usuarios] t1 on t0.IdRuta=t1.IdRuta
			inner join periodos t2 on t2.IdPeriodo= t0.IdPeriodo
			inner join Cuotas t3 on t1.IdRuta = t3.idruta
			inner join CategoriasRutas t4 on T1.IDCatRuta = t4.ID
			WHERE t2.liquidado='Y' AND  
			CAST (t2.FechaInicio AS date)>= '".$fechaInicio."' and 
			CAST(t2.FechaInicio AS date)<= '".$fechaFin."'  and t1.ESTADO ='1'
			GROUP BY  t1.IdRuta, t1.Nombre, t1.Apellidos, t3.CUOTAMENSUAL,T3.DIAS_EFECTIVOS,T3.MES,T3.ANIO,t2.liquidado,T1.IDCatRuta, T4.Descripcion,t3.MES
			) 
			SELECT IdRuta,Descripcion,Nombre,Apellidos,CUOTAMENSUAL,cast (LIBRAS_VENDIDAS as decimal (18,2)) as LIBRAS_VENDIDAS,
			cast((CUOTAMENSUAL/DIAS_EFECTIVOS)*DIAS_TRANSCURRIDOS as decimal(18,2)) AS CUOTA_A_LLEVAR,
			cast(LIBRAS_VENDIDAS-(CUOTAMENSUAL/DIAS_EFECTIVOS)*DIAS_TRANSCURRIDOS as decimal (18,2)) AS GAP_LIBRAS,
			cast (CUOTAMENSUAL-LIBRAS_VENDIDAS as decimal (18,2)) AS FALTA_VENDER,
			cast(((LIBRAS_VENDIDAS/CUOTAMENSUAL)*100) as decimal (18,2))AS AVANCE_VENTAS,
			cast ((LIBRAS_VENDIDAS/((CUOTAMENSUAL/DIAS_EFECTIVOS)*DIAS_TRANSCURRIDOS)*100)as decimal (18,2)) AS CUMPLIMIENTO,
			cast((LIBRAS_VENDIDAS/DIAS_TRANSCURRIDOS) as decimal (18,2)) AS PROMEDIO_DIARIO,
			DIAS_EFECTIVOS,PRIMER_DIA,DIAS_TRANSCURRIDOS,Liquidado,MES FROM TABLA
			".$queryRuta."
			 ORDER BY IdRuta");

		echo json_encode($query->result_array());
	}
	public function librasXdia($fecha1,$fecha2,$codvendedor)
	{
		$json = array();$i = 0;
		$query = $this->db->query("WITH TABLA AS (
			SELECT T0.CODVENDEDOR,T0.NOMBREVENDEDOR,
			CASE  WHEN DATEPART(dw,T0.FECHA) = 1 THEN  (T1.CANTIDAD*T1.GRAMOS)/454 ELSE 0 END AS 'DOMINGO',
			CASE  WHEN DATEPART(dw,T0.FECHA) = 2 THEN  (T1.CANTIDAD*T1.GRAMOS)/454 ELSE 0 END AS 'LUNES',
			CASE  WHEN DATEPART(dw,T0.FECHA) = 3 THEN  (T1.CANTIDAD*T1.GRAMOS)/454 ELSE 0 END AS 'MARTES',
			CASE  WHEN DATEPART(dw,T0.FECHA) = 4 THEN  (T1.CANTIDAD*T1.GRAMOS)/454 ELSE 0 END AS 'MIERCOLES',
			CASE  WHEN DATEPART(dw,T0.FECHA) = 5 THEN  (T1.CANTIDAD*T1.GRAMOS)/454 ELSE 0 END AS 'JUEVES',
			CASE  WHEN DATEPART(dw,T0.FECHA) = 6 THEN  (T1.CANTIDAD*T1.GRAMOS)/454 ELSE 0 END AS 'VIERNES',
			CASE  WHEN DATEPART(dw,T0.FECHA) = 7 THEN  (T1.CANTIDAD*T1.GRAMOS)/454 ELSE 0 END AS 'SABADO'
			FROM Facturas T0 
			INNER JOIN Facturas_Detalles T1 ON T1.IDENCABEZADO = T0.IDENCABEZADO
			WHERE T0.ESTADOAPP <>4 AND CAST(T0.FECHA AS DATE) >= '".$fecha1."' AND CAST(T0.FECHA AS DATE) <= '".$fecha2."'
			AND T0.CODVENDEDOR = ".$codvendedor."
		)
		SELECT CODVENDEDOR,NOMBREVENDEDOR,SUM(DOMINGO) 'DOMINGO', SUM(LUNES) 'LUNES',SUM(MARTES) 'MARTES',SUM(MIERCOLES) 'MIERCOLES',SUM(JUEVES) 'JUEVES',SUM(VIERNES) 'VIERNES',SUM(SABADO) 'SABADO'
		FROM TABLA
		GROUP BY CODVENDEDOR,NOMBREVENDEDOR
		");


		if($query->num_rows() > 0){
			echo json_encode($query->result_array());
			return;
			
		 }
         echo 0; return;
	}
}

/* End of file .php */
