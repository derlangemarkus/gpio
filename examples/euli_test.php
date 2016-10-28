<?php
require_once '../lib/Gpio.class.php';
require_once '../lib/Lcd.class.php';
require_once '../euli/Euli.class.php';


$euli = Euli::getInstance(18, 7, 8, 25, 24, 23, 14);
$euli->init();
sleep(1);

$euli->clearDisplay();
$euli->outputText("Hallo,", Lcd::LINE1);
$euli->outputText("ich bin Euli.", Lcd::LINE2);
$euli->openEyes();
sleep(2);

$euli->clearDisplay();
$euli->outputText("Gute Nacht!");
$euli->closeEyes();