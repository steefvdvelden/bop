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
		
		//Maak verbinding met de database
		$config = new config("localhost", "Meijer", "Kooiaap", "Kooiaap", "");
		$db = new Db($config);
		$db->openConnection();
		
		// Roep de verschillende functies aan die bij de SOAP actions horen.
		switch($SoapAction) 
		{
				case "getSuppliers": //Code check acceptance, put result in $result.
					$answerXML = getSuppliers($db,$elementName,$namespace,$nsURI);
					break;
				case "getSupplierByID": //Code check acceptance, put result in $result.
					$answerXML = getSupplierByID($db,$elementName,$input,$namespace,$nsURI);
					break;
				case "getSemiFinishedProducts": //Code check acceptance, put result in $result.
					$answerXML = getSemiFinishedProducts($db,$elementName,$namespace,$nsURI);
					break;
				case "getSemiFinishedProductsByID": //Code check acceptance, put result in $result.
					$answerXML = getSemiFinishedProductsByID($db,$elementName,$input,$namespace,$nsURI);
					break;
				case "insertPurchaseOrder": //Code check acceptance, put result in $result.
					$answerXML = insertPurchaseOrder($db,$elementName,$input,$namespace,$nsURI);
					break;
				case "getNewOrder": //Code check acceptance, put result in $result.
					$answerXML = getNewOrder($db,$elementName,$namespace,$nsURI);
					break;
				case "updateProducts":
					$answerXML = updateProducts($db,$elementName,$input,$namespace,$nsURI);
					break;
				default: 
					//WriteSoapError("INVALID_ACTION", "Invalid Action: '$SoapAction'");
					//$answerXML->loadXML("<?xml version=\"1.0\"<result>Error</result>");
					$answerXML = $db->getXMLError($SoapAction, $elementName, $namespace, $nsURI, "INVALID_ACTION");
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
	
	function getSuppliers($db,$elementName,$namespace,$nsURI){
		// stel de opdracht samen
		$opdracht = "SELECT * FROM Suppliers ORDER BY CompanyName";
		// voer de opdracht uit
		$result = $db->getXMLRecordSet($opdracht,$elementName,$namespace,$nsURI);
		$db->closeConnection();
		return $result;
	}
	
	function getSemiFinishedProducts($db,$elementName,$namespace,$nsURI){
		// stel de opdracht samen
		$opdracht = "SELECT * FROM SemiFinishedProducts";
		// voer de opdracht uit
		$result = $db->getXMLRecordSet($opdracht,$elementName,$namespace,$nsURI);
		$db->closeConnection();
		return $result;
	}
	
	function getSemiFinishedProductsByID($db,$elementName,$input,$namespace,$nsURI){
		// stel de opdracht samen
		$opdracht = "SELECT * FROM SemiFinishedProducts WHERE SupplierID = ".$input["supId"];
		// voer de opdracht uit
		$result = $db->getXMLRecordSet($opdracht,$elementName,$namespace,$nsURI);
		$db->closeConnection();
		return $result;
	}
	
	function getSupplierByID($db,$elementName,$input,$namespace,$nsURI){
		// stel de opdracht samen
		$opdracht = "SELECT * FROM Suppliers WHERE SupplierID = ".$input["supId"];
		// voer de opdracht uit
		$result = $db->getXMLRecordSet($opdracht,$elementName,$namespace,$nsURI);
		$db->closeConnection();
		return $result;
	}

	function insertPurchaseOrder($db,$elementName,$input,$namespace,$nsURI){
		// Haal het record uit de input
		$counter=0;
		foreach ($input as $i => $row) { 
			if ($row["Amount"]>0) {// skip de lege regel van input
				$Supplier=$row["Supplier"];
				$Address=$row["Address"];
				$Postalcode=$row["Postalcode"];
				$City=$row["City"];
				$Product=$row["Product"];
				$Amount=$row["Amount"];
				$Price_per_unit=$row["Price_per_unit"];
				
				// stel de opdracht samen
				$opdracht[$counter] .= "INSERT INTO PurchaseOrders (Supplier, Address, Postalcode, City, Product, Amount, Price_per_unit) "
				 					.'VALUES ("'.$Supplier.'", "'.$Address.'", "'.$Postalcode
									.'", "'.$City.'", "'.$Product.'", "'.$Amount.'", "'.$Price_per_unit.'")';
				$counter++;
			}
		}
		// voer de opdracht uit
		$result = $db->getXMLUpdateResult($opdracht,$elementName,$namespace,$nsURI,false);
		$db->closeConnection();
		return $result;
	}
	
	function getNewOrder($db,$elementName,$namespace,$nsURI){
		// stel de opdracht samen
		$opdracht = "SELECT * FROM purchaseorders WHERE ReceivedDate is null and Supplier = 'Meijer plaatbewerking' LIMIT 1"; 
		// voer de opdracht uit
		$result = $db->getXMLRecordSet($opdracht,$elementName,$namespace,$nsURI);
		$db->closeConnection();
		return $result;
	}
	
	function updateProducts($db,$elementName,$input,$namespace,$nsURI){
		// stel de opdracht samen
		$counter=0;
		foreach ($input as $i => $row) { 
			if ($row["SemiFinishedID"]>0) // skip de lege regel van input
				$opdracht[$counter] = "UPDATE SemiFinishedProducts SET UnitsInStock = ".$row["UnitsInStock"]
							." WHERE SemiFinishedID = ".$row["SemiFinishedID"];
			$counter++;
		}
		$result = $db->getXMLUpdateResult($opdracht,$elementName,$namespace,$nsURI,true);
		$db->closeConnection();
		return $result;
	}
	
?>	