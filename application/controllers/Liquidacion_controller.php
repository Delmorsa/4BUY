<?php
/**
 * Created by PhpStorm.
 * User: Sistemas
 * Date: 28/1/2019
 * Time: 07:08
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Liquidacion_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Liquidacion_model");
		$this->load->library("session");
		if ($this->session->userdata("logged") != 1) {
			redirect(base_url() . 'index.php', 'refresh');
		}
	}

	public function index(){
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1023");
		if($permiso){
			$data["lista"] = $this->Liquidacion_model->getPeriodo();
			$data["liq"] = $this->Liquidacion_model->getPeriodoLiq();
			$data["pend"] = $this->Liquidacion_model->getPeriodoPend();
			$data["anul"] = $this->Liquidacion_model->getPeriodoAnul();
			$data["rutas"] = $this->Hana_model->getRutas();
			$this->load->view('header/header');
			$this->load->view('header/menu');
			$this->load->view('liquidacion/periodo_liquidacion',$data);
			$this->load->view('footer/footer');
			$this->load->view('jsView/liquidacion/jsperiodo');
		}else{
			redirect("Error_403", "refresh");
		}
	}

	public function Liquidacion($idperiodo){
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1027");
		if($permiso){
			$this->Login_model->insertLog(
					 $this->session->userdata('id'),
					 "Liquidacion",
					 "El usuario ".$this->session->userdata("User")." ingreso al modulo liquidar ruta",
					 "Hora:".date("Y-m-d H:i:s"),
					 "codigo de periodo ".$idperiodo,
						1
			);
			$var = $this->Liquidacion_model->Liquidacion($idperiodo);
			$unid = $this->Liquidacion_model->preliquidacionXUnidad($idperiodo);
			$liq = $var["periodos"];
			$factdet = $var["detFacturas"];
			$factdet1 = $var["detFacturas1"];
			$data["liq"] = $liq;
			$data["det"] = $factdet;
			$data["det1"] = $factdet1;
			$data["unidades"] = $unid;
			$this->load->view('header/header');
			$this->load->view('header/menu');
			$this->load->view('liquidacion/liquidacion',$data);
			$this->load->view('footer/footer');
			$this->load->view('jsView/liquidacion/jsliquidacion');
		}else{
			redirect("Error_403", "refresh");
		}

	}

	public function VerDetdalleliquidacion($idperiodo){
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1027");
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


	public function guardarPeriodo(){
		$fechaIn = $this->input->get_post("fechaIn");
		$fechaFin = $this->input->get_post("fechaFin");
		$horaInicio = $this->input->get_post("HoraIn");
		$horaFin = $this->input->get_post("HoraFin");
		$ruta = $this->input->get_post("Rutas");
		$this->Liquidacion_model->guardarPeriodo($fechaIn, $fechaFin, $horaInicio, $horaFin,$ruta);
	}

	public function actualizarPeriodo(){
		$id = $this->input->get_post("idperiod");
		$fechaIn = $this->input->get_post("fechaIn");
		$fechaFin = $this->input->get_post("fechaFin");
		$horaInicio = $this->input->get_post("HoraIn");
		$horaFin = $this->input->get_post("HoraFin");
		$ruta = $this->input->get_post("Rutas");
		$Activo = $this->input->get_post("Activo");
		$this->Liquidacion_model->actualizarPeriodo($id, $fechaIn, $fechaFin, $horaInicio, $horaFin,$ruta,$Activo);
	}

	public function guardarLiquidacion(){
		$this->Liquidacion_model->guardarLiquidacion(
			$this->input->post("top"),
			$this->input->post("datos")
		);
	}

	public function exportarExcelLiquidacion($idperiodo){
		$var = $this->Liquidacion_model->VerDetdalleliquidacion($idperiodo);
		$liq = $var["periodos"];
		$factdet = $var["detFacturas"];
		$liqenc = $var["liquidaciones"];
		$liqdet = $var["liqDetalles"];
		$data["liq"] = $liq;
		$data["det"] = $factdet;
		$data["liqenc"] = $liqenc;
		$data["liqdet"] = $liqdet;
		$this->load->view('Exportar/Excel_liquidacion',$data);
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

	public function anularPeriodo($idperiodo){
		$this->Liquidacion_model->anularPeriodo($idperiodo);
	}

	/***************************************************************************/
	//GUARDAR TOTALES LIQUIDACION
	public function guardarTotalesLiquidacion(){
		$idperiodo = $this->input->get_post("idperiodo");
		$idliquidacion = $this->input->get_post("idliquidacion");
		$librasRemision = $this->input->get_post("librasRemision");
		$librasVendidas = $this->input->get_post("librasVendidas");
		$librasDev = $this->input->get_post("librasDev");
		$librasMerma = $this->input->get_post("librasMerma");
		$cargaPaseante = $this->input->get_post("cargaPaseante");
		$this->Liquidacion_model->guardarTotalesLiquidacion($idperiodo,$idliquidacion,$librasRemision,$librasVendidas,$librasDev,$librasMerma,$cargaPaseante);
	}

}

/* End of file Liquidacion_controller.php */
