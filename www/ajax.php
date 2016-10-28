<?php
require_once '../lib/Gpio.class.php';
require_once '../lib/Lcd.class.php';
require_once '../euli/Euli.class.php';

if(!$argv)
{
	if($_GET["eyes"])
	{
		exec("sudo php /home/pi/projects/www/ajax.php ".$_GET["eyes"]);
	}
	exit;
}

$arguments = $argv[1];
$euli = Euli::getInstance(18, 7, 8, 25, 24, 23, 14);
switch($arguments)
{
	case "open":
		$euli->openEyes();
		$euli->clearDisplay();
		$euli->outputText("Hallo,", Lcd::LINE1);
		$euli->outputText("ich bin EuLI.", Lcd::LINE2);
		break;
	
	case "close":
		$euli->closeEyes();
		$euli->clearDisplay();
		$euli->outputText("Zzzzzzzzz");
		break;
}
