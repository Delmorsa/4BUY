<?php
class Hana_model extends CI_Model
{
	private $db2;
    public function __construct(){
        parent::__construct();
        $this->load->database();
        $this->db2 = $this->load->database("dbintegracion", true);
    }

    public $BD = 'SBO_DELMOR';
    

    public  function OPen_database_odbcSAp(){//CONEXION A HANA DELMOR      
         $conn = @odbc_connect("HANAPHP","DELMOR","CazeheKuS2th", SQL_CUR_USE_ODBC);
         if(!$conn){
            echo '<div class="row errorConexion white-text center">
                    ¡ERROR DE CONEXION CON EL SERVIDOR!
                </div>';
         } else {
           return $conn;
         }        
    }
    

    public  function getRutas(){
        $rutas;
        $array = array();
            if ($this->session->userdata("IdRol") == 3) {
                $rutas = $this->db->select("IdRuta")
                      ->where("IdSupervisor", $this->session->userdata('id'))
                      ->get("cm_Rutas_Asignadas");

             for ($i=0; $i < count($rutas->result_array()); $i++) { 
                $array[] = $rutas->result_array()[$i]["IdRuta"];
             } 

             $result = 'and "SlpCode" in ('."'".implode("','",$array)."'".')';
            }

        $conn = $this->OPen_database_odbcSAp();
        
        if (isset($rutas)) {
            if ($rutas->num_rows() > 0) {
                $query = 'SELECT "SlpCode","SlpName" FROM '.$this->BD.'.OSLP'.' 
                WHERE "SlpName" LIKE '."'%Vendedor RUTA%'".'
                '.$result.'
                ORDER BY "SlpCode" ASC';
            }
        }else{
            $query = 'SELECT "SlpCode","SlpName" FROM '.$this->BD.'.OSLP'.' 
                WHERE "SlpName" LIKE '."'%Vendedor RUTA%'".'
                ORDER BY "SlpCode" ASC';
        }
        
        $resultado =  @odbc_exec($conn,$query);
        $json = array();  
        $i=0;      

        while ($fila = @odbc_fetch_array($resultado)){
            $json[$i]["IdRuta"] = $fila["SlpCode"];  
            $json[$i]["Ruta"] = $fila["SlpName"];          
            $i++;
        }
         return $json;
    }    

    //MOSTRAR CLIENTES DESDE SB1
    public function getClientes($search){
       $conn = $this->OPen_database_odbcSAp();
       $query = ' SELECT "CardCode","CardName" 
                  FROM '.$this->BD.'.OCRD
                  WHERE "CardName" LIKE '."'%".$search."%'".' 
                  LIMIT 10'; 
       $resultado = @odbc_exec($conn,$query);
       $json = array();
       $i = 0;
       while ($fila = @odbc_fetch_array($resultado)) {
            $json[$i]["Codigo"] = $fila["CardCode"];
            $json[$i]["Nombre"] = utf8_encode($fila["CardName"]);                     
            $i++;
        }        
        echo json_encode($json);  
        echo @odbc_errormsg($conn);        
    }

    //Cargar Inventario desde SB1 cuyo stock sea mayor a 0
    public function inventario($start,$length,$search){
        $conn = $this->OPen_database_odbcSAp();
        $srch = "";
        if ($search) {
            $srch = 'and ( T0."ItemCode" LIKE '."'%".$search."%'".' OR
                           T1."ItemName" LIKE '."'%".$search."%'".' OR
                           T1."SalUnitMsr" LIKE '."'%".$search."%'".' OR     
                           T0."WhsCode" LIKE '."'%".$search."%'".' OR     
                           T2."WhsName" LIKE '."'%".$search."%'".' OR     
                           T0."OnHand" LIKE '."'%".$search."%'".'                
                        )';
        }


