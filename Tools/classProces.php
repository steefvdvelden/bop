<?php
	class Proces{
		public  $procesConfig;
		public  $Dom;
		public  $XPath;
		public  $locatie;
		public  $pad;
		public  $skipTo;
		public  $pijltjes;
		private $messageObj;
		private $emptyCount;
		private $submitLocatie;
		
// 		const WEB_SERVICE = 1;
// 		const SHOW_FORM = 2;
// 		const SUB_PROCES = 3;
// 		const TRANSFORM = 4;
				
		function __construct($procesConfig){
			$this->procesConfig=$procesConfig;
			//Zet de messagemap in messageObj met als root <messagemap> (zonder root werkt de loadXML niet)
			$this->messageObj = "<?xml version=\"1.0\"?><messagemap>".$this->procesConfig->messageMap.'</messagemap>';
			$this->Dom = new DOMDocument("1.0");
			$this->Dom->loadXML($this->messageObj);
			$this->XPath = new DOMXpath($this->Dom);
			$this->locatie = 0;
			$this->pad = 0;
			$this->skipTo = 0;
			$this->pijltjes = '';
			$this->emptyCount = 0;
			$this->submitLocatie = "";
		}
		
		function __destruct(){
		}
		
		/**
		 *  Toon een formulier, dus niet de procesflow
		 */
		public function showForm(){
			try{
				echo '<div class="formulier">';
				$formName = "Schermen/".$this->procesConfig->formName;
				include $formName;
				echo '</div>';
			}
			catch (exception $e){
				echo $e;
			}
		}
		
		/**
		 * Invoegen van een activiteit
		 * @param 	int	$locatie	de positie in de flow
		 * @param	int	$pad	pad waarop de activiteit zich bevindt,
		 *			0 = één pad,
		 *			1 = twee paden, linker pad,
		 *			2 = twee paden, rechter pad,
		 * @param	int	$positie	positie op het scherm,
		 *			0 = één pad tonen, linker pad,
		 *			1 = twee paden, linker pad,
		 *			2 = twee paden, rechter pad,
		 * @param	string	$activiteitNaam	naam van de activiteit (php file naam)
		 */
		public function includeActiviteit($locatie, $pad, $positie, $activiteitNaam){
			try{	
				if($pad==0)				
					$this->pad=0;
				$this->locatie = $locatie;
				if($positie==1)
					echo '<table style="padding=20px"><tr><td>';
				$this->pijltjes.=$this->bepaalKleur($pad);
				$activiteitNaam = "Activiteiten/".$activiteitNaam;
				include $activiteitNaam;
				$this->createActiviteit($activiteit,$pad);
				$this->drawConnections($positie);
			}
			catch (exception $e){
				echo $e;
			}
		}

		/**
		 * Invoegen van een beslissing
		 * @param	int	$locatie	positie in de flow
		 * @param	int	$pad	pad waarop de activiteit zich bevindt,
		 *			0 = één pad,
		 *			1 = twee paden, linker pad,
		 *			2 = twee paden, rechter pad
		 * @param	string	$beslissingNaam	naam van de beslissing (php file naam)
		 * @param	int	$pad_2	na deze locatie begint pad 2, dus de laatste activiteit van het eerste pad
		 * @param	int	$padjoin	na deze locatie zijn de paden weer samengevoegd, dus de laatste actviteit van pad 2
		 */
		public function includeBeslissing($locatie, $pad, $beslissingNaam,$pad_2, $padjoin){
			try{	
				$this->procesConfig->padjoin=$padjoin;
				$this->procesConfig->pad_2=$pad_2;
				$this->locatie = $locatie;
				$this->pad=0;
				$beslissingNaam="Beslissingen/".$beslissingNaam;
				include $beslissingNaam;
				echo $this->showDecision($branch_1 ,$comment);
				$canvas='can_'.$this->locatie;
				echo '<canvas width="400" height="90" id="'.$canvas.'"></canvas>'.
				     '<script type="text/javascript">'.
				     'test = initArrows_Desic("'.$canvas.'",pad, "'.$this->pijltjes.'");'.
				     '</script>';
			}
			catch (exception $e){
				echo $e;
			}
		}
		
		/**
		 * Invoegen van een lege activiteit, gebruikt om paden te balanceren
		 * @param	int	$locatie	positie in de flow
		 * @param	int	$pad	pad waarop de activiteit zich bevindt.
		 *			0 = één pad,
		 *			1 = twee paden, linker pad,
		 *			2 = twee paden, rechter pad
		 * @param	int	$positie	positie op het scherm.
		 *			0 = één pad tonen, linker pad,
		 *			1 = twee paden, linker pad,
		 *			2 = twee paden, rechter pad
		 */
		public function includeEmpty($locatie, $pad, $positie){
			try{
				$this->locatie=$locatie;
				$this->emptyCount++;
				$canvas='empty_'.$this->emptyCount;
				if($positie==1)
					echo '<table style="padding=20px"><tr><td>';
				$kleur=$this->bepaalKleur($pad);
				echo '<canvas width="210" height="70" id="'.$canvas.'"></canvas>'.
					 '<script type="text/javascript">'.
					 'pad = initArrows_Empty("'.$canvas.'", '.$positie.', '.$this->locatie.', "'.$kleur.'");'.
					 '</script>';
				if($positie==0)
					echo '<br>';
				if ($this->locatie-1==$this->procesConfig->stap)
					echo $this->reloadFlow();
				$this->pijltjes.=$kleur;
				$this->drawConnections($pad);
			}
			catch (exception $e){
				echo $e;
			}
		}
		
		/**
		 * Deze functie zorgt ervoor dat de flow op een punt verder in het proces wordt voortgezet.
		 * Dit is om te faciliteren dat er in het eerste pad na een beslissing nog een nieuwe
		 * beslissing geplaatst kan worden. Deze functie mag alleen in pad twee gebruikt worden.
		 * Zorg er dus voor dat de conditie in de beslissing goed is opgesteld.
		 * @param	int	$locatie	positie in de flow
		 * @param	int	$pad	pad waarop de activiteit zich bevindt.
		 *			0 = één pad,
		 *			1 = twee paden, linker pad,
		 *			2 = twee paden, rechter pad
		 * @param	int	$positie	positie op het scherm.
		 *			0 = één pad tonen, linker pad,
		 *			1 = twee paden, linker pad,
		 *			2 = twee paden, rechter pad
		 * @param	int	$jumpTo	locatie waar naartoe gesprongen moet worden
		 */
				public function includeJumpTo($locatie, $pad, $positie, $jumpTo){
			try{
				$this->locatie=$locatie;
				echo '<td width=160px>'.$locatie.'<center><div class="bullet jump">'.$jumpTo.
					 '</div></center></td></tr></table>';
				if ($this->locatie-1==$this->procesConfig->stap){
					$this->procesConfig->stap=$jumpTo-2;
					echo $this->reloadFlow();
				}
				if (($this->procesConfig->stap>$this->locatie) and ($pad==$this->pad))
					$this->skipTo=$jumpTo;
				$this->pijltjes.=$this->bepaalKleur($pad);
				$this->drawConnections(0);
			}
			catch (exception $e){
				echo $e;
			}
		}

		/**
		 * Invoegen van een translate activiteit, moet nog afgerond worden
		 * @param int	$locatie	positie in de flow
		 * @param int	$pad	pad waarop de activiteit zich bevindt.
		 *			0 = één pad.
		 *			1 = twee paden, linker pad,
		 *			2 = twee paden, rechter pad
		 * @param	int	$positie	positie op het scherm.
		 *			0 = één pad tonen, linker pad,
		 *			1 = twee paden, linker pad,
		 *			2 = twee paden, rechter pad
		 * @param $translateNaam = naam van de translate activiteit (php file naam)
		 */
		public function includeTranslate($locatie, $pad, $positie, $translateNaam){
			try{
				if($pad==0)
					$this->pad=0;
				$this->locatie=$locatie;
				$kleur=$this->bepaalKleur($pad);
				if ($this->locatie-1>=$this->procesConfig->stap){
					include $translateNaam;
				}
				if($positie==1)
					echo '<table style="padding=20px"><tr><td>';
				echo '<div width="210" height="100" class="proces_box actie"><b><br>'.
					      $locatie.'.Hier moet een naam<br><br></b></div>';
				if ($this->locatie-1==$this->procesConfig->stap)
					echo $this->reloadFlow();
				$this->pijltjes.=$kleur;
				$this->drawConnections($pad);
			}
			catch (exception $e){
				echo $e;
			}
		}
		
		/**
		 * Toon de het eindpunt
		 * @param	int	$locatie	de plek in de flow
		 */
		public function includeEind($locatie){
			try {
				$this->locatie=$locatie;
				$source = $this->readStartInput("zender");
				$disabled=($this->procesConfig->stap==$this->locatie-1) ? "":"disabled";
				echo '<table><td width=220><center>'.
				'<form action="'.$source.'" method="POST" class="bullet einde">'.
				$this->formInput("", "",true).
				'<Input type="submit" value="'.$locatie.'" '.$disabled.'>'.
				'</form></center></td></table>';
				if ($this->submitLocatie!="")
					echo '<script type="text/javascript">setTimeout(function() {document.getElementById("'.$this->submitLocatie.'").submit();},300);</script>';
			}
					catch (exception $e){
				echo $e;
			}
		}
		
		/** 
		 * Toon de het startpunt
		 * @param	int	$locatie de plek in de flow, zal altijd 0 moeten zijn
		 */
		public function includeStart($locatie){
			echo '<table><td class="procesinfo">';
			echo $this->printProcesInfo();
			echo '</td><td>';
			echo '<table><td width=220><center><div class="bullet start">'.$locatie.'</div></center></td></table>';
			$this->locatie=$locatie;
			$this->pijltjes='Groen';
			$this->drawConnections(0);
		}
		
		/** 
		 *Toon de aktiviteit in de procesflow, hier wordt gecontroleerd of de activiteit aan de beurt is,
		 * al is uitgevoerd, of op een apd ligt dat niet uitgevoerd wordt.
		 * @param Activiteit	$activiteit de gegevens van deze activitiet
		 * @param int	$pad het pad waarop de activieit ligt,
		 *			0 = één pad,
		 *			1 = twee paden, linker pad,
		 *			2 = twee paden, rechter pad
		 * @return	string	HTML opmaak om de activiteit te tonen
		 */
		public function showActiviteit($activiteit, $pad){
			try{	
				$form=$activiteit->makeFormHeader($this);
				if ((($pad==$this->pad) or ($this->pad==0)) and ($this->locatie>=$this->skipTo)){
					if ($this->procesConfig->stap < $this->locatie) {
						if (($this->procesConfig->stap==$this->locatie-1) 
								and ($this->procesConfig->automatisch=="on")){
							$this->submitLocatie = $activiteit->submitLocatie;
						}
						$form .= $activiteit->makeFormContent($this);
					}
					else{
						$form.="Is al uitgevoerd.<br>";
						$form.=$activiteit->completedTekst;
						if(($this->procesConfig->stap==$this->procesConfig->padjoin)
								 and ($pad==2)){
							$this->pad=0;
						}
						else{
							$this->pad=$pad;
						}
					}
				}
				else{				
					$form.=$activiteit->makeFormContent($this);
				}
				$form.='</Form>';
				return $form;
			}
			catch (exception $e){
				return $e;
			}
		}
		
		/**
		 * Toon een beslissingspunt, hier kan maar één pad zijn, na dit punt zijn er twee paden
		 * @param	boolean	$branch_1	boolean statement met de conditie voor het linker pad
		 * @param	string	$comment	commentaar dat naast de beslissing getoond wordt
		 * @return	string	HTML opmaak om de beslissing te tonen.
		 */
		public function showDecision($branch_1,$comment){
			try{	
				$decision='<script>pad=0;</script><table><td width="33"></td>';
				$decision.='<td><a href="#" class="diamond"><div class="content">';
				if ($this->locatie>=$this->skipTo){
					if ($this->procesConfig->stap>=$this->locatie-1){
						if ($branch_1){
							$decision.="<wel><script>pad=1;</script>";
							$this->pad=1;
							$this->pijltjes='GroenGrijs';
							if ($this->procesConfig->stap==$this->locatie-1)
								$this->procesConfig->stap++;
						}
						else{
							$decision.="<niet><script>pad=2;</script>";
							$this->pad=2;
							$this->pijltjes='GrijsGroen';
							if ($this->procesConfig->stap==$this->locatie-1)
								$this->procesConfig->stap=$this->procesConfig->pad_2;
						}
					}
					else
						$this->pijltjes='ZwartZwart';
				}
				else 
					$this->pijltjes='GrijsGrijs';
				$decision.=$this->locatie.'.'.'Beslissing';
				$decision.='</div></a></td><td width="50"></td><td>';
				$decision.=$comment;
				$decision.='</td></table>';
				return $decision;
			}
			catch (exception $e){
				return $e;
			}
		}

		/**
		 * Het uitvoeren van de de activiteit afhankelijk van het type activiteit.
		 * @param	Activiteit	$activiteit	de gegevens van deze activiteit
		 * @param	int	$pad	het pad waarin deze activiteit zich bevindt.
		 *			0 = één pad,
		 *			1 = twee paden, linker pad,
		 *			2 = twee paden, rechter pad
		 */
		public function createActiviteit($activiteit,$pad){
			if($this->procesConfig->actie==$activiteit->action){
				switch ($activiteit->typeActiviteit){
					case WEB_SERVICE:
						$getSoap = Webservice::callSoapService( $activiteit->serverURL, 
																$activiteit->serviceName, 
																$activiteit->message,
																$activiteit->elementName,
																$activiteit->namespace,
																$activiteit->nsURI,
																$activiteit->rsInput);
						$domDoc=Utils::getDomDocument($getSoap, $activiteit->elementName.'s', $activiteit->nsURI);
						$this->procesConfig->messageMap.=str_replace('"', "'",Utils::GetXML($domDoc));
						echo $this->reloadFlow($activiteit->formNaam);
						break;
					case SHOW_FORM:
						echo $this->reloadFlow($activiteit->formNaam);
						break;
					case SUB_PROCES:
						// No specific action needed
						break;
					case TRANSFORM:
						// Load XML file
						$xml = Utils::getDomDocument($this->Dom,
												 $activiteit->elementName,
												 $activiteit->nsURI);
						// Load XSL file
						$xsl = new DOMDocument;
						$xsl->load($activiteit->xslSheet);
						// Configure the transformer
						$proc = new XSLTProcessor;
						// Attach the xsl rules
						$proc->importStyleSheet($xsl);
						$getTransform=$proc->transformToDoc($xml);
						$domDoc=Utils::getDomDocument($getTransform, $activiteit->elementOut, $activiteit->nsURIOut);
						$this->procesConfig->messageMap.=str_replace('"', "'",Utils::GetXML($domDoc));
						echo $this->reloadFlow($activiteit->formNaam);
						break;
				}
			}
			else{
				echo $this->showActiviteit($activiteit, $pad);
			}
		}
		
		/**
		 * Herladen van het scherm, elke stap die een activiteit uitvoert moet de flow herladen
		 * @param	string	$formName	de naam van het formulier dat bij het herladen getoond moet worden
		 *			als deze leeg is wordt het proces getoond
		 * @return	string HTML opmaak met een form en javascript dat de pagina opnieuw laadt
		 */
		public function reloadFlow($formName = ""){
			try{
				$this->procesConfig->stap++;
				if ($this->procesConfig->stap==$this->procesConfig->pad_2)
					$this->procesConfig->stap=$this->procesConfig->padjoin;
				$form='<Form id="ReLoad" action="'.$this->procesConfig->procesRunner.'" method="POST">';
				$form.=$this->formInput("", $formName);
				$form.='<Input type="submit" value="Verder">';
				$form.='</Form>';
				// De volgende regel een comment maken zorgt ervoor dat de flow niet meer automatisch herlaadt
				// Dit kan handig zijn bij het debuggen.
				$form.='<script type="text/javascript">document.getElementById("ReLoad").submit();</script>';
				return $form;
			}	
			catch (exception $e){
				return $e;
			}
		}
		
		/**
		 * Bepaal de kleur van de pijltjes, Zwart, proces is hier nog niet, 
		 *			Groen, proces heeft dit pad doorlopen, Grijs, pad wordt niet doorlopen
		 * @param	int	$pad	het pad van waaruit deze functie wordt aangeroepen.
		 *			0 = één pad,
		 *			1 = twee paden, aanroep vanuit eerste pad,
		 *			2 = twee paden, aanroep vanuit tweede pad.
		 * @return	string	De kleur van het pijltje dat getekend moet worden
		 */
		public function bepaalKleur($pad){
			if($pad<=1)
				$this->pijltjes='';
			if ((($pad==$this->pad) or ($this->pad==0)) and ($this->locatie>=$this->skipTo)){
				if ($this->procesConfig->stap < $this->locatie)
					$kleur='Zwart';
				else
					$kleur='Groen';
			}
			else
					$kleur='Grijs';
			return $kleur;		
		}
		
		/**
		 * Teken de pijltjes die de procesflow weergeven
		 * @param	int	$pad	het pad van waaruit deze functie wordt aangeroepen.
		 *			0 = één pad,
		 *			1 = twee paden, aanroep vanuit eerste pad,
		 *			2 = twee paden, aanroep vanuit tweede pad.
		 */
		public function drawConnections($pad){
			try {
				$join=($this->locatie==$this->procesConfig->padjoin) ? 1 : 0;
				$canvas='can_'.$this->locatie;
				switch ($pad){
					case 0:
						echo '<canvas width="400" height="70" id="'.$canvas.'"></canvas></br>'.
								'<script type="text/javascript">'.
								'pad = initArrows_Proces("'.$canvas.
								'","'.$this->pijltjes.'", pad);</script>';
						break;
					case 1:
						echo '</td><td>';
						break;
					case 2:
						echo '</td></tr></table><canvas width="400" height="70" id="'.$canvas.'"></canvas><br>'.
								'<script type="text/javascript">'.
								'pad = initArrows_Split("'.$canvas.'", pad,'.$join.',"'.$this->pijltjes.'");'.
								'</script>';
						break;
				}
			}
			catch (exception $e){
				echo $e;
			}
		}
		
		/**
		 * Maken van het &ltform&gt element om de volgende bladzijde te laden.
		 * Dat kan hetzelfde proces zijn, een volgend proces, of terug naar het aanroepende proces
		 * @param string	$action	de activiteit die uitgevoerd moet worden bij het laden van de volgende bladzijde
		 * @param string	$formName	het formulier dat getoond moet worden op het nieuwe scherm, als dit leeg is wordt het proces getoond
		 * @param boolean	$goBack	als true, ga terug naar het aanroepende proces
		 * @param	boolean	$subProces	als true, laad een nieuw proces.
		 * @return	string	HTML opmaak voor het afdrukken van een from
		 */
		public function formInput($action, $formName, $goBack=false, $subProces=false){
			try{
				if ($goBack){
					$messagemap=$this->readStartInput("Messagemap").$this->procesConfig->messageMap;
					$step=$this->readStartInput("Step")+1;
					$join=$this->readStartInput("Join");
					$pad2=$this->readStartInput("Pad2");
					$startInput=$this->readStartInput("StartInput");
				}
				else {
					$messagemap=$this->procesConfig->messageMap;
					if ($subProces){
						$step=0;
						$join=0;
						$pad2=0;
						$startInput=$this->makeStartInput();
					}
					else {
						$step=$this->procesConfig->stap;
						$join=$this->procesConfig->padjoin;
						$pad2=$this->procesConfig->pad_2;
						$startInput=$this->procesConfig->startInput;
					}
				}
				$formInput='<input type="hidden" name="MessageMap" value="'.$messagemap.'">';
				$formInput.='<input type="hidden" name="Step" value='.$step.'>';
				$formInput.='<input type="hidden" name="Join" value="'.$join.'">';
				$formInput.='<input type="hidden" name="Pad2" value="'.$pad2.'">';
				$formInput.='<input type="hidden" name="StartInput" value="'.$startInput.'">';
				$formInput.='<input type="hidden" name="Action" value="'.$action.'">';	
				$formInput.='<input type="hidden" name="FormName" value='.$formName.'>';
				$formInput.='<input type="hidden" name="Automatisch" value="'.$this->procesConfig->automatisch.'">';
				return $formInput;	
			}
			catch(exception $e){
					return $e;
			}
		}
		
		/**
		 * Maken van het invoer bericht om door te geven aan een volgend proces
		 * @return	string XML bestand met de huidige gegevens van het proces die meegegeven
		 * 				moeten worden aan het volgende proces
		 */
		public function makeStartInput(){
			$zender=Utils::GetBaseUrl().$this->procesConfig->procesRunner;
			$startInput='<MessageMap>'.$this->procesConfig->messageMap.'</MessageMap>';
			$startInput.='<Step>'.$this->procesConfig->stap.'</Step>';
			$startInput.='<Join>'.$this->procesConfig->padjoin.'</Join>';
			$startInput.='<Pad2>'.$this->procesConfig->pad_2.'</Pad2>';
			$startInput.='<zender>'.$zender.'</zender>';
			$startInput.='<procesFile>'.$this->procesConfig->procesFile.'</procesFile>';
			$startInput.='<StartInput>'.$this->procesConfig->startInput.'</StartInput>';
			return $startInput;
		}
		
		/**
		 * Ophalen van van een gegeven uit het input bericht
		 * @param	string	$dataElement	de naam van het gegeven dat moet worden opgehaald
		 * @return	string	het gegeven dat in het invoerbericht zat
		 */
		public function readStartInput($dataElement){
			$pos = strpos($this->procesConfig->startInput, "<StartInput>");
			if ($pos == false)
				$procesData=$this->procesConfig->startInput;
			else
				$procesData=substr($this->procesConfig->startInput, 0, $pos);
			$startInput=substr($this->procesConfig->startInput,$pos);
			$messageObj = "<?xml version=\"1.0\"?><messagemap>".$procesData.'</messagemap>';
			$Dom = new DOMDocument("1.0");
			$Dom->loadXML($messageObj);
			$XPath = new DOMXpath($Dom);
			if ($dataElement=="StartInput"){
				$readElement=substr($startInput,12,strlen($startInput)-25);
			}
			else {
				$dataElement="//messagemap/".$dataElement;
				$readElement=Utils::GetValue($XPath, $dataElement);
			}
			return $readElement;
		}
		
		/**
		 * Ophalen waarde uit de messagemap
		 * @param	string	$elementName	het root element van de gegevens die moeten worden opgehaald
		 * @param	string	$fieldName	het gegeven dat opgehaald moet worden, kan ook meerdere velden bevatten
		 * @param	string	$namespace	de prefix die gebruikt wordt
		 * @param	string	$nsURI	de namespace van de gegevens
		 * @return	string	De gegevens uit de messagemap op de ingevoerde locatie 
		 */
		public function getMessagemapValue($elementName,$fieldName,$namespace="",$nsURI=""){
			if ($nsURI!=""){
				$this->XPath->registerNamespace($namespace,$nsURI);
				$elementName = $namespace.":".$elementName;
			}
			$fieldLocation = "//messagemap/$elementName/$fieldName";
			$MMValue = Utils::GetValue($this->XPath, $fieldLocation);
			if($MMValue==""){
				// Try result from webservice
				$fieldLocation = "//messagemap/{$elementName}s/$elementName/$fieldName";
				$MMValue = Utils::GetValue($this->XPath, $fieldLocation);
			}
			return $MMValue;
		}
		
		/**
		 * Afdrukkengegevens van de procesgegevens: stap, procesnaam en de messagemap
		 * @return string de gegevens die afgedrukt moeten worden.
		 */
		public function printProcesInfo(){
			$messagemapPrint = str_replace("<", "&lt", $this->procesConfig->messageMap);
			$messagemapPrint = str_replace(">", "&gt", $messagemapPrint);
			$stapPrint=$this->procesConfig->stap;
			$naamPrint=$this->procesConfig->procesRunner;
			$output="<b>Proces:</b> $naamPrint <br>";
			$output.="<b>Stap:</b> $stapPrint <br>";
			$output.="<b>Messagemap:</b> $messagemapPrint <br>";
			return $output;
		}
	}
	
	class ProcesConfig{
	
		public  $procesRunner;
		public  $procesFile;
		public  $messageMap;
		public  $stap;
		public  $formName;
		public  $actie;
		public  $padjoin;
		public  $pad_2;
		public  $startinput;
		public  $automatisch;
					
		function __construct($procesRunner, $posted){
			$this->procesRunner=$procesRunner;
			$this->procesFile=!empty($posted["ProcesFile"]) ? $posted["ProcesFile"] : "";
			$this->messageMap=!empty($posted["MessageMap"]) ? $posted["MessageMap"] : "";
			$this->stap=!empty($posted["Step"]) ? $posted["Step"] : 0;
			$this->formName=!empty($posted["FormName"]) ? $posted["FormName"] : "";
			$this->actie=!empty($posted["Action"]) ? $posted["Action"] : "";
			$this->padjoin = !empty($posted["Join"]) ? $posted["Join"] : 0;
			$this->pad_2 = !empty($posted["Pad2"]) ? $posted["Pad2"] : 0;
			$this->startInput = !empty($posted["StartInput"]) ? $posted["StartInput"] : "";
			$this->automatisch = !empty($posted["Automatisch"]) ? $posted["Automatisch"] : "";
		}
	
		function __destruct(){
		}
	}

	class Activiteit{
	
		public  $action;
		public  $typeActiviteit;
		public  $serverURL;
		public  $serviceName;
		public  $xslSheet;
		public  $elementName;
		public  $elementOut;
		public  $namespace;
		public  $nsURI;
		public  $rsInput;
		public  $nsURIOut;
		public  $message;
		public  $formNaam;
		public  $naam;
		public  $completedTekst;
		public  $button;
		public  $subProces;
		public  $submitLocatie;
			
		const WEB_SERVICE = 1;
		const SHOW_FORM = 2;
		const SUB_PROCES = 3;
		const TRANSFORM = 4;

		function __construct($action, $location, $typeActiviteit){
			$this->action = $action.$location;
			$this->typeActiviteit = $typeActiviteit;
			if ($typeActiviteit==SUB_PROCES){
				$this->submitLocatie = "";
			}
			else {
				$this->submitLocatie = $this->action;
			}
			$this->serverURL = "";
			$this->serviceName = "";
			$this->elementName = "";
			$this->namespace = "";
			$this->nsURI = "";
			$this->rsInput = 0;
			$this->message = "";
			$this->formNaam = "";
			$this->naam = "";
			$this->completedTekst = "";
			$this->button = "";
			$this->subProces ="";
		}	
	
		function __destruct(){
		}

		/**
		 * Deze functie bepaald hoe het &ltform&gt element opgemaakt moet worden.
		 * @param Proces $proces De proces gegevens die aangeven waar het proces op dit moment is
		 * @return string De eerste regel van het &ltform&gt element
		 */
		public function makeFormHeader($proces){
			if ($this->typeActiviteit==SUB_PROCES){
				$header = '<form action="'.$this->subProces.'" method="POST" class="proces_box sub">';
			}
			else{
				$header='<Form id="'.$this->action.
						'" action="'.$proces->procesConfig->procesRunner.
						'" method="POST" class="proces_box actie">';
			}
			$header .= '<b>'.$proces->locatie.'.'.$this->naam.'</b><br>';
			return $header;
		}
		
		/**
		 * Maak het &ltfomr&gt element dat gebruikt wordt om het scherm opnieuw te laden met de gegevens
		 * van de betreffende activiteit.
		 * @param Proces $proces De proces gegevens die aangeven waar het proces op dit moment is
		 * @return string Het &ltform&gt element dat op de pagina gezet wordt.
		 */
		public function makeFormContent($proces){
			$disabled=($proces->procesConfig->stap==$proces->locatie-1) ? "":"disabled";
			if ($this->typeActiviteit==SUB_PROCES){
				$content = $proces->formInput("", "",false,true);
			}
			else {
				$content=$proces->formInput($this->action, "");
			}
			$content .= '<Input type="submit" value="'.$this->button.'" '.$disabled.'>';
			return $content;
		}
	}