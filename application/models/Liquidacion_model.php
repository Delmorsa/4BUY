<?php
/**
 * Created by PhpStorm.
 * User: Sistemas
 * Date: 28/1/2019
 * Time: 07:07
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Liquidacion_model extends CI_Model
{
	public function __construct(){
		parent::__construct();
		$this->load->database();
	}

	public function getPeriodo(){
		$this->db->where("Activo","Y");
		$this->db->where("Liquidado","N");
		$query = $this->db->get("cm_periodos");
		if ($query->num_rows() > 0){
			return $query->result_array();
		}
		return 0;
	}

	public function getPeriodoLiq(){
		$this->db->where("Activo","N");
		$this->db->where("Liquidado","Y");
		$query = $this->db->get("cm_periodos");
		if ($query->num_rows() > 0){
			return $query->result_array();
		}
		return 0;
	}

	public function getPeriodoPend(){
		$this->db->where("Activo","P");
		$this->db->where("Liquidado","N");
		$query = $this->db->get("cm_periodos");
		if ($query->num_rows() > 0){
			return $query->result_array();
		}
		return 0;
	}

	public function getPeriodoAnul(){
		$this->db->where("Activo","C");
		$this->db->where("Liquidado","N");
		$query = $this->db->get("cm_periodos");
		if ($query->num_rows() > 0){
			return $query->result_array();
		}
		return 0;
	}

	public function validacionFechIinicio($fechaIn,$ruta){
		$query = $this->db->query("select FechaFinal from Periodos 
									where Activo in ('Y','N','P')
									and cast(FechaFinal as date) = '".$fechaIn."'
									and IdRuta = '".$ruta."' ");
		if($query->num_rows() > 0){
			return false;
		}else{
			return true;
		}
	}

	//region Funciones para Periodo Liquidacion
	public function guardarPeriodo($fechaIn, $fechaFin, $horaInicio, $horaFin,$ruta){
		date_default_timezone_set("America/Managua");
		$actualiza = false; $mensaje = array();
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1024");
		if($permiso){
			$FechaInicioVali = $this->validacionFechIinicio($fechaIn,$ruta);
			if($FechaInicioVali){
				$duplicados = $this->evitarDuplicados($fechaIn,$fechaFin,$ruta);
				if($duplicados == true){
					$mensaje[0]["mensaje"] = "Ya existe un periodo de liquidacion con fecha inicio ".$fechaIn." y
				fecha final ".$fechaFin."
				para la ruta ".$ruta."";
					$mensaje[0]["tipo"] = "error";
					echo json_encode($mensaje);
				}else{
					$query = $this->db->query("select Activo,Liquidado from Periodos where IdRuta = '".$ruta."'
				and Activo not in ('C','N','P')");
				if($query->num_rows() == 0){
					$actualiza = true;
				}

				if($actualiza == true){
					//region GUARDAR PERIODO DE LIQUIDACION
					//obtener id de cuota
					$cuotas = $this->db->query("select IDCUOTA from Cuotas
												where MES = '".date("n")."' and ANIO = '".date("Y")."'
												and IDRUTA = '".$ruta."'");

					$id = $this->db->query("SELECT ISNULL(MAX(IdPeriodo),0)+1 as id FROM Periodos");
					$data = array(
						"IdPeriodo" => $id->result_array()[0]["id"],
						"FechaInicio" =>$fechaIn." ".$horaInicio.date(":s"),
						"FechaFinal" =>$fechaFin." ".$horaFin.date(":s"),
						"IdRuta" => $ruta,
						"Activo" => "Y",
						"IdUsuarioCrea" => $this->session->userdata('id'),
						"FechaCrea" => gmdate(date("Y-m-d H:i:s")),
						"Liquidado" => "N",
						"IdCuota" => $cuotas->result_array()[0]["IDCUOTA"]
					);
					$this->db->insert("Periodos", $data);
					//endregion
					$mensaje[0]["mensaje"] = "Periodo de liquidacion guardado con exito";
					$mensaje[0]["tipo"] = "success";
					echo json_encode($mensaje);
				}else{
					$mensaje[0]["mensaje"] = "No se puede crear el nuevo periodo de liquidacion para la ruta ".$ruta." 
				 por que existe un perido activo. Debe liquidar las facturas del periodo vigente para esta ruta";
					$mensaje[0]["tipo"] = "error";
					echo json_encode($mensaje);
				}
			}
		 }else{

		        $mensaje[0]["mensaje"] = "La fecha del nuevo periodo no puede iniciar con ".$fechaIn." ya que otro periodo  
				 que ya esta liquidado termina con esta fecha para la ruta ".$ruta."";
				$mensaje[0]["tipo"] = "error";
				echo json_encode($mensaje);
		 }
		}else{
			$mensaje[0]["mensaje"] = "No tienes permiso para realizar esta operacion";
			$mensaje[0]["tipo"] = "error";
			echo json_encode($mensaje);
		}
	}

	public function actualizarPeriodo($id,$fechaIn, $fechaFin, $horaInicio, $horaFin,$ruta,$Activo){
		$mensaje = array(); $actualiza = false;
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1025");
		if($permiso){
			/*$verfica = $this->verificarFacturasAct($id);
		if($verfica){
			$mensaje[0]["mensaje"] = "No se puede actualizar por que hay facturas activas ligadas
			 a este periodo de liquidacion";
			$mensaje[0]["tipo"] = "error";
			echo json_encode($mensaje);
		}else{*/
			$query = $this->db->query("select Activo,Liquidado from Periodos where IdRuta = '".$ruta."'
				and Activo = 'P'");
			if($query->num_rows() == 0){
				$actualiza = true;
			}
			if($actualiza == true){
				$data = array(
					"FechaInicio" =>$fechaIn." ".$horaInicio.date(":s"),
					"FechaFinal" =>$fechaFin." ".$horaFin.date(":s"),
					"IdRuta" => $ruta,
					"IdUsuarioEdita" => $this->session->userdata('id'),
					"FechaEdita" => gmdate(date("Y-m-d H:i:s")),
					"Activo" => $Activo
				);
				$this->db->where("IdPeriodo",$id);
				$act = $this->db->update("Periodos",$data);
				if($act){
					$mensaje[0]["mensaje"] = "Periodo de liquidacion actualizado con exito";
					$mensaje[0]["tipo"] = "success";
					echo json_encode($mensaje);
				}else{
					$mensaje[0]["mensaje"] = "No se pudo actualizar los datos de este periodo de liquidacion,
			 si el problema persiste pongase en contacto con el administrador";
					$mensaje[0]["tipo"] = "error";
					echo json_encode($mensaje);
				}
			}else{
				$mensaje[0]["mensaje"] = "Ya existe un periodo de liquidacion en espera para la ruta ".$ruta.". 
				Debe liquidar las facturas del periodo pendiente";
				$mensaje[0]["tipo"] = "error";
				echo json_encode($mensaje);
			}
			//}
		}else{
			$mensaje[0]["mensaje"] = "No tienes permiso para realizar esta operacion";
			$mensaje[0]["tipo"] = "error";
			echo json_encode($mensaje);
		}
	}

	public function evitarDuplicados($fechainicio, $fechafinal, $ruta){
		$query = $this->db->query("select cast(FechaInicio as date), cast(FechaFinal as date),
		   IdRuta from Periodos where  cast(FechaInicio as date) = "."'".$fechainicio."'"." 
		   and cast(FechaFinal as date) = "."'".$fechafinal."'"."
		   and IdRuta = "."'".$ruta."'"."
		   and Activo <> 'C' ");
		if($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}
	//endregion

	public function Liquidacion($idPeriodo){
		$json = array(); $i = 0; $e = 0;
		$notin = "";
		$query = $this->db->where("IdPeriodo", $idPeriodo)
						  ->get("Periodos");

		$queryFactDet = $this->db->where("IDPERIODO", $idPeriodo)
							     ->get("cm_Detalle_Encabezado_Factura");

		$queryFactDet1 = $this->db->query("EXEC	[dbo].[SP_PRODUCTOS_LIQUIDAR] @IDPERIODO = '".$idPeriodo."'"); 
		//ITEMS VENDIDOS POR EL VENDEDOR
		foreach ($queryFactDet1->result_array() as $key) {
				$json[$i]["CODIGO"] = $key["CODIGO"];
				$json[$i]["DESCRIPCION"] = $key["DESCRIPCION"];
				$json[$i]["GRAMOS"] = $key["GRAMOS"];
				$json[$i]["PRECIO"] = $key["PRECIO"];
				$json[$i]["REMISION"] = $this->Hana_model->getremisionSAP($query->result_array()[0]["FechaInicio"],$query->result_array()[0]["FechaFinal"],$query->result_array()[0]["IdRuta"],$key["CODIGO"]); //$key["REMISION"];
				$json[$i]["UVENCREDITO"] = $key["UVENCREDITO"];
				$json[$i]["UVENCONTADO"] = $key["UVENCONTADO"];
				$json[$i]["UNIDTOTAL"] = $key["UNIDTOTAL"];
				$json[$i]["SUBTOTALCREDITO"] = $key["SUBTOTALCREDITO"];
				$json[$i]["SUBTOTALCONTADO"] = $key["SUBTOTALCONTADO"];
				$json[$i]["DESCCREDITO"] = $key["DESCCREDITO"];
				$json[$i]["DESCCONTADO"] = $key["DESCCONTADO"];
				$json[$i]["ISCCREDITO"] = $key["ISCCREDITO"];
				$json[$i]["ISCCONTADO"] = $key["ISCCONTADO"];
				$json[$i]["IVACREDITO"] = $key["IVACREDITO"];
				$json[$i]["IVACONTADO"] = $key["IVACONTADO"];
				$json[$i]["TOTALCREDITO"] = $key["TOTALCREDITO"];
				$json[$i]["TOTALCONTADO"] = $key["TOTALCONTADO"];
				$json[$i]["LIBRAS"] = $key["LIBRAS"];
				$i++;
			    $notin .= "'".$key["CODIGO"]."',";

		}
		$notin = substr($notin,0,-1);

		//ITEMS NO VENDIDOS POR EL VENDEDOR
		$artNoVendidos = $this->Hana_model->getArtNoVendidos($query->result_array()[0]["FechaInicio"],$query->result_array()[0]["FechaFinal"],$notin,$query->result_array()[0]["IdRuta"]);
		//print_r($artNoVendidos);4	
		
		foreach ($artNoVendidos as $key) {
			
			$json[$i]["CODIGO"] = $key["CODIGO"];
			$json[$i]["DESCRIPCION"] = $key["DESCRIPCION"];
			$json[$i]["GRAMOS"] = $key["GRAMOS"];
			$json[$i]["PRECIO"] = 0;
			$json[$i]["REMISION"] = $key["EXISTENCIA"];
			$json[$i]["UVENCREDITO"] = 0;
			$json[$i]["UVENCONTADO"] = 0;
			$json[$i]["UNIDTOTAL"] = 0;
			$json[$i]["SUBTOTALCREDITO"] = 0;
			$json[$i]["SUBTOTALCONTADO"] = 0;
			$json[$i]["DESCCREDITO"] = 0;
			$json[$i]["DESCCONTADO"] = 0;
			$json[$i]["ISCCREDITO"] = 0;
			$json[$i]["ISCCONTADO"] = 0;
			$json[$i]["IVACREDITO"] = 0;
			$json[$i]["IVACONTADO"] = 0;
			$json[$i]["TOTALCREDITO"] = 0;
			$json[$i]["TOTALCONTADO"] = 0;
			$json[$i]["LIBRAS"] = 0;
			$e++;
			$i++;
		}
		$retorno = array(
			"periodos" => $query->result_array(),
			"detFacturas" => $queryFactDet->result_array(),
			"detFacturas1" => $json//$queryFactDet1->result_array()
		);

		//echo json_encode($retorno);
		return $retorno;
	}

	
	public function guardarLiquidacion($top,$datos){
		date_default_timezone_set("America/Managua");
		$this->db->trans_begin();
		$mensaje = array();
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1028");
		if($permiso){
			$validacion = $this->validarLiquidacion($top[0]);
			if($validacion){
				$var = $this->db->query("SELECT ISNULL(MAX(IdLiquidacion),0)+1 AS IdLiquidacion FROM Liquidacion");
				$insert = array(
					"IdLiquidacion" => $var->result_array()[0]["IdLiquidacion"],
					"IdPeriodo" => $top[0],
					"IdUsuarioLiquida" => $this->session->userdata('id'),
					"FechaCrea" => gmdate(date("Y-m-d H:i:s")),
					"Impreso" => "N",
					"Tipo" => $top[1],
					"Liquidado" => "Y",
					"Anulado" => "N",
					"IdRuta" => $top[2],
					"UnidadesTotales" => $top[3],
					"SubTotal" => $top[4],
					"Isc" => $top[5],
					"Iva" => $top[6],
					"Total" => $top[7],
					"LibrasVendidas" => $top[8]
				);
				$this->db->insert("Liquidacion",$insert);

				$vardet = $this->db->query("SELECT TOP 1 IdLiquidacion FROM Liquidacion ORDER BY IdLiquidacion DESC");

				if ($vardet->num_rows() > 0){
					for ($i=0; $i < count($datos); $i++) {
						$array = explode(",",$datos[$i]);
						$porcentajemerma = ($array[4])*0.05;
						$merma = 0; $porcentaje = 0;

						if($array[20] > $porcentajemerma){
							$mensaje[0]["tipo"] = "error";
							$mensaje[0]["mensaje"] = "la merma sobrepasa la cantidad permitida
							 para el producto ".$array[0].". La merma permitida es de ".$array[20]." ";
							echo json_encode($mensaje);
						}else{
							$lastid = $this->db->query("SELECT ISNULL(MAX(IdDetalle),0)+1 AS IdDetalle FROM Liquidacion_Detalle");
							@$porcentaje = ($array[20]/ $array[4])*100;

							$valor = is_nan($porcentaje);
							if($valor){
								$merma	= 0;
							}else{
								$merma =  $porcentaje;
							}
							$insertArray = array(
								"IdDetalle" => $lastid->result_array()[0]["IdDetalle"],
								"IdLiquidacion" => $vardet->result_array()[0]["IdLiquidacion"],
								"Codigo" => $array[0],
								"Descripcion" => $array[1],
								"PesoGramos" => $array[2],
								"Precio" => $array[3],
								"Carga" => $array[4],
								"Devolucion" => $array[5],
								"UnidadesVenCredito" => $array[6],
								"UnidadesVenContado" => $array[7],
								"UnidadesVenTotal" => $array[8],
								"SubtotalContado" => $array[9],
								"SubtotalCredito" => $array[10],
								"DescContado" => $array[11],
								"DescCredito" => $array[12],
								"IscContado" => $array[13],
								"IscCredito" => $array[14],
								"IvaContado" => $array[15],
								"IvaCredito" => $array[16],
								"TotalContado" => $array[17],
								"TotalCredito" => $array[18],
								"LibrasVendidas" => $array[19],
								"Merma" => $array[20],
								"PorcentajeMerma" => $merma,
								"Activo" => "Y"
							);

							$this->db->insert("Liquidacion_Detalle",$insertArray);
						}
					}
				}

				$this->cerrarPeriodo($top[0]);

				$mensaje[0]["tipo"] = "success";
				$mensaje[0]["mensaje"] = "Proceso de liquidacion exitoso";
				echo json_encode($mensaje);
			}else{
				$mensaje[0]["tipo"] = "error";
				$mensaje[0]["mensaje"] = "Ya se ha realizado una liquidacion con este período";
				echo json_encode($mensaje);
			}
		}else{
			$mensaje[0]["tipo"] = "error";
			$mensaje[0]["mensaje"] = "No tienes permiso para realizar esta operacion";
			echo json_encode($mensaje);
		}

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}
	}

	//region Validar guardado de liquidacion
	public function validarLiquidacion($numConsec){
		$query = $this->db->where("IdPeriodo", $numConsec)
			->get("Liquidacion");
		if($query->num_rows() > 0){
			return false;
		}else{
			return true;
		}
	}
	//endregion

	//region Actualizar datos del periodo correspondiente una vez se haya liquidado
	public function cerrarPeriodo($IdPeriodo){
		date_default_timezone_set("America/Managua");
		$this->db->where("IdPeriodo", $IdPeriodo);
		$upd = array(
			"Activo" => "N",
			"Liquidado" => "Y",
			"IdUsuarioLiquida" => $this->session->userdata('id'),
			"FechaLiquidacion" => gmdate(date("Y-m-d H:i:s"))
		);
		$this->db->update("Periodos",$upd);
	}
	//endregion

	public function verificarFacturasAct($idperiodo){
		$query = $this->db->query("select IDPERIODO from Facturas where IDPERIODO = '".$idperiodo."' and ESTADOAPP = '1' ");
		if($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}

	public function anularPeriodo($idperiodo){
		$mensaje = array();
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1026");
		if($permiso){
			$verfica = $this->verificarFacturasAct($idperiodo);
			if($verfica){
				$mensaje[0]["mensaje"] = "No se puede anular por que hay facturas activas ligadas
			 a este periodo de liquidacion";
				$mensaje[0]["tipo"] = "error";
				echo json_encode($mensaje);
			}else{
				date_default_timezone_set("America/Managua");
				$this->db->where("IdPeriodo", $idperiodo);
				$data = array(
					"Activo" => "C",
					"Liquidado" => "N",
					"IdUsuarioAnula" => $this->session->userdata('id'),
					"FechaAnula" => gmdate(date("Y-m-d H:i:s"))
				);
				$this->db->update("Periodos",$data);
				$mensaje[0]["mensaje"] = "Periodo anulado";
				$mensaje[0]["tipo"] = "success";
				echo json_encode($mensaje);
			}
		}else{
			$mensaje[0]["mensaje"] = "No tienes permiso para realizar esta operacion";
			$mensaje[0]["tipo"] = "error";
			echo json_encode($mensaje);
		}

	}

	public function liquidacionXUnidad($idperiodo){
		/*pinky code*/
		/*$query = $this->db->query("SELECT IDPERIODO,CODIGO,DESCRIPCION,REMISION, GRAMOS,SUM(UNIDTOTAL) UNIDADES,SUM(LIBRAS) LIBRAS,
									SUM(TOTALCREDITO)+SUM(TOTALCONTADO) AS TOTAL,
									 ROUND(REMISION - SUM(UNIDTOTAL), 2) DEVOLUCION FROM 
									[dbo].[View_Facturas_Liquidaciones] 
									WHERE IDPERIODO = ".$idperiodo."
									GROUP BY IDPERIODO,CODIGO,DESCRIPCION,REMISION,GRAMOS
									ORDER BY CODIGO");*/
        $query = $this->db->query("SELECT  T0.Codigo CODIGO,T0.Descripcion DESCRIPCION, T0.PesoGramos GRAMOS,
									(SELECT MAX(Carga) FROM Liquidacion_Detalle WHERE IdLiquidacion = T0.IdLiquidacion AND Codigo = T0.Codigo) REMISION
									,SUM(UnidadesVenTotal) UNIDADES,
									(SELECT Min(Devolucion) 
									FROM Liquidacion_Detalle WHERE IdLiquidacion = T0.IdLiquidacion AND Codigo = T0.Codigo) DEVOLUCION,
									SUM(t0.LibrasVendidas) LIBRAS,
									SUM(TotalCredito)+SUM(TotalContado) TOTAL,
									SUM(Merma) MERMA
									FROM Liquidacion_Detalle T0
									INNER JOIN Liquidacion t1 on t1.IdLiquidacion = t0.IdLiquidacion 
									WHERE T1.IdPeriodo = ".$idperiodo."
									GROUP BY T0.IdLiquidacion, T0.Codigo,T0.Descripcion, T0.PesoGramos");
		if($query->num_rows() > 0){
			return $query->result_array();
		}
		return 0;
	}

	public function preliquidacionXUnidad($idperiodo){
		/*pinky code*/
		$query = $this->db->query("SELECT IDPERIODO,CODIGO,DESCRIPCION,REMISION, GRAMOS,SUM(UNIDTOTAL) UNIDADES,SUM(LIBRAS) LIBRAS,
									SUM(TOTALCREDITO)+SUM(TOTALCONTADO) AS TOTAL,
									 ROUND(REMISION - SUM(UNIDTOTAL), 2) DEVOLUCION FROM 
									[dbo].[View_Facturas_Liquidaciones] 
									WHERE IDPERIODO = ".$idperiodo."
									GROUP BY IDPERIODO,CODIGO,DESCRIPCION,REMISION,GRAMOS
									ORDER BY CODIGO");
        
		if($query->num_rows() > 0){
			return $query->result_array();
		}
		return 0;
	}

	public function VerDetdalleliquidacion($idPeriodo){
		$query = $this->db->where("IdPeriodo", $idPeriodo)
			->get("Periodos");

		$queryFactDet = $this->db->where("IDPERIODO", $idPeriodo)
			->get("cm_Detalle_Encabezado_Factura");

		//region Mostrar detalle de liquidacion
		$liqenc = $this->db->query("select * from Liquidacion where IdPeriodo = '".$idPeriodo."' ");

		$liqdetalle = $this->db->query("select * from Liquidacion_Detalle
		WHERE IdLiquidacion = '".$liqenc->result_array()[0]["IdLiquidacion"]."' ");
		//endregion

		$retorno = array(
			"periodos" => $query->result_array(),
			"detFacturas" => $queryFactDet->result_array(),
			"liquidaciones" => $liqenc->result_array(),
			"liqDetalles" => $liqdetalle->result_array()
		);
		return $retorno;
	}
}

/* End of file .php */
