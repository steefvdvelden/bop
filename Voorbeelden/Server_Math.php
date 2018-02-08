<?php
// **********************************************
// Webservice Demo by Elmü (www.netcult.ch/elmue)
// **********************************************

// =============== Webservice Server =============

// This server will work with GET and with POST requests.

// You must sanitize all input data in the real world to fight back hacker attacks!
// This simple sample does not sanitize data

$Operation = $_REQUEST["Operation"];
$Value1    = $_REQUEST["Value1"];
$Value2    = $_REQUEST["Value2"];

switch (strtoupper($Operation))
{
	case "MULTIPLY":
		echo "<Result><Value>".($Value1 * $Value2)."</Value></Result>";
		exit;
		
	case "ADD":
		echo "<Result><Value>".($Value1 + $Value2)."</Value></Result>";
		exit;

	case "CONCAT":
		echo "<Result><Value>$Value1$Value2</Value></Result>";
		exit;
		
	default:
		header("HTTP/1.1 501 Not supported");
		echo "<Result><Error>The operation is not supported.</Error></Result>";
		exit;
}