         $qnr = 'SELECT COUNT(1) "Total" FROM '.$this->BD.'.OITW T0
                    INNER JOIN '.$this->BD.'.OITM T1 on T0."ItemCode" = T1."ItemCode"
                    INNER JOIN '.$this->BD.'.OWHS T2 on T0."WhsCode" = T2."WhsCode"
                    WHERE T0."OnHand" <> '.'0'.' and T1."ItmsGrpCod" in ('.'101'.') and T0."WhsCode" = '.'02'.' '.$srch;
        $resultqnr = @odbc_exec($conn,$qnr);
        $arrayqnr = array();
        $iqnr = 0;
        while ($filaqnr = @odbc_fetch_array($resultqnr)) {
            $arrayqnr[$iqnr] = $filaqnr["Total"];
            $iqnr++;
        }
        if($length == -1){
			$query = 'SELECT T1."ItmsGrpCod",T1."ItemName",T1."SalUnitMsr",T2."WhsName",T0.* FROM '.$this->BD.'.OITW T0
                    INNER JOIN '.$this->BD.'.OITM T1 on T0."ItemCode" = T1."ItemCode"
                    INNER JOIN '.$this->BD.'.OWHS T2 on T0."WhsCode" = T2."WhsCode"
                    WHERE T0."OnHand" <> '.'0'.' and T1."ItmsGrpCod" in ('.'101'.') and T0."WhsCode" = '.'07'.'
                    '.$srch.'
                    ORDER BY T0."ItemCode" ASC ';
		}else{
			$query = 'SELECT T1."ItmsGrpCod",T1."ItemName",T1."SalUnitMsr",T2."WhsName",T0.* FROM '.$this->BD.'.OITW T0
                    INNER JOIN '.$this->BD.'.OITM T1 on T0."ItemCode" = T1."ItemCode"
                    INNER JOIN '.$this->BD.'.OWHS T2 on T0."WhsCode" = T2."WhsCode"
                    WHERE T0."OnHand" <> '.'0'.' and T1."ItmsGrpCod" in ('.'101'.') and T0."WhsCode" = '.'07'.'
                    '.$srch.'
                    ORDER BY T0."ItemCode" ASC
                    LIMIT '.$length.' OFFSET '.$start.' ';
		}
        $resultado = @odbc_exec($conn,$query);
        $json = array();
        $i = 0;
        while ($fila = @odbc_fetch_array($resultado)) {
            $json[$i]["ItemCode"] = $fila["ItemCode"];
            $json[$i]["ItemName"] = utf8_encode($fila["ItemName"]);
            $json[$i]["SalUnitMsr"] = utf8_encode($fila["SalUnitMsr"]);
            $json[$i]["WhsCode"] = utf8_encode($fila["WhsCode"]);
            $json[$i]["WhsName"] = utf8_encode($fila["WhsName"]);
            $json[$i]["OnHand"] = number_format($fila["OnHand"],2);
            $i++;
        }

        $retorno = array(
           "datos" => $json,
           "numDataTotal" => $arrayqnr[0]
        );

