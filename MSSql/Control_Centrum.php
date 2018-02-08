<?php
// **********************************************
// Hanze EAI - Control Centrum
// **********************************************
$startInput="<zender>Control_Centrum.php</zender>";
/*
 * 
*/
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../Tools/Hanze_BOP.css">
		<title>Control Centrum MSSql processen</title>
	</head>
	<body>
		<p><h2>Vanaf deze bladzijde kunnen de verschillende processen gestart worden<br></h2>

<?php // Load XML file
$xml = new DOMDocument;
$xml->load('processes.xml');
// Load XSL file

$xsl = new DOMDocument;
$xsl->load('../Tools/ControlCentum.xsl');
// Configure the transformer
$proc = new XSLTProcessor;

// Attach the xsl rules
$proc->importStyleSheet($xsl);
$scherm=$proc->transformToXML($xml);
$scherm = str_replace("startinput_to_replace", $startInput, $scherm);
$scherm = str_replace("newline", "</td></tr><tr><td>", $scherm);
echo $scherm;
?>
	</body>
</html>
