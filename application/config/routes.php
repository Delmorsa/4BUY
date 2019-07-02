<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'Login_controller';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route["Error_403"] = "Error_controller/Forbidden";
$route["Fecha_Caduca"] = "Error_controller/FechaRemCaduca";

$route["Login"] = "Login_controller/Acreditar";
$route["LogOut"] = "Login_controller/Salir";

$route["Usuarios"] = "Usuarios_controller";

/**********RUTAS ROLES*************/
$route["Roles"] = "Usuarios_controller/Roles";
$route["GuardarRoles"] = "Usuarios_controller/guardarRoles";
$route["ActualizarEst/(:any)/(:any)"] = "Usuarios_controller/actualizarEstado/$1/$2";
$route["ActualizarRol/(:any)"] = "Usuarios_controller/actualizarRol/$1";
/**********RUTAS ROLES*************/

/**********RUTAS USUARIOS*************/
$route["GuardarUsuario"] = "Usuarios_controller/guardarUsuario";
$route["ActualizarEstUser/(:any)/(:any)/(:any)"] = "Usuarios_controller/actualizarEstadoUser/$1/$2/$3";
$route["ActualizarUsuario"] = "Usuarios_controller/actualizarUsuario";

$route["LogsView"] = "Usuarios_controller/LogsView";
$route["ShowLogs"] = "Usuarios_controller/ShowLogs";

$route["Perfil"] = "Usuarios_controller/Perfil";
$route["ActualizarPerfil"] = "Usuarios_controller/actualizarPerfil";
$route["CambiarPassword"] = "Usuarios_controller/cambiarPassword";

$route["GuardarConsecutivos/(:any)"] = "Usuarios_controller/guardarConsecutivos/$1";

/**********RUTAS USUARIOS*************/

/**********RUTAS VENDEDORES*************/
$route["vendedores"] = "Vendedores_controller";
$route["RutasAsignadas/(:any)"] = "Vendedores_controller/rutasAsignadas/$1";
$route["RutasNoAsignadas"] = "Vendedores_controller/rutasNoAsignadas";

$route["AsignarVendedor"] = "Vendedores_controller/asignarVendedor";
$route["QuitarVendedor"] = "Vendedores_controller/quitarVendedor";
/**********RUTAS VENDEDORES*************/

/**********RUTAS AUTORIZACIONES*************/
$route["Autorizaciones"] = "Autorizaciones_controller";
$route["CrearPermiso"] = "Autorizaciones_controller/crearPermiso";
$route["ActualizarPermiso"] = "Autorizaciones_controller/actualizarPermiso";
$route["DarBaja/(:any)/(:any)"] = "Autorizaciones_controller/darBaja/$1/$2";

$route["Asignar_Permiso"] = "Autorizaciones_controller/asignar_aut";
$route["GetAuthAJAX"] = "Autorizaciones_controller/getAuthAJAX";
$route["AsignarPermiso"] = "Autorizaciones_controller/asignarPermiso";
$route["QuitarPermiso"] = "Autorizaciones_controller/quitarPermiso";
$route["GetAuthAsig/(:any)"] = "Autorizaciones_controller/getAuthAsig/$1";
/**********RUTAS AUTORIZACIONES*************/

/*************************RUTAS REMISIONES********************************************/
$route["Inventario"] = "Remisiones_controller";
$route["InventarioList"] = "Remisiones_controller/inventario";
$route["InventarioSinStock"] = "Remisiones_controller/inventarioSinStock";
//inventario por rutas
$route["InventarioRutas"] = "Remisiones_controller/inventarioRutas";
$route["inventarioRutasSinStock"] = "Remisiones_controller/inventarioRutasSinStock";

$route["Remisiones"] = "Remisiones_controller/remisiones";
$route["ProductosList"] = "Remisiones_controller/getProductosRutas";
$route["ProductosListMerma"] = "Remisiones_controller/getProductosMermas";

