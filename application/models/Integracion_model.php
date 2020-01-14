<?php
/**
 * Created by PhpStorm.
 * User: Sistemas
 * Date: 12/1/2019
 * Time: 13:09
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Integracion_model extends CI_Model {
	private $db2;
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->db2 = $this->load->database("dbintegracion", true);
		//Do your magic here
	}

	public function getFacturasPendientes($ruta, $fechaInicio, $fechaFin){
		$json = array();
		$i = 0;
		$query = $this->db->query("SELECT
								  t1.*
								  FROM [4BUY].[dbo].[cm_encabezado_facturas] t1
								  inner join Periodos t2 on t1.IDPERIODO = t2.IdPeriodo
								  where t1.CODVENDEDOR = '".$ruta."' and CAST(t1.FECHA AS DATE) BETWEEN '".$fechaInicio."'
								  AND '".$fechaFin."' AND t1.ESTADOAPP = '1' and  t2.Liquidado = 'Y'");
		foreach ($query->result_array() as $key){
			$serie = explode("-",$key["IDFACTURA"]);

			$json["data"][$i]["IDENCABEZADO"] = utf8_encode($key["IDENCABEZADO"]);
			$json["data"][$i]["IDFACTURASERIE"] = $serie[0];
			$json["data"][$i]["IDFACTURACONSECUTIVO"] = $serie[1];
			$json["data"][$i]["CODCONDPAGO"] = $key["CODCONDPAGO"];
			$json["data"][$i]["CONDPAGO"] = $key["CONDPAGO"];
			$json["data"][$i]["COMENTARIOANULACION"] = $key["COMENTARIOANULACION"];
			$json["data"][$i]["FECHA"] = date_format(new DateTime($key["FECHA"]), "Y-m-d H:i:s");
			$json["data"][$i]["TIEMPO"] = $key["TIEMPO"];
			$json["data"][$i]["CODLISTAPRECIO"] = $key["CODLISTAPRECIO"];
			$json["data"][$i]["LISTAPRECIO"] = $key["LISTAPRECIO"];
			$json["data"][$i]["CODCLIENTE"] = $key["CODCLIENTE"];
			$json["data"][$i]["NOMBRE"] = $key["NOMBRE"];
			$json["data"][$i]["NOMBRECOMERCIAL"] = $key["NOMBRECOMERCIAL"];
			$json["data"][$i]["RUC"] = $key["RUC"];
			$json["data"][$i]["CODVENDEDOR"] = $key["CODVENDEDOR"];
			$json["data"][$i]["NOMBREVENDEDOR"] = $key["NOMBREVENDEDOR"];
			$json["data"][$i]["ESTADOAPP"] = $key["ESTADOAPP"];
			$json["data"][$i]["SUBTOTAL"] = number_format($key["SUBTOTAL"],2);
			$json["data"][$i]["DESCUENTO"] = number_format($key["DESCUENTO"],2);
			$json["data"][$i]["ISC"] = number_format($key["ISC"],2);
			$json["data"][$i]["IVA"] = number_format($key["IVA"],2);
			$json["data"][$i]["TOTAL_CREDITO"] = number_format($key["TOTAL_CREDITO"],2);
			$json["data"][$i]["TOTAL_CONTADO"] = number_format($key["TOTAL_CONTADO"],2);
			$json["data"][$i]["Detalles"] = "<a onclick='detalleFactura(".$key["IDENCABEZADO"].")' id='Fact".$key["IDENCABEZADO"]."' href='#' style='text-align:center !important;'
			class='btn btn-sm btn-link btn-block center'><i class='fa fa-expand left'></i></a>";
			$i++;
		}
		echo json_encode($json);
	}

	public function detallesFacturas($idencabezado){
		$json = array();
		$i = 0;
		$query = $this->db->where("IDENCABEZADO" ,$idencabezado)
			->get("cm_Detalle_Encabezado_Factura");

		foreach ($query->result_array() as $key){
			$json[$i]["IDFACTURA"] = $key["IDFACTURA"];
			$json[$i]["FECHA"] = date_format(new DateTime($key["FECHA"]), "Y-m-d");
			$json[$i]["CODVENDEDOR"] = $key["CODVENDEDOR"];
			if($key["CODCONDPAGO"] == -1){
				$json[$i]["CODCONDPAGO"] = "CONTADO";
			}else{
				$json[$i]["CODCONDPAGO"] = "CREDITO";
			}
			$json[$i]["NOMBRE"] = $key["NOMBRE"];
			$json[$i]["NUMLINEA"] = $key["NUMLINEA"];
			$json[$i]["CODIGO"] = $key["CODIGO"];
			$json[$i]["DESCRIPCION"] = $key["DESCRIPCION"];
			$json[$i]["CANTIDAD"] = $key["CANTIDAD"];
			$json[$i]["PRECIO"] = $key["PRECIO"];
			$json[$i]["ISC"] = $key["ISCDET"];
      $json[$i]["IVA"] = $key["IVADET"];
      $json[$i]["TOTAL"] = $key["TOTALDET"];
      $json[$i]["CODALMACEN"] = $key["CODALMACEN"];
			$i++;
		}
		echo json_encode($json);
	}

	public function IntegrarFacturas($ruta, $fechaInicio, $fechaFin,$fechaInt){

		try{
		$Array = array();
		$enc = $this->db->query("SELECT t1.*,t2.Liquidado FROM Facturas t1
								inner join Periodos t2 on t1.IDPERIODO = t2.IdPeriodo
								where t1.CODVENDEDOR = '".$ruta."' and t2.Liquidado = 'Y'
								and CAST(FECHA AS DATE) >= '".$fechaInicio."' AND CAST(FECHA AS DATE) <= '".$fechaFin."'
								AND t1.ESTADOAPP = '1' ORDER BY t1.IDENCABEZADO");
		//$enc = $this->db->query("SELECT * FROM Facturas WHERE 	ESTADOAPP = '1' AND IDFACTURA = 'APP03-000019' ORDER BY IDENCABEZADO");

		$this->db2->trans_begin();
		if($enc->num_rows() > 0){
			foreach ($enc->result_array() as $item) {
				$desc_porcentaje = 0;// ($item["DESCUENTO"]/$item["SUBTOTAL"])*100;

				$this->db2->query("EXEC SP_INSERT_DOCUMENTOS 1,".$item["IDENCABEZADO"].",'I','N','N','O','O','N','RMS','I',13,
                '".$fechaInt."','".$fechaInt."','".$item["CODCLIENTE"]."',
                       '".utf8_encode($item["NOMBRE"])."','".$item["RUC"]."','".$item["IDFACTURA"]."',".$desc_porcentaje.",'COR',
                       'Generado por APP 4BUY',".$item["CODCONDPAGO"].",".$item["CODVENDEDOR"].",116,120,'13',
                       ".$item["TOTAL"].",1,1,''");

				/*echo "EXEC SP_INSERT_DOCUMENTOS 2,'I','N','N','O','O','N','RMS','I',13,
			'".$fechaInt."','".$fechaInt."','".$item["CODCLIENTE"]."',
				   '".utf8_encode($item["NOMBRE"])."','".$item["RUC"]."','".$item["IDFACTURA"]."',0,'COR',
				   'Generado por APP 4BUY',".$item["CODCONDPAGO"].",".$item["CODVENDEDOR"].",116,120,'13',
				   ".$item["TOTAL"].",1,1,'';
			"."<br>";
				echo "--->".$item["IDENCABEZADO"]."<br>";*/

				//$lastId = $this->db2->query("SELECT MAX(IdDoc) as IdDoc FROM SCGRMS_DOCUMENTOS WHERE NumDoc = '".$item["IDENCABEZADO"]."' ");
				$lastId = $this->db2->query("SELECT MAX(IdDoc) as IdDoc FROM SCGRMS_DOCUMENTOS");

				$det = $this->db->query("SELECT * FROM Facturas_Detalles WHERE IDENCABEZADO = '".$item["IDENCABEZADO"]."'");

				if($det->num_rows() > 0){
					foreach ($det->result_array() as $key) {
						$prctDescuento = ($key["DESCUENTO"] / ($key["CANTIDAD"] * $key["PRECIO"])) * 100;
						$this->db2->query("
						EXEC [dbo].[SP_INSERT_DOCUMENTOS_DETALLE]
                                            ".$lastId->result_array()[0]["IdDoc"].",".$item["IDENCABEZADO"].",".$key["NUMLINEA"].",
                                            '".$key["CODIGO"]."',".$key["CANTIDAD"].",".$key["PRECIO"].",'COR',".$prctDescuento.",
                                            ".$key["PRECIOANTDESCUENTO"].",
                                            ".$key["CODALMACEN"].",".$key["CODVENDEDOR"].",'".$key["SUJETOIMP"]."',
                                            '".$key["CODIMPUESTO"]."',1,1,''");
						/*echo "EXEC [dbo].[SP_INSERT_DOCUMENTOS_DETALLE]
                                            ".$lastId->result_array()[0]["IdDoc"].",".$item["IDENCABEZADO"].",".$key["NUMLINEA"].",
                                            '".$key["CODIGO"]."',".$key["CANTIDAD"].",".$key["PRECIO"].",'COR',".$prctDescuento.",
                                            ".$key["PRECIOANTDESCUENTO"].",
                                            ".$key["CODALMACEN"].",".$key["CODVENDEDOR"].",'".$key["SUJETOIMP"]."',
                                            '".$key["CODIMPUESTO"]."',1,1,''"."<br>";*/
					}
				}
				echo "*-*-*-*-*-*-*-*-*-**-*-*-*-*-*-*-*-*-"."<br><br>";
				if ($this->db2->trans_status() === FALSE)
				{
					echo "Error al Ingresar la Factura: ".$item["IDFACTURA"].". Ocurrio el siguiente error:"
						."Intentelo Nuevamente O contacte a sistemas";
					$this->db2->trans_rollback();
				}else{
					$this->db2->trans_commit();
					//exitoso
					echo "Se Ingresaron las siguientes facturas: ".$item["IDFACTURA"]." (".$item["CODCLIENTE"].")"."<br>";

					//region Actualizar estadoapp en 4buy
					$verify = $this->db2->query("select NumRef from SCGRMS_DOCUMENTOS where NumRef ='".$item["IDFACTURA"]."'
					                             and EstadoIntegra = 'I'");
					if($verify->num_rows() > 0){
						foreach ($verify->result_array() as $veri) {
							$this->db->where("IDFACTURA",$veri["NumRef"]);
							$data = array(
								"ESTADOAPP" => 2
							);
							$this->db->update("Facturas",$data);
						}
					}
					//endregion

					$verify1 = $this->db2->query("select NumRef from SCGRMS_DOCUMENTOS where NumRef ='".$item["IDFACTURA"]."' ");
					if($verify1->num_rows() > 0){
						for ($i = 0; $i < count($verify1->result_array()); $i++){
							$Array[] = $verify1->result_array()[$i]["NumRef"];
						}
					}
					$consultafinal = $this->db->query('SELECT IDFACTURA FROM Facturas WHERE IDPERIODO = '.$item["IDPERIODO"].'
					                                   AND IDFACTURA NOT IN ('."'".implode("','",$Array)."'".')');
					if($consultafinal->num_rows() > 0){
						echo "Las siguientes facturas no se pudieron integrar";
						echo "<br><br>";
						print_r($consultafinal->result_array());
					}

					$update = array(
						"CodCompania" => 1
					);
					$this->db2->update("SCGRMS_DOCUMENTOS", $update);

					$update1 = array(
						"CodCompania" => 1
					);
					$this->db2->update("SCGRMS_PAGOS", $update1);

				}
			}
		}
		}catch(Exception $e) {
	    echo $e->getMessage();
	  }
	}

	public function VerificarEstadoIntegrado(){
		$estadoY = $this->db2->query("select NumRef,EstadoIntegra from SCGRMS_DOCUMENTOS where EstadoIntegra = 'Y'
									  and NumRef like '%APP%' ");
		if($estadoY->num_rows() > 0){
			foreach ($estadoY->result_array() as $item) {
				$this->db->where("IDFACTURA",$item["NumRef"]);
				$this->db->where("ESTADOAPP",2);
				$data = array(
					"ESTADOAPP" => 3
				);
				$this->db->update("Facturas",$data);
			}
		}
	}

}

/* End of file .php */
