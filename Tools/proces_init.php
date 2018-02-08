<link rel="stylesheet" type="text/css" href="../Tools/Hanze_BOP.css">
<script src="../Tools/javascripts.js"></script>
<?php
	include '../Tools/classUtils.php';
	include '../Tools/classWebservice.php';
	include '../Tools/classFormOutput.php';
	include '../Tools/classProces.php';
	foreach ($_POST as $key => $value)
		$posted[$key]=$value;
	$procesRunner = basename($_SERVER['PHP_SELF']);
	$procesConfig=new ProcesConfig($procesRunner, $posted);
	$proces=new Proces($procesConfig);
?>
