<?php
// **********************************************
// Webservice Demo by Elmü (www.netcult.ch/elmue)
// **********************************************
?>
<html>
<head>
<title>Math WebService Client Demo</title>
<style>
h1  { color:#000066; }
h3  { color:#666600; }
pre { background-color:#FFFFE0; padding:5px; border:1px solid #666600; }
</style>
</head>
<body>
<h1>Math Calculation Webservice</h1>

<?php
// ----------- Webservice running in Server_Math.php ----------------

require_once("../Tools/classUtils.php");
require_once("../Tools/classWebservice.php");

$SERVER_URL = Utils::GetBaseUrl() . "Server_Math.php";

$Service = new Webservice($SERVER_URL, "POST", "utf-8");

// Possible Operations: "Add", "Multiply", "Concat"
$Params["Operation"] = $_REQUEST["Operation"];
$Params["Value1"]    = $_REQUEST["Value1"];
$Params["Value2"]    = $_REQUEST["Value2"];

if ($_REQUEST["Debug"] == "on") $Service->PRINT_DEBUG = true;

flush();
$Response = $Service->SendRequest($Params);

/* returns $Response["Body"]=

<?xml version="1.0"?>
<Result>
  <Value>63</Value>
</Result>

*/ 
$XPath = $Response["XPath"];

echo "<h3>Result:</h3><pre>";

// To understand XPath queries read this: http://www.w3schools.com/XPath/xpath_syntax.asp
// Note that XML is case sensitive !!
echo "<b>Error</b>: ".Utils::GetValue($XPath, "//Result/Error")."<br>";
echo "<b>Value</b>: ".Utils::GetValue($XPath, "//Result/Value");

echo "</pre>";

?>
<b><a href="voorbeelden.php">Back to Startpage</a></b>
</body>
</html>

