<?php
require_once '../lib/SegmentDisplay.class.php';
require_once '../lib/Gpio.class.php';

$segmentDisplay = new SegmentDisplay(14, 15, 18, 23, 24, 25, 8, 7);


$text = "Das ist ein Test";
for($i = 0; $i < mb_strlen($text); $i++)
{
	$segmentDisplay->showChar(mb_substr($text, $i, 1));
	sleep(1);
}

exit;
for($i = 48; $i <= 122; $i++)
{
	$char = chr($i);
	echo $char."\n";
	$segmentDisplay->showChar($char);
	sleep(1);
}