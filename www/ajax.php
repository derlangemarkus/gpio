<?php
require_once '../lib/Gpio.class.php';
require_once '../lib/Lcd.class.php';
require_once '../euli/Euli.class.php';

$euli = new Euli(18);
switch($_GET["eyes"])
{
	case "open":
		$euli->openEyes();
		break;
	
	case "close":
		$euli->closeEyes();
		break;
}
