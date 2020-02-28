<?php
/**
 * Created by Cesar.
 * User: Cesar
 * Date: 11/1/2019
 * Time: 12:02
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Facturas_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function mostrarFacturas($start,$length,$search,$fecha1,$fecha2,$ruta,$tipo){
		$vendedor= ''; $filter_dates = ""; $filter_rout = ""; $filter_tipo = "";
		$array = array();
		$srch = '';
		if($this->session->userdata("IdRol") == 4){
			$vendedor = $this->db->select("CODVENDEDOR")
				->where("CODVENDEDOR", $this->session->userdata("IdRuta"))
				->get("Facturas");
		}else{
			$vendedor = $this->db->select("CODVENDEDOR")
						->get("Facturas");
		}

		for ($i=0; $i < count($vendedor->result_array()); $i++) {
			$array[] = $vendedor->result_array()[$i]["CODVENDEDOR"];
		}

		$filter_rout = "CODVENDEDOR IN ("."'".implode("','",$array)."'".")";

		/*******************************PARAMETROS DEL FILTRO************************************************/
		if($fecha1 && $fecha2){
			$filter_dates = "and cast(FECHA as DATE) >= '".$fecha1."' and cast(FECHA as DATE) <= '".$fecha2."' ";
		}
		if($ruta){
			$filter_rout = "";
			$filter_rout = "CODVENDEDOR = '".$ruta."' ";
		}

		if($tipo){
			$filter_tipo = "and CODCONDPAGO = '".$tipo."' ";
		}
		/*******************************PARAMETROS DEL FILTRO************************************************/

		if ($search) {
			$srch = 'and ( IDENCABEZADO LIKE '."'%".$search."%'".' OR
      					   IDFACTURA LIKE '."'%".$search."%'".' OR
      					   FECHA LIKE '."'%".$search."%'".' OR
      					   TIEMPO LIKE '."'%".$search."%'".' OR
      					   CODCLIENTE LIKE '."'%".$search."%'".' OR
      					   NOMBRE LIKE '."'%".$search."%'".' OR
      					   NOMBRECOMERCIAL LIKE '."'%".$search."%'".' OR
      					   CODVENDEDOR LIKE '."'%".$search."%'".' OR
      					   SUBTOTAL LIKE '."'%".$search."%'".' OR
      					   DESCUENTO LIKE '."'%".$search."%'".' OR
      					   ISC LIKE '."'%".$search."%'".' OR
      					   IVA LIKE '."'%".$search."%'".' OR
      					   TOTAL LIKE '."'%".$search."%'".'
                        )';
		}

		$qnr = "SELECT count(1) 'Cantidad' FROM  Facturas where  ".$filter_rout."
		 		   ".$filter_dates." ".$srch;
		$qnr = $this->db->query($qnr);
		$qnr = $qnr->result_array()[0]["Cantidad"];


		if($length == -1){
			$q = $this->db->query("select * from cm_encabezado_facturas WHERE  ".$filter_rout."
			                   ".$filter_dates." ".$filter_tipo."  ".$srch."
							   order by IDFACTURA DESC");
		}else{
			$q = $this->db->query("select * from cm_encabezado_facturas where  ".$filter_rout."
			                   ".$filter_dates." ".$filter_tipo." ".$srch."
							   order by IDFACTURA DESC OFFSET ".$start." ROWS FETCH NEXT ".$length." ROWS ONLY;");
		}

		$retornar = array(
			"numDataTotal" => $qnr, 
			"datos" => $q
		);
		return $retornar;
	}

	public function detallesFacturas($idencabezado){
		$query = $this->db->order_by("NUMLINEA")
			    ->where("IDENCABEZADO" ,$idencabezado)
				->get("cm_Detalle_Encabezado_Factura");
		if ($query->num_rows() > 0){
			return $query->result_array();
		}
		return 0;
	}

	//region Cargar facturas del mes actual para los vendedores
	public function facturasMesActual($CodVendedor){
		$query = $this->db->where("CODVENDEDOR", $CodVendedor)
			     ->get("cm_Facturas_Mes_Actual");
		if($query->num_rows() > 0){
			return $query->result_array();
		}
		return 0;
	}
	//endregion

	public function getRutasFacturas(){
		$query = $this->db->distinct()
				->select("CODVENDEDOR")
			    ->order_by("CODVENDEDOR ASC")
				->get("Facturas");
		if($query->num_rows() > 0){
			return $query->result_array();
		}
		return 0;
	}

	public function getListaFactAnul(){
		$query = $this->db->where("ESTADO",0)
				->get("Facturas_Anulacion");
		if($query->num_rows() > 0){
			return $query->result_array();
		}
		return 0;
	}

	//region Actualizar item de detalle factura
	public function actualizarItemFactura($iddetalle, $cant, $total){
		$mensaje = array();
		/*$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1036");
		if($permiso){*/
			$this->db->where("IDDETALLE", $iddetalle);
			$data = array(
				"CANTIDAD" => $cant,
				"TOTAL" => $total
			);
			$updt = $this->db->update("Facturas_Detalles", $data);
			if($updt){
				$mensaje[0]["tipo"] = "success";
				$mensaje[0]["mensaje"] = "Datos actualizados con éxito";
				echo json_encode($mensaje);
			}else{
				$mensaje[0]["tipo"] = "error";
				$mensaje[0]["mensaje"] = "Error inesperado al actualizar los datos del producto,
			si el problema persiste contáctece con el administrador";
				echo json_encode($mensaje);
			}
		/*}else{
			$mensaje[0]["tipo"] = "error";
			$mensaje[0]["mensaje"] = "No tienes permiso para realizar esta operacion";
			echo json_encode($mensaje);
		}*/
	}
	//endregion

}

/* End of file .php */
