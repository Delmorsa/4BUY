<!doctype html>
<html class="fixed">
	<head>
        <link rel="shortcut icon" href="<?PHP echo base_url();?>assets/img/LOGO.png">
        <title>4BUY </title>
		<!-- Basic -->
		<meta charset="UTF-8">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
		<meta http-equiv="Pragma" content="no-cache" />
		<meta http-equiv="Expires" content="0" />

		<!-- Web Fonts  -->
		<!--<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">-->

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
        <link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css" />
        <link rel="stylesheet" href="<?php echo base_url()?>assets/css/sweetalert2.css" />
        <link rel="stylesheet" href="<?php echo base_url()?>assets/css/datatables.css">
        <link rel="stylesheet" href="<?php echo base_url()?>assets/css/pnotify.custom.css">
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/select2.css">
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/treeview.css">
		<!-- <link rel="stylesheet" href="assets/vendor/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/css/datepicker3.css" /> -->

		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-fileupload.min.css" />
		<!-- Theme CSS -->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/skins/default.css" />

        <!-- Theme Custom CSS -->
        <link rel="stylesheet" href="<?php echo base_url()?>assets/css/theme-custom.css">
        <link rel="stylesheet" href="<?php echo base_url()?>assets/css/nanoscroller.css">
        <link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker3.css">
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-timepicker.css">

		<!--chart-->	
		
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/chart/Chart.min.css">>
		<script src="<?php echo base_url()?>assets/js/chart/Chart.js"></script>
		<script src="<?php echo base_url()?>assets/js/chart/Chart.bundle.js"></script>
		<script src="<?php echo base_url()?>assets/js/chart/utils.js"></script>
		<!-- Head Libs -->
		<script src="<?php echo base_url()?>assets/js/modernizr.js"></script>

		<style type="text/css">
			@media (min-width: 576px)
			.modal-dialog-centered {
			    min-height: calc(100% - (1.75rem * 2));
			}

			.modal-dialog-centered {
			    display: -webkit-box;
			    display: -ms-flexbox;
			    display: flex;
			    -webkit-box-align: center;
			    -ms-flex-align: center;
			    align-items: center;
			    min-height: calc(90% - (.5rem * 2));
			}

			@media (min-width: 576px)
			#loading {
			    max-width: 500px;
			    margin: 1.75rem auto;
			}
		</style>
	</head>
	<body onload="setTimeout(function() {
	  showNotifications();
	},3000);">
