<?php

// ******************************************************
// Webservice Client Class by Elmü (www.netcult.ch/elmue)
// ******************************************************

// requires PHP 5 !
class Webservice
{
	const TIME_OUT = 10;

	public  $PRINT_DEBUG = false;
	
	private $UserAgent;
	private $Method;
	private $CharSet;
	private $Url;
	
	// ServerUrl = "http://xml.weather.com:80/weather/local"
	// ServerUrl = "https://sdb.amazonaws.com"
	// Method    = "POST" / "GET" / "SOAP"
	// CharSet   = "utf-8"
	public function __construct($ServerUrl, $Method, $CharSet, $UserAgent="")
	{
		// Avoids Notice when run on WAMP
		error_reporting(E_ERROR | E_WARNING | E_PARSE);
		
		// The php_domxml.dll is an old DLL is from PHP 4 and must be disabled in PHP 5! 
		// This happens due to a bug in Xampp default configuration.
		if (function_exists(domxml_open_mem))
			throw new Exception("Please comment out the line 'extension=php_domxml.dll' in Apache/bin/php.ini and restart the server!");
		
		if (empty($UserAgent)) $UserAgent = "PHP WebService Client";
		
		$this->Url       = parse_url($ServerUrl);
		$this->UserAgent = $UserAgent;
		$this->Method    = strtoupper($Method);
		$this->CharSet   = $CharSet;
		
		if (empty($this->Url["path"])) $this->Url["path"] = "/";
	}
	
