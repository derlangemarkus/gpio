<?php
require_once '../lib/Gpio.class.php';
require_once '../lib/Lcd.class.php';
require_once '../euli/Euli.class.php';

class openEyesThread extends Thread
{
	public function __construct($euli)
    {
        $this->euli = $euli;
    }
	
	public function run()
    {
        $this->euli->openEyes();
    }
}


$euli = Euli::getInstance(18, 7, 8, 25, 24, 23, 14);
$euli->init();
sleep(1);

$euli->clearDisplay();
$openEyesThread = new openEyesThread($euli);
$openEyesThread->start();
$euli->outputText("Hallo,", Lcd::LINE1);
$euli->outputText("ich bin Euli.", Lcd::LINE2);
sleep(2);

$euli->clearDisplay();
$euli->closeEyes();
$euli->outputText("Gute Nacht!");


