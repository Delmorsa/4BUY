<?php
/**
 * Created by Cesar Mejía.
 * User: Sistemas
 * Date: 13/2/2019 15:35 2019
 * FileName: Reportes_controller.php
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('Reportes_model');
		if ($this->session->userdata("logged") != 1) {
			redirect(base_url() . 'index.php', 'refresh');
		}
	}

	public function index()
	{
		/*
		 Code Here
		*/
	}

	public function consolidadoRem()
	{
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1031");
		if($permiso){
			$data["rutas"] = $this->Hana_model->getRutas();
			$this->load->view('header/header');
			$this->load->view('header/menu');
			$this->load->view('Reportes/consolidado_remisiones',$data);
			$this->load->view('footer/footer');
			$this->load->view('jsView/reportes/jsconsolidadoRem');
		}else{
			redirect("Error_403", "refresh");
		}
	}

	public function consolidadoRems(){
		$fechaEntrega = $this->input->get_post("fecha");
		$fechaFin = $this->input->get_post("fechaFin");
		$codRubri = $this->input->get_post("rubro");
		$codTipo = $this->input->get_post("tipo");
		$ruta = $this->input->get_post("ruta");
		$this->Reportes_model->consolidadoRem($fechaEntrega,  $fechaFin, $codRubri, $codTipo, $ruta);
		
	}

	public function detConsolidadoAjax($fechaEntrega, $codRubro, $codTipo, $IdUsuario,$codRuta){
		$this->Reportes_model->detConsolidadoAjax($fechaEntrega, $codRubro, $codTipo, $IdUsuario,$codRuta);
	}

	//region Reportes Remisiones
	public function RemisionesReport()
	{
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1032");
		if($permiso){
			$data["rutas"] = $this->Hana_model->getRutas();
			$this->load->view('header/header');
			$this->load->view('header/menu');
			$this->load->view('Reportes/reportes_remisiones',$data);
			$this->load->view('footer/footer');
			$this->load->view('jsView/reportes/jsreportes_remisiones');
		}else{
			redirect("Error_403", "refresh");
		}
	}

	public function reportesRemision(){
		$fechaEntrega = $this->input->get_post("fechaEntrega");
		$fechaFin = $this->input->get_post("fechaFin");
		$codRubro = $this->input->get_post("codRubro");
		$codTipo = $this->input->get_post("codTipo");
		$ruta = $this->input->get_post("ruta");
		$this->Reportes_model->reportesRemision($fechaEntrega, $fechaFin, $codRubro, $codTipo, $ruta);
	}

	public function detallesReportesRemision($idRemision){
		$this->Reportes_model->detallesReportesRemision($idRemision);
	}
	//endregion

	public function rptventas()
	{
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1038");
		if($permiso){
			$data["rutas"] = $this->Hana_model->getRutas();
			$this->load->view('header/header');
			$this->load->view('header/menu');
			$this->load->view('Reportes/reporte_venta',$data);
			$this->load->view('footer/footer');
			$this->load->view('jsView/reportes/jsrpt_Ventas');
		}else{
			redirect("Error_403", "refresh");
		}
	}
	public function filtrarrptventas()
	{
		$fechaEntrega = $this->input->get_post("fechaEntrega");
		$fechaFin = $this->input->get_post("fechaFin");		
		$ruta = $this->input->get_post("ruta");
		$this->Reportes_model->filtrarrptventas($fechaEntrega, $fechaFin, $ruta);
	}
	public function rptconsecutivos()
	{			
		$this->load->view('header/header');
		$this->load->view('header/menu');
		$this->load->view('Reportes/reporte_consecutivos');
		$this->load->view('footer/footer');
		$this->load->view('jsView/reportes/jsreportes_consecutivos');
		
	}
	public function reportesConsecutivos(){
		$fechaInicio = $this->input->get_post("fecha1");
		$fechaFin = $this->input->get_post("fecha2");		
		$this->Reportes_model->reportesConsecutivos($fechaInicio, $fechaFin,true);
	}

	public function printConsecutivos($fecha1,$fecha2)
	{
		$data["consec"] = $this->Reportes_model->reportesConsecutivos($fecha1, $fecha2,false);		
		$this->load->view('Exportar/Excel_Consecutivos',$data);	
	}

	public function reporteConsecutivosDetallado(){
		$fechaInicio = $this->input->get_post("fecha1");
		$fechaFin = $this->input->get_post("fecha2");
		$this->Reportes_model->reporteConsecutivosDetallado($fechaInicio, $fechaFin,true);		
	}

    public function printConsecutivosDetallado($fecha1,$fecha2)
	{
		$data["consec"] = $this->Reportes_model->reporteConsecutivosDetallado($fecha1,$fecha2,false);			
		$this->load->view('Exportar/Excel_ConsecutivosDetallado',$data);	
	}

	public function rptDevolucionesRuta(){
		$this->load->view('header/header');
			$this->load->view('header/menu');
			$this->load->view('Reportes/reporte_devoluciones_rutas');
			$this->load->view('footer/footer');
			$this->load->view('jsView/reportes/jsreporte_devoluciones');
	}

	public function devolucionesPorRutas()
	{
		$fecha1 = $this->input->get_post("fecha1");
		$fecha2 = $this->input->get_post("fecha2");
		$this->Reportes_model->devolucionesPorRutas($fecha1,$fecha2,true);
	}

	public function printDevoluciones($fecha1,$fecha2)
	{
		$data["dev"] = $this->Reportes_model->devolucionesPorRutas($fecha1,$fecha2,false);		
		$this->load->view('Exportar/Excel_Devoluciones',$data);	
	}

	public function createXLS($fecha1,$fecha2){
		//crear el nombre del archivo
		$fileName = 'Consecutivos-'.time().'.xlsx';
		//cargar libreria excel
		$this->load->library("excel");
		$empInfo = $this->Reportes_model->reportesConsecutivos($fecha1,$fecha2,false);
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		//setear header o encabezado
		$objPHPExcel->getActiveSheet()->SetCellValue('A1','SERIE');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1','MINIMO');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1','MAXIMO');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1','N° FACTURAS');
		//Setear las filas
		$rowCount = 2;
		foreach ($empInfo as $key) {
			$objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $key["SERIE"]);
			$objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, $key["MINIMO"]);
			$objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $key["MAXIMO"]);
			$objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $key["NUMERO"]);
			$rowCount++;
		}
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save($fileName);
		//Descargar archivo
		header("Content-Type: application/vnd.ms-excel");
		redirect(base_url().$fileName);
    }

    public function createXLSDetallado($fecha1,$fecha2){
		//crear el nombre del archivo
		$fileName = 'ConsecutivosDetallado-'.time().'.xlsx';
		//cargar libreria excel
		$this->load->library("excel");
		$empInfo = $this->Reportes_model->reporteConsecutivosDetallado($fecha1,$fecha2,false);
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		//setear header o encabezado
		$objPHPExcel->getActiveSheet()->SetCellValue('A1','SERIE');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1','CONSECUTIVO');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1','RUTA');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1','NOMBRE VENDEDOR');
		//Setear las filas
		$rowCount = 2;
		foreach ($empInfo as $key) {
			$objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $key["SERIE"]);
			$objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, $key["Consecutivo"]);
			$objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $key["CODVENDEDOR"]);
			$objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $key["Nombre"]);
			$rowCount++;
		}
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save($fileName);
		//Descargar archivo
		header("Content-Type: application/vnd.ms-excel");
		redirect(base_url().$fileName);
    }

    public function ReporteDeVentas(){

    	$fechainicio = $this->input->get_post("fechainicio");
    	$fechafinal = $this->input->get_post("fechafinal");
    	$codRuta = $this->input->get_post("ruta");
    	$this->Reportes_model->reporteDeVentas($fechainicio,$fechafinal,$codRuta,true);	
		}

		//region Reporte de Merma
		public function viewReporteMerma(){
			$this->load->view('header/header');
			$this->load->view('header/menu');
			$this->load->view('Reportes/reporte_mermas');
			$this->load->view('footer/footer');
			$this->load->view('jsView/reportes/jsreporte_merma');
		}

		public function reporteMermas(){
			$rango1 = $this->input->get_post("rango1");
			$rango2 = $this->input->get_post("rango2");
			$this->Reportes_model->reporteMermas($rango1,$rango2,TRUE);
		}

		public function encabezadoMerma(){
			$rango1 = $this->input->get_post("rango1");
			$rango2 = $this->input->get_post("rango2");
			$this->Reportes_model->encabezadoMerma($rango1,$rango2,TRUE);
		}

		public function printReporteMermas($rango1,$rango2)
		{
			$data["mermas"] = $this->Reportes_model->reporteMermas($rango1,$rango2,false);	
			$data["enc"] = $this->Reportes_model->encabezadoMerma($rango1,$rango2,false);			
			$data["enc2"] = $this->Reportes_model->encabezadoMerma2($rango1,$rango2,false);			
			$this->load->view('Exportar/Exportar_Mermas',$data);	
		}
		/*******************************************************************************************************/
		public function viewReporteVentasDeposito(){
			$data["foraneos"] = $this->Reportes_model->mostrarVendForaneos();
			$this->load->view('header/header');
			$this->load->view('header/menu');
			$this->load->view('Reportes/reporte_venta_deposito',$data);
			$this->load->view('footer/footer');
			$this->load->view('jsView/reportes/jsreporte_ventas_deposito');
		}

		public function reporteDeVentasDeposito(){
			$fechaInicio = $this->input->get_post("fechainicio");
			$fechaFin = $this->input->get_post("fechafinal");
			$ruta = $this->input->get_post("ruta");
			$this->Reportes_model->reporteDeVentasDeposito($fechaInicio,$fechaFin,$ruta,true);
		}

		public function printReporteDeVentasDeposito($fechaInicio,$fechaFin,$ruta)
		{
			$data["ventasdep"] = $this->Reportes_model->reporteDeVentasDeposito($fechaInicio,$fechaFin,$ruta,false);
			$this->load->view('Exportar/Exportar_ventasDeposito',$data);	
		}
		//end region

		public function VerificarNotificacionAntiguedad()
		{
			//poner permiso

			$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1041");
			if($permiso){
				$this->Hana_model->VerificarNotificacionAntiguedad();
			}else{
				return "noTIne permiso";
			}
		}

		public function pagoProveedores()
		{
			
			$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1041");
			if($permiso){
				$data["pagos"] = $this->Hana_model->getPagosProveedores();
				echo json_encode($data["pagos"]);
				$this->load->view('header/header');
				$this->load->view('header/menu');
				$this->load->view('proveedores/proveedores',$data);
				$this->load->view('footer/footer');			
			}else{
				redirect("Error_403", "refresh");
			}
		}

		public function Reporte_facturas_empleados()
		{
			$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1059");
			if($permiso){
				$data["foraneos"] = $this->Hana_model->getRutas();
				$this->load->view('header/header');
				$this->load->view('header/menu');
				$this->load->view('Reportes/reporte_facturas_empleado',$data);
				$this->load->view('footer/footer');
				$this->load->view('jsView/reportes/jsreporte_facturas_empleados');
			}else{
				redirect("Error_403", "refresh");
			}
		}

		
		public function ajax_reporte_facturas_empleados(){
			$fechaInicio = $this->input->get_post("fechainicio");
			$fechaFin = $this->input->get_post("fechafinal");
			$ruta = $this->input->get_post("ruta");
			$this->Reportes_model->ajax_reporte_facturas_empleados($fechaInicio,$fechaFin,$ruta,true);
		}
}

/* End of file Controllername.php */
