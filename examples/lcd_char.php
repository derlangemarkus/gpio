<?php
require_once '../lib/Lcd.class.php';
require_once '../lib/Gpio.class.php';

$lcd = new Lcd(7, 8, 25, 24, 23, 14);
$lcd->init();

$chars = array(
	array(16,16,16,16,16,16,16,0),
	array(24,8,8,8,8,8,24,0),
	array(28,4,4,4,4,4,28,0),
	array(30,2,2,2,2,2,30,0),
	array(31,1,1,1,1,1,31,0),
	array(31,16,16,16,16,16,31,0),
	array(15,8,8,8,8,8,15,0),
	array(7,4,4,4,4,4,7,0),
	array(3,2,2,2,2,2,3,0),
	array(1,1,1,1,1,1,1,0)
);

$i=0;
while(true)
{
	$lcd->generateChar(0, $chars[$i%sizeof($chars)]);
	$lcd->generateChar(1, $chars[($i+5)%sizeof($chars)]);
	
	$rchars = array_reverse($chars);
	$lcd->generateChar(2, $rchars[$i%sizeof($chars)]);
	$lcd->generateChar(3, $rchars[($i+5)%sizeof($chars)]);
	
	$lcd->outputText("\0\1\0\1\0", Lcd::LINE1);
	$lcd->outputText("\2\3\2\3\2", Lcd::LINE2);
	$i++;
	usleep(200000);
}




