<?php
/**
 * Created by PhpStorm.
 * User: Sistemas
 * Date: 28/1/2019
 * Time: 07:08
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Articulos_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Liquidacion_model");
		$this->load->model("Articulos_model");
		$this->load->library("session");
		if ($this->session->userdata("logged") != 1) {
			redirect(base_url() . 'index.php', 'refresh');
		}
	}

	
	public function index(){
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1060");
		if($permiso){
			$data["lista"] = $this->Articulos_model->getArticulos();			
			$this->load->view('header/header');
			$this->load->view('header/menu');
			$this->load->view('comisiones/Articulos',$data);
			$this->load->view('footer/footer');
			$this->load->view('jsView/comisiones/jsarticulos');
		}else{
			redirect("Error_403", "refresh");
		}
	}

	public function actualizarArticulos()
	{
		//$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1060");
		
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1060");
		if($permiso){
			$this->Hana_model->actualizarArticulos();
		} else {
			$mensaje[0]["mensaje"] = "no tiene permiso";
		    $mensaje[0]["tipo"] = "error";
		    //$this->db->trans_commit();
		    echo json_encode($mensaje);
		    return;
		}
	}

	public function CrearPeriodoCongelacion(){
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1060");
		if($permiso){

			$data["bodegas"] = $this->Hana_model->getBodegas();
			//echo json_encode($data);
			$this->load->view('header/header');
			$this->load->view('header/menu');
			$this->load->view('congelacion/CrearPeriodoCongelacion',$data);
			$this->load->view('footer/footer');
			$this->load->view('jsView/congelacion/jscongelacion');
		}else{
			redirect("Error_403", "refresh");
		}
	}

	public function guardarCongelacion()
	{
		$this->Congelacion_model->guardarCongelacion($this->input->get_post("codBodega"),$this->input->get_post("descBodega"),$this->input->get_post("desde"),$this->input->get_post("hasta"),$this->input->get_post("CodCategoria"),$this->input->get_post("Categoria"),$this->input->get_post("datos"));

	}

	public function filtrarExistenciaLotes()
	{
		$this->Hana_model->filtrarExistenciaLotes($this->input->get_post("bodega"),$this->input->get_post("desde"),$this->input->get_post("hasta"));
	}

	public function VerDetdalleliquidacion($idperiodo){
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1060");
		if($permiso){
			$var = $this->Liquidacion_model->VerDetdalleliquidacion($idperiodo);
			$unid = $this->Liquidacion_model->liquidacionXUnidad($idperiodo);
			$liq = $var["periodos"];
			$factdet = $var["detFacturas"];
			$liqenc = $var["liquidaciones"];
			$liqdet = $var["liqDetalles"];
			$data["liq"] = $liq;
			$data["det"] = $factdet;
			$data["liqenc"] = $liqenc;
			$data["liqdet"] = $liqdet;
			$data["unidades"] = $unid;
			$this->load->view('header/header');
			$this->load->view('header/menu');
			$this->load->view('liquidacion/detalle_liquidacion',$data);
			$this->load->view('footer/footer');
			$this->load->view('jsView/liquidacion/jsliquidacion');
		}else{
			redirect("Error_403", "refresh");
		}

	}
	

	public function exportarExcelLiquidacionUnidades($idperiodo){
		$var = $this->Liquidacion_model->VerDetdalleliquidacion($idperiodo);
		$liq = $var["periodos"];
		$factdet = $var["detFacturas"];
		$factdet1 = $this->Liquidacion_model->liquidacionXUnidad($idperiodo);
		$data["liq"] = $liq;
		$data["det"] = $factdet;
		$data["det1"] = $factdet1;
		$this->load->view('Exportar/Excel_liquidacion_unidades',$data);
	}

	public function verReporteInventario($id)
	{
		//$data["datos"] = $this->Congelacion_model->verReporteInventario($id);
		//echo json_encode($data["datos"]);
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1060");
		if($permiso){
			$data["datos"] = $this->Congelacion_model->verReporteInventario($id);			
			$this->load->view('header/header');
			$this->load->view('header/menu');
			$this->load->view('congelacion/reporteCongelacion',$data);
			$this->load->view('footer/footer');
			$this->load->view('jsView/congelacion/jscongelacionReporte');
		}else{
			redirect("Error_403", "refresh");
		}
	}
	

}

/* End of file Liquidacion_controller.php */
