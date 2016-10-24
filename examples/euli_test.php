<?php
require_once '../lib/Gpio.class.php';
require_once '../lib/Lcd.class.php';
require_once '../euli/Euli.class.php';


$euli = new Euli(18);
$euli->init();
sleep(1);
$euli->openEyes();
sleep(2);
$euli->closeEyes();