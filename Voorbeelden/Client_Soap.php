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

$SERVER_URL = Utils::GetBaseUrl() . "Server_Soap.php";

$Service = new Webservice($SERVER_URL, "SOAP", "utf-8");

$Action  = $_REQUEST["Action"];
$Message = $_REQUEST["Message"];
$Message = str_replace("<", "", $Message); // should be encoded for XML
$Message = str_replace(">", "", $Message);
$Message = str_replace("&", "", $Message);

$Soap = "<?xml version=\"1.0\"?>
<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\">
  <soapenv:Header/>
  <soapenv:Body>
    <$Action>
      <Message>$Message</Message>
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

echo "<h3>Result:</h3><pre>";
echo "<b>Error</b>:   " .Utils::GetValue ($XPath, "//soapenv:Body/soapenv:Fault/faultstring")."<br>";
echo "<b>Action</b>:  $Action<br>";
echo "<b>Answer</b>:  $Answer<br>";
echo "<b>Message</b>: " .Utils::GetValue ($XPath, "//soapenv:Body/$Answer/Message")."<br>";
echo "</pre>";

?>
<b><a href="voorbeelden.php">Back to Startpage</a></b>
</body>
</html>

