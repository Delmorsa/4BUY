<?php
/**
 * Created by PhpStorm.
 * User: Sistemas
 * Date: 28/1/2019
 * Time: 07:07
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Canales_model extends CI_Model
{
	public function __construct(){
		parent::__construct();
		$this->load->database();
	}

	
	public function getCanales()
	{
		$query = $this->db->order_by("IdCanal",'ASC')
			    ->where("Estado" ,true)
				->get("C_Canal");
		if ($query->num_rows() > 0){
			return $query->result_array();
		}
		return 0;
	}

	public function getAllCanales()
	{
		$query = $this->db->order_by("IdCanal",'ASC')
			    //->where("Estado" ,true)
				->get("C_Canal");
		if ($query->num_rows() > 0){
			return $query->result_array();
		}
		return 0;
	}

	public function GuardarCanal($nombre)
	{
		$this->db->trans_begin();
		$mensaje = array();

		date_default_timezone_set("America/Managua");

		$existe = $this->db->query("SELECT * FROM C_Canal WHERE LOWER(Nombre) = LOWER('".$nombre."')");


		if ($existe->num_rows()>0) {
			$mensaje[0]["mensaje"] = "Ya existe un canal con esta descripción";
			$mensaje[0]["tipo"] = "error";
			echo json_encode($mensaje);
			$this->db->trans_rollback();
			return;
		}


		$encabezado = array(
			"Nombre" => $nombre,
			"Descripcion" => $nombre,
			"IdUsuarioCrea" => $this->session->userdata("id"),
			"FechaCrea" => 	gmdate(date("Y-m-d H:i:s")),		
			"Estado" => true
			
		);
		$inserto = $this->db->insert("C_Canal",$encabezado);

		if ($inserto) {				
			$mensaje[0]["mensaje"] = "Datos guardados correctamente";
			$mensaje[0]["tipo"] = "success";
			$this->db->trans_commit();
			echo json_encode($mensaje);
			return;				
		}else{
			$mensaje[0]["mensaje"] = "No se pudo guardar el canal intentelo nuevamente";
			$mensaje[0]["tipo"] = "error";
			echo json_encode($mensaje);
			$this->db->trans_rollback();
			return;
		}
		if ($this->db->trans_status() === FALSE)
		{
		        $this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}	
	}

	public function EditarCanal($IdCanal,$nombre)
	{
		$this->db->trans_begin();
		$mensaje = array();

		date_default_timezone_set("America/Managua");

		$existe = $this->db->query("SELECT * FROM C_Canal WHERE IdCanal = ".$IdCanal." AND Estado = 1");

		if ($existe->num_rows()==0) {
			$mensaje[0]["mensaje"] = "No se pudo editar el canal, el canal esta inactivo o no existe";
			$mensaje[0]["tipo"] = "error";
			echo json_encode($mensaje);
			$this->db->trans_rollback();
			return;
		}

		$encabezado = array(
			"Nombre" => $nombre,
			"Descripcion" => $nombre,
			"IdUsuarioEdita" => $this->session->userdata("id"),
			"FechaEdita" =>	gmdate(date("Y-m-d H:i:s"))
		);

		$this->db->where('IdCanal',$IdCanal);
		$inserto = $this->db->update("C_Canal",$encabezado);

		if ($inserto) {
			$mensaje[0]["mensaje"] = "Datos editados correctamente";
			$mensaje[0]["tipo"] = "success";
			$this->db->trans_commit();
			echo json_encode($mensaje);
			return;				
		}else{
			$mensaje[0]["mensaje"] = "No se pudo editar el canal intentelo nuevamente";
			$mensaje[0]["tipo"] = "error";
			echo json_encode($mensaje);
			$this->db->trans_rollback();
			return;
		}

		if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}	
	}
	
	
	public function GuardarBajaCanal($IdCanal,$estado)
	{
		$this->db->trans_begin();
		$mensaje = array();

		date_default_timezone_set("America/Managua");

		$existe = $this->db->query("SELECT * FROM C_Canal WHERE IdCanal = ".$IdCanal."");

		if ($existe->num_rows()==0) {
			$mensaje[0]["mensaje"] = "No se pudo editar el canal, el canal no existe";
			$mensaje[0]["tipo"] = "error";
			echo json_encode($mensaje);
			$this->db->trans_rollback();
			return;
		}

		$encabezado = array(
			"Estado" => !$estado,			
			"IdUsuarioEdita" => $this->session->userdata("id"),
			"FechaEdita" =>	gmdate(date("Y-m-d H:i:s"))
		);

		$this->db->where('IdCanal',$IdCanal);
		$inserto = $this->db->update("C_Canal",$encabezado);

		if ($inserto) {
			$mensaje[0]["mensaje"] = "Datos editados correctamente";
			$mensaje[0]["tipo"] = "success";
			$this->db->trans_commit();
			echo json_encode($mensaje);
			return;				
		}else{
			$mensaje[0]["mensaje"] = "No se pudo editar el canal intentelo nuevamente";
			$mensaje[0]["tipo"] = "error";
			echo json_encode($mensaje);
			$this->db->trans_rollback();
			return;
		}

		if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}	
	}

	public function VendedoresCanalaesAjax($idCanal,$filtro)
	{
		$json = array();
		$i = 0;

		$query = $this->db->query("SELECT * FROM C_Rutas 
			WHERE IdRuta NOT IN (SELECT IdRuta FROM C_RutaCanal WHERE IdCanal = ".$idCanal." and Estado = 1) and Nombre like '%Vended%'
			and IdRuta NOT IN (SELECT IdRuta FROM C_RutaCanal WHERE IdCanal <> ".$idCanal." and Estado = 1)
			and Nombre like '%".$filtro."%'");

		foreach ($query->result_array() as $key) {
			$json[$i]["IdRuta"] = $key["IdRuta"];
			$json[$i]["Nombre"] = $key["Nombre"];
			$i++;
		}

		echo json_encode($json);
	}

	public function traerRutasCanal($id_canal)
	{
		$query = $this->db->query("SELECT t0.* 
										FROM C_Rutas t0
										inner join  C_RutaCanal t1 on t1.IdRuta = T0.IdRuta
										inner join C_Canal t2 on t2.IdCanal = T1.IdCanal
										where t2.IdCanal = ".$id_canal." and t1.Estado = 1");	

       
        $json = array();
        $i = 0;
        if ($query->num_rows() > 0) {
            /*if ($bandera==false) {
                return $query->result_array();
            }*/
            foreach ($query->result_array() as $key) {
                $json["data"][$i]["IdRuta"] = $key["IdRuta"];
                $json["data"][$i]["Nombre"] = $key["Nombre"];                
                $json["data"][$i]["Opcion"] = '
                <a href="javascript:void(0)" class="btn btn-xs btn-danger rowDelete"><i class="fa fa-trash-o"></i></a>';
                $i++;
            }
            echo json_encode($json);
            return;
        }
        echo 0;
        return;

	}

	public function GuardarVendedoresCanal($idCanal,$detalle)
	{
		$existe = $this->db->query("SELECT * FROM C_Canal WHERE IdCanal = ".$idCanal." and Estado = 1");
		$inserto=null;$update=null;
		if ($existe->num_rows()>0) {

			$this->db->trans_begin();
			//inactivo todas las rutas para volver a activarlas
			$update = $this->db->query("UPDATE C_RutaCanal SET Estado = 0 WHERE IdCanal = ".$idCanal."");

			if ($update) {//si todo sale bien reactivo el estado si existe o ingreso la nueva fila


				$det = json_decode($detalle, true);

				foreach ($det as $obj) {
					//echo "SELECT * FROM C_RutaCanal WHERE IdCanal = ".$idCanal." and IdRuta = ".$obj[0]."";
					$existe = $this->db->query("SELECT * FROM C_RutaCanal WHERE IdCanal <> ".$idCanal." and IdRuta = ".$obj[0]." and Estado = 1");
					if ($existe->num_rows()>0) {
						$mensaje[0]["mensaje"] = "La ruta: ".$obj[1]." esta asignada a otro canal, eliminela del otro canal para continuar";
						$mensaje[0]["tipo"] = "error";
						echo json_encode($mensaje);
						$this->db->trans_rollback();
						return;
					}

					$existe = $this->db->query("SELECT * FROM C_RutaCanal WHERE IdCanal = ".$idCanal." and IdRuta = ".$obj[0]."");
					if ($existe->num_rows()>0) {
						$update = array(
							"IdUsuarioEdita" => $this->session->userdata("id"),
							"FechaEdita" =>	gmdate(date("Y-m-d H:i:s")),
							"Estado" => 1
						);
						$this->db->where('IdCanal',$idCanal);
						$this->db->where('IdRuta',$obj[0]);

						$update = $this->db->update('C_RutaCanal',$update);
					}else{
						$insert = array(
							"IdRuta" => $obj[0],
							"IdCanal" => $idCanal,
							"IdUsuarioCrea" => $this->session->userdata("id"),
							"FechaCrea" => 	gmdate(date("Y-m-d H:i:s")),
							"Estado" => 1
						);

						$update = $this->db->insert('C_RutaCanal',$insert);
					}
				}
				if ($inserto || $update) {
					$mensaje[0]["mensaje"] = "Datos guardados correctamente";
					$mensaje[0]["tipo"] = "success";
					$this->db->trans_commit();
					echo json_encode($mensaje);
					return;
				}

				$mensaje[0]["mensaje"] = "Ha ocurrido un error inesperado";
				$mensaje[0]["tipo"] = "error";
				echo json_encode($mensaje);
				$this->db->trans_rollback();
				return;
			}
		}

		$mensaje[0]["mensaje"] = "No se pudo editar el canal ya que se encuentra inactivo o no existe";
		$mensaje[0]["tipo"] = "error";
		echo json_encode($mensaje);
		$this->db->trans_rollback();
		return;
		
	}
}

/* End of file .php */