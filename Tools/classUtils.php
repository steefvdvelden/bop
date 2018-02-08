<?php

// **********************************************
// Webservice Demo by Elmü (www.netcult.ch/elmue)
// **********************************************

// To understand XPath queries read this: http://www.w3schools.com/XPath/xpath_syntax.asp
// Note that XML is case sensitive !!
class Utils
{
	// retrieve a XML node's value
	public static function GetValue($XPath, $Path)
	{
		if (empty($XPath))
			return "";
		$Nodes = $XPath->query($Path);
		if (empty($Nodes) || $Nodes->length == 0)
			return "";
			
		return $Nodes->item(0)->nodeValue;
	}
	
	// retrieve a XML node's atrribute value
	public static function GetAttrib($XPath, $Path, $AttrName)
	{
		if (empty($XPath))
			return "";
		
		$Nodes = $XPath->query($Path);
		if (empty($Nodes) || $Nodes->length == 0)
			return "";
			
		return $Nodes->item(0)->getAttribute($AttrName);
	}
	
	// returns the URL in which the current PHP script is running
	public static function GetBaseUrl()
	{
		$Self = $_SERVER["PHP_SELF"];
		$Pos = strrpos($Self, "/");
		
		return "http://localhost:8080".substr($Self, 0, $Pos+1);
	}
	// Zet een het deel van een DOMDocument dat in het element met elementName zit om in een array
	public static function GetArray($Recordset,$elementName,$nsURI="",$multi=false){
		$list=($nsURI=="") ? $Recordset->getElementsByTagName($elementName) :
								 $Recordset->getElementsByTagNameNS($nsURI, $elementName);
		$doc1=new domDocument('1.0');
		$doc1->formatOutput=true;
		if (!$multi) 
			$result["leeg"]="";
		else 
			$result[0]["leeg"]="";
		$counter=0;
		for($i=0; $i<$list->length; $i++) $doc1->appendChild($doc1->importNode($list->item($i), true));
		foreach ($doc1->childNodes AS $items) {
			foreach ($items->childNodes AS $item) 
				if (!$multi) 
					$result[$item->nodeName]= $item->nodeValue;
				else 
					$result[$counter][$item->nodeName]= $item->nodeValue;
			$counter++;
		}
		return $result;
	}
	
	// Get XML object as plain text starting at $elementname (no XML header)
	public static function GetXML($domDoc){
		$result="";
		foreach($domDoc->childNodes as $node)
			// saveHTML om er echt platte tekst van te maken.
			$result .= $domDoc->saveHTML($node);
		return $result;
	}	

	// DomDocument maken
	public static function getDomDocument($Recordset,$elementName,$nsURI=""){
		$list=($nsURI=="") ? $Recordset->getElementsByTagName($elementName) :
								 $Recordset->getElementsByTagNameNS($nsURI, $elementName);
		$doc1=new domDocument('1.0');
		$doc1->formatOutput=true;
		for($i=0; $i<$list->length; $i++) 
			$doc1->appendChild($doc1->importNode($list->item($i), true));
		return $doc1;
	}
}
?>