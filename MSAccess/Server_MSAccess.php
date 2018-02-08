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
		// staan in het XML bericht in het element met elementName. Deze gegevens worden hier in de array $input gezet en zijn dan beschikbaar
		// onder $input["Gegevennaam"].
		// Een andere optie is om ze stuk voor stuk op te halen met Utils::GetValue($XPath, "//soapenv:Body/$SoapAction/$elementName/Gegevennaam")
		$XPath = new DOMXpath($Dom);
		$elementName = Utils::GetValue($XPath, "//soapenv:Body/$SoapAction/elementName");
		$namespace = Utils::GetValue($XPath, "//soapenv:Body/$SoapAction/nameSpace");
		$nsURI = Utils::GetValue($XPath, "//soapenv:Body/$SoapAction/nsURI");		
		$rsInput = Utils::GetValue($XPath, "//soapenv:Body/$SoapAction/rsInput");
		$input=Utils::GetArray($Dom, $elementName, $nsUri, ($rsInput==1));
		
		//Maak de verbinding met de database
		$dbName="SKAIBOX.mdb";
		$db = new AccessDb($dbName);
		$db->connect();

		// Roep de verschillende funcites aan die bij de SOAP actions horen.
		switch($SoapAction) 
		{
				case "getRelaties": //Code check acceptance, put result in $result.
					$answerXML = getRelaties($db,$elementName,$namespace,$nsURI);
					break;
				case "insertAudit":
					$answerXML = insertAudit($db,$elementName,$namespace,$nsURI);
					break;
				default: 
					WriteSoapError("INVALID_ACTION", "Invalid Action: '$SoapAction'");
					$answerXML->loadXML("<?xml version=\"1.0\"?><$elementName>Error</$elementName>");
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
	
	function getRelaties($db,$elementName,$namespace,$nsURI){
		// stel de opdracht samen
		$opdracht = "SELECT TOP 10 * FROM TestSKAIBOX_relaties";
		// voer de opdracht uit	
		$result = $db->fetchXMLRecordSet($opdracht,$elementName,$namespace,$nsURI);
		$db->disconnect();
		return $result;
	}

	function insertAudit($db,$elementName,$namespace,$nsURI){
		// stel de opdracht samen
		$opdracht = "INSERT INTO TestSKAIBOX_auditfile (type, bestandsnaam) VALUES ('TST' , 'tester')";
		// voer de opdracht uit	
		$result = $db->fetchXMLUpdateResult($opdracht,$elementName,$namespace,$nsURI);
		$db->disconnect();
		return $result;
	}
	?>	