<?php
class FormOutput{
	public $proces;
	public $titel;
	private $body;
	public $message;
	private $inputYes;
	public $exitButtons;
	public $elementName;
	public $nameSpace;
	public $nsURI;
	private $destination;
	
	const OK_ONLY = 0;
	const YES_NO = 1;
	const NO_BUTTONS = 3;
	const MESSAGE = 1;
	const TABLE = 2;
	const SELECT_FORM = 3;
	const INPUT_BOX = 4;
	
	function __construct($proces,$titel){
		$this->proces = $proces;
		$this->titel = $titel;
		$this->body = "";
		$this->message = "";
		$this->exitButtons = 0;
		$this->elementName = "";
		$this->nameSpace ="";
		$this->nsURI ="";
		$this->inputYes = false;
		$this->destination = Utils::GetBaseUrl().$proces->procesConfig->procesRunner;
	}
	
	public function show(){
		echo $this->MakeTitel($this->titel);
		echo $this->MakeFormStart();
		echo $this->body;
		echo $this->MakeConfirmButton();
		echo $this->MakeCancelButton();
	}
	public function AddElement($type,$elementData){
		switch ($type){
			case FormOutput::MESSAGE:
				$this->body.="<center><h2>$elementData</h2></center>";
				break;
			case FormOutput::TABLE:
				$this->body.=$elementData->build($this);
				break;
			case FormOutput::SELECT_FORM:
				$this->inputYes = true;
				$this->body.= $elementData->build($this);
				break;
			case FormOutput::INPUT_BOX:
				$this->inputYes = true;
				$this->body.=$elementData->build();
				break;
			default:
				// No action
		}
	}
	// Create a Table from a recordset, with extra column option
	public function CreateTable($domDoc){
		$result=FormOutput::makeHeaderLine($domDoc,$extraName);
		foreach ($domDoc->childNodes AS $items) {
			$result .= '<tr>';
			foreach ($items->childNodes AS $item)
				if ($item->nodeName!="#text")
					$result .= '<td class="lijst">'.$item->nodeValue.'</td>';
			$result .= $extra;
			$result .= '</tr>';
		}
		$result ="<table>".$result."</table>";
		// De selectie wordt in de message map gezet in het Javascript. De variable Selectie moet hier dus bekend gemaakt worden voor JS.
		return $result;
	}
	
	/** 
	 * Kopregel maken voor een formulier op basis van een gegevensset en eventueel een extra kolom
	 * @param	DOMDocument	$doc1	De gegevensset
	 * @param	string		$extraName	De naam van de extra kolom
	 * @return	De HTML code die de header toont
	 */ 
	public function makeHeaderLine($doc1,$extraName=""){
		$result="<tr>";
		foreach ($doc1->documentElement->childNodes AS $item) {
			if ($item->nodeName!="#text")
				$result .= '<td>'.$item->nodeName.'</td>';
		}
		if ($extraName!=""){
			$result .= '<td>'.$extraName.'</td>';
		}
		$result .= '</tr>';
		return $result;
	}
	
	private function MakeCancelButton(){
		$button = "<center><br><Form action='Control_Centrum.php' method='POST' class ='proces_box selectie'>".
			 "<b>Afbreken en terug naar Control Centrum</b><br>".
			 "<Input type='submit' value='Terug'></Form><br></center>";
		return $button;
	}
	
	private function MakeTitel($titel){
		$outputHTML = "<center><h1>$titel</h1></center>";
		return $outputHTML;
	}
	
	private function MakeFormStart(){
		if ($this->inputYes){
			$procesRunner="../Tools/Process_Form.php";
			$inputLines = '<input type= "hidden" name="Destination" value="'.$this->destination.'">';
			$inputLines .= '<input type= "hidden" name="ElementName" value="'.$this->elementName.'">';
			if ($this->nameSpace!=""){
				$inputLines .= '<input type= "hidden" name="NameSpace" value="'.$this->nameSpace.'">';
				$inputLines .= '<input type= "hidden" name="nsURI" value="'.$this->nsURI.'">';
			}
		}
		else{
			$procesRunner=$this->destination;
			$inputLines="";
		}
		$inputLines.=$this->proces->formInput("","");
		$HTMLOutput="<form method='post' action='$procesRunner'>".$inputLines;
		return $HTMLOutput;
	}
	
	private function MakeConfirmButton(){
		$buttons = "";
		switch ($this->exitButtons){
			case FormOutput::YES_NO:
				$buttons = "<table><td>".
						"<button type='submit' name='YesNo' value='yes' class='proces_box selectie'>". 
						"<b><br>YES<br>&nbsp</b></button></td>".
						"<td><button type='submit' name='YesNo' value='no' class='proces_box selectie'>". 
						"<b><br>NO<br>&nbsp</b></button></td></table>";
				break;
			case FormOutput::OK_ONLY:
				$buttons = "<div class='proces_box selectie'>".
						   "<b>Afronden en terug naar flow</b><br>".
						   "<Input type='submit' value='Klaar'></div>";
				break;
			default:
				$buttons = "";
		}
		return "<center>$buttons</center></form>"; 
	}
	
	public function getDestination(){
		return $this->destination;
	}
}

class InputElements{
	public $inputBoxes;
	