        return $retorno;
    }

    //MOSTRAR INVENTARIO DESDE SB1 CUYO STOCK SEA IGUAL A 0
    public function inventarioSinStock($start,$length,$search){
        $conn = $this->OPen_database_odbcSAp();
        $srch = "";
        if ($search) {
            $srch = 'and ( T0."ItemCode" LIKE '."'%".$search."%'".' OR
                           T1."ItemName" LIKE '."'%".$search."%'".' OR
                           T1."SalUnitMsr" LIKE '."'%".$search."%'".' OR     
                           T0."WhsCode" LIKE '."'%".$search."%'".' OR     
                           T2."WhsName" LIKE '."'%".$search."%'".' OR     
                           T0."OnHand" LIKE '."'%".$search."%'".'                
                        )';
        }

        $qnr = 'SELECT COUNT(1) "Total" FROM '.$this->BD.'.OITW T0
                    INNER JOIN '.$this->BD.'.OITM T1 on T0."ItemCode" = T1."ItemCode"
                    INNER JOIN '.$this->BD.'.OWHS T2 on T0."WhsCode" = T2."WhsCode"
                    WHERE T1."ItmsGrpCod" = '.'101'.' and T0."WhsCode" = '.'07'.'
                    and T1."ItemName" not like '."'%CODIGO VACIO%'".'
                    and T0."ItemCode" between '."'1101'".' and '."'88999'".' '.$srch;
        $resultqnr = @odbc_exec($conn,$qnr);
        $arrayqnr = array();
        $iqnr = 0;
        while ($filaqnr = @odbc_fetch_array($resultqnr)) {
            $arrayqnr[$iqnr] = $filaqnr["Total"];
            $iqnr++;
        }

        if($length == -1){
			$query = 'SELECT T1."ItmsGrpCod",T1."ItemName",T1."SalUnitMsr",T2."WhsName",T0.* FROM '.$this->BD.'.OITW T0
                    INNER JOIN '.$this->BD.'.OITM T1 on T0."ItemCode" = T1."ItemCode"
                    INNER JOIN '.$this->BD.'.OWHS T2 on T0."WhsCode" = T2."WhsCode"
                    WHERE T1."ItmsGrpCod" = '.'101'.' and T0."WhsCode" = '.'07'.'
                    and T1."ItemName" not like '."'%CODIGO VACIO%'".'
                    and T0."ItemCode" between '."'1101'".' and '."'88999'".'
                    '.$srch.'
                    ORDER BY T0."ItemCode" ASC';
		}else{
			$query = 'SELECT T1."ItmsGrpCod",T1."ItemName",T1."SalUnitMsr",T2."WhsName",T0.* FROM '.$this->BD.'.OITW T0
                    INNER JOIN '.$this->BD.'.OITM T1 on T0."ItemCode" = T1."ItemCode"
                    INNER JOIN '.$this->BD.'.OWHS T2 on T0."WhsCode" = T2."WhsCode"
                    WHERE T1."ItmsGrpCod" = '.'101'.' and T0."WhsCode" = '.'07'.'
                    and T1."ItemName" not like '."'%CODIGO VACIO%'".'
                    and T0."ItemCode" between '."'1101'".' and '."'88999'".'
                    '.$srch.'
                    ORDER BY T0."ItemCode" ASC
                    LIMIT '.$length.' OFFSET '.$start.' ';
		}
        $resultado = @odbc_exec($conn,$query);
        $json = array();
        $i = 0;
        while ($fila = @odbc_fetch_array($resultado)) {
            $json[$i]["ItemCode"] = $fila["ItemCode"];
            $json[$i]["ItemName"] = utf8_encode($fila["ItemName"]);
            $json[$i]["SalUnitMsr"] = utf8_encode($fila["SalUnitMsr"]);
            $json[$i]["WhsCode"] = utf8_encode($fila["WhsCode"]);
            $json[$i]["WhsName"] = utf8_encode($fila["WhsName"]);
            $json[$i]["OnHand"] = number_format($fila["OnHand"],2);
            $i++;
        }
         $retorno = array(
           "datos" => $json,
           "numDataTotal" => $arrayqnr[0]
        );

        return $retorno;   
        echo odbc_errormsg ($conn);
    } 

    //Cargar Inventario por rutas desde SB1
    public function inventarioRutas($start,$length,$search){
        $rutas;
        $array = array();
            if ($this->session->userdata("IdRol") == 3) {
                $rutas = $this->db->select("IdRuta")
                      ->where("IdSupervisor", $this->session->userdata('id'))
                      ->get("cm_Rutas_Asignadas");
            } else {
                $rutas = $this->db->select("IdRuta")
                      ->get("Usuarios");
            }
            
             for ($i=0; $i < count($rutas->result_array()); $i++) { 
                $array[] = $rutas->result_array()[$i]["IdRuta"];
             }          

        $conn = $this->OPen_database_odbcSAp();
            if ($rutas->num_rows() > 0) {    
               $srch = '';     
               if ($search) {
                $srch = 'and ( T0."ItemCode" LIKE '."'%".$search."%'".' OR
                           T1."ItemName" LIKE '."'%".$search."%'".' OR
                           T1."SalUnitMsr" LIKE '."'%".$search."%'".' OR     
                           T0."WhsCode" LIKE '."'%".$search."%'".' OR     
                           T2."WhsName" LIKE '."'%".$search."%'".' OR     
                           T0."OnHand" LIKE '."'%".$search."%'".' OR
                           T3."SlpCode" LIKE '."'%".$search."%'".' OR
                           T3."SlpName" LIKE '."'%".$search."%'".'               
                        )';
                }

            $qnr = 'SELECT COUNT(1) "Total"
                    FROM '.$this->BD.'.OITW T0
                    INNER JOIN '.$this->BD.'.OITM T1 on T0."ItemCode" = T1."ItemCode"
                    INNER JOIN '.$this->BD.'.OWHS T2 on T0."WhsCode" = T2."WhsCode"
                    INNER JOIN '.$this->BD.'.VIEW_BODEGAS_VENDEDORES T3 on T0."WhsCode" = T3."WhsCode"
                    WHERE T0."OnHand" <> '.'0'.' and T1."ItmsGrpCod" = '.'101'.'  
                    and T3."SlpCode" in ('."'".implode("','",$array)."'".') '.$srch;


				if($length == -1){
					$query = 'SELECT T1."ItmsGrpCod",T1."ItemName",T1."SalUnitMsr",T2."WhsName",T3."SlpCode",T3."SlpName",T0.*
                    FROM '.$this->BD.'.OITW T0
                    INNER JOIN '.$this->BD.'.OITM T1 on T0."ItemCode" = T1."ItemCode"
                    INNER JOIN '.$this->BD.'.OWHS T2 on T0."WhsCode" = T2."WhsCode"
                    INNER JOIN '.$this->BD.'.VIEW_BODEGAS_VENDEDORES T3 on T0."WhsCode" = T3."WhsCode"
                    WHERE T0."OnHand" <> '.'0'.' and T1."ItmsGrpCod" = '.'101'.' 
                    and T3."SlpCode" in ('."'".implode("','",$array)."'".')
                    '.$srch.'
                    ORDER BY T0."ItemCode" ASC ';
				}else{
					$query = 'SELECT T1."ItmsGrpCod",T1."ItemName",T1."SalUnitMsr",T2."WhsName",T3."SlpCode",T3."SlpName",T0.*
                    FROM '.$this->BD.'.OITW T0
                    INNER JOIN '.$this->BD.'.OITM T1 on T0."ItemCode" = T1."ItemCode"
                    INNER JOIN '.$this->BD.'.OWHS T2 on T0."WhsCode" = T2."WhsCode"
                    INNER JOIN '.$this->BD.'.VIEW_BODEGAS_VENDEDORES T3 on T0."WhsCode" = T3."WhsCode"
                    WHERE T0."OnHand" <> '.'0'.' and T1."ItmsGrpCod" = '.'101'.' 
                    and T3."SlpCode" in ('."'".implode("','",$array)."'".')
                    '.$srch.'
                    ORDER BY T0."ItemCode" ASC
                    LIMIT '.$length.' OFFSET '.$start.' ';
				}
            }
        
        $resultadoqnr = @odbc_exec($conn,$qnr);
        $jsonqnr = array();
        $iqnr = 0;
        while ($filaqnr = @odbc_fetch_array($resultadoqnr)) {
            $jsonqnr[$iqnr] = $filaqnr["Total"];
            $iqnr++;
        }

        $resultado = @odbc_exec($conn,$query);
        $json = array();
        $i = 0;
        while ($fila = @odbc_fetch_array($resultado)) {
            $json[$i]["ItemCode"] = $fila["ItemCode"];
            $json[$i]["ItemName"] = utf8_encode($fila["ItemName"]);
            $json[$i]["SalUnitMsr"] = utf8_encode($fila["SalUnitMsr"]);
            $json[$i]["WhsCode"] = utf8_encode($fila["WhsCode"]);
            $json[$i]["WhsName"] = utf8_encode($fila["WhsName"]);
            $json[$i]["OnHand"] = number_format($fila["OnHand"],2);
            $json[$i]["SlpCode"] = $fila["SlpCode"];
            $json[$i]["SlpName"] = $fila["SlpName"];
            $i++;
        }
        $retorno = array(
            "datos" => $json,
            "numDataTotal" => $jsonqnr[0]
        ); 

        return $retorno;
     }

    //Cargar Inventario por rutas desde SB1 cuyo stock sea igual a 0
    public function inventarioRutasSinStock($start,$length,$search){
        $rutas;
        $array = array();
            if ($this->session->userdata("IdRol") == 3) {
                $rutas = $this->db->select("IdRuta")
                      ->where("IdSupervisor", $this->session->userdata('id'))
                      ->get("cm_Rutas_Asignadas");
            } else {
                $rutas = $this->db->select("IdRuta")
                      ->get("Usuarios");
            }

             for ($i=0; $i < count($rutas->result_array()); $i++) { 
                $array[] = $rutas->result_array()[$i]["IdRuta"];
             }          

        $conn = $this->OPen_database_odbcSAp();
            if ($rutas->num_rows() > 0) {
               $srch = '';     
               if ($search) {
                $srch = 'and ( T0."ItemCode" LIKE '."'%".$search."%'".' OR
                           T1."ItemName" LIKE '."'%".$search."%'".' OR
                           T1."SalUnitMsr" LIKE '."'%".$search."%'".' OR     
                           T0."WhsCode" LIKE '."'%".$search."%'".' OR     
                           T2."WhsName" LIKE '."'%".$search."%'".' OR     
                           T0."OnHand" LIKE '."'%".$search."%'".' OR
                           T3."SlpCode" LIKE '."'%".$search."%'".' OR
                           T3."SlpName" LIKE '."'%".$search."%'".'               
                        )';
                }    

            $qnr = 'SELECT COUNT(1) "Total"
                    FROM '.$this->BD.'.OITW T0
                    INNER JOIN '.$this->BD.'.OITM T1 on T0."ItemCode" = T1."ItemCode"
                    INNER JOIN '.$this->BD.'.OWHS T2 on T0."WhsCode" = T2."WhsCode"
                    INNER JOIN '.$this->BD.'.VIEW_BODEGAS_VENDEDORES T3 on T0."WhsCode" = T3."WhsCode"
                    WHERE T1."ItmsGrpCod" = '.'101'.' 
                    and T3."SlpCode" in ('."'".implode("','",$array)."'".')
                    and T1."ItemName" not like '."'%CODIGO VACIO%'".'
                    and T0."ItemCode" between '."'1101'".' and '."'88999'".' '.$srch;

            	if($length == -1){
					$query = 'SELECT T1."ItmsGrpCod",T1."ItemName",T1."SalUnitMsr",T2."WhsName",T3."SlpCode",T3."SlpName",T0.*
                    FROM '.$this->BD.'.OITW T0
                    INNER JOIN '.$this->BD.'.OITM T1 on T0."ItemCode" = T1."ItemCode"
                    INNER JOIN '.$this->BD.'.OWHS T2 on T0."WhsCode" = T2."WhsCode"
                    INNER JOIN '.$this->BD.'.VIEW_BODEGAS_VENDEDORES T3 on T0."WhsCode" = T3."WhsCode"
                    WHERE T1."ItmsGrpCod" = '.'101'.' 
                    and T3."SlpCode" in ('."'".implode("','",$array)."'".')
                    and T1."ItemName" not like '."'%CODIGO VACIO%'".'
                    and T0."ItemCode" between '."'1101'".' and '."'88999'".'
                    '.$srch.'
                    ORDER BY T0."ItemCode" ASC';
				}else{
					$query = 'SELECT T1."ItmsGrpCod",T1."ItemName",T1."SalUnitMsr",T2."WhsName",T3."SlpCode",T3."SlpName",T0.*
                    FROM '.$this->BD.'.OITW T0
                    INNER JOIN '.$this->BD.'.OITM T1 on T0."ItemCode" = T1."ItemCode"
                    INNER JOIN '.$this->BD.'.OWHS T2 on T0."WhsCode" = T2."WhsCode"
                    INNER JOIN '.$this->BD.'.VIEW_BODEGAS_VENDEDORES T3 on T0."WhsCode" = T3."WhsCode"
                    WHERE T1."ItmsGrpCod" = '.'101'.' 
                    and T3."SlpCode" in ('."'".implode("','",$array)."'".')
                    and T1."ItemName" not like '."'%CODIGO VACIO%'".'
                    and T0."ItemCode" between '."'1101'".' and '."'88999'".'
                    '.$srch.'
                    ORDER BY T0."ItemCode" ASC
                    LIMIT '.$length.' OFFSET '.$start.' ';
				}
            }
        $resultadoqnr = @odbc_exec($conn,$qnr);
        $jsonqnr = array();
        $iqnr = 0;
        while ($filaqnr = @odbc_fetch_array($resultadoqnr)) {
            $jsonqnr[$iqnr] = $filaqnr["Total"];
            $iqnr++;
        }

        $resultado = @odbc_exec($conn,$query);
        $json = array();
        $i = 0;
        while ($fila = @odbc_fetch_array($resultado)) {
            $json[$i]["ItemCode"] = $fila["ItemCode"];
            $json[$i]["ItemName"] = utf8_encode($fila["ItemName"]);
            $json[$i]["SalUnitMsr"] = utf8_encode($fila["SalUnitMsr"]);
            $json[$i]["WhsCode"] = utf8_encode($fila["WhsCode"]);
            $json[$i]["WhsName"] = utf8_encode($fila["WhsName"]);
            $json[$i]["OnHand"] = number_format($fila["OnHand"],2);
            $json[$i]["SlpCode"] = $fila["SlpCode"];
            $json[$i]["SlpName"] = $fila["SlpName"];
            $i++;
        }
        
        $retorno = array(
            "datos" => $json,
            "numDataTotal" => $jsonqnr[0]
        );
        return $retorno;
    }

    public function getProductosRutas($search){
    	$qfilter = '';
        /*$array = array();
        $rutas = $this->db->select("IdRuta")
                 ->where("IdSupervisor", $this->session->userdata('id'))
                 ->get("cm_Rutas_Asignadas");

        for ($i=0; $i < count($rutas->result_array()); $i++) {
            $array[] = $rutas->result_array()[$i]["IdRuta"];
        }*/
        if(isset($search)){
        	$qfilter = 'WHERE "ItemName" LIKE '."'%".$search."%'".'
                        OR "ItemCode" LIKE '."'%".$search."%'".'';
		}
        $conn = $this->OPen_database_odbcSAp();
                    $query = 'SELECT DISTINCT "ItemCode","ItemName","SWeight1" 
                        FROM '.$this->BD.'."VIEW_BODEGAS_EXISTENCIAS"
                        '.$qfilter.'
                        ORDER BY "ItemCode" ASC
                        LIMIT 10';

            $resultado = @odbc_exec($conn,$query);
            $json = array();
            $i = 0;
            while ($fila = @odbc_fetch_array($resultado)) {
                $json[$i]["ItemCode"] = $fila["ItemCode"];
                $json[$i]["ItemName"] = utf8_encode($fila["ItemName"]);
                $json[$i]["SWeight1"] = utf8_encode($fila["SWeight1"]);
                $i++;
            }
            echo json_encode($json);
            echo @odbc_error($conn);
    }

    public function getProductosMermas($search){
    	$qfilter = '';
        if(isset($search)){
        	$qfilter = 'AND ("ItemName" LIKE '."'%".$search."%'".'
                        OR "ItemCode" LIKE '."'%".$search."%'".')';
		}
        $conn = $this->OPen_database_odbcSAp();
                    $query = 'SELECT DISTINCT "ItemCode","ItemName","SWeight1" 
                        FROM '.$this->BD.'."VIEW_BODEGAS_EXISTENCIAS"
                        WHERE "Merma" = '."'Y'".' AND "WhsCode" = '."'01'".'
                        '.$qfilter.'
                        ORDER BY "ItemCode" ASC
                        LIMIT 10';

            $resultado = @odbc_exec($conn,$query);
            $json = array();
            $i = 0;
            while ($fila = @odbc_fetch_array($resultado)) {
                $json[$i]["ItemCode"] = $fila["ItemCode"];
                $json[$i]["ItemName"] = utf8_encode($fila["ItemName"]);
                //$json[$i]["SWeight1"] = utf8_encode($fila["SWeight1"]);
                $i++;
            }
            echo json_encode($json);
            echo @odbc_error($conn);
    }

	public function getStockProdAjax($itemcode){
		$conn = $this->OPen_database_odbcSAp();
			$query = 'select SUM("OnHand") "OnHand" ,SUM("Available QTY") "Available QTY","SWeight1"
						from "SBO_DELMOR"."VIEW_BODEGAS_EXISTENCIAS"
						WHERE "ItemCode" = '."'".$itemcode."'".' group by "SWeight1"'; //APP_ARTICULOS_EXISTENCIA

		$resultado = @odbc_exec($conn,$query);
		$json = array();
		$i = 0;
		while ($fila = @odbc_fetch_array($resultado)) {
			$json[$i]["EXISTENCIA"] = utf8_encode($fila["Available QTY"]);
      $json[$i]["GRAMOS"] = number_format($fila["SWeight1"],0);
			$i++;
		}
		echo json_encode($json);
		//echo json_encode(@odbc_error($conn));
	}

	public function anularFactura($refFactura, $comentario){
		/*Variables*/
		$mensaje = array(); $json = array(); $i = 0; $integrada = false; $pendiente = false;
		/*Variables*/
    	$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1018");
		if($permiso){
			//region Buscar en SAP
			$conn = $this->OPen_database_odbcSAp();
			$query = 'SELECT IFNULL("DocNum",'."'0'".')"DocNum",IFNULL("NumAtCard", '."'NULL'".') "NumAtCard",
				  IFNULL("CANCELED", '."'NULL'".') "CANCELED" FROM '.$this->BD.'.OINV where "NumAtCard" = '."'".$refFactura."'".' ';
			$resultado = @odbc_exec($conn,$query);
			while ($fila = @odbc_fetch_array($resultado)) {
				$json[$i]["DocNum"] = $fila["DocNum"];
				$json[$i]["NumAtCard"] = $fila["NumAtCard"];
				$json[$i]["CANCELED"] = $fila["CANCELED"];

				if($json[$i]["CANCELED"] == "N"){
					$mensaje[0]["tipo"] = "warning";
					$mensaje[0]["mensaje1"] = "Factura # ".$json[$i]["NumAtCard"]."";
					$mensaje[0]["mensaje"] = "Para anular esta factura primero debe anularla en SAP. Cod Factura SAP: ".$json[$i]["DocNum"]."";
					$integrada = true;
				}
				$i++;
			}
			//endregion

			//region Si no esta en SAP buscar en base de datos de integracion
			if($integrada == false){
				//"No esta integrada, buscar en base de datos de integracion";
				$queryICG = $this->db2->select("NumDoc,NumRef,EstadoIntegra")
					->where("NumRef",$refFactura)
					->where("EstadoIntegra <>",'Y')
					->get("SCGRMS_DOCUMENTOS");
				if($queryICG->num_rows() > 0){
					$pendiente = true;
				}
				if($pendiente == true){

					//region Actualizar EstadoIntegra SCGRMS_DOCUMENTOS
					$this->db2->where("NumRef",$refFactura);
					$docArray = array(
						"EstadoIntegra" => "C"
					);
					$this->db2->update("SCGRMS_DOCUMENTOS",$docArray);
					//endregion

					//region Actualizar EstadoIntegra SCGRMS_PAGOS
					foreach ($queryICG->result_array() as $item){
						$this->db2->where("NumDoc",$item["NumDoc"]);
						$pagoArray = array(
							"EstadoIntegra" => "C"
						);
						$this->db2->update("SCGRMS_PAGOS",$pagoArray);
					}
					//endregion

					//region Actualizar Facturas en 4BUY
					$this->db->where("IDFACTURA", $refFactura);
					$datos = array(
						"ESTADOAPP" => 4,
						"COMENTARIOANULACION" => $comentario
					);
					$act = $this->db->update("Facturas",$datos);
					if($act){
						$this->db->where("IDFACTURA",$refFactura);
						$dataFactAnul = array(
							"FECHAANULACION" => gmdate(date("Y-m-d H:i:s")),
							"IDUSUARIOANULA" => $this->session->userdata('id'),
							"ESTADO" => 1
						);
						$this->db->update("Facturas_Anulacion",$dataFactAnul);
					}
					//endregion

				}else{
					//region Actualizar Facturas en 4BUY
					$this->db->where("IDFACTURA", $refFactura);
					$datosAc = array(
						"ESTADOAPP" => 4,
						"COMENTARIOANULACION" => $comentario
					);
					$act = $this->db->update("Facturas",$datosAc);
					if($act){
						$this->db->where("IDFACTURA",$refFactura);
						$dataFactAnul = array(
							"FECHAANULACION" => gmdate(date("Y-m-d H:i:s")),
							"IDUSUARIOANULA" => $this->session->userdata('id'),
							"ESTADO" => 1
						);
						$this->db->update("Facturas_Anulacion",$dataFactAnul);
					}
					//endregion
				}

			}
			//endregion
			echo json_encode($mensaje);
			echo @odbc_errormsg($conn);
			/*AABF-7675,  AA1F-21469*/
		}else{
			//$mensaje[0]["autorizado"] = "noautorizado";
		}
	}

	public function mostrarTraslados($fecha1, $fecha2){
		$conn = $this->OPen_database_odbcSAp();

			$query = 'SELECT t0."DocEntry",t0."DocNum",CAST(t0."CreateDate" AS DATE) "CreateDate",CAST(t0."DocDate" AS DATE) "DocDate",
					CONCAT(CASE LENGTH("CreateTS") WHEN 5 THEN CONCAT('.'0'.',SUBSTRING(RIGHT("CreateTS",6),1,1))
					ELSE SUBSTRING(RIGHT("CreateTS",6),1,2) END, CONCAT(CONCAT('."'".':'."'".',SUBSTRING(RIGHT("CreateTS",4),1,2)),
					CONCAT('."'".':'."'".',RIGHT("CreateTS",2)) )) "Hora"
					,t1."USERID",t1."U_NAME",t0."Filler",T3."WhsName",t0."ToWhsCode",T2."WhsName" "ToWhsName"
					FROM '.$this->BD.'.OWTR t0
					INNER JOIN '.$this->BD.'.OUSR T1 ON T0."UserSign" = T1."USERID"
					LEFT JOIN '.$this->BD.'.OWHS T2 ON T2."WhsCode" = t0."ToWhsCode"
					LEFT JOIN '.$this->BD.'.OWHS T3 ON T3."WhsCode" = t0."Filler"
					WHERE CAST(t0."DocDate" as DATE) >= '."'".$fecha1."'".' and 
					CAST(t0."DocDate" as DATE) <= '."'".$fecha2."'".' ';

		$resultado =  @odbc_exec($conn,$query);
		$json = array();
		$i=0;

		while ($fila = @odbc_fetch_array($resultado)){
			$json["data"][$i]["DocNum"] = $fila["DocNum"];
			$json["data"][$i]["CreateDate"] =$fila["CreateDate"];
			$json["data"][$i]["DocDate"] = $fila["DocDate"];
			$json["data"][$i]["Hora"] = $fila["Hora"];
			$json["data"][$i]["U_NAME"] = utf8_encode($fila["U_NAME"]);
			$json["data"][$i]["Filler"] = $fila["Filler"];
			$json["data"][$i]["WhsName"] = utf8_encode($fila["WhsName"]);
			$json["data"][$i]["ToWhsCode"] = $fila["ToWhsCode"];
			$json["data"][$i]["ToWhsName"] = utf8_encode($fila["ToWhsName"]);
			$json["data"][$i]["Detalles"] = "<p style='text-align:center;' class='expand text-primary'>
			<a onclick='detalles(".'"'.$fila["DocEntry"].'","'.$fila["Hora"].'","'.$fila["Filler"].'","'.$fila["ToWhsCode"].'","'.$fila["DocNum"].'"
			 ,"'.$fila["DocDate"].'","'.utf8_encode($fila["WhsName"]).'","'.utf8_encode($fila["ToWhsName"]).'"
			 ,"'.utf8_encode($fila["U_NAME"]).'"'.")'
			 href='javascript:void(0)'><i class='center fa fa-expand'></i></a></p>";
			$i++;
		}
		echo json_encode($json);
	}

	public function detalleTraslados($docEntry){
		$conn = $this->OPen_database_odbcSAp();

		$query = 'SELECT "DocEntry","LineNum","ItemCode","Dscription","Quantity","Price","LineTotal"
				  FROM '.$this->BD.'.WTR1
				  WHERE "DocEntry" = '."'".$docEntry."'".'
				  ORDER BY "DocEntry", "LineNum"
';

		$resultado =  @odbc_exec($conn,$query);
		$json = array();
		$i=0;

		while ($fila = @odbc_fetch_array($resultado)){
			$json["data"][$i]["DocEntry"] = $fila["DocEntry"];
			$json["data"][$i]["LineNum"] = $fila["LineNum"];
			$json["data"][$i]["ItemCode"] = $fila["ItemCode"];
			$json["data"][$i]["Dscription"] = utf8_encode($fila["Dscription"]);
			$json["data"][$i]["Quantity"] = number_format($fila["Quantity"],2);
			$json["data"][$i]["Price"] = number_format($fila["Price"],2);
			$json["data"][$i]["LineTotal"] = number_format($fila["LineTotal"],2);
			$i++;
		}
		echo json_encode($json);
		echo odbc_errormsg($conn);
	}
   public function getArtNoVendidos($fecha1,$fecha2,$notin,$idRuta)
  {
    $conn = $this->OPen_database_odbcSAp();
    $resultado =  @odbc_exec($conn,$query);
    $json = array();
    $i=0;

    $query = 'SELECT "CODIGO","DESCRIPCION","UM","GRAMOS",SUM("EXISTENCIA")- 
               (
                SELECT IFNULL(SUM("Quantity"),0) FROM '.$this->BD.'.OWTR TI0 INNER JOIN '.$this->BD.'.WTR1 TI1 ON TI0."DocEntry" = TI1."DocEntry"
                WHERE TI0."Filler" = T0."BODEGADESTINO" AND TI1."ItemCode" = T0."CODIGO" 
                AND CAST(TI0."DocDate" AS DATE) >= '."'".$fecha1."'".' AND CAST(TI0."DocDate" AS DATE) <= '."'".$fecha2."'".') "EXISTENCIA"
          FROM '.$this->BD.'.VIEW_ARTICULOS_EXISTENCIA T0
          WHERE T0."CODIGO" BETWEEN '."'1101'".' AND '."'88999'".'
          AND CAST(T0."CODVENDEDOR" AS CHAR) = '."'".$idRuta."'".'
          AND CAST(T0."FECHA" AS DATE) >= CAST('."'".$fecha1."'".' AS DATE) AND CAST(T0."FECHA" AS DATE) <= CAST('."'".$fecha2."'".'AS DATE) 
          AND T0."CODIGO" NOT IN('.$notin.')
          GROUP BY "CODIGO","DESCRIPCION","UM","GRAMOS","BODEGADESTINO"';
      //echo $query;
      $resultado =  @odbc_exec($conn,$query);
      while ($fila = @odbc_fetch_array($resultado)){

        $json[$i]["CODIGO"] = $fila["CODIGO"];
        $json[$i]["DESCRIPCION"] = utf8_encode($fila["DESCRIPCION"]);
        $json[$i]["UM"] = $fila["UM"];
        $json[$i]["GRAMOS"] = utf8_encode($fila["GRAMOS"]);
        $json[$i]["EXISTENCIA"] = number_format($fila["EXISTENCIA"],2);        
        $i++;
      }
      //echo json_encode($json);
      
      return $json;
      echo odbc_errormsg($conn);
  }
  public function getremisionSAP($fechainicio,$fechafinal,$idruta,$codArticulo){
    $conn = $this->OPen_database_odbcSAp();
     $query = 'CALL '.$this->BD.'.SP_APP_EXISTENCIA_POR_ARTICULO('."'".$fechainicio."'".','."'".$fechafinal."'".','.$idruta.','.$codArticulo.')';
    
    $resultado = @odbc_exec($conn,$query);
    //echo @odbc_num_rows($resultado);
    if (@odbc_num_rows($resultado)==0) {            
            return 0;
    }
         while ($fila = @odbc_fetch_array($resultado)){
            if ($fila['EXISTENCIA'] > 0) { 
                return $fila['EXISTENCIA'];
            }
        }
  }

}
?>