	// returns $RetArray["Status"], $RetArray["Body"], $RetArray["XPath"]
	// Method = POST/GET --> $Data = array("Parameter1" => "Value1", "Parameter2" => "Value2")
	// Method = SOAP     --> $Data = "<?xml ....><soap:Envelope....>..."
	public function SendRequest($Data, $SoapAction="")
	{
		if (is_array($Data))
		{
			$DispParams = "<table cellspacing=0 cellpadding=0>";
	        $Params = array();
	        foreach ($Data as $Key => $Value) 
	        {
	        	$Encode = str_replace('%7E', '~', rawurlencode($Value));
	            $Params[] = "$Key=$Encode";
	            $DispParams .= "<tr><td>$Key</td><td>&nbsp;=&nbsp;$Value</td></tr>";
	        }
	        $DispParams .= "</table>";
	        $Query = implode('&', $Params);
		}
		else $DispParams = $Data;
		
		switch ($this->Method)
		{
		case "GET":
	        $SendData  = "GET ".$this->Url["path"]."?$Query HTTP/1.0\r\n";
	        $SendData .= "Host: " . $this->Url['host'] . "\r\n";
	        $SendData .= "User-Agent: " . $this->UserAgent . "\r\n";
	        $SendData .= "\r\n";
			break;

		case "POST":
	        $SendData  = "POST ".$this->Url["path"]." HTTP/1.0\r\n";
	        $SendData .= "Host: " . $this->Url['host'] . "\r\n";
	        $SendData .= "Content-Type: application/x-www-form-urlencoded; charset=".$this->CharSet."\r\n";
	        $SendData .= "Content-Length: " . strlen($Query) . "\r\n";
	        $SendData .= "User-Agent: " . $this->UserAgent . "\r\n";
	        $SendData .= "\r\n";
	        $SendData .= $Query;
			break;

		case "SOAP":
			$Dom = new DOMDocument("1.0", $this->CharSet);
			$Dom->loadXML($Data);
			$Dom->formatOutput = true;
			$Soap = $Dom->saveXml();
	        $SendData  = "POST ".$this->Url["path"]." HTTP/1.0\r\n";
	        $SendData .= "Host: " . $this->Url['host'] . "\r\n";
	        $SendData .= "Content-Type: text/xml; charset=".$this->CharSet."\r\n";
	        $SendData .= "Content-Length: " . strlen($Soap) . "\r\n";
	        $SendData .= "SOAPAction: $SoapAction\r\n";
	        $SendData .= "User-Agent: " . $this->UserAgent . "\r\n";
	        $SendData .= "\r\n";
	        $SendData .= $Soap;
			break;
			
		default:
			throw new Exception("Invalid Method: ".$this->Method);	
		}
		
		if ($this->PRINT_DEBUG)
		{
			echo "<h3>Url:</h3><pre>".$this->Url['scheme']."://".$this->Url['host'].$this->Url['path']."</pre>";
			
			if (is_Array($Data))
			{
				echo "<h3>Parameter:</h3><pre>$DispParams</pre>";
			}
			$Html = $SendData;
			$Html = str_replace("<", "&lt;", $Html);
			$Html = str_replace(">", "&gt;", $Html);
			$Html = trim($Html);
			echo "<h3>Request:</h3><pre>$Html</pre>";
			echo "<hr>";
			flush();
		}

        $Port = array_key_exists('port',$this->Url) ? $this->Url['port'] : null;

        switch ($this->Url['scheme']) 
        {
            case 'https':
                if (!function_exists(openssl_verify))
                    throw new Exception("Please remove the comment in the line ';extension=php_openssl.dll' in Apache/bin/php.ini and restart the server!");

                $Scheme = 'ssl://';
                $Port = ($Port === null) ? 443 : $Port;
                break;

            case 'http':
                $Scheme = '';
                $Port = ($Port === null) ? 80 : $Port;
                break;
                
            default:
            	throw new Exception("Invalid protocol in: ".$this->ServerUrl);
        }
        
        $Socket = @fsockopen($Scheme . $this->Url['host'], $Port, $ErrNo, $ErrStr, WebService::TIME_OUT);
        if (!$Socket)
        	throw new Exception ("Unable to establish connection to host " . $this->Url['host'] . " $ErrStr");
        
        fwrite($Socket, $SendData);

        $Response = "";
        while (!feof($Socket)) 
        {
        	// Read blocks of 1000 Bytes
            $Response .= fgets($Socket, 1000);
        }
        fclose($Socket);

		// Between Header and ResponseBody there are two empty lines
        list($Header, $ResponseBody) = explode("\r\n\r\n", $Response, 2);
        
        $Split = preg_split("/\r\n|\n|\r/", $Header);
       
        // Decode the first line of the header: "HTTP/1.1 200 OK"
        list($Protocol, $StatusCode, $StatusText) = explode(' ', trim(array_shift($Split)), 3);
    	
    	$RetArray["Status"] = $StatusCode;
    	$RetArray["Body"]   = $ResponseBody;
    	
    	try
    	{
	        $Dom = new DOMDocument("1.0", $this->CharSet);
    	    $Dom->loadXML($ResponseBody, LIBXML_NOERROR | LIBXML_NOWARNING);
    	    
    	    $RetArray["XPath"] = new DOMXpath($Dom);
    	}
    	catch (Exception $Ex) {}
    	
		if ($this->PRINT_DEBUG)
		{
			if ($Dom->hasChildNodes())
			{
				$Dom->formatOutput = true;
				$Body = $Dom->saveXml();
			}
			else $Body = $ResponseBody;

			$Body = str_replace("<", "&lt;", $Body);
			$Body = str_replace(">", "&gt;", $Body);

			echo "<h3>Response Header:</h3><pre>$Header</pre>";
			echo "<h3>Response Status:</h3><pre>$StatusCode</pre>";
			echo "<h3>Response Body:  </h3><pre>$Body</pre>";
			flush();
		}
        return $RetArray;
    }
    // Call Soap Service
    public static function callSoapService($serverURL,$Action,$Message,$elementName,$namespace="",$nsURI="",$rsInput=0){
    	try 
    	{
	    	$Message ="<elementName>$elementName</elementName>".
	 	    		  "<nameSpace>$namespace</nameSpace><nsURI>$nsURI</nsURI><rsInput>$rsInput</rsInput>".
	    			  "<$elementName>$Message</$elementName>";
	    	$Service = new Webservice($serverURL, "SOAP", "utf-8");
	    	$Soap = "<?xml version=\"1.0\"?>
	    	<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\">
	    	<soapenv:Header/>
	    	<soapenv:Body>
	    	<$Action>
	    	$Message
	    	</$Action>
	    	</soapenv:Body>
	    	</soapenv:Envelope>";
	    	flush();
	    
	    	$Response = $Service->SendRequest($Soap, $Action);
	    	$Body = new DOMDocument();
	    	$Body->loadXML($Response["Body"]);
			return $Body;
    	}
    	catch(exception $e)
    	{
    		return $e;
    	}
    }
}

?>