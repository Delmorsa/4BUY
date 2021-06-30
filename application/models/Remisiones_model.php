<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("America/Managua");
class Remisiones_model extends CI_Model {

	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
	}

	public function ultimoConsecutivo(){
		$res = array();
		$var = $this->db->query("SELECT ISNULL(MAX(Consecutivo),0)+1 AS Consecutivo FROM Remisiones");
		if($var->num_rows() > 0){
			$res[0]["Consecutivo"] =  $var->result_array()[0]["Consecutivo"];
			echo json_encode($res);
		}
	}

	public function guardarRemision($top,$datos){
		$this->db->trans_begin();
		$mensaje = array();
		$validacion = $this->validarConsecutivo($top[7]);
		if($validacion){
			$mensaje["mensaje"] = "guarda";
			$cons = $this->db->query("SELECT ISNULL(MAX(Consecutivo),0)+1 AS Consecutivo FROM Remisiones");
			$var = $this->db->query("SELECT ISNULL(MAX(IdRemision),0)+1 AS IdRemision FROM Remisiones");
			$insert = array(
				"IdRemision" => $var->result_array()[0]["IdRemision"],
				"IdUsuario" => $this->session->userdata("id"),
				"IdRuta" => $top[0],
				"Vendedor" => $top[1],
				"Rubro" => $top[2],
				"FechaEntrega" => gmdate($top[3]),
				"FechaLiq" => gmdate($top[4]),
				"Tipo" => $top[5],
				"Referencia" => $top[6],
				"Consecutivo" => $cons->result_array()[0]["Consecutivo"],
				"Estado" => 1,
				"MontoTotal" => $top[7],
				"Comentario" => $top[8],
				"FechaCrea" => gmdate(date("Y-m-d h:i:s")),
				"NombreRotador" => $top[9]
			);
			$this->db->insert("Remisiones",$insert);

			echo json_encode($mensaje);
			$var = $this->db->query("SELECT TOP 1 IdRemision FROM Remisiones ORDER BY IdRemision DESC");

			if ($var->num_rows() > 0){
				for ($i=0; $i < count($datos); $i++) {
					$array = explode(",",$datos[$i]);
					$insertArray = array(
						"IdRemision" => $var->result_array()[0]["IdRemision"],
						"CodigoProd" => $array[0],
						"DescripcionProd" => $array[1],
						"LBS" => $array[2],
						"Cantidad" => $array[3],
						"CantidadLBS" => $array[4],
						"Precio" => $array[5]
					);

					$this->db->insert("DetalleRemision",$insertArray);
				}
			}
		}else{
			$mensaje["mensaje"] = "noguarda";
			echo json_encode($mensaje);
		}

		  if ($this->db->trans_status() === FALSE)
		  {
		     $this->db->trans_rollback();
		  } else {
		  	$this->db->trans_commit();
		  }

	}

	public function getVendedorAjax($ruta){
		$json = array();
		$i = 0;
		$query = $this->db->select("Nombre")
		         ->select("Apellidos")
				 ->where("IdRuta",$ruta)
				 ->where("Estado",1)
				 ->get("Usuarios");

		foreach ($query->result_array() as $key) {
			$json[$i]["Nombre"] = $key["Nombre"]." ".$key["Apellidos"];
			$i++;
		}
		echo json_encode($json);
	}

	public function listaOrdenes($start,$length,$search){
		$srch = "";
		$users = '';

		if($this->session->userdata("Rol") != "Supervisor"){
			$array = array();
			$con = $this->db->distinct()
				->select('IdUsuario')
				->get("cm_Lista_Remisiones");
			for ($i=0; $i < count($con->result_array()); $i++) {
				$array[] = $con->result_array()[$i]["IdUsuario"];
			}
			$users= 'and IdUsuario in ('."'".implode("','",$array)."'".') ';

		}else{
			$users = "and IdUsuario = '".$this->session->userdata("id")."' ";
		}

		if ($search){
			$srch = "and (
					IdRemision like '%".$search."%' or
					FechaEntrega like '%".$search."%' or	
					CantTotal like '%".$search."%' or
					TotalLbs like '%".$search."%' or
					FechaLiq like '%".$search."%' or	
					FechaCrea like '%".$search."%' or	
					FechaEdita like '%".$search."%'	or
					FechaBaja like '%".$search."%'
			)";
		}

		$qnr = "SELECT count(1) 'Cantidad' FROM [4BUY].[dbo].cm_Lista_Remisiones where CodTipo = '1' 
		and CAST(FechaEntrega AS DATE) BETWEEN DATEADD(DAY,-7,GETDATE() -1)
		and CAST(GETDATE() + 7 AS DATE) ".$srch." ".$users;
		$qnr = $this->db->query($qnr);
		$qnr = $qnr->result_array()[0]["Cantidad"];

		if($length == -1){
			$q = $this->db->query("select * from [4BUY].[dbo].cm_Lista_Remisiones where CodTipo = '1' 
			   and CAST(FechaEntrega AS DATE) BETWEEN DATEADD(DAY,-7,GETDATE() -1)
			   and CAST(GETDATE() + 7 AS DATE)".$srch." ".$users."
		                      order by IdRemision");
		}else{
			$q = $this->db->query("select * from [4BUY].[dbo].cm_Lista_Remisiones where CodTipo = '1'
			 and CAST(FechaEntrega AS DATE) BETWEEN DATEADD(DAY,-7,GETDATE() -1)
		and CAST(GETDATE() + 7 AS DATE) ".$srch." ".$users."
		                      order by IdRemision offset ".$start." rows fetch next ".$length." rows only;");
		}

		$retornar = array(
			"numDataTotal" => $qnr,
			"datos" => $q
		);
		return $retornar;
	}

	public function listaPreventas($start,$length,$search){
		$srch = "";
		$users = '';

		if($this->session->userdata("Rol") != "Supervisor"){
			$array = array();
			$con = $this->db->distinct()
				->select('IdUsuario')
				->get("cm_Lista_Remisiones");
			for ($i=0; $i < count($con->result_array()); $i++) {
				$array[] = $con->result_array()[$i]["IdUsuario"];
			}
			$users= 'and IdUsuario in ('."'".implode("','",$array)."'".') ';

		}else{
			$users = "and IdUsuario = '".$this->session->userdata("id")."' ";
		}

		if ($search){
			$srch = "and (
					FechaEntrega like '%".$search."%' or	
					CantTotal like '%".$search."%' or
					TotalLbs like '%".$search."%' or
					FechaLiq like '%".$search."%' or	
					FechaCrea like '%".$search."%' or	
					FechaEdita like '%".$search."%'	or
					FechaBaja like '%".$search."%'
			)";
		}

		$qnr = "SELECT count(1) 'Cantidad' FROM [4BUY].[dbo].cm_Lista_Remisiones where CodTipo = '2' 
		and CAST(FechaEntrega AS DATE) BETWEEN DATEADD(DAY,-7,GETDATE() -1)
and CAST(GETDATE() + 7 AS DATE) ".$srch." ".$users;
		$qnr = $this->db->query($qnr);
		$qnr = $qnr->result_array()[0]["Cantidad"];

		if($length == -1){
			$q = $this->db->query("select * from [4BUY].[dbo].cm_Lista_Remisiones where CodTipo = '2' 
			and CAST(FechaEntrega AS DATE) BETWEEN DATEADD(DAY,-7,GETDATE() -1)
and CAST(GETDATE() + 7 AS DATE) ".$srch." ".$users."
		                      order by IdRemision");//
		}else{
			$q = $this->db->query("select * from [4BUY].[dbo].cm_Lista_Remisiones where CodTipo = '2' 
			 and CAST(FechaEntrega AS DATE) BETWEEN DATEADD(DAY,-7,GETDATE() -1)
and CAST(GETDATE() + 7 AS DATE) ".$srch." ".$users."
		                      order by IdRemision OFFSET ".$start." ROWS FETCH NEXT ".$length." ROWS ONLY;");//
		}

		$retornar = array(
			"numDataTotal" => $qnr,
			"datos" => $q
		);
		return $retornar;
	}

	public function listaRecargos($start,$length,$search){
		$srch = "";
		$users = '';

		if($this->session->userdata("Rol") != "Supervisor"){
			$array = array();
			$con = $this->db->distinct()
				->select('IdUsuario')
				->get("cm_Lista_Remisiones");
			for ($i=0; $i < count($con->result_array()); $i++) {
				$array[] = $con->result_array()[$i]["IdUsuario"];
			}
			$users= 'and IdUsuario in ('."'".implode("','",$array)."'".') ';

		}else{
			$users = "and IdUsuario = '".$this->session->userdata("id")."' ";
		}

		if ($search){
			$srch = "and (
					FechaEntrega like '%".$search."%' or	
					CantTotal like '%".$search."%' or
					TotalLbs like '%".$search."%' or
					FechaLiq like '%".$search."%' or	
					FechaCrea like '%".$search."%' or	
					FechaEdita like '%".$search."%'	or
					FechaBaja like '%".$search."%'
			)";
		}

		$qnr = "SELECT count(1) 'Cantidad' FROM [4BUY].[dbo].cm_Lista_Remisiones where CodTipo = '3' 
		and CAST(FechaEntrega AS DATE) BETWEEN DATEADD(DAY,-7,GETDATE() -1)
and CAST(GETDATE() + 7 AS DATE) ".$srch." ".$users;
		$qnr = $this->db->query($qnr);
		$qnr = $qnr->result_array()[0]["Cantidad"];

		if($length == -1){
			$q = $this->db->query("select * from [4BUY].[dbo].cm_Lista_Remisiones where CodTipo = '3' 
			 and CAST(FechaEntrega AS DATE) BETWEEN DATEADD(DAY,-7,GETDATE() -1)
and CAST(GETDATE() + 7 AS DATE) ".$srch." ".$users."
		                      order by IdRemision");//
		}else{
			$q = $this->db->query("select * from [4BUY].[dbo].cm_Lista_Remisiones where CodTipo = '3'
			 and CAST(FechaEntrega AS DATE) BETWEEN DATEADD(DAY,-7,GETDATE() -1)
and CAST(GETDATE() + 7 AS DATE) ".$srch." ".$users."
		                      order by IdRemision OFFSET ".$start." ROWS FETCH NEXT ".$length." ROWS ONLY;");//
		}

		$retornar = array(
			"numDataTotal" => $qnr,
			"datos" => $q
		);
		return $retornar;
	}

    public function listaWalmart($start,$length,$search){
        $srch = "";
        $users = '';

        if($this->session->userdata("Rol") != "Supervisor"){
            $array = array();
            $con = $this->db->distinct()
                ->select('IdUsuario')
                ->get("cm_Lista_Remisiones");
            for ($i=0; $i < count($con->result_array()); $i++) {
                $array[] = $con->result_array()[$i]["IdUsuario"];
            }
            $users= 'and IdUsuario in ('."'".implode("','",$array)."'".') ';

        }else{
            $users = "and IdUsuario = '".$this->session->userdata("id")."' ";
        }

        if ($search){
            $srch = "and (
					FechaEntrega like '%".$search."%' or	
					CantTotal like '%".$search."%' or
					TotalLbs like '%".$search."%' or
					FechaLiq like '%".$search."%' or	
					FechaCrea like '%".$search."%' or	
					FechaEdita like '%".$search."%'	or
					FechaBaja like '%".$search."%'
			)";
        }

        $qnr = "SELECT count(1) 'Cantidad' FROM [4BUY].[dbo].cm_Lista_Remisiones where CodTipo = '5' 
		and CAST(FechaEntrega AS DATE) BETWEEN DATEADD(DAY,-7,GETDATE() -1)
and CAST(GETDATE() + 7 AS DATE) ".$srch." ".$users;
        $qnr = $this->db->query($qnr);
        $qnr = $qnr->result_array()[0]["Cantidad"];

        if($length == -1){
            $q = $this->db->query("select * from [4BUY].[dbo].cm_Lista_Remisiones where CodTipo = '5' 
			 and CAST(FechaEntrega AS DATE) BETWEEN DATEADD(DAY,-7,GETDATE() -1)
and CAST(GETDATE() + 7 AS DATE) ".$srch." ".$users."
		                      order by IdRemision");//
        }else{
            $q = $this->db->query("select * from [4BUY].[dbo].cm_Lista_Remisiones where CodTipo = '5'
			 and CAST(FechaEntrega AS DATE) BETWEEN DATEADD(DAY,-7,GETDATE() -1)
and CAST(GETDATE() + 7 AS DATE) ".$srch." ".$users."
		                      order by IdRemision OFFSET ".$start." ROWS FETCH NEXT ".$length." ROWS ONLY;");//
        }

        $retornar = array(
            "numDataTotal" => $qnr,
            "datos" => $q
        );
        return $retornar;
    }

    public function listaCasaMant($start,$length,$search){
        $srch = "";
        $users = '';

        if($this->session->userdata("Rol") != "Supervisor"){
            $array = array();
            $con = $this->db->distinct()
                ->select('IdUsuario')
                ->get("cm_Lista_Remisiones");
            for ($i=0; $i < count($con->result_array()); $i++) {
                $array[] = $con->result_array()[$i]["IdUsuario"];
            }
            $users= 'and IdUsuario in ('."'".implode("','",$array)."'".') ';

        }else{
            $users = "and IdUsuario = '".$this->session->userdata("id")."' ";
        }

        if ($search){
            $srch = "and (
					FechaEntrega like '%".$search."%' or	
					CantTotal like '%".$search."%' or
					TotalLbs like '%".$search."%' or
					FechaLiq like '%".$search."%' or	
					FechaCrea like '%".$search."%' or	
					FechaEdita like '%".$search."%'	or
					FechaBaja like '%".$search."%'
			)";
        }

        $qnr = "SELECT count(1) 'Cantidad' FROM [4BUY].[dbo].cm_Lista_Remisiones where CodTipo = '6' 
		and CAST(FechaEntrega AS DATE) BETWEEN DATEADD(DAY,-7,GETDATE() -1)
and CAST(GETDATE() + 7 AS DATE) ".$srch." ".$users;
        $qnr = $this->db->query($qnr);
        $qnr = $qnr->result_array()[0]["Cantidad"];

        if($length == -1){
            $q = $this->db->query("select * from [4BUY].[dbo].cm_Lista_Remisiones where CodTipo = '6' 
			 and CAST(FechaEntrega AS DATE) BETWEEN DATEADD(DAY,-7,GETDATE() -1)
and CAST(GETDATE() + 7 AS DATE) ".$srch." ".$users."
		                      order by IdRemision");//
        }else{
            $q = $this->db->query("select * from [4BUY].[dbo].cm_Lista_Remisiones where CodTipo = '6'
			 and CAST(FechaEntrega AS DATE) BETWEEN DATEADD(DAY,-7,GETDATE() -1)
and CAST(GETDATE() + 7 AS DATE) ".$srch." ".$users."
		                      order by IdRemision OFFSET ".$start." ROWS FETCH NEXT ".$length." ROWS ONLY;");//
        }

        $retornar = array(
            "numDataTotal" => $qnr,
            "datos" => $q
        );
        return $retornar;
    }

    public function listaIndependiente($start,$length,$search){
        $srch = "";
        $users = '';

        if($this->session->userdata("Rol") != "Supervisor"){
            $array = array();
            $con = $this->db->distinct()
                ->select('IdUsuario')
                ->get("cm_Lista_Remisiones");
            for ($i=0; $i < count($con->result_array()); $i++) {
                $array[] = $con->result_array()[$i]["IdUsuario"];
            }
            $users= 'and IdUsuario in ('."'".implode("','",$array)."'".') ';

        }else{
            $users = "and IdUsuario = '".$this->session->userdata("id")."' ";
        }

        if ($search){
            $srch = "and (
					FechaEntrega like '%".$search."%' or	
					CantTotal like '%".$search."%' or
					TotalLbs like '%".$search."%' or
					FechaLiq like '%".$search."%' or	
					FechaCrea like '%".$search."%' or	
					FechaEdita like '%".$search."%'	or
					FechaBaja like '%".$search."%'
			)";
        }

        $qnr = "SELECT count(1) 'Cantidad' FROM [4BUY].[dbo].cm_Lista_Remisiones where CodTipo = '7' 
		and CAST(FechaEntrega AS DATE) BETWEEN DATEADD(DAY,-7,GETDATE() -1)
and CAST(GETDATE() + 7 AS DATE) ".$srch." ".$users;
        $qnr = $this->db->query($qnr);
        $qnr = $qnr->result_array()[0]["Cantidad"];

        if($length == -1){
            $q = $this->db->query("select * from [4BUY].[dbo].cm_Lista_Remisiones where CodTipo = '7' 
			 and CAST(FechaEntrega AS DATE) BETWEEN DATEADD(DAY,-7,GETDATE() -1)
and CAST(GETDATE() + 7 AS DATE) ".$srch." ".$users."
		                      order by IdRemision");//
        }else{
            $q = $this->db->query("select * from [4BUY].[dbo].cm_Lista_Remisiones where CodTipo = '7'
			 and CAST(FechaEntrega AS DATE) BETWEEN DATEADD(DAY,-7,GETDATE() -1)
and CAST(GETDATE() + 7 AS DATE) ".$srch." ".$users."
		                      order by IdRemision OFFSET ".$start." ROWS FETCH NEXT ".$length." ROWS ONLY;");//
        }

        $retornar = array(
            "numDataTotal" => $qnr,
            "datos" => $q
        );
        return $retornar;
    }

	public function listaAdelantos($start,$length,$search){
		$srch = "";
		$users = '';

		if($this->session->userdata("Rol") != "Supervisor"){
			$array = array();
			$con = $this->db->distinct()
				->select('IdUsuario')
				->get("cm_Lista_Remisiones");
			for ($i=0; $i < count($con->result_array()); $i++) {
				$array[] = $con->result_array()[$i]["IdUsuario"];
			}
			$users= 'and IdUsuario in ('."'".implode("','",$array)."'".') ';

		}else{
			$users = "and IdUsuario = '".$this->session->userdata("id")."' ";
		}

		if ($search){
			$srch = "and (
					FechaEntrega like '%".$search."%' or	
					CantTotal like '%".$search."%' or
					TotalLbs like '%".$search."%' or
					FechaLiq like '%".$search."%' or	
					FechaCrea like '%".$search."%' or	
					FechaEdita like '%".$search."%'	or
					FechaBaja like '%".$search."%'
			)";
		}

		$qnr = "SELECT count(1) 'Cantidad' FROM [4BUY].[dbo].cm_Lista_Remisiones where CodTipo = '4' 
		and CAST(FechaEntrega AS DATE) BETWEEN DATEADD(DAY,-7,GETDATE() -1)
and CAST(GETDATE() + 7 AS DATE) ".$srch." ".$users;
		$qnr = $this->db->query($qnr);
		$qnr = $qnr->result_array()[0]["Cantidad"];

		if($length == -1){
			$q = $this->db->query("select * from [4BUY].[dbo].cm_Lista_Remisiones where CodTipo = '4' 
			 and CAST(FechaEntrega AS DATE) BETWEEN DATEADD(DAY,-7,GETDATE() -1)
and CAST(GETDATE() + 7 AS DATE) ".$srch." ".$users."
		                      order by IdRemision");//
		}else{
			$q = $this->db->query("select * from [4BUY].[dbo].cm_Lista_Remisiones where CodTipo = '4' 
			and CAST(FechaEntrega AS DATE) BETWEEN DATEADD(DAY,-7,GETDATE() -1)
and CAST(GETDATE() + 7 AS DATE) ".$srch." ".$users."
		                      order by IdRemision OFFSET ".$start." ROWS FETCH NEXT ".$length." ROWS ONLY;");//
		}

		$retornar = array(
			"numDataTotal" => $qnr,
			"datos" => $q
		);
		return $retornar;
	}

	public function detallesRemisiones($idRemision){
		$query = $this->db->where("IdRemision",$idRemision)
				 ->get("cm_Detalles_Lista_Remisiones");
		if($query->num_rows() > 0){
			return $query->result_array();
		}
		return 0;
	}

	//region Editar y/o Actualizar Remisiones
	public function editarRemisionEnc($idRemision){
		$encabezado = $this->db->where("IdRemision",$idRemision)
		              ->get("cm_Editar_Enc_Remision");

		if($encabezado->num_rows() > 0){
			return $encabezado->result_array();
		}else{
			return 0;
		}

	}

	public function editarRemisionDet($idRemision){
		$detalle = $this->db->where("IdRemision",$idRemision)
			->get("DetalleRemision");
		if($detalle->num_rows() > 0){
			return $detalle->result_array();
		}else{
			return 0;
		}
	}

	public function actualizarRemision($top,$datos){
		$this->db->trans_begin();
		$this->db->where("IdRemision",$top[0]);
		$upda= array(
			"IdRuta" => $top[1],
			"Vendedor" => $top[2],
			"Rubro" => $top[3],
			"FechaEntrega" => gmdate($top[4]),
			"FechaLiq" => gmdate($top[5]),
			"Tipo" => $top[6],
			"Referencia" => $top[7],
			"Consecutivo" => $top[8],
			"MontoTotal" => $top[9],
			"Comentario" => $top[10],
			"FechaEdita" => gmdate(date("Y-m-d h:i:s")),
			"NombreRotador" => $top[11]
		);
		$this->db->update("Remisiones",$upda);

		$delet = $this->db->where("IdRemision",$top[0])
		         ->delete('DetalleRemision');
		if($delet){
			for ($i=0; $i < count($datos); $i++) {
				$array = explode(",",$datos[$i]);
				$insertArray = array(
					"IdRemision" => $top[0],
					"CodigoProd" => $array[0],
					"DescripcionProd" => $array[1],
					"LBS" => $array[2],
					"Cantidad" => $array[3],
					"CantidadLBS" => $array[4],
					"Precio" => 0
				);

				$this->db->insert("DetalleRemision",$insertArray);
			}
		}

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}

	}

	public function darBajaRemision($idRemision,$estado){
		$this->db->where("IdRemision",$idRemision);
		$data = array(
			"Estado" => $estado,
			"FechaBaja" => gmdate(date("Y-m-d H:i:s"))
		);
		$this->db->update("Remisiones",$data);
	}

	public function printRemisionEnc($idRemision){
		    $this->db->select("t.*,u.Nombre,u.Apellidos")
					 ->from("cm_Editar_Enc_Remision t")
					 ->join("Usuarios u", "t.IdUsuario = u.IdUsuario")
			         ->where("t.IdRemision",$idRemision);

			$encabezado = $this->db->get();

		if($encabezado->num_rows() > 0){
			return $encabezado->result_array();
		}else{
			return 0;
		}

	}
	//endregion

	//region Validar si la fecha de entrega ya paso que no se pueda editar la remision
	public function validarFechaEntrega($idRemision){
		date_default_timezone_set("America/Managua");
		$fechaActual = date("Y-m-d");
		$valida = $this->db->select("FechaEntrega")
			      ->where("IdRemision",$idRemision)
		          ->get("Remisiones");
		if($fechaActual >= $valida->result_array()[0]["FechaEntrega"]){
			return true;
		}else{
			return false;
		}
	}
	//endregion

	public function validarConsecutivo($numConsec){
		$query = $this->db->where("Consecutivo", $numConsec)
		         ->get("Remisiones");
		if($query->num_rows() > 0){
			return false;
		}else{
			return true;
		}
	}

	//region Consolidado Remisiones
	public function detallesRemisionesCons($FechaEntrega, $codRubro, $codTipo, $codRuta){
		$queryRuta = '';
		$queryUser = '';
		if($codRuta != "NULL"){
			$queryRuta = "and IdRuta = ".$codRuta."";	
		}
		if($this->session->userdata('IdRol') != 1){
			$queryUser = "and IdUsuario = ".$this->session->userdata('id')." ";
		}
		$query = $this->db->query("SELECT [CodigoProd]
      ,[DescripcionProd]
      ,[LBS]
      ,sum(Cantidad) as Cantidad
      ,sum(CantidadLBS) CantidadLBS
      ,[FechaEntrega]
      ,[Rubro]
      ,[Tipo]
      ,[RubroD]
      ,[TipoD]
      ,[Estado]
      ,[IdUsuario]
      ,[Nombre]
      ,[Apellidos] from cm_print_Consolidado_Remisiones
									where FechaEntrega = '".$FechaEntrega."'
									and Rubro = '".$codRubro."' and Tipo = '".$codTipo."' ".$queryUser."
									".$queryRuta."
									group by [CodigoProd]
      ,[DescripcionProd]
      ,[LBS]
	  ,[FechaEntrega]
      ,[Rubro]
      ,[Tipo]
      ,[RubroD]
      ,[TipoD]
      ,[Estado]
      ,[IdUsuario]
      ,[Nombre]
      ,[Apellidos]
      ORDER BY CodigoProd ASC
  ");
		if($query->num_rows() > 0){
			return $query->result_array();
		}
		return 0;
	}

	public function printRemisionEncCons($fechaEntrega, $codRubro, $codTipo){
		$this->db->select("t.*,u.Nombre,u.Apellidos")
			->from("cm_Editar_Enc_Remision t")
			->join("Usuarios u", "t.IdUsuario = u.IdUsuario")
			->where("t.FechaEntrega",$fechaEntrega)
		    ->where("Rubro",$codRubro)
			->where("Tipo",$codTipo);

		$encabezado = $this->db->get();

		if($encabezado->num_rows() > 0){
			return $encabezado->result_array();
		}else{
			return 0;
		}

	}
	//endregion

	public function getCantidadRemisionByCod($itemcode)
	{
		$json = array(); $i = 0;
		/*Revisar que estado sea activo ========> Pendiente*/ 
		$query = $this->db->query("select t2.CodigoProd,SUM(t2.Cantidad) Cantidad,
		CAST(t1.FechaCrea as date) FechaCrea 
		from Remisiones t1
		inner join DetalleRemision t2 on t1.IdRemision = t2.IdRemision
		where CAST(FechaCrea as date) = CAST(GETDATE() as date)
		and t2.CodigoProd = '".$itemcode."'
		and Estado = '1'
		group by t2.CodigoProd,CAST(t1.FechaCrea as date)");

		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key) {
				$json[$i]["CANTIDAD"] = $key["Cantidad"];
				$i++;
			}
			echo json_encode($json);
		}
	}

}

/* End of file Remisiones_model.php */
/* Location: ./application/models/Remisiones_model.php */
