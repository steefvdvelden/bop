<?php
// **********************************************
// Webservice Demo by Elmü (www.netcult.ch/elmue)
// **********************************************

// =============== Webservice Soap Server =============

// You must sanitize all input data in the real world to fight back hacker attacks!
// This simple sample does not sanitize data

ProcessSoapRequest($_SERVER["HTTP_SOAPACTION"], $HTTP_RAW_POST_DATA);

function ProcessSoapRequest($SoapAction, $RawPostData)
{
	require_once("../Tools/classUtils.php");

	$Dom = new DOMDocument("1.0");
	$Dom->loadXML($RawPostData, LIBXML_NOERROR | LIBXML_NOWARNING);
	
	$XPath = new DOMXpath($Dom);
	
	$Message = Utils::GetValue($XPath, "//soapenv:Body/$SoapAction/Message");
	
	if (empty($Message))
		WriteSoapError("EMPTY_MESSAGE", "The message is empty");

	switch ($SoapAction)
	{
		case "STR_RevertRQ": $Message = strrev    ($Message); break;
		case "STR_UpperRQ":  $Message = strtoupper($Message); break;
		case "STR_LowerRQ":  $Message = strtolower($Message); break;
		default: WriteSoapError("INVALID_ACTION", "Invalid Action: '$SoapAction'");
	}
	
	// Request -> Response
	$SoapAnswer = str_replace("RQ", "RS", $SoapAction);
	
	echo "<?xml version=\"1.0\"?>\r\n".
	     "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\">\r\n".
	     "   <soapenv:Header/>\r\n".
	     "   <soapenv:Body>\r\n".
	     "      <$SoapAnswer TimeStamp=\"".time()."\">\r\n".
	     "         <Message>$Message</Message>\r\n".
	     "      </$SoapAnswer>\r\n".
	     "   </soapenv:Body>\r\n".
	     "</soapenv:Envelope>\r\n";
}

function WriteSoapError($ErrCode, $ErrMessage)
{
	header("HTTP/1.1 500 Internal Server Error");
	echo "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\">\r\n".
	     "  <soapenv:Body>\r\n".
	     "    <soapenv:Fault>\r\n".
	     "      <faultcode>$ErrCode</faultcode>\r\n".
	     "      <faultstring>$ErrMessage</faultstring>\r\n".
	     "    </soapenv:Fault>\r\n".
	     "  </soapenv:Body>\r\n".
	     "</soapenv:Envelope>";
	exit;
}
