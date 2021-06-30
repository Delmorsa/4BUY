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
       $query = ' SELECT "CardCode","CardName","CardFName"
                  FROM '.$this->BD.'.OCRD
                  WHERE "CardName" LIKE '."'%".$search."%'".'
                  OR "CardCode" LIKE '."'%".$search."%'".'
                  LIMIT 10';
       $resultado = @odbc_exec($conn,$query);
       $json = array();
       $i = 0;
       while ($fila = @odbc_fetch_array($resultado)) {
            $json[$i]["Codigo"] = $fila["CardCode"];
            $json[$i]["Nombre"] = utf8_encode($fila["CardName"]);
            $json[$i]["NombreComercial"] = utf8_encode($fila["CardFName"]);
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
                        WHERE "MERMA" = '."'Y'".' AND "WhsCode" = '."'01'".'
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
            $json[$i]["GRAMOS"] = number_format($fila["SWeight1"],0,",","");
            //$json[$i]["GRAMOS"] = number_format(12,0);
			$i++;
		}
		echo json_encode($json);
        @odbc_close($conn);
		//echo json_encode(@odbc_error($conn));
	}

	public function getStockSistemaSAP($idperiodo)	{
		    //articulos y su existencia
		 $conn = $this->OPen_database_odbcSAp();
		 //$query = 'SELECT * from '.$this->BD.'.APP_ARTICULOS_EXISTENCIA WHERE CODVENDEDOR = '."'".$codVendedor."'".' AND "EXISTENCIA">0';

		 $periodo = $this->db->query("SELECT IdPeriodo, CAST(FechaInicio AS DATE)FECHAINICIO,
		 CAST(FechaFinal AS DATE)FECHAFINAL, IdRuta
		 FROM Periodos WHERE IdPeriodo =".$idperiodo."");
		 //echo "SELECT IdPeriodo, CAST(FechaInicio AS DATE)FECHAINICIO,CAST(FechaFinal AS DATE)FECHAFINAL FROM Periodos WHERE Activo = 'Y' AND Liquidado = 'N' AND IdRuta =".$codVendedor."";
		 if ($periodo->num_rows() == 0) {
				 return 0;
		 }

		 $query = 'CALL '.$this->BD.'.SP_APP_EXISTENCIA_ARTICULOS('."'".$periodo->result_array()[0]["FECHAINICIO"]."'".','."'".$periodo->result_array()[0]["FECHAFINAL"]."'".','.$periodo->result_array()[0]["IdRuta"].')';
		 //echo $query;
		 $resultado = @odbc_exec($conn,$query);
		 $json = array();
		 $i=0;

		 if (@odbc_num_rows($resultado)==0) {
				 $json['results'][$i]["mCod"] = "NO RESULTADOS";
				 echo json_encode($json);
				 return;
		 }

		 while ($fila = @odbc_fetch_array($resultado)){
				 if ($fila['EXISTENCIA'] > 0) {
						// $json['results'][$i]["mExistencia"] = number_format($fila['EXISTENCIA']-$this->stock4Buy($fila['CODIGO'],$codVendedor),2);
						 //$json['results'][$i]["mExistenciaOriginal"] = number_format($fila['EXISTENCIA'],2);
						 return  number_format($fila['EXISTENCIA'],2);
				 }
		 }
		 //echo json_encode($json);
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
                return str_replace(",", "", $fila['EXISTENCIA']);
            }
        }
  }

	//mostrar Categoria de cada producto al momento de subir inventario
	public function getCategoriaById($itemcode){
		$conn = $this->OPen_database_odbcSAp();
		$query = 'SELECT "CodCategoria", "Categoria" FROM "SBO_DELMOR"."VIEW_ARTICULOS_CATEGORIA"
							  WHERE "ItemCode" = '."'".$itemcode."'".' ';

		$resultado = @odbc_exec($conn,$query);
		$json = array();
		$i = 0;
		while ($fila = @odbc_fetch_array($resultado)) {
			$json[$i]["CodCategoria"] = utf8_encode($fila["CodCategoria"]);
      $json[$i]["Categoria"] = utf8_encode($fila["Categoria"]);
            //$json[$i]["GRAMOS"] = number_format(12,0);
			$i++;
		}
		echo json_encode($json);
        @odbc_close($conn);
		//echo json_encode(@odbc_error($conn));
	}

  public function VerificarNotificacionAntiguedad()
  {
    $conn = $this->OPen_database_odbcSAp();

      $query = 'SELECT *
            FROM '.$this->BD.'.ANTIGUEDAD_PROVEEDORES t0            
            WHERE t0."1-30"  > 0';
            //echo $query;
      $resultado =  @odbc_exec($conn,$query);

      $numero = @odbc_num_rows($resultado);

      echo $numero;
  }
  
  public function getPagosProveedores()
  {
    $conn = $this->OPen_database_odbcSAp();
    $json = array();
    $i=0;
      $query = 'SELECT * FROM '.$this->BD.'.ANTIGUEDAD_PROVEEDORES t0 WHERE t0."1-30"  > 0';
      //echo $query;
      $resultado = @odbc_exec($conn,$query);

      //print_r($resultado);

        while ($fila = @odbc_fetch_array($resultado)){
          
          $json[$i]["DocNum"] = $fila["DocNum"];
          $json[$i]["NumAtCard"] = $fila["NumAtCard"];
          $json[$i]["CardCode"] = $fila["CardCode"];
          $json[$i]["CardName"] = $fila["CardName"];
          $json[$i]["LicTradNum"] = $fila["LicTradNum"];
          $json[$i]["DocStatus"] = $fila["DocStatus"];
          $json[$i]["ImpRetencion"] = $fila["ImpRetencion"];
          $json[$i]["TIPO"] = $fila["TIPO"];
          $json[$i]["VatSum"] = $fila["VatSum"];
          $json[$i]["DocTotal"] = $fila["DocTotal"];
          $json[$i]["Comments"] = $fila["Comments"];
          $json[$i]["Fecha_Factura"] = $fila["Fecha_Factura"];
          $json[$i]["Vencimiento"] = $fila["Vencimiento"];
          $json[$i]["Dias"] = $fila["Dias"];
          $json[$i]["Corriente"] = $fila["Corriente"];
          $json[$i]["1-30"] = $fila["1-30"];
          $json[$i]["31-60"] = $fila["31-60"];
          $json[$i]["61-90"] = $fila["61-90"];
          $json[$i]["91-120"] = $fila["91-120"];
          $json[$i]["121-+"] = $fila["121-+"];
          $json[$i]["CheckNum"] = $fila["CheckNum"];
          $json[$i]["BankCode"] = $fila["BankCode"];
          $json[$i]["AcctNum"] = $fila["AcctNum"];
          $json[$i]["TASA"] = $fila["TASA"];
          $i++;
        }
      return $json;
  }

  /*******comisiones***********/
  public function actualizarArticulos()
  {
    $conn = $this->OPen_database_odbcSAp();
    $json = array();
    $insertados = 0;
    $actualizados = 0;
    $query = 'SELECT T0."ItemCode", T0."ItemName", T1."CodCategoria", T1."Categoria",T2."GroupCode",T2."GroupName"
    FROM '.$this->BD.'.OITM T0
    INNER JOIN '.$this->BD.'.VIEW_ARTICULOS_CATEGORIA t1 on t1 ."ItemCode" = T0."ItemCode"
    INNER JOIN '.$this->BD.'.OCOG T2 ON T2."GroupCode" = T0."CommisGrp"
    WHERE T1."CodCategoria" is not null
    AND T2."GroupCode" <> 0
    ORDER BY T0."ItemCode"';
    //echo $query; return;
    $resultado = @odbc_exec($conn,$query);

    //print_r($resultado);

    while ($fila = @odbc_fetch_array($resultado)){


      $this->db->where('IdProducto',$fila["ItemCode"]);
      $existe = $this->db->get('C_Productos');

      if ($existe->num_rows()>0) {

        $this->db->where("IdProducto", $fila["ItemCode"]);
        $upd = array(
          "Nombre" => utf8_encode($fila["ItemName"]),
          "IdGrupo" => $fila["GroupCode"],
          "IdCategoria" => $fila["CodCategoria"],
          //"Categoria" => $fila["GroupName"],
          "IdUsuarioEdita" => $this->session->userdata('id'),
          "FechaEdita" => gmdate(date("Y-m-d H:i:s"))
        );
        $this->db->update("C_Productos",$upd);

        $actualizados++;

      }else{

        $insertArray = array(
          "IdProducto" => $fila["ItemCode"],
          "Nombre" => utf8_encode($fila["ItemName"]),
          "IdGrupo" => $fila["GroupCode"],
          "IdCategoria" => $fila["CodCategoria"],
          //"Categoria" => $fila["GroupName"],
          "IdUsuarioCrea" => $this->session->userdata('id'),
          "FechaCrea" => gmdate(date("Y-m-d H:i:s")),
          "Estado" => true
        );
        $insert = $this->db->insert("C_Productos",$insertArray);
        $insertados++;

      }
    }

    $mensaje[0]["mensaje"] = "Se insertaron ".$insertados." artículos y se actualizaron: ".$actualizados."";
    $mensaje[0]["tipo"] = "success";
    $this->db->trans_commit();
    echo json_encode($mensaje);
    return;    
    //return $json;

  }

  
  public function actualizarCategorias()
  {
    $conn = $this->OPen_database_odbcSAp();
    $json = array();
    $insertados = 0;
    $actualizados = 0;
    $query = 'SELECT "ItmsTypCod","ItmsGrpNam"
    FROM '.$this->BD.'.OITG 
    WHERE /*"ItmsTypCod" NOT IN (16,17,23,11) AND*/ "ItmsTypCod" IN (SELECT "CodCategoria" FROM '.$this->BD.'."VIEW_ARTICULOS_CATEGORIA")
    ORDER BY "ItmsTypCod" ASC';            
    $resultado = @odbc_exec($conn,$query);

    //echo $query;return;
    //$this->db->query("DELETE FROM C_Categorias");
    while ($fila = @odbc_fetch_array($resultado)){

      $this->db->where('IdCategoria',$fila["ItmsTypCod"]);
      $existe = $this->db->get('C_Categorias');

      if ($existe->num_rows()>0) {

        $this->db->where("IdCategoria", $fila["ItmsTypCod"]);
          $upd = array(
            "Nombre" => utf8_encode($fila["ItmsGrpNam"]),
            "IdUsuarioEdita" => $this->session->userdata('id'),
            "FechaEdita" => gmdate(date("Y-m-d H:i:s"))
          );
          $this->db->update("C_Categorias",$upd);

          $actualizados++;

      }else{

        $insertArray = array(
          "IdCategoria" => $fila["ItmsTypCod"],
          "Nombre" =>  utf8_encode($fila["ItmsGrpNam"]),
          "IdUsuarioCrea" => $this->session->userdata('id'),
          "FechaCrea" => gmdate(date("Y-m-d H:i:s")),
          "Estado" => true
        );

        $insert = $this->db->insert("C_Categorias",$insertArray);
        $insertados++;
      }

    }

    $mensaje[0]["mensaje"] = "Se insertaron ".$insertados." grupos y se actualizaron: ".$actualizados." grupos";
    $mensaje[0]["tipo"] = "success";
    //$this->db->trans_commit();
    echo json_encode($mensaje);
    return;
  }

  public function actualizarRutas()
  {
    $conn = $this->OPen_database_odbcSAp();
    $json = array();
    $insertados = 0;
    $actualizados = 0;
    $query = 'SELECT "SlpCode","SlpName" FROM '.$this->BD.'.OSLP ORDER BY "SlpCode" ASC';            
    $resultado = @odbc_exec($conn,$query);

    //print_r($resultado);
    while ($fila = @odbc_fetch_array($resultado)){

      $this->db->where('IdRuta',$fila["SlpCode"]);
      $existe = $this->db->get('C_Rutas');

      if ($existe->num_rows()>0) {

        $this->db->where("IdRuta", $fila["SlpCode"]);
          $upd = array(
            "Nombre" => utf8_encode($fila["SlpName"]),
            "IdUsuarioEdita" => $this->session->userdata('id'),
            "FechaEdita" => gmdate(date("Y-m-d H:i:s"))
          );
          $this->db->update("C_Rutas",$upd);

          $actualizados++;

      }else{

        $insertArray = array(
          "IdRuta" => $fila["SlpCode"],
          "Nombre" =>  utf8_encode($fila["SlpName"]),
          "IdUsuarioCrea" => $this->session->userdata('id'),
          "FechaCrea" => gmdate(date("Y-m-d H:i:s")),
          "Estado" => true
        );
        $insert = $this->db->insert("C_Rutas",$insertArray);
        $insertados++;
      }

    }

    $mensaje[0]["mensaje"] = "Se insertaron ".$insertados." rutas y se actualizaron: ".$actualizados." rutas";
    $mensaje[0]["tipo"] = "success";
    //$this->db->trans_commit();
    echo json_encode($mensaje);
    return;
  }

  public function traerVentasDevoluciones($vendedor = null,$desde,$hasta)
  {
    $and = '';
    if ($vendedor!= null) {
      $and = ' WHERE T0."CODVENDEDOR" = '.$vendedor;
    }
    $conn = $this->OPen_database_odbcSAp();
    $json = array();
    $i=0;

    $query = 'SELECT "FECHA","CODVENDEDOR","VENDEDOR","CODCOMISION","NOMBRECOMISION","CODCATEGORIA","CATEGORIA",SUM("LIBRAS_VENDIDAS") "LIBRAS_VENDIDAS",SUM("LIBRAS_DEVOLUCION")"LIBRAS_DEVOLUCION" FROM 
      (
      SELECT
        cast(T0."FECHA" as date)"FECHA",
        T0."WhsCode",
        T0."CODVENDEDOR",
        T0."VENDEDOR",
        T0."CODCOMISION",
        T0."NOMBRECOMISION",
        T0."CODCATEGORIA",
        T0."CATEGORIA",
        SUM( "LIBRAS_VENDIDAS" ) "LIBRAS_VENDIDAS",
        SUM( "LIBRAS_DEVOLUCION" ) "LIBRAS_DEVOLUCION",
        IFNULL(
          (
          SELECT
            SUM( ( ( TI0."Weight1" ) / 1000 ) / 0.454 ) "Lbs" 
          FROM
            SBO_DELMOR."VIEW_DEVOLUCIONES_DELMOR" TI0 
          WHERE
            TI0."DocType" = '."'I'".'
            AND TI0."InvntSttus" = '."'O'".'
            AND TI0."DocDate" >= '."'".$desde."'".' 
            AND TI0."DocDate" <= '."'".$hasta."'".' 
            AND TI0."SlpCode" = T0."CODVENDEDOR" 
            AND TI0."CommisGrp" = T0."CODCOMISION" 
            AND TI0."CodCategoria" = T0."CODCATEGORIA" 
            AND cast(TI0."DocDate" as date) =  cast (T0."FECHA" as date)
          ),
          0 
        ) "DEVOLUCION",
        T0."COMISION" 
      FROM
        (
        SELECT
          T0."CODCLIENTE",
          T0."CLIENTE",
          T0."FECHA",
          T0."WhsCode",
          T0."CODVENDEDOR",
          T0."VENDEDOR",
          T0."CODCOMISION",
          T0."NOMBRECOMISION",
          T0."CODCATEGORIA",
          T0."CATEGORIA",
          T0."CODIGO",
          T0."DESCRIPCION",
          SUM( T0."LIBRAS" ) "LIBRAS_VENDIDAS",
          IFNULL(
            (
            SELECT
              SUM( ( ( TI1."Weight1" ) / 1000 ) / 0.454 ) "Lbs" 
            FROM
              SBO_DELMOR.ORIN TI0
              INNER JOIN SBO_DELMOR.RIN1 TI1 ON TI1."DocEntry" = TI0."DocEntry" 
            WHERE
              TI0."DocType" = '."'I'".'
              AND TI0."InvntSttus" = '."'O'".'
              AND TI0."DocDate" >= '."'".$desde."'".' 
              AND TI0."DocDate" <= '."'".$hasta."'".' 
              AND TI1."ItemCode" = T0."CODIGO" 
              AND TI0."SlpCode" = T0."CODVENDEDOR" 
            ),
            0 
          ) "LIBRAS_DEVOLUCION",
          T0."COMISION" 
        FROM        
        SBO_DELMOR."VIEW_VENTAS_DELMOR" T0 
        WHERE
          T0."CODVENDEDOR" <> '."'-1'".' 
          AND T0."ESTADO" NOT IN ( '."'Y'".', '."'C'".' ) 
          AND T0."SUBTIPO" <> '."'ND'".' 
          AND T0."FECHA" >= '."'".$desde."'".' 
          AND T0."FECHA" <= '."'".$hasta."'".'
          AND T0."CODCOMISION" <> 0 
        GROUP BY
          T0."FECHA",
          T0."CODCLIENTE",
          T0."CLIENTE",
          T0."WhsCode",
          T0."CODVENDEDOR",
          T0."VENDEDOR",
          T0."CODCOMISION",
          T0."NOMBRECOMISION",
          T0."COMISION",
          T0."CODCATEGORIA",
          T0."CATEGORIA",
          T0."CODIGO",
          T0."DESCRIPCION" 
        ORDER BY
          T0."FECHA",
          T0."CODVENDEDOR",
          T0."CODCOMISION",
          T0."CODCATEGORIA" 
        ) T0 
      GROUP BY
        T0."FECHA",
        T0."WhsCode",
        T0."CODVENDEDOR",
        T0."VENDEDOR",
        T0."CODCOMISION",
        T0."NOMBRECOMISION",
        T0."COMISION",
        T0."CODCATEGORIA",
        T0."CATEGORIA" 
      ORDER BY
        T0."CODVENDEDOR",
        T0."VENDEDOR",
        T0."CODCOMISION",
        T0."NOMBRECOMISION",
        T0."CODCATEGORIA",
        T0."CATEGORIA"
      )
      GROUP BY "FECHA","CODVENDEDOR","VENDEDOR","CODCOMISION","NOMBRECOMISION","CODCATEGORIA","CATEGORIA"';

      // echo $query;return;

      
      $resultado = @odbc_exec($conn,$query);
      while ($fila = @odbc_fetch_array($resultado)){
          
          $json[$i]["FECHA"] = $fila["FECHA"];
          $json[$i]["CODVENDEDOR"] = $fila["CODVENDEDOR"];
          $json[$i]["VENDEDOR"] = utf8_encode($fila["VENDEDOR"]);
          $json[$i]["CODCOMISION"] = $fila["CODCOMISION"];
          $json[$i]["NOMBRECOMISION"] = utf8_encode($fila["NOMBRECOMISION"]);
          $json[$i]["CODCATEGORIA"] = $fila["CODCATEGORIA"];
          $json[$i]["CATEGORIA"] = utf8_encode($fila["CATEGORIA"]);
          $json[$i]["LIBRAS_VENDIDAS"] = $fila["LIBRAS_VENDIDAS"];
          $json[$i]["LIBRAS_DEVOLUCION"] = $fila["LIBRAS_DEVOLUCION"];
          $json[$i]["DEVOLUCION"] = $fila["DEVOLUCION"];          
          $i++;
        }
      return $json;
  }

  public function getDevolucion($tipo,$ruta,$desde,$hasta,$IdCategoria,$IdGrupo)
  { 
    $conn = $this->OPen_database_odbcSAp();
    $json = array();
    if ($tipo == 1) {
      
      $query = 'SELECT SUM((("Weight1")/1000)/0.454) "Libras" FROM  
                '.$this->BD.'."VIEW_DEVOLUCIONES_DELMOR"
                WHERE "CommisGrp" = '.$IdGrupo.'
                AND "CodCategoria" = '.$IdCategoria.'
                AND "SlpCode" = '.$ruta.'
                AND CAST("DocDate" as date) >= '."'".$desde."'".' and CAST("DocDate" as date) <= '."'".$hasta."'".'';
      //echo $query.";<br>";
      $resultado = @odbc_exec($conn,$query);   

      while ($fila = @odbc_fetch_array($resultado)){
        return $fila["Libras"];
      }
    }
    return 0;
  }

  public function generarReportePagoDevoluciones($tipo,$trabajador,$desde,$hasta,$SlpCode = null,$bandera = null)
  {
      $conn = $this->OPen_database_odbcSAp();
      
      if ($tipo == 1) {
          $json = array();
          $i=0;

          $and = '';
          $SlpCodes = '';

          $vendedores = $this->db->query("SELECT * FROM Usuarios WHERE Estado = 1 AND IdRuta IN (SELECT IdRuta FROM C_RutaCanal WHERE Estado = 1)");

          if ($vendedores->result_array()>0) {
              foreach ($vendedores->result_array() as $key) {
                $SlpCodes .= $key["IdRuta"].",";
              }
            $SlpCodes = substr($SlpCodes, 0, -1);
          }
          if ($SlpCodes != '') {
            $and = ' and "SlpCode" in ('.$SlpCodes.')';
          }

          if ($SlpCode != null && $trabajador != "0") {
            $and = ' and "SlpCode" = '.$SlpCode.'';
          }

          $query = 'SELECT "SlpCode","SlpName","CommisGrp","GroupName","CodCategoria","Categoria", SUM((("Weight1")/1000)/0.454) "Libras"
          FROM  
          '.$this->BD.'."VIEW_DEVOLUCIONES_DELMOR"
          WHERE CAST("DocDate" as date) >= '."'".$desde."'".' and CAST("DocDate" as date) <= '."'".$hasta."'".'
          '.$and.'
          GROUP BY "SlpCode","SlpName","CommisGrp","GroupName","CodCategoria","Categoria"
          ORDER BY "SlpName","CommisGrp","Categoria" desc';
          
          //echo $query; return;
          $resultado = @odbc_exec($conn,$query);

          while ($fila = @odbc_fetch_array($resultado)){
            $json["data"][$i]["Nombre"] = utf8_encode($fila["SlpName"]);
            $json["data"][$i]["CommisGrp"] = $fila["CommisGrp"];
            $json["data"][$i]["GroupName"] = $fila["GroupName"];
            $json["data"][$i]["CodCategoria"] = $fila["CodCategoria"];
            $json["data"][$i]["Categoria"] = $fila["Categoria"];
            $json["data"][$i]["Libras"] = number_format($fila["Libras"],2);
            $i++;
          }
          
          if ($bandera != null) {            
            return $json;
          }
          echo json_encode($json);
      }
      if ($tipo == 2) {

          $json = array();
          $i=0;
          $and = '';      
          $SlpCodes = '';

          $vendedores = $this->db->query("SELECT * FROM Usuarios WHERE Estado = 1 AND IdRuta IN (SELECT IdRuta FROM C_RutaCanal WHERE Estado = 1)");
          if ($vendedores->result_array()>0) {
              foreach ($vendedores->result_array() as $key) {
                $SlpCodes .= $key["IdRuta"].",";
              }
            $SlpCodes = substr($SlpCodes, 0, -1);
          }

          if ($SlpCode != null && $trabajador != "0") {
            $vendedores = $this->db->query("SELECT * FROM Usuarios WHERE IdSupervisor =".$trabajador." AND Estado = 1 AND IdRuta IN (SELECT IdRuta FROM C_RutaCanal WHERE Estado = 1)");
            $SlpCodes = '';
            if ($vendedores->result_array()>0) {
              foreach ($vendedores->result_array() as $key) {
                $SlpCodes .= $key["IdRuta"].",";
              }
            }

            $SlpCodes = substr($SlpCodes, 0, -1);
          }

          if ($SlpCodes != '') {
            $and = ' and "SlpCode" in ('.$SlpCodes.')';
          }

          $query = 'SELECT "SlpCode","SlpName","CommisGrp","GroupName","CodCategoria","Categoria", SUM((("Weight1")/1000)/0.454) "Libras"
          FROM  
          '.$this->BD.'."VIEW_DEVOLUCIONES_DELMOR"
          WHERE CAST("DocDate" as date) >= '."'".$desde."'".' and CAST("DocDate" as date) <= '."'".$hasta."'".'
          '.$and.'
          GROUP BY "SlpCode","SlpName","CommisGrp","GroupName","CodCategoria","Categoria"
          ORDER BY "SlpName","CommisGrp","Categoria" desc';
          
          //echo $query; return;
          $resultado = @odbc_exec($conn,$query);
          $carajo = 0;
          while ($fila = @odbc_fetch_array($resultado)){
            $json["data"][$i]["Nombre"] = utf8_encode($fila["SlpName"]);
            $json["data"][$i]["CommisGrp"] = $fila["CommisGrp"];
            $json["data"][$i]["GroupName"] = $fila["GroupName"];
            $json["data"][$i]["CodCategoria"] = $fila["CodCategoria"];
            $json["data"][$i]["Categoria"] = $fila["Categoria"];
            $json["data"][$i]["Libras"] = number_format($fila["Libras"],2);
            $carajo += $fila["Libras"];
            $i++;
          }
          
          //echo $carajo;
          if ($bandera != null) {            
            return $json;
          }
          echo json_encode($json);
      }
      if ($tipo == 3) {

          $json = array();
          $i=0;
          $and = '';      
          $SlpCodes = '';

          $query = 'SELECT "SlpCode","SlpName","CommisGrp","GroupName","CodCategoria","Categoria", SUM((("Weight1")/1000)/0.454) "Libras"
          FROM  
          '.$this->BD.'."VIEW_DEVOLUCIONES_DELMOR"
          WHERE CAST("DocDate" as date) >= '."'".$desde."'".' and CAST("DocDate" as date) <= '."'".$hasta."'".'
          
          GROUP BY "SlpCode","SlpName","CommisGrp","GroupName","CodCategoria","Categoria"
          ORDER BY "SlpName","CommisGrp","Categoria" desc';
          
          //echo $query; return;
          $resultado = @odbc_exec($conn,$query);
          while ($fila = @odbc_fetch_array($resultado)){
            $json["data"][$i]["Nombre"] = 'Gerente de ventas';
            $json["data"][$i]["CommisGrp"] = "";
            $json["data"][$i]["GroupName"] = "";
            $json["data"][$i]["CodCategoria"] = "";
            $json["data"][$i]["Categoria"] = "";
            $json["data"][$i]["Libras"] = number_format($fila["Libras"],2);
            
            $i++;
          }
          
          //echo $carajo;
          if ($bandera != null) {            
            return $json;
          }
          echo json_encode($json);
      }

      if ($tipo == 4) {
       $json = array();
       $i=0;

       $and = '';

       /*validar la condicion de trabajador*********************************/
       $and = '';
        if ($trabajador != null && $trabajador != 0) {
          $and = ' and IdUsuario = '.$trabajador."";
        }

          $impulsadoras = $this->db->query('SELECT * FROM Usuarios where Estado = 1 and IdRol = 20 and IdSupervisora <> 2137'.$and);//agregar rol 21 rol si se quiere a las supervisoras de  impulsadoras tambien

          foreach ($impulsadoras->result_array() as $key) {
            $inClientes = '';
            $queryClientes = $this->db->query('SELECT * FROM C_ClientesImpulsadoras where IdImpulsadora = '.$key["IdUsuario"].' and Estado = 1');
            
            foreach ($queryClientes->result_array() as $key2) {
              $inClientes .= "'".$key2["IdCliente"]."',";
            }
            $inClientes = substr($inClientes, 0, -1);


            $query = 'SELECT "CodCategoria" AS "CODCATEGORIA","Categoria" as "CATEGORIA",sum( "Weight1" ) / 454 "Libras"
            FROM "SBO_DELMOR"."VIEW_DEVOLUCIONES_DELMOR" 
            WHERE
            cast("DocDate" as date) >= cast('."'".$desde."'".' as date)
            AND cast("DocDate" as date) <= cast('."'".$hasta."'".' as date)
            AND "CardCode" IN ('.$inClientes.')
            GROUP BY "CodCategoria","Categoria"';

            //echo $query;
            $resultado = @odbc_exec($conn,$query);

            while ($fila = @odbc_fetch_array($resultado)){

              $json["data"][$i]["Nombre"] = $key["Nombre"].' '.$key["Apellidos"];
              $json["data"][$i]["CATEGORIA"] = $fila["CATEGORIA"];                    
              $json["data"][$i]["Libras"] = number_format($fila["Libras"],2);                   
              $i++;

            }

          }/////

          if ($bandera != null) {
            return $json;
          }
          echo json_encode($json);
      }

      if ($tipo == 5) {

          $json = array();
          $i=0;

          $and = '';
          $SlpCodes = '';

         
            $and = ' and "SlpCode" = 2';
          
          //2137
          $query = 'SELECT "SlpCode","SlpName","CommisGrp","GroupName","CodCategoria","Categoria", SUM((("Weight1")/1000)/0.454) "Libras"
          FROM  
          '.$this->BD.'."VIEW_DEVOLUCIONES_DELMOR"
          WHERE CAST("DocDate" as date) >= '."'".$desde."'".' and CAST("DocDate" as date) <= '."'".$hasta."'".'
          '.$and.'
          GROUP BY "SlpCode","SlpName","CommisGrp","GroupName","CodCategoria","Categoria"
          ORDER BY "SlpName","CommisGrp","Categoria" desc';
          
          //echo $query; return;
          $resultado = @odbc_exec($conn,$query);

          while ($fila = @odbc_fetch_array($resultado)){
            $json["data"][$i]["Nombre"] = utf8_encode($fila["SlpName"]);
            $json["data"][$i]["CommisGrp"] = $fila["CommisGrp"];
            $json["data"][$i]["GroupName"] = $fila["GroupName"];
            $json["data"][$i]["CodCategoria"] = $fila["CodCategoria"];
            $json["data"][$i]["Categoria"] = $fila["Categoria"];
            $json["data"][$i]["Libras"] = number_format($fila["Libras"],2);
            $i++;
          }
          
          if ($bandera != null) {
            return $json;
          }
          echo json_encode($json);
      }

      if ($tipo == 6) {
       $json = array();
       $i=0;

       $and = '';

       /*validar la condicion de trabajador*********************************/
       $and = '';
        if ($trabajador != null && $trabajador != 0) {
          $and = ' and (IdSupervisora = '.$trabajador." or IdUsuario = ".$trabajador.")";
        }

          $impulsadoras = $this->db->query(" SELECT t0.*,
                                             CASE WHEN t1.IdUsuario is not null then CONCAT(t1.Nombre,' ',t1.Apellidos) else CONCAT(t0.Nombre,' ',t0.Apellidos) end as jefe 
                                             FROM Usuarios t0
                                             LEFT JOIN Usuarios t1 on t1.IdUsuario = t0.IdSupervisora
                                             WHERE t0.Estado = 1 and t0.IdRol = 20 and t0.IdSupervisora <> 2137".$and);
          //agregar rol 21 rol si se quiere a las supervisoras de  impulsadoras tambien

          /*echo "SELECT t0.*,
                                             CASE WHEN t1.IdUsuario is not null then CONCAT(t1.Nombre,' ',t1.Apellidos) else CONCAT(t0.Nombre,' ',t0.Apellidos) end as jefe 
                                             FROM Usuarios t0
                                             LEFT JOIN Usuarios t1 on t1.IdUsuario = t0.IdSupervisora
                                             WHERE t0.Estado = 1 and t0.IdRol = 20 and t0.IdSupervisora <> 2137".$and;
                                             return;*/
//          echo json_encode($impulsadoras->result_array());return;

          foreach ($impulsadoras->result_array() as $key) {

            
            $inClientes = '';
            $queryClientes = $this->db->query('SELECT * FROM C_ClientesImpulsadoras where IdImpulsadora = '.$key["IdUsuario"].' and Estado = 1');
            
            foreach ($queryClientes->result_array() as $key2) {
              $inClientes .= "'".$key2["IdCliente"]."',";
            }
            $inClientes = substr($inClientes, 0, -1);

            $query = 'SELECT "CodCategoria" AS "CODCATEGORIA","Categoria" as "CATEGORIA",sum( "Weight1" ) / 454 "Libras"
            FROM "SBO_DELMOR"."VIEW_DEVOLUCIONES_DELMOR" 
            WHERE
            cast("DocDate" as date) >= cast('."'".$desde."'".' as date)
            AND cast("DocDate" as date) <= cast('."'".$hasta."'".' as date)
            AND "CardCode" IN ('.$inClientes.')
            GROUP BY "CodCategoria","Categoria"';

            //echo $query;
            $resultado = @odbc_exec($conn,$query);
            while ($fila = @odbc_fetch_array($resultado)){
            
              $json["data"][$i]["jefe"] = $key["jefe"];
              $json["data"][$i]["Nombre"] = $key["Nombre"].' '.$key["Apellidos"];
              $json["data"][$i]["CATEGORIA"] = $fila["CATEGORIA"];
              $json["data"][$i]["Libras"] = number_format($fila["Libras"],2);
              $i++;

            }

          }/////

          if ($bandera != null) {
            return $json;
          }
          echo json_encode($json);
      }
      
  }


  public function getDevolucionSupervisor($tipo,$supervisor,$desde,$hasta,$IdCategoria,$IdGrupo)
  { 
    $conn = $this->OPen_database_odbcSAp();
    $json = array();
    $SlpCodes = '';

    $supervisores = $this->db->query("SELECT * FROM Usuarios WHERE IdSupervisor =".$supervisor." AND Estado = 1 AND IdRuta IN (SELECT IdRuta FROM C_RutaCanal WHERE Estado = 1)");
    /*echo "SELECT * FROM Usuarios WHERE IdSupervisor =".$supervisor." AND Estado = 1 AND IdRuta IN (SELECT IdRuta FROM C_RutaCanal WHERE Estado = 1)"."<br>";*/

    if ($supervisores->result_array()>0) {
      foreach ($supervisores->result_array() as $key) {
        $SlpCodes .= $key["IdRuta"].",";
      }
    }

      $SlpCodes = substr($SlpCodes, 0, -1);

      $query = 'SELECT IFNULL(SUM((("Weight1")/1000)/0.454),0) "Libras" FROM  
                '.$this->BD.'."VIEW_DEVOLUCIONES_DELMOR"
                WHERE "CommisGrp" = '.$IdCategoria.'
                AND "CodCategoria" = '.$IdGrupo.'
                AND "SlpCode" in ('.$SlpCodes.')
                AND CAST("DocDate" as date) >= '."'".$desde."'".' and CAST("DocDate" as date) <= '."'".$hasta."'".'';
                if ($supervisor == 2079) {
                  //echo $query; return;
                  # code...
                }

      $resultado = @odbc_exec($conn,$query);   

      while ($fila = @odbc_fetch_array($resultado)){
        return $fila["Libras"];
      }
    
    return 0;
  }
  public function generarPagoGerente($desde,$hasta,$bandera)
  {
     $conn = $this->OPen_database_odbcSAp();
     $json = array();
     $i=0;

     $and = '';   

     $query = 'SELECT SUM("Lbs") "Libras", (SELECT  SUM((("Weight1")/1000)/0.454) "Libras"
          FROM  
          "SBO_DELMOR"."VIEW_DEVOLUCIONES_DELMOR"
          WHERE CAST("DocDate" as date) >= '."'".$desde."'".' and CAST("DocDate" as date) <= '."'".$hasta."'".')
      "Devolucion" 
        FROM (
         SELECT T0."DocNum" "Factura",T0."CardName",T3."ItmsGrpCod", T3."ItmsGrpNam",  T7."FirmName", T2."ItemCode", T2."ItemName", 
         T1."Quantity", T1."Weight1"/1000 "Kg", (T1."Weight1"/1000)/0.454 "Lbs", T1."PriceBefDi"*T1."Quantity" "VtaBruta", (T1."PriceBefDi"*T1."Quantity")-T1."LineTotal" "Descuento",
         T1."LineTotal",
         T1."TaxCode", IFNULL(T5."TaxSum",0) "IVA",
         T1."LineTotal" + (IFNULL(T6."TaxSum",0)+ IFNULL(T5."TaxSum",0)) Total,
         IFNULL(T6."TaxSum",0) "ISC", T1."WhsCode"
         FROM '.$this->BD.'.OINV T0     
         INNER JOIN '.$this->BD.'.OCRD T8 ON T8."CardCode" = T0."CardCode"
         left JOIN '.$this->BD.'.OTER T9 ON T9."territryID" = T8."Territory"
         INNER JOIN '.$this->BD.'.INV1 T1 ON T1."DocEntry"=T0."DocEntry"
         INNER JOIN '.$this->BD.'.OITM T2 ON T2."ItemCode"=T1."ItemCode"
         INNER JOIN '.$this->BD.'.OITB T3 ON T3."ItmsGrpCod"=T2."ItmsGrpCod"
         LEFT JOIN '.$this->BD.'.INV4 T5 ON T5."DocEntry"=T1."DocEntry" AND T5."LineNum"=T1."LineNum" AND T5."StaCode"='."'IVA'".'
         LEFT JOIN '.$this->BD.'.INV4 T6 ON T6."DocEntry"=T1."DocEntry" AND T6."LineNum"=T1."LineNum" AND T6."StaCode"='."'ISC'".'
         LEFT JOIN '.$this->BD.'.OMRC T7 ON T7."FirmCode"=T2."FirmCode"
         WHERE T0."DocDate">= '."'".$desde."'".' AND T0."DocDate"<= '."'".$hasta."'".' 
         AND T0."CANCELED" NOT IN ('."'Y'".', '."'C'".')
         AND T0."DocSubType" NOT IN ('."'DN'".')
       )';

        //echo $query; return;
      $resultado = @odbc_exec($conn,$query);
      $json = array();
          $i = 0;

      while ($fila = @odbc_fetch_array($resultado)){
            $json["data"][$i]["IdUsuario"] = "";
            $json["data"][$i]["Nombre"] = "";
            $json["data"][$i]["IdCategoria"] = "";
            $json["data"][$i]["Categoria"] = "";
            $json["data"][$i]["IdGrupo"] = "";
            $json["data"][$i]["Grupo"] = "";
            $json["data"][$i]["IdCanal"] = "";
            $json["data"][$i]["Devolucion"] = number_format($fila["Devolucion"],2);
            $json["data"][$i]["Libras"] = number_format($fila["Libras"],2);
            $json["data"][$i]["Total"] = number_format(($fila["Libras"]-$fila["Devolucion"])*0.01,2);          
            $json["data"][$i]["TotalLibras"] = number_format($fila["Libras"]-$fila["Devolucion"],2);
            $json["data"][$i]["Comision"] = "1%";          
        $i++;
        ///echo "entroooo";
      }
      //echo $bandera;
      //echo json_encode($json);
      if ($bandera != null) {
        return $json;
      }
      echo json_encode($json);    
  }

  public function generarPagoImpulsadoras($tipo,$trabajador,$desde,$hasta,$bandera,$consolidar = null)
  {
     $conn = $this->OPen_database_odbcSAp();
     $json = array();
     $i=0;

     $and = '';   
     
      /*validar la condicion de trabajador*********************************/
      $and = '';
      if ($trabajador != null && $trabajador != 0) {
        $and = ' and IdUsuario = '.$trabajador."";
      }


      $impulsadoras = $this->db->query('SELECT * FROM Usuarios where Estado = 1 and IdRol = 20 and IdSupervisora <> 2137'.$and);//agregar rol 21 rol si se quiere a las supervisoras de  impulsadoras tambien

      foreach ($impulsadoras->result_array() as $key) {
        $inClientes = '';
        $queryClientes = $this->db->query('SELECT * FROM C_ClientesImpulsadoras where IdImpulsadora = '.$key["IdUsuario"].' and Estado = 1');

        foreach ($queryClientes->result_array() as $key2) {
          $inClientes .= "'".$key2["IdCliente"]."',";
        }

        $inClientes = substr($inClientes, 0, -1);       
        
        $query = 'SELECT
          "CODCATEGORIA",
          "CATEGORIA",
          SUM("Libras") as "Libras",
          SUM("Devolucion") as "Devolucion" 
          FROM (
          SELECT
          "CODCATEGORIA", 
          "CATEGORIA",
          sum( "LIBRAS" ) "Libras",
          IFNULL(
          (
          SELECT
          sum( "Weight1" ) / 454 
          FROM
          "SBO_DELMOR"."VIEW_DEVOLUCIONES_DELMOR" 
          WHERE "CodCategoria" = "CODCATEGORIA"
          AND "CardCode" = "CODCLIENTE" 
          AND cast("DocDate" as date) >= cast('."'".$desde."'".' as date)
          AND cast("DocDate" as date) <= cast('."'".$hasta."'".' as date)
          ),0) "Devolucion"
          FROM SBO_DELMOR."VIEW_VENTAS_DELMOR" T0 
          WHERE T0."ESTADO" NOT IN ('."'Y'".', '."'C'".')
          AND T0."SUBTIPO" <> '."'DN'".'
          AND cast(T0."FECHA" as date) >= cast('."'".$desde."'".' as date)
          AND cast(T0."FECHA" as date) <= cast('."'".$hasta."'".' as date)
          AND T0."CODCLIENTE" IN ('.$inClientes.')
          GROUP BY
          T0."CODCATEGORIA",T0."CATEGORIA","CODCLIENTE" 
        ) GROUP BY "CODCATEGORIA",  "CATEGORIA"';

        if ($consolidar != null) {
          $query = 'SELECT "CODCATEGORIA",
                  "CATEGORIA",
                  SUM( "Libras" ) "Libras",
                  sum( "Devolucion" ) "Devolucion" 
                  FROM (
                  SELECT "CODCATEGORIA","CATEGORIA","CODCLIENTE","CLIENTE","NOMBRECOMERCIAL", sum("LIBRAS") "Libras", 
                  IFNULL((select sum ("Weight1")/454 
                  FROM "SBO_DELMOR"."VIEW_DEVOLUCIONES_DELMOR" 
                  WHERE "CardCode" = "CODCLIENTE" 
                  AND cast("DocDate" as date) >= cast('."'".$desde."'".' as date)
                  AND cast("DocDate" as date) <= cast('."'".$hasta."'".' as date)
                  AND "CodCategoria" = "CODCATEGORIA"
                  ),0) "Devolucion"
                  FROM SBO_DELMOR."VIEW_VENTAS_DELMOR" T0 
                  WHERE T0."ESTADO" NOT IN ('."'Y'".', '."'C'".')
                  AND T0."SUBTIPO" <> '."'DN'".'
                  AND cast(T0."FECHA" as date) >= cast('."'".$desde."'".' as date)
                  AND cast(T0."FECHA" as date) <= cast('."'".$hasta."'".' as date)
                  AND T0."CODCLIENTE" IN ('.$inClientes.')
                  GROUP BY T0."CODCATEGORIA",T0."CATEGORIA",T0."CODCLIENTE",T0."CLIENTE",T0."NOMBRECOMERCIAL"
                )
                GROUP BY "CODCATEGORIA","CATEGORIA"';
        }

        //echo $query;return;

       

        $resultado = @odbc_exec($conn,$query);
        
        if ($consolidar == null) {

            while ($fila = @odbc_fetch_array($resultado)){
                $queryComision = $this->db->query(
                  "SELECT ISNULL(ValorImpulsadora,0) comision
                    FROM C_ImpulsadoraComision T0
                    INNER JOIN C_ImpulsadoraPeriodo T1 ON T1.IdPeriodo = T0.IdPeriodo
                    WHERE T1.Estado = 1
                    AND T1.TIPO = 3
                    AND T1.FechaInicial <= '".$desde."'
                    AND T1.FechaFinal >= '".$hasta."'
                    and month(t1.FechaInicial) = month('".$desde."')
                    and year(t1.FechaInicial) = year('".$desde."')
                    AND T0.IdImpulsadora = ".$key["IdUsuario"]."
                    AND T0.IdCategoria = ".$fila["CODCATEGORIA"]
                );

                $comision = 0;
                if ($queryComision->num_rows()>0) {
                  $comision = $queryComision->result_array()[0]["comision"];
                }

                $timestamp = strtotime($hasta);
                $day = date('d', $timestamp);


                if ($comision>0) {

                  $json["data"][$i]["Adelanto"] = 0;

                  //if ($day == 15 || $day == 16) {//si es quincena
                  $json["data"][$i]["Adelanto"] = number_format($this->getAdelanto($key["IdUsuario"]),2);
                  //}

                  $json["data"][$i]["IdUsuario"] = $key["IdUsuario"];
                  $json["data"][$i]["Nombre"] = $key["Nombre"].' '.$key["Apellidos"];
                  $json["data"][$i]["CODCATEGORIA"] = $fila["CODCATEGORIA"];
                  $json["data"][$i]["CATEGORIA"] = utf8_encode($fila["CATEGORIA"]);
                  $json["data"][$i]["Devolucion"] = number_format($fila["Devolucion"],2);
                  $json["data"][$i]["Libras"] = number_format($fila["Libras"],2);
                  $json["data"][$i]["TotalLibras"] = number_format($fila["Libras"]-$fila["Devolucion"],2);                  
                  $json["data"][$i]["Total"] = number_format(($fila["Libras"]-$fila["Devolucion"])*$comision,2);//CALCULAR COMISION
                  $json["data"][$i]["Comision"] = number_format($comision,2);//CALCULAR COMISION QUERY
                  $i++;
                }

            }
        }else{

          $this->db->query("TRUNCATE TABLE C_TempV");
            while ($fila = @odbc_fetch_array($resultado)){

                $queryComision = $this->db->query(
                  "SELECT ISNULL(ValorImpulsadora,0) comision
                    FROM C_ImpulsadoraComision T0
                    INNER JOIN C_ImpulsadoraPeriodo T1 ON T1.IdPeriodo = T0.IdPeriodo
                    WHERE T1.Estado = 1
                    AND T1.FechaInicial <= '".$desde."'
                    AND T1.FechaFinal >= '".$hasta."'
                    and month(t1.FechaInicial) = month('".$desde."')
                    and year(t1.FechaInicial) = year('".$desde."')
                    AND T0.IdImpulsadora = ".$key["IdUsuario"]."
                    AND T0.IdCategoria = ".$fila["CODCATEGORIA"]
                );

                $comision = 0;
                if ($queryComision->num_rows()>0) {
                  $comision = $queryComision->result_array()[0]["comision"];
                } 
                if ($comision>0) {
                   $insert = array(
                    "IdUsuario" => $key["IdUsuario"],
                    "Nombre" => $key["Nombre"].' '.$key["Apellidos"],
                    "IdCategoria" => $fila["CODCATEGORIA"],
                    "Categoria" => utf8_encode($fila["CATEGORIA"]),
                    "Libras" => $fila["Libras"],
                    "Devolucion" => $fila["Devolucion"],
                    "Comision" => $comision,
                    "TotalLibras" => $fila["Libras"] - $fila["Devolucion"],
                    "Total" => ($fila["Libras"]-$fila["Devolucion"])*$comision
                  );

                  $this->db->insert("C_TempV",$insert);
                }//end if ($comision>0) 
            }

            $resultado = $this->db->query("SELECT IdUsuario,Nombre, SUM(Libras) Libras,SUM(Devolucion) Devolucion, SUM(TotalLibras)TotalLibras,sum(Total)Total
                                      FROM C_TempV 
                                      GROUP BY IdUsuario,Nombre");

            foreach ($resultado->result_array() as $key) {
              $json["data"][$i]["Adelanto"] = number_format($this->getAdelanto($key["IdUsuario"]),2);

              $json["data"][$i]["IdUsuario"] = $key["IdUsuario"];
              $json["data"][$i]["Nombre"] = $key["Nombre"];
              $json["data"][$i]["Devolucion"] = number_format($key["Devolucion"],2);
              $json["data"][$i]["Libras"] = number_format($key["Libras"],2);
              $json["data"][$i]["TotalLibras"] = number_format($key["TotalLibras"],2);
              $json["data"][$i]["Total"] = number_format($key["Total"],2);//CALCULAR COMISION
              //$json["data"][$i]["Comision"] = number_format($comision,2);//CALCULAR COMISION QUERY

              $i++;
            }

        }

    }//end foreach impulsadoras
    

    if ($bandera != null) {
      return $json;
    }
    echo json_encode($json);
  }

  public function printReporteJefeImpulsadoras($tipo,$trabajador,$desde,$hasta,$bandera,$consolidar = null)
  {

    $conn = $this->OPen_database_odbcSAp();
    $json = array();
    $i=0;

    $and = '';

    /*validar la condicion de trabajador*********************************/
    $and = '';
    if ($trabajador != null && $trabajador != 0) {
      $and = ' and (t0.IdSupervisora = '.$trabajador.' OR t0.IdUsuario = '.$trabajador.')';
    }
    $consulta = "SELECT case when t1.IdUsuario is not null then concat(t1.Nombre,' ',t1.Apellidos) else concat(t0.Nombre,' ',t0.Apellidos) end as jefe ,
        t0.*
        FROM Usuarios t0
        left join Usuarios t1 on t1.IdUsuario = t0.IdSupervisora
        where t0.IdRol in (20,21) and t0.IdSupervisora <> 2137".$and;//el 2137 es levi

    $impulsadoras = $this->db->query($consulta);
    //echo $consulta;

    foreach ($impulsadoras->result_array() as $key) {

      
      $inClientes = '';
      $queryClientes = $this->db->query('SELECT * FROM C_ClientesImpulsadoras where IdImpulsadora = '.$key["IdUsuario"].' and Estado = 1');

      foreach ($queryClientes->result_array() as $key2) {
        $inClientes .= "'".$key2["IdCliente"]."',";
      }

      $inClientes = substr($inClientes, 0, -1);

      $queryImpulsadoras = 'SELECT "CODCATEGORIA",
        "CATEGORIA",
        SUM("Libras") as "Libras",
        SUM("Devolucion") as "Devolucion" 
        FROM (
        SELECT
        "CODCATEGORIA", 
        "CATEGORIA",
        sum( "LIBRAS" ) "Libras",
        IFNULL(
        (
          SELECT sum( "Weight1" ) / 454 
          FROM
          "SBO_DELMOR"."VIEW_DEVOLUCIONES_DELMOR" 
          WHERE "CodCategoria" = "CODCATEGORIA"
          AND "CardCode" = "CODCLIENTE" 
          AND cast("DocDate" as date) >= cast('."'".$desde."'".' as date)
          AND cast("DocDate" as date) <= cast('."'".$hasta."'".' as date)
        ),0) "Devolucion"
        FROM SBO_DELMOR."VIEW_VENTAS_DELMOR" T0 
        WHERE T0."ESTADO" NOT IN ('."'Y'".', '."'C'".')
        AND T0."SUBTIPO" <> '."'DN'".'
        AND cast(T0."FECHA" as date) >= cast('."'".$desde."'".' as date)
        AND cast(T0."FECHA" as date) <= cast('."'".$hasta."'".' as date)
        AND T0."CODCLIENTE" IN ('.$inClientes.')
        GROUP BY
        T0."CODCATEGORIA",T0."CATEGORIA","CODCLIENTE" 
      ) GROUP BY "CODCATEGORIA",  "CATEGORIA"';
      

    if ($consolidar != null) {


      $queryImpulsadoras = 'SELECT "CODCATEGORIA",
        "CATEGORIA",
        SUM("Libras") as "Libras",
        SUM("Devolucion") as "Devolucion" 
        FROM (
          SELECT
          "CODCATEGORIA", 
          "CATEGORIA",
          sum( "LIBRAS" ) "Libras",
          IFNULL(
          (
          SELECT
          sum( "Weight1" ) / 454 
          FROM
          "SBO_DELMOR"."VIEW_DEVOLUCIONES_DELMOR"
          WHERE "CodCategoria" = "CODCATEGORIA"
          AND "CardCode" = "CODCLIENTE" 
          AND cast("DocDate" as date) >= cast('."'".$desde."'".' as date)
          AND cast("DocDate" as date) <= cast('."'".$hasta."'".' as date)
        ),0) "Devolucion"
        FROM SBO_DELMOR."VIEW_VENTAS_DELMOR" T0 
        WHERE T0."ESTADO" NOT IN ('."'Y'".', '."'C'".')
        AND T0."SUBTIPO" <> '."'DN'".'
        AND cast(T0."FECHA" as date) >= cast('."'".$desde."'".' as date)
        AND cast(T0."FECHA" as date) <= cast('."'".$hasta."'".' as date)
        AND T0."CODCLIENTE" IN ('.$inClientes.')
        GROUP BY
        T0."CODCATEGORIA",T0."CATEGORIA","CODCLIENTE" 
      ) GROUP BY "CODCATEGORIA",  "CATEGORIA"';


    }

        //echo $query;return;


    $resultado = @odbc_exec($conn,$queryImpulsadoras);

      if ($consolidar == null) {

        while ($fila = @odbc_fetch_array($resultado)){// comisiones de las impulsadoas de cada una de las jefes

          $queryComision = $this->db->query(//TRAER LA COMISION DE LA JEFA
            "SELECT t1.ValorImpulsadora 
              FROM C_ImpulsadoraPeriodo t0
              inner join  C_JefeImpulsadoraComision t1 on t1.IdPeriodo = t0.IdPeriodo
              where  t0.estado = 1 and t0.Mes = month('".$desde."') and t0.Anio = year('".$desde."')"
          );

          $comision = 0;
          if ($queryComision->num_rows()>0) {
            $comision = $queryComision->result_array()[0]["ValorImpulsadora"];
          }


          //echo $key["jefe"].'->'.$key["Nombre"].' '.$key["Apellidos"].'->'.$comision."<br>";
          if ($comision>0) {

            //$json["data"][$i]["Adelanto"] = 0;
            $json["data"][$i]["Adelanto"] = number_format($this->getAdelanto($key["IdUsuario"]),2);
            $json["data"][$i]["IdUsuario"] = $key["IdUsuario"];
            $json["data"][$i]["Jefe"] = $key["jefe"];
            $json["data"][$i]["Nombre"] = $key["Nombre"].' '.$key["Apellidos"];
            $json["data"][$i]["CODCATEGORIA"] = $fila["CODCATEGORIA"];
            $json["data"][$i]["CATEGORIA"] = utf8_encode($fila["CATEGORIA"]);
            $json["data"][$i]["Devolucion"] = number_format($fila["Devolucion"],2);
            $json["data"][$i]["Libras"] = number_format($fila["Libras"],2);
            $json["data"][$i]["TotalLibras"] = number_format($fila["Libras"]-$fila["Devolucion"],2);                  
            $json["data"][$i]["Total"] = number_format(($fila["Libras"]-$fila["Devolucion"])*$comision,2);//CALCULAR COMISION
            $json["data"][$i]["Comision"] = number_format($comision,2);//CALCULAR COMISION QUERY
            $i++;

          }//end comision
        }//end while
      }else{
        $this->db->query("TRUNCATE TABLE C_TempV");
        while ($fila = @odbc_fetch_array($resultado)){

            $queryComision = $this->db->query(
              "SELECT ISNULL(ValorImpulsadora,0) comision
              FROM C_ImpulsadoraComision T0
              INNER JOIN C_ImpulsadoraPeriodo T1 ON T1.IdPeriodo = T0.IdPeriodo
              WHERE T1.Estado = 1
              AND T1.FechaInicial <= '".$desde."'
              AND T1.FechaFinal >= '".$hasta."'
              and month(t1.FechaInicial) = month('".$desde."')
              and year(t1.FechaInicial) = year('".$desde."')
              AND T0.IdImpulsadora = ".$key["IdUsuario"]."
              AND T0.IdCategoria = ".$fila["CODCATEGORIA"]
            );

            $comision = 0;
            if ($queryComision->num_rows()>0) {
              $comision = $queryComision->result_array()[0]["comision"];
            } 
            if ($comision>0) {
              $insert = array(
                "IdUsuario" => $key["IdUsuario"],
                "Nombre" => $key["Nombre"].' '.$key["Apellidos"],
                "IdCategoria" => $fila["CODCATEGORIA"],
                "Categoria" => utf8_encode($fila["CATEGORIA"]),
                "Libras" => $fila["Libras"],
                "Devolucion" => $fila["Devolucion"],
                "Comision" => $comision,
                "TotalLibras" => $fila["Libras"] - $fila["Devolucion"],
                "Total" => ($fila["Libras"]-$fila["Devolucion"])*$comision
              );

              $this->db->insert("C_TempV",$insert);
            }//end if ($comision>0) 
        }//END WHILE

        $resultado = $this->db->query("SELECT IdUsuario,Nombre, SUM(Libras) Libras,SUM(Devolucion) Devolucion, SUM(TotalLibras)TotalLibras,SUM(Total)Total
          FROM C_TempV 
          GROUP BY IdUsuario,Nombre");

            foreach ($resultado->result_array() as $key) {
                $json["data"][$i]["Adelanto"] = number_format($this->getAdelanto($key["IdUsuario"]),2);
                $json["data"][$i]["IdUsuario"] = $key["IdUsuario"];
                $json["data"][$i]["Nombre"] = $key["Nombre"];
                $json["data"][$i]["Devolucion"] = number_format($key["Devolucion"],2);
                $json["data"][$i]["Libras"] = number_format($key["Libras"],2);
                $json["data"][$i]["TotalLibras"] = number_format($key["TotalLibras"],2);
                $json["data"][$i]["Total"] = number_format($key["Total"],2);//CALCULAR COMISION
                //$json["data"][$i]["Comision"] = number_format($comision,2);//CALCULAR COMISION QUERY
                $i++;
            }
        }

    }//end foreach impulsadoras
    
    if ($bandera != null) {
      return $json;
      //echo json_encode($json);
    }
    echo json_encode($json);
  }


  public function getAdelanto($id)
  {
    //echo "SELECT isnull(Adelanto,0) Adelanto FROM Usuarios where IdUsuario = ".$id;
    $query = $this->db->query("SELECT isnull(Adelanto,0) Adelanto FROM Usuarios where IdUsuario = ".$id);

    if ($query->num_rows()>0) {
      return $query->result_array()[0]["Adelanto"];
    }
    return 0;
  }
}
?>
     