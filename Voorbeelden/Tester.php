<?php
// **********************************************
// Webservice Demo by Elmü (www.netcult.ch/elmue)
// **********************************************
?>
<html>
<head>
<title>Soap WebService Client Demo</title>
<style>
h1  { color:#000066; }
h3  { color:#666600; }
pre { background-color:#FFFFE0; padding:5px; border:1px solid #666600; }
</style>
</head>
<body>
<h1>Soap Webservice</h1>

<?php
// ----------- localhost Soap Webservice --------------

require_once("../Tools/classUtils.php");
require_once("../Tools/classWebservice.php");

//$SERVER_URL = "http://rihh-hp:8080/webservice/ExampleWSService";
//$SERVER_URL="http://localhost/Hanze_BOP/Meijer_BOP/Server_Meijer.php";

$SERVER_URL = $_REQUEST["Server"];
$Service = new Webservice($SERVER_URL, "SOAP", "utf-8");

$Action  = $_REQUEST["Action"];
$elementName  = $_REQUEST["Element"];
$param1= $_REQUEST["Param1"];
$param2= $_REQUEST["Param2"];
$Value1 = $_REQUEST["Value1"];
$Value1 = str_replace("<", "", $Value1); // should be encoded for XML
$Value1 = str_replace(">", "", $Value1);
$Value1 = str_replace("&", "", $Value1);
$param2= $_REQUEST["Param2"];
$Value2 = $_REQUEST["Value2"];
$Value2 = str_replace("<", "", $Value2); // should be encoded for XML
$Value2 = str_replace(">", "", $Value2);
$Value2 = str_replace("&", "", $Value2);
$message = ($param1!="")?"<$param1>$Value1</$param1>":"";
$message .= ($param2!="")?"<$param2>$Value2</$param2>":"";
	    	$Soap = "<?xml version=\"1.0\"?>
	    	<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\">
	    	<soapenv:Header/>
	    	<soapenv:Body>
	    	<$Action>
	    	<elementName>$elementName</elementName><$elementName>$message</$elementName>
	    	</$Action>
	    	</soapenv:Body>
	    	</soapenv:Envelope>";


if ($_REQUEST["Debug"] == "on") $Service->PRINT_DEBUG = true;

flush();
$Response = $Service->SendRequest($Soap, $Action);

/* returns $Response["Body"]=

<?xml version="1.0"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
   <soapenv:Header/>
   <soapenv:Body>
      <STR_Revert TimeStamp="1247085963">
         <Message>txeT elttil a si sihT</Message>
      </STR_Revert>
   </soapenv:Body>
</soapenv:Envelope>

*/ 
$XPath = $Response["XPath"];

$Answer = str_replace("RQ", "RS", $Action);

$Body = new DOMDocument();
$Body->loadXML($Response["Body"]);
$domDoc=Utils::getDomDocument($Body, $elementName);
$Antwoord=Utils::GetXML($domDoc);


echo "<h3>Result:</h3><pre>";
echo "<b>Error</b>:   " .Utils::GetValue ($XPath, "//soapenv:Body/soapenv:Fault/faultstring")."<br>";
echo "<b>Action</b>:  $Action<br>";
echo "<b>Answer</b>:  $Answer<br>";
echo "<b>Message</b>: " .$Antwoord."<br>";
echo "</pre>";


?>
<b><a href="voorbeelden.php">Back to Startpage</a></b>
</body>
</html>

