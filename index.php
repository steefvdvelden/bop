<?php
// **********************************************
// Webservice Demo Hanze Thema 4.1 BI - BPM
// **********************************************
?>
<html>

<head>
<link rel="stylesheet" type="text/css" href="../Tools/Hanze_BOP.css">
<title>Thema 4.1 BI - BPM, practicum EAI</title>
</head>

<body>
<h1>Voorbeelden Webservice en Soap calls</h1>
<b><font color=green>Voorbeelden met debug mode, om de verstuurde berichten te bekijken</font></b><br>

<Form action="Voorbeelden/voorbeelden.php" method="POST">
	<Input type="submit" value="Voorbeelden">
</Form>

<h1>Voorbeeld Control Centrum Hanze EAI - Meijer B.V.</h1>
<b><font color=green>Control Centrum van de processen die bij Meijer draaien</font></b><br>
	
<Form action="Meijer_BOP/Control_Centrum.php" method="POST">
	<Input type="submit" value="Control_Centrum">
</Form>

<h1>Voorbeeld Control Centrum Hanze EAI - Kooij B.V</h1>
<b><font color=green>Control Centrum van de processen die bij Kooij draaien</font></b><br>
	
<Form action="Kooij_BOP/Control_Centrum.php" method="POST">
	<Input type="submit" value="Control_Centrum">
</Form>

<h1>Voorbeeld MS-Access database</h1>
<b><font color=green>Control Centrum van de processen die gebruik maken van MS-Access</font></b><br>
	
<Form action="MSAccess/Control_Centrum.php" method="POST">
	<Input type="submit" value="Control_Centrum">
</Form>

<h1>Voorbeeld MSSQL processen</h1>
<b><font color=green>Control Centrum van de processen die gebruik maken van MSSQl</font></b><br>
	
<Form action="MSSql/Control_Centrum.php" method="POST">
	<Input type="submit" value="Control_Centrum">
</Form>
</body>
</html>
