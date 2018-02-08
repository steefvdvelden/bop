<?php
	// Dit is de server die de SOAP calls in de processen van Meijer-Kooi afhandelt.
	ProcessSoapRequest($_SERVER["HTTP_SOAPACTION"], $HTTP_RAW_POST_DATA);
	
	function ProcessSoapRequest($SoapAction, $RawPostData)
	{
		require_once("../Tools/classUtils.php");
		require_once("../Tools/classDb.php");
		require_once("../Tools/classWebservice.php");
		
		// Het Soap bericht komt binnen als XML text file en moet dus eerst in een DOMDocument geladen worden
		$Dom = new DOMDocument("1.0");
		$Dom->loadXML($RawPostData, LIBXML_NOERROR | LIBXML_NOWARNING);
		
		// De gegevens die eventueel nodig zijn voor het verwerken van de call, zoals selectie criteria en record gegevens voor een insert
		// staan in het XML bericht in het element met elementName. 
		// Deze gegevens worden hier in de array $input gezet en zijn dan beschikbaar onder $input["Gegevennaam"].
		// Een andere optie is om ze stuk voor stuk op te halen met
		// Utils::GetValue($XPath, "//soapenv:Body/$SoapAction/$elementName/Gegevennaam")
		$XPath = new DOMXpath($Dom);
		$elementName = Utils::GetValue($XPath, "//soapenv:Body/$SoapAction/elementName");
		$namespace = Utils::GetValue($XPath, "//soapenv:Body/$SoapAction/nameSpace");
		$nsURI = Utils::GetValue($XPath, "//soapenv:Body/$SoapAction/nsURI");
		$rsInput = Utils::GetValue($XPath, "//soapenv:Body/$SoapAction/rsInput");
		$input=Utils::GetArray($Dom, $elementName, $nsUri, ($rsInput==1));
		
		//Maak de verbinding met de database
		$config = new config("localhost", "Meijer", "Kooiaap", "meijer", "");
		$db = new Db($config);
		$db->openConnection();
	
		// Roep de verschillende functies aan die bij de SOAP actions horen.
		switch($SoapAction) {
				case "getTevredenheid": //Code check acceptance, put result in $result.
					$answerXML = getTevredenheid($db,$elementName,$namespace,$nsURI);
					break;
				case "getOrdersInProductie": //Code check acceptance, put result in $result.
					$answerXML = getOrdersInProductie($db,$elementName,$namespace,$nsURI);
					break;
				case "getVoorraad": //Code check acceptance, put result in $result.
					$answerXML = getVoorraad($db,$elementName,$input,$namespace,$nsURI);
					break;
// Voor het toevoegen van een nieuwe service, onderstaande blok kopieëren, activeren
// serviceNaam vervangen door de de echte naam en de functie van die naam maken.
// $input is alleen nodig als de service gebruik maakt van input parameters.
//				case "serviceNaam": 
//					$answerXML = serviceNaam($db,$elementName,$input,$namespace,$nsURI);
//					break;
				default: 
					WriteSoapError("INVALID_ACTION", "Invalid Action: '$SoapAction'");
					$answerXML->loadXML("<?xml version=\"1.0\"?><error>Invalid Action</error>");
		}
		// Het antwoord wordt omgezet naar XML in text formaat om in het SOAP bericht in te voegen.
		$result="";
		foreach($answerXML->childNodes as $node)
			$result .= $answerXML->saveXML($node);
		
		$SoapAnswer = $SoapAction;
		
		echo
			"<?xml version=\"1.0\"?>\r\n".
			"<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\">\r\n".
			"<soapenv:Header/>\r\n".
	     "   <soapenv:Body>\r\n".
	     "      <$SoapAnswer>";
	     echo $result;
	 	 echo "</$SoapAnswer>".
	     "   </soapenv:Body>\r\n".
	     "</soapenv:Envelope>\r\n";
	}
	
	function WriteSoapError($ErrCode, $ErrMessage)
	{
		header("HTTP/1.1 500 Internal Server Error");
		echo 
			"<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\">\r\n".
			"	<soapenv:Body>\r\n".
			"		<soapenv:Fault>\r\n".
			"			<faultcode>$ErrCode</faultcode>\r\n".
			"			<faultstring>$ErrMessage</faultstring>\r\n".
			"		</soapenv:Fault>\r\n".
			"	</soapenv:Body>\r\n".
			"</soapenv:Envelope>";
		exit;	
	
	}
	
	function getTevredenheid($db,$elementName,$namespace,$nsURI){
		// stel de opdracht samen
		$opdracht = "SELECT AVG (Mark) as gemiddelde FROM CustomerSatisfaction";
		// voer de opdracht uit
		$result = $db->getXMLRecordSet($opdracht,$elementName,
				                       $namespace,$nsURI);
		$db->closeConnection();
		return $result;
	}
	
	function getOrdersInProductie($db,$elementName,$namespace,$nsURI){
		// stel de opdracht samen
		$opdracht = "SELECT COUNT(OrderID) as TotalOrders FROM salesorders WHERE Production = true";
		// voer de opdracht uit
		$result = $db->getXMLRecordSet($opdracht,$elementName,
				                       $namespace,$nsURI);
		$db->closeConnection();
		return $result;
	}
	
	function getVoorraad($db,$elementName,$input,$namespace,$nsURI){
		// stel de opdracht samen
		$opdracht = "SELECT ProductID,RawMaterialInStock,NumberOfRawMaterial_PP,ProductID FROM products".
		            " WHERE ProductID=".$input["ProductID"];
		// voer de opdracht uit
		$result = $db->getXMLRecordSet($opdracht,$elementName,
				$namespace,$nsURI);
		$db->closeConnection();
		return $result;
	}
	
?>	