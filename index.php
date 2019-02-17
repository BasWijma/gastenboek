<?php
if(isset($_POST['submit'])) {
	$error = "";

	if($_POST["naam"] == "") { $error .= "<li>Vul je naam in</li>"; }
	if (preg_match('/[\'^£$%&*()}{@#~?><>,|=+¬]/', $_POST["naam"])) { $error .= "<li>Je naam mag geen speciale tekens bevatten</li>"; }
	if($_POST["bericht"] == "") { $error .= "<li>Bericht mag niet leeg zijn</li>"; }
	if($error != "") { $error = "<ul>".$error."</ul>"; }
	else {

		$bericht = str_replace("\r\n","<br />",$_POST["bericht"]);
		$filename = time().rand(1000,9999).".txt";
		$path = "berichten/";
		$new_file = fopen($path.$filename,"w") or die("Opslaan mislukt");
		fwrite($new_file,$_POST["naam"]."\r\n".$bericht);
		fclose($new_file);
		$red = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		header("Location: ".$red);
	}


	
}
?>
<html>
<head>
<title>Gastenboek</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>

<h1>Gastenboek</h1>
<p>Laat een bericht achter.</p>

<form action='' method='post'>
	<input type='text' name='naam' placeholder='Vul je naam in' value='<?php echo $_POST["naam"]; ?>' /><br />
    <textarea placeholder='Typ je bericht' name='bericht'><?php echo $_POST["bericht"]; ?></textarea><br />
    <input type='submit' value='Plaats bericht' name='submit' />
</form>

<?php
if($error != "") { echo $error; }

$folder = "berichten";
$files = array_diff(scandir($folder), array('..', '.'));
rsort($files);
foreach($files as $file) {
	$filename = $folder."/".$file;
	$open = fopen($filename, "r") or die("Bestand niet gevonden!");
	$contents = explode("\r\n",fread($open,filesize($filename)));
	fclose($open);
	
	echo "<table cellpadding='0' cellspacing='0'><tr><th><b>".$contents[0]."</b> op ".date("j F Y (H:i)",substr($file,0,10))."</th></tr>
			<tr><td>".$contents[1]."</td><tr></table>";
	
}
?>



</body>
</html>