$route["SaveRemision"] = "Remisiones_controller/guardarRemision";
$route["GetVendedorAjax/(:any)"] = "Remisiones_controller/getVendedorAjax/$1";   /*Cargar vendedor en campo de texto al seleccionar una ruta*/

$route["ListaRemision"] = "Remisiones_controller/listaRemisiones";
$route["ListOrdensAjax"] = "Remisiones_controller/listaOrdenes";
$route["ListPreventasAjax"] = "Remisiones_controller/listaPreventas";
$route["ListRecargosAjax"] = "Remisiones_controller/listaRecargos";
$route["ListaAdelantosAjax"] = "Remisiones_controller/listaAdelantos";
$route["detallesRemisionesAjax/(:any)"] = "Remisiones_controller/detallesRemisiones/$1";
$route["EditarRem/(:any)"] = "Remisiones_controller/editarRemision/$1";
$route["DarBajaRemision/(:any)/(:any)"] = "Remisiones_controller/darBajaRemision/$1/$2";

$route["guardarRemisionExcel"] = "Remisiones_controller/guardarRemisionExcel";

$route["ActualizarRemision"] = "Remisiones_controller/actualizarRemision";
$route["PrintRemision/(:any)"] = "Remisiones_controller/viewPrintRemision/$1";

$route["GetStockProdAjax/(:any)"] = "Remisiones_controller/getStockProdAjax/$1";

$route["Consolidado/(:any)/(:any)/(:any)/(:any)"] = "Remisiones_controller/viewPrintRemisionCons/$1/$2/$3/$4";

$route["UltimoConsecutivo"] = "Remisiones_controller/ultimoConsecutivo";

$route["Traslados"] = "Remisiones_controller/Traslados";
$route["MostrarTraslados/(:any)/(:any)"] = "Remisiones_controller/mostrarTraslados/$1/$2";
$route["DetalleTraslados/(:any)"] = "Remisiones_controller/detalleTraslados/$1";

/*************************FIN RUTAS REMISIONES********************************************/

//region RUTAS MODULO FACTURAS
$route["Facturas"] = "Facturas_controller";
$route["Notificaciones"] = "Facturas_controller/mostrarNotificaciones";
$route["MostrarFacturas"] = "Facturas_controller/mostrarFacturas";
$route["DetalleFacturas/(:any)"] = "Facturas_controller/detalleFacturas/$1";
$route["ImprimirFactura/(:any)"] = "Facturas_controller/detalleFacturasPrint/$1";
$route["AnularFactura/(:any)"] = "Facturas_controller/anularFactura/$1";

//anulacion de facturas
$route["Anulaciones"] = "Facturas_controller/listaFactxAnular";

//Actualizar item facturas (Detalle Facturas)
$route["ActualizarItemFactura"] = "Facturas_controller/actualizarItemFactura";
//endregion

//region RUTAS MODULO INTEGRACION
$route["Integracion"] = "Integracion_controller";
$route["FacturasAjax/(:any)/(:any)/(:any)"] = "Integracion_controller/getFacturasPendientes/$1/$2/$3";
$route["detallesFacturasAjax/(:any)"] = "Integracion_controller/detallesFacturas/$1";

$route["Integrar"] = "Integracion_controller/IntegrarFacturas";
//endregion

//region RUTAS MODULO LIQUIDACION
$route["PeriodoLiq"] = "Liquidacion_controller";
$route["guardarPeriodo"] = "Liquidacion_controller/guardarPeriodo";
$route["actualizarPeriodo"] = "Liquidacion_controller/actualizarPeriodo";

$route["Liquidacion/(:any)"] = "Liquidacion_controller/Liquidacion/$1";
$route["GuardarLiquidacion"] = "Liquidacion_controller/guardarLiquidacion";
$route["AnularPeriodo/(:any)"] = "Liquidacion_controller/anularPeriodo/$1";