	function __construct(){
		$this->inputBoxes = "";
	}
	
	public function build(){
		$inputElements="<table>";
		foreach($this->inputBoxes as $key => $value){
			$inputElements.="<tr><td>$key: </td><td><input type= 'text' name='$key' value='$value'></td></tr>";
		}
		$inputElements.="</table>";
		return $inputElements;
	}
}
class TableForm{
	public $messageMapElement;
	public $messageMapNS;
	private $domDocument;
	
	function __construct(){
		$this->messageMapElement ="";
		$this->messageMapNS="";
		$this->domDocument="";
	}
	
	public function build($formOutput){
		$this->domDocument=Utils::getDomDocument($formOutput->proces->Dom, 
												 $this->messageMapElement,
												 $this->messageMapNS);
		return $this->CreateTable();	
	}
	
	public function CreateTable(){
		$result=FormOutput::makeHeaderLine($this->domDocument);
		foreach ($this->domDocument->childNodes AS $items) {
			$result .= '<tr>';
			foreach ($items->childNodes AS $item)
				if ($item->nodeName!="#text")
					$result .= '<td class="lijst">'.$item->nodeValue.'</td>';
				$result .= $extra;
				$result .= '</tr>';
		}
		$result ="<center><table>".$result."</table></center>";
		return $result;
	}
	
}
class SelectForm{
	public $source;
	public $messageMapNS;
	public $messageMapElement;
	public $serverURL;
	public $selectValues;
	public $getWebService;
	public $extraType;
	public $extraField;
	public $extraValue;
	private $domDocument;
	private $extra;
	
	const SELECT_LIST = 1;
	const SINGLE_INPUT = 2;
	const WEB_SERVICE = 1;
	const MESSAGEMAP = 2;
	
	function __construct(){
		$this->source = 0;
		$this->messageMapNS="";
		$this->messageMapElement = "";
		$this->serverURL="";
		$this->selectValues="";
		$this->getWebService="";
		$this->extraType=0;
		$this->extraField="";
		$this->domDocument="";
		$this->extra="";
	}
	
	/**
	 * Deze functie toont een selectie formulier op basis van het aagemaakte SelectForm object.
	 * Als eerste worden de te tonen gegevens opgehaald met behulp van een webservice call,
	 * hierna wordt eventueel een extra kolom toegevoegd, waarin de gebruiker extra informatie kan toevoegen
	 * aan het geselecteerde record, waarna het formulier wordt aangemaakt
	 * @return string De HTML gegevens die het formulier tonen
	 */
	public function build($formOutput){
		// Ophalen gegevensset waaruit de waarde geselecteerd moet worden en in het domDocument zetten
		switch ($this->source){
			case SelectForm::WEB_SERVICE:
				$this->getWebserviceResult($formOutput);
				break;
			case SelectForm::MESSAGEMAP:
				$this->getMessageMapResult($formOutput);
				break;
			default:
				return "";
		}
		// Maken van extra kolom in de lijst, hier kan bijvoorbeeld een aantal of een opmerking door de 
		// gebruiker worden toegevoegd aan het geselecteerde record
		$this->extra = $this->extraKolom();
		
		// Maak het selectie formulier aan en geef het terug
		return  $this->CreateSelectForm($formOutput);
	}
	
	private function getWebserviceResult($formOutput){
		$getSoap = Webservice::callSoapService($this->serverURL,
				$this->getWebService,
				$this->selectValues,
				$formOutput->elementName);
		$this->domDocument=Utils::getDomDocument($getSoap, $formOutput->elementName);
	}
	
	private function getMessageMapResult($formOutput){
		$this->domDocument=Utils::getDomDocument($formOutput->proces->Dom,
												 $this->messageMapElement,
												 $this->messageMapNS);
	}
	
	private function extraKolom(){
		switch ($this->extraType){
			case SelectForm::SELECT_LIST:
				$extraKolom = "<td><select name='$this->extraField'>";
				foreach ($this->extraValue as $value){
					$extraKolom .= "<option value='$value'>$value</option>";
				}
				$extraKolom .= "</select></td>";
				break;
			case SelectForm::SINGLE_INPUT:
				$extraKolom = "<td><input type= 'text' name='$this->extraField' value='$this->extraValue'></td>";
				break;
			default:
				$extraKolom = "";
		}
		return $extraKolom;
	}
	
	private function CreateSelectForm($formOutput){
		$first=true;
		$result = '<center><table>';
		$result .=FormOutput::makeHeaderLine($this->domDocument,$this->extraField);
		foreach ($this->domDocument->childNodes AS $items) {
			$result .= '<tr>';
			$choice="";
			foreach ($items->childNodes AS $item){
				if ($item->nodeName!="#text"){
					$result .= '<td class="lijst">'.$item->nodeValue.'</td>';
					$choice.='<'.$item->nodeName.'>'.$item->nodeValue.'</'.$item->nodeName.'>';
				}
			}
			if($this->extraField!=""){
				if($first){
					$result .= $this->extra;
					$first=false;
				}
				else{
					$result .= "<td></td>";
				}
			}
			$result .= '<td><button type=submit name="Selection" value="'.$choice.'">Select</button></td>';
			$result .= '</tr>';
		}
		$result .="</table></center>";
		
		return $result;
	}
}