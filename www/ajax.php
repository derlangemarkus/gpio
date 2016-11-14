<?php
require_once '../lib/Gpio.class.php';
require_once '../lib/Lcd.class.php';
require_once '../euli/Euli.class.php';

if(!$argv)
{
	echo("sudo php /home/pi/projects/www/ajax.php ".escapeshellarg(json_encode($_GET)));
	exec("sudo php /home/pi/projects/www/ajax.php ".escapeshellarg(json_encode($_GET)));
	exit;
}

$arguments = json_decode($argv[1], true);
$euli = Euli::getInstance(18, 7, 8, 25, 24, 23, 14);
switch($arguments["cmd"])
{
	case "eyes":
		if($arguments["direction"] == "open")
		{
			$euli->openEyes();
			$euli->clearDisplay();
			$euli->outputText("Hallo,", Lcd::LINE1);
			$euli->outputText("ich bin EuLI.", Lcd::LINE2);
		}
		else
		{
			$euli->closeEyes();
			$euli->clearDisplay();
			$euli->outputText("Zzzzzzzzz");
		}
		break;
	
	case "text":
		$euli->outputText($arguments["line1"], Lcd::LINE1);
		$euli->outputText($arguments["line2"], Lcd::LINE2);
		$euli->outputText($arguments["line3"], Lcd::LINE3);
		$euli->outputText($arguments["line4"], Lcd::LINE4);
		break;
}