$route["Detalleliquidacion/(:any)"] = "Liquidacion_controller/VerDetdalleliquidacion/$1";
//endregion

//region RUTAS MODULO CUOTAS
$route["Cuotas"] = "Cuotas_controller";
$route["GuardarCuotas"] = "Cuotas_controller/guardarCuota";
$route["ActualizarCuotas"] = "Cuotas_controller/actualizarCuota";

$route["ListaCuotas"] = "Cuotas_controller/CuotaList";
$route["ReporteCuotas"] = "Cuotas_controller/ReporteCuotas";
$route["ExportarCuota/(:any)/(:any)/(:any)/(:any)"] = "Cuotas_controller/Exportar/$1/$2/$3/$4";
//endregion

//region RUTAS PARA MODULO REPORTES
$route["ConsolidadoRem"] = "Reportes_controller/consolidadoRem";
$route["ConsolidadoRems"] = "Reportes_controller/consolidadoRems";
$route["detConsolidadoAjax/(:any)/(:any)/(:any)/(:any)/(:any)"] = "Reportes_controller/detConsolidadoAjax/$1/$2/$3/$4/$5";
$route["Reporte_Remisiones"] = "Reportes_controller/RemisionesReport";
$route["ReportesRemision"] = "Reportes_controller/reportesRemision";
$route["DetallesReportesRemision/(:any)"] = "Reportes_controller/detallesReportesRemision/$1";

$route["rptventas"] = "Reportes_controller/rptventas";
$route["filtrarrptventas"] = "Reportes_controller/filtrarrptventas";
$route["rptconsecutivos"] = "Reportes_controller/rptconsecutivos";
$route["reportesConsecutivos"] = "Reportes_controller/reportesConsecutivos";
$route["printConsecutivos/(:any)/(:any)"] = "Reportes_controller/printConsecutivos/$1/$2";

$route["rptDevolucionesRuta"] = "Reportes_controller/rptDevolucionesRuta";
$route["devolucionesPorRutas"] = "Reportes_controller/devolucionesPorRutas";
$route["printDevoluciones/(:any)/(:any)"] = "Reportes_controller/printDevoluciones/$1/$2";

$route["reporteConsecutivosDetallado"] = "Reportes_controller/reporteConsecutivosDetallado";
$route["printConsecutivosDetallado/(:any)/(:any)"] = "Reportes_controller/printConsecutivosDetallado/$1/$2";

$route["RptVentas"] = "Reportes_controller/ReporteDeVentas";
//endregion

$route["ExcelLiquidacion/(:any)"] = "Liquidacion_controller/exportarExcelLiquidacion/$1";
$route["ExcelLiqUnid/(:any)"] = "Liquidacion_controller/exportarExcelLiquidacionUnidades/$1";

$route["createXLS/(:any)/(:any)"] = "Reportes_controller/createXLS/$1/$2";
$route["createXLSDetallado/(:any)/(:any)"] = "Reportes_controller/createXLSDetallado/$1/$2";
$route["Reporte_Merma"] = "Reportes_controller/viewReporteMerma";
$route["encabezadoMerma"] = "Reportes_controller/encabezadoMerma";
$route["ReporteMerma"] = "Reportes_controller/reporteMermas";
$route["printReporteMermas/(:any)/(:any)"] = "Reportes_controller/printReporteMermas/$1/$2";
$route["VentasDepositos"] = "Reportes_controller/viewReporteVentasDeposito";
$route["reporteDeVentasDeposito"] = "Reportes_controller/reporteDeVentasDeposito";
$route["printVentasDep/(:any)/(:any)/(:any)"] = "Reportes_controller/printReporteDeVentasDeposito/$1/$2/$3";
//graficas

$route["grafica/(:any)/(:any)/(:any)/(:any)"] = "Cuotas_controller/grafica/$1/$2/$3/$4";
$route["librasXdia"] = "Cuotas_controller/librasXdia";